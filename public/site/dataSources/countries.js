(function() {
    'use strict';

    app.factory("countries", getCountries);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getCountries ($resource, mainConfig){
        return $resource(
            mainConfig.apiUrl + ':entity',
            {
                entity: 'area',
                filter: 'area_type:eq:0;status:eq:1', // 0 - страна
                fields: ':fields'
            });
    }
})();