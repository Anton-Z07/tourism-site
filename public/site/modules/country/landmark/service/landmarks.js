(function() {
    'use strict';

    app.factory("countryLandmarks", getCountryLandmarks);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getCountryLandmarks ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity',
            {
                entity:     'landmark',
                filter:     ':filter',
                fields:     ':fields',
                pagesize:   ':pagesize',
                order:      ':order'
            },
            {
                query: {
                    method: "GET",
                    isArray: true
                }
            }
        );
    }
})();