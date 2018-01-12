(function() {
    'use strict';

    app.controller('mainController', mainController);

    function mainController (generalInfo, $stateParams, mainConfig, $sce, countryLandmarks, areaEntityList, $scope, phrases) {
        var that            = this,
            countryAlias    = $stateParams.countryAlias,
            fieldsArray     = [
                'image',
                'gallery'
            ],
            fields          = fieldsArray.join(),
            entitesNames = ['landmark', 'people', 'feature', 'kitchen'],
            lmFieldsArray     = [
                'image.files',
                'property.icon.files'
            ],
            lmFields          = lmFieldsArray.join(),
            listSize          = 50;

        that.moreButtonText = phrases.pageMore;
        that.baseUrl  = mainConfig.basePublicUrl;
        that.showGrid = false;
        that.showSticked = false;
        that.recentArts = [];
        that.stickedArts = [];

        // этот сервис использовался для формирования ссылки на карту с границами area
        // https://developers.google.com/maps/documentation/embed/start
        generalInfo.query(
            {
                area_id : countryAlias,
                fields  : fields
            },
            function(data) {
                that.mainInfo           = data;
                that.googleMapsEmbedUrl = $sce.trustAsResourceUrl(
                    'https://www.google.com/maps/embed/v1/place?q=' +
                    data.name +
                    '&key=' +
                    mainConfig.googleMapsApiKey);
        });

        var setFields = function(entityName) {
            var entityFieldsArray     = [
                entityName,
                entityName + '.image.files',
                entityName + '.original_name',
                entityName + '.area.name'
            ];

            return entityFieldsArray.join();
        };

        var setFilter = function(entityName, sticked) {
            var filter = [
                entityName + '.status:eq:1',
                entityName + '.sticky:eq:' + +sticked
            ];

            return filter.join(mainConfig.filterSeparator);
        };

        var setEntityQuery = function(entityName, sticked) {
            return {
                area_id : countryAlias,
                filter  : setFilter(entityName, sticked),
                fields  : setFields(entityName),
                pagesize: listSize
            }
        };

        var setLandmarkQuery = function(sticked) {
            var filter = [
                'area:eq:' + countryAlias,
                'sticky:eq:' + +sticked
            ];

            filter = filter.join(mainConfig.filterSeparator);

            return {
                filter      : filter,
                fields      : lmFields,
                pagesize    : listSize,
                order       : 'updated_at:desc'
            }
        };

        // формирование списка свежих статей
        var addEntities = function(array, sticked) {
            var groupType = '',
                result = [];

            for (var key in array) {
                groupType = key;
                if (array.hasOwnProperty(key)) {
                    array[key].forEach(function (item) {
                        item.entity = groupType;
                        result.push(item);
                    });
                }
            }

            var sliceLength;

            if (sticked) {
                sliceLength =  mainConfig.areaPageStickedListSize;
            }
            else {
                sliceLength =  mainConfig.areaPageArticleListSize;
            }

            result.sort(sortByDate);
            result = result.slice(0, sliceLength);

            return result;
        };

        var sortByDate = function(a, b){
            var dateA = new Date(a.updated_at),
                dateB = new Date(b.updated_at);

            return dateB - dateA; //sort by date descending
        };

        var getMainPageArticles = function(sticked) {
            var dummy = [];

            countryLandmarks.query(
                setLandmarkQuery(sticked),
                function (data) {
                    dummy[entitesNames[0]] = data;
                })
                .$promise.then(function () {
                    // теперь люди, фишки, кухни
                    var entityName = entitesNames[1];
                    areaEntityList.query(
                        setEntityQuery(entityName, sticked),
                        function (data) {
                            dummy[entityName] = data[entityName];
                        })
                        .$promise.then(function () {
                            var entityName = entitesNames[2];
                            areaEntityList.query(
                                setEntityQuery(entityName, sticked),
                                function (data) {
                                    dummy[entityName] = data[entityName];
                                })
                                .$promise.then(function () {
                                    var entityName = entitesNames[3];
                                    areaEntityList.query(
                                        setEntityQuery(entityName, sticked),
                                        function (data) {
                                            dummy[entityName] = data[entityName];
                                        })
                                        .$promise.then(function () {
                                                if (sticked) {
                                                    that.stickedArts = addEntities(dummy, sticked);
                                                }
                                                else {
                                                    that.recentArts = addEntities(dummy, sticked);
                                                }

                                                $scope.$on('masonry.loaded', function () {
                                                    if (sticked) {
                                                        that.showSticked = true;
                                                    }
                                                    else {
                                                        that.showGrid = true;
                                                    }
                                                });
                                        });
                                });
                        });
                })
        };

        getMainPageArticles(true);
        getMainPageArticles(false);
    }
})();