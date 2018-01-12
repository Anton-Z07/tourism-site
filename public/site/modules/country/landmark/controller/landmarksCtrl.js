(function () {
    'use strict';



    app.controller('landmarksCtrl', landmarksCtrl);

    /**
     *
     * @param mainConfig
     * @param phrases
     * @param countryLandmarks
     * @param $stateParams
     * @param landmarkProperties
     * @param generalInfo
     * @param $scope
     * @param $location
     * @param areaEntityList
     */
    function landmarksCtrl(mainConfig, phrases, countryLandmarks, landmarkProperties, generalInfo, $stateParams, $scope, $location, areaEntityList) {
        var that            = this,
            countryAlias    = $stateParams.countryAlias,
            isVicinity      = $stateParams.v == 'vicinity',
            fieldsArray     = [
                'image.files',
                'property.icon.files'
            ],
            fields          = fieldsArray.join(),
            relatedItemsCount = 4,
            pageNumber      = 1,
            sorting         = {
                1: 'landmark.id:asc',
                2: 'landmark.rating:desc',
                3: 'landmark.updated_at:desc'
            },
            queryFilter = [
                'area:eq:' + countryAlias
            ],
            filter = queryFilter.join(mainConfig.filterSeparator),
            queryParams = {
                filter      : filter,
                fields      : fields,
                pagesize    : mainConfig.pagesize,
                page        : pageNumber,
                order       : sorting[2]
            };

        that.currentOrder     = sorting[2]; // сортировка по умолчанию
        that.baseUrl          = mainConfig.basePublicUrl;
        that.moreButtonText   = phrases.pageMore;
        that.alsoBeIntresting = phrases.alsoBeIntresting;
        that.lowerFilters     = [];
        that.pagesize         = mainConfig.pagesize;

        that.isVicinity = isVicinity;
        that.showGrid = false;
        var isInitQuery = true;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'landmark_text'
            },
            function(data) {
                that.entity_text = data.landmark_text;
            }
        );

        var resetPageNumber = function (queryParams) {
            queryParams.page = 1;
        };

        var addCustomParam = function (customParamsArray, separator) {
            return queryFilter.concat(customParamsArray).join(separator).toString();
        };

        var addCustomField = function (customParamsArray, separator) {
            return fieldsArray.concat(customParamsArray).join(separator).toString();
        };

        var toggleCustomParam = function (targetParams, newParam, separator) {
            var targetParamsArray = targetParams.split(separator),
                isNewParamExist = false,
                result = [];

            targetParamsArray.forEach(function(item) {
                if (item === newParam) {
                    isNewParamExist = true;
                }
                else {
                    result.push(item);
                }
            });

            if (!isNewParamExist){
                result.push(newParam);
            }

            result = result.join(separator).toString();

            return result;
        };

        var parseCoordinates = function(data) {
            var coordinates = [],
                point,
                iconSides = {
                    width : 25,
                    height: 25
                },
                icon,
                iconPath;

            data.forEach(function(item) {
                if (item.property.length == 0){
                    iconPath = that.baseUrl + 'upload/images/map_icon_empty.png'
                }
                else {
                    iconPath = item.property[0].icon.files[0].path;
                }

                icon = {
                    scaledSize  : new google.maps.Size(iconSides.width, iconSides.height),
                    url         : that.baseUrl + iconPath
                };

                point = {
                    position : new google.maps.LatLng(item['latitude'], item['longitude']),
                    icon     : icon,
                    title    : item.name,
                    area     : item.area[0].name,
                    itemid   : item.id
                };

                coordinates.push(point);
            });

            return coordinates;
        };

        var setBounds = function(coordinates, map) {

            var bounds = new google.maps.LatLngBounds();

            coordinates.forEach(function(point){
               bounds.extend(point.position);
            });

            google.maps.event.trigger(map, 'resize');
            map.fitBounds(bounds);
        };

        //var setCenter = function(coordinates, map) {
        //    var center = map.getCenter(coordinates);
        //    map.setCenter(center);
        //
        //    console.log('setting center done');
        //};

        var setMarkers = function(coordinates, map) {
            var marker,
                infowindow = new google.maps.InfoWindow();

            coordinates.forEach(function(point){
                // нужно улучшить формирование url-а достопримечательности
                var baseLocation = $location.absUrl().substring(0, $location.absUrl().indexOf('?'));

                var contentString =
                    '<div id="content">'+
                        '<div id="siteNotice"></div>'+
                        '<div class="show-onmap-btn custom-gm-style"><a href="' + baseLocation + '/' + point.itemid + '">' + point.title + '</a></div>'+
                        '<div id="bodyContent">'+
                        '</div>'+
                    '</div>';

                marker = new google.maps.Marker({
                    position    : point.position,
                    map         : map,
                    icon        : point.icon
                });

                google.maps.event.addListener(marker, 'click', (function(marker) {
                    return function() {
                        infowindow.setContent(contentString);
                        infowindow.open(map, marker);
                    }
                })(marker));
            });
        };

        var setMap = function(data, map) {
            var coordinates = parseCoordinates(data);

            setBounds(coordinates, map);
            setMarkers(coordinates, map);
        };

        $scope.$on('mapInitialized', function(event, map) {
            setMap(that.landmarks, map);
        });

        areaEntityList.query(
            {
                area_id : countryAlias,
                fields  : 'landmark.image.files,landmark.property.icon.files,landmark.area.name',
                filter  : 'landmark.status:eq:1;landmark.vicinity:eq:'+ +isVicinity,
                order   : sorting[2]
            },
            function (data) {
                that.landmarks  = data.landmark;
                that.area_name  = data.name;
                that.area_id    = data.id;

                updateRelatedItems(that.landmarks);

                $scope.$on('masonry.loaded', function (scope, element, attrs) {
                    if (isInitQuery)
                    {
                        that.showGrid = true;
                        isInitQuery = false;
                    }
                });
            });

        that.relatedItems = [];
        var updateRelatedItems = function(items) {
            var excludedEntitiesId = [],
                excludedString = 'id:notin:';

            items.forEach(function(item){
                excludedEntitiesId.push(item.id);
            });

            excludedString = excludedString.concat(excludedEntitiesId.join(','));

            var relatedQueryParams = {
                filter  : queryParams.filter.concat(';' + excludedString),
                pagesize: relatedItemsCount,
                fields  : fields
            };

            //надо придумать рандомную выборку сущностей при помощи API
            //пока выбираются те, которые еще не показаны, по порядку
            countryLandmarks.query(
                relatedQueryParams,
                function (data) {
                    that.relatedItems = data;
                });
        };

        that.loadMore = function() {
            pageNumber++; // следующий номер страницы

            queryParams.page     = pageNumber;
            queryParams.order    = that.currentOrder;
            queryParams.pagesize = mainConfig.pagesize;
            //queryParams.filter  = 'area:eq:' + countryAlias;

            countryLandmarks.query(
                queryParams,
                function (data) {
                    that.landmarks = that.landmarks.concat(data);
                    updateRelatedItems(that.landmarks);
                });
        };

        that.orderBy = function(sortId) {
            that.currentOrder = sorting[sortId];

            pageNumber = 1; // сброс всех страниц, открытых кнопкой "Ещё"

            queryParams.page    = pageNumber;
            queryParams.order   = that.currentOrder;

            countryLandmarks.query(
                queryParams,
                function (data) {
                    that.landmarks = data;
                });
        };


        var loadLowerProperties = function(upper_property_id) {
            var subQueryParams = {
                property_id : upper_property_id,
                fields      : 'lower'
            };

            landmarkProperties.query(
                subQueryParams,
                function(properties) {
                    //that.lowerFilters = that.lowerFilters.concat(properties.lower);
                    that.lowerFilters = properties.lower;
                }
            );
        };

        // нужно сделать универсальное определение родительского свойства
        var isParentProperty = function(property_id) {
            return property_id <= 10;
        };

        var realFilterParams = queryParams.filter;

        // фильтрация достопримечательностей
        that.addFilter = function(property_id) {
            that.showGrid = false;

            var newLandmarkFilter = 'property.id:eq:' + property_id;

            queryParams.fields  = addCustomField('property', ',');
            queryParams.pagesize = mainConfig.pagesize;

            if (isParentProperty(property_id)) {
                realFilterParams = addCustomParam(newLandmarkFilter, ';');
            }
            else {
                realFilterParams = toggleCustomParam(realFilterParams, newLandmarkFilter, ';');
            }

            queryParams.filter  = realFilterParams;

            resetPageNumber(queryParams);

            countryLandmarks.query(
                queryParams,
                function (data) {
                    that.landmarks = data;
                    if (isParentProperty(property_id))
                    {
                        loadLowerProperties(property_id);
                    }

                    $scope.$on('masonry.loaded', function (scope, element, attrs) {
                        if (!isInitQuery) {
                            that.showGrid = true;
                        }
                    });

                    updateRelatedItems(that.landmarks);
                });
        };
    }
})();