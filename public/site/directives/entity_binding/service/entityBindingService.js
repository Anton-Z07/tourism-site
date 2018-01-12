(function() {
    'use strict';

    app.factory("entityBindingInfo", getEntityBindingInfo);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getEntityBindingInfo ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:entity_id',
            {
                entity   : ':entity',
                entity_id: ':entity_id',
                fields   : ':fields',
                filter   : ':filter'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();