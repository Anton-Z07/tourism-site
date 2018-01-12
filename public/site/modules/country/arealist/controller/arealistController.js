(function() {
    'use strict';

    app.controller('arealistController', arealistController);

    function arealistController (generalInfo, $stateParams, mainConfig, $scope) {
        var that = this,
            countryAlias = $stateParams.countryAlias,
            alreadySorted = false;

        that.baseUrl = mainConfig.basePublicUrl;
        that.showGrid = false;
        that.sortByName = false;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'lower.lower.name' // все регионы и города страны
            },
            function(data) {
                that.sortByName = false;
                that.generalInfo = data;

                $scope.$on('masonry.loaded', function (scope, element, attrs) {
                    that.showGrid = true;
                });
            }
        );

        var alphSort = function(a, b) {
            if(a.name < b.name)
                return -1;

            if(a.name > b.name)
                return 1;

            return 0;
        };

        that.orderBy = function(ordering) {
            if (ordering == 0){
                that.sortByName = false;
            }
            else if (ordering == 1){
                that.sortByName = true;
            }

            if (!alreadySorted) {
                that.showGrid = false;
                alreadySorted = true;

                var areas  = that.generalInfo;
                var cities = [];

                areas.lower.forEach(function(region){
                    region.lower.forEach(function(city) {
                        if (city.area_type == '2') {
                            cities.push(city);
                        }
                    });
                });

                cities.sort(alphSort);

                var result    = [],
                    alphGroup = [],
                    nextKey;

                cities.forEach(function(city, key, cities) {
                    var dummy,
                        prevLetter,
                        nextLetter;

                    nextKey = key + 1;

                    if (nextKey < cities.length) {
                        prevLetter = city.name.slice(0,1);
                        nextLetter = cities[nextKey].name.slice(0,1);

                        alphGroup.push(city);

                        if (prevLetter != nextLetter)
                        {
                            dummy = {
                                'letter': prevLetter,
                                'cities': alphGroup
                            };

                            result.push(dummy);
                            alphGroup = [];
                        }
                    }
                });

                that.cities = result;

                that.showGrid = true;
            }
        };
    }
})();