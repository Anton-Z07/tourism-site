(function () {
    'use strict';

    app.controller('entityListController', entityListController);

    /**
     *
     * @param mainConfig
     * @param areaEntityList
     * @param $stateParams
     * @param generalInfo
     * @param phrases
     * @param $scope
     * @param $q
     */
    function entityListController(mainConfig, areaEntityList, generalInfo, $stateParams, phrases, $scope, $q) {
        var that            = this,
            countryAlias    = $stateParams.countryAlias,
            entityName      = $stateParams.entityName,
            fieldsArray     = [
                entityName,
                entityName + '.web_image.files',
                entityName + '.original_name'
            ],
            fields          = fieldsArray.join(),
            filterArray     = [
                entityName + '.status:eq:1',
                entityName + '.system:eq:0'

            ],
            order               = entityName + '.id:desc',
            filter              = filterArray.join(mainConfig.filterSeparator),
            relatedItemsCount   = 4,
            areaBinding         = [];

        that.entity             = [];
        that.baseUrl            = mainConfig.basePublicUrl;
        that.moreButtonText     = phrases.pageMore;
        that.pagesize           = mainConfig.pagesize;
        that.entityName         = entityName;
        that.pagesize           = mainConfig.pagesize;
        that.showGrid           = false;
        that.alsoBeIntresting   = phrases.alsoBeIntresting;

        var areaTypeSort = function(a, b) {
            if(a.area_type < b.area_type)
                return 1;

            if(a.area_type > b.area_type)
                return -1;

            return 0;
        };

        var getQueryParams = function(area_id) {
            return {
                area_id : area_id,
                filter  : filter,
                fields  : fields,
                order   : order,
                pagesize: mainConfig.pageSize
            }
        };

        var arrayUnique =  function(array) {
            var a = array.concat();
            for(var i=0; i<a.length; ++i) {
                for(var j=i+1; j<a.length; ++j) {
                    if(a[i].id === a[j].id)
                        a.splice(j--, 1);
                }
            }
            return a;
        };

        generalInfo.query(
            {
                area_id: countryAlias,
                fields : entityName + '_text,upper'
            },
            function(data) {
                var dummy;

                that.entity_text = data[entityName + '_text'];

                dummy = {
                    'id'        : data.id,
                    'area_type' : data.area_type
                };
                areaBinding.push(dummy);

                data.upper.forEach(function(area){
                    dummy = {
                        'id'        : area.id,
                        'area_type' : area.area_type
                    };
                    areaBinding.push(dummy);
                });

                areaBinding.sort(areaTypeSort);
            }
        ).$promise.then(function(){
            areaEntityList.query(
                getQueryParams(areaBinding[0].id),
                function (data) {
                    that.entity = that.entity.concat(data[entityName]);
                    if (areaBinding.length == 1){
                        that.showGrid       = true;
                        that.relatedItems   = getRelatedItems(that.entity);
                    }
                }).$promise.then(function(){
                    areaEntityList.query(
                        getQueryParams(areaBinding[1].id),
                        function (data) {
                            that.entity = arrayUnique(that.entity.concat(data[entityName]));
                            if (areaBinding.length == 2){
                                that.showGrid       = true;
                                that.relatedItems   = getRelatedItems(that.entity);
                            }
                        }).$promise.then(function(){
                            areaEntityList.query(
                                getQueryParams(areaBinding[2].id),
                                function (data) {
                                    that.entity         = arrayUnique(that.entity.concat(data[entityName]));
                                    that.showGrid       = true;
                                    that.relatedItems   = getRelatedItems(that.entity);
                                })
                        });
                });
            });

        var getRelatedItems = function(items) {
            var result = [];
            // требуется улучшение алгоритма выбора связанных элементов
            if (items.length > mainConfig.pagesize) {
                result = items.slice(-relatedItemsCount);
            }

            return result;
        };
    }
})();