(function() {
    'use strict';

    app.factory("general", getGeneral);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getGeneral ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:area_id',
            {
                entity:     'area',
                area_id:    ':area_id',
                fields:     ':fields'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();