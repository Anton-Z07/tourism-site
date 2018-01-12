(function () {
    'use strict';

    app.controller('countrieslistCtrl', countrieslistCtrl);

    /**
     *
     * @param countries
     * @param mainConfig
     * @param generalInfo
     * @param $q
     */
    function countrieslistCtrl(countries, mainConfig, generalInfo, $q) {
        var that = this,
            popular_area = [];

        that.baseUrl = mainConfig.basePublicUrl;
        that.showCountries = false;
        that.populars = [];


        var setPopularAreas = function (countries) {
            var dummy       = {},
                defer       = $q.defer(),
                promises    = [],
                lowers      = [];

            countries.forEach(function(country) {
                promises.push(
                    generalInfo.query(
                        {
                            area_id : country.id,
                            fields  : 'lower.name,lower.popular',
                            filter  : 'lower.popular:eq:1'
                        },
                        function (data) {
                            dummy = {
                                country_id   : country.id,
                                popular_area : data.lower
                            };
                        }).$promise.then(function () {
                            lowers.push(dummy);
                        })
                );
            });

            $q.all(promises).then(function() {
                defer.resolve();

                var result      = [],
                    country_pop = {},
                    region_pops,
                    city_pops;

                lowers.forEach(function(populars) {
                    if (populars.popular_area.length > 0) {
                        region_pops = [];
                        city_pops   = [];

                        populars.popular_area.forEach(function (item) {
                            if (item.area_type == '1') {
                                region_pops.push(item);
                            }
                            else if (item.area_type == '2') {
                                city_pops.push(item);
                            }
                        });

                        country_pop = {
                            area_id : populars.country_id,
                            regions : region_pops,
                            cities  : city_pops
                        };

                        popular_area.push(country_pop);
                    }
                });

                that.populars       = popular_area;
                that.showCountries  = true;
            });

        };

        countries.query(
            {
                fields: 'flag,image'
            },
            function (data) {
                that.countries = data;
            }).$promise.then(
                function () {
                   setPopularAreas(that.countries);
                });
    }
})();