(function() {
    'use strict';

    app.factory("generalInfo", getGeneralInfo);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getGeneralInfo ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:area_id',
            {
                entity:     'area',
                area_id:    ':area_id', // значение параметра задаётся в контроллере
                fields:     ':fields',
                filter:     ':filter'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();