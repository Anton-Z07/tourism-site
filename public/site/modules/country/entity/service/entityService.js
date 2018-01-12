(function() {
    'use strict';

    app.factory("entityInfo", getEntityInfo);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getEntityInfo ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity_name/:entity_id',
            {
                entity_name : ':entity_name',
                entity_id   : ':entity_id',
                fields      : ':fields'
            },
            {
                query: {
                    method: "GET"
                }
            }
        );
    }
})();