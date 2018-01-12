(function() {
    'use strict';

    app.factory("entityTitleInfo", getEntityTitleInfo);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getEntityTitleInfo ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:entity_id',
            {
                entity   : ':entity',
                entity_id: ':entity_id'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();