(function() {
    'use strict';

    app.factory("landmarkInfo", getLandmarkInfo);

    /**
     *
     * @param $resource
     * @param mainConfig
     * @returns {*}
     */
    function getLandmarkInfo ($resource, mainConfig) {
        return $resource(
            mainConfig.apiUrl + ':entity/:landmark_id',
            {
                entity      : 'landmark',
                landmark_id : ':landmark_id', // значение параметра задаётся в контроллере
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