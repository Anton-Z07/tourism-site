(function() {
    'use strict';

    app.factory("landmarkProperties", getLandmarkProperties);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getLandmarkProperties ($resource, mainConfig){
        return $resource(
            mainConfig.apiUrl + ':entity/:property_id',
            {
                entity      : 'landmarkproperty',
                property_id : ':property_id',
                fields      : ':fields'
            },
            {
                query: {
                    method: "GET",
                    isArray: false
                }
            });
    }
})();