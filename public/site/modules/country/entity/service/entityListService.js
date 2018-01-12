(function() {
    'use strict';

    app.factory("areaEntityList", getAreaEntity);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getAreaEntity ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:area_id',
            {
                entity  : 'area',
                area_id : ':area_id', // значение параметра задаётся в контроллере
                fields  : ':fields',
                filter  : ':filter',
                order   : ':order'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();