(function() {
    'use strict';

    app.controller('entityPageController', entityPageController);

    function entityPageController (entityInfo, $stateParams, mainConfig, $resource) {
        var that            = this,
            entityName      = $stateParams.entityName,
            entityItemId    = $stateParams.entityItemId,
            fieldsArray     = [
                'text',
                'area',
                'detail',
                'image.files'
                ],
            fields;

        if (entityName === 'landmark'){
            fieldsArray.push('full_text');
            fieldsArray.push('property.icon.files');

            var closestLandmarks = $resource(mainConfig.apiUrl + 'landmark/closest_landmarks/:landmark_id?fields=:fields');
            closestLandmarks.query(
                { 
                    landmark_id: entityItemId,
                    fields: ['area','image.files'].join(',')
                },
                function(data) {
                    that.closestLandmarks = data;
                }
            );
        }

        fields = fieldsArray.join();

        that.baseUrl = mainConfig.basePublicUrl;

        entityInfo.query(
            {
                entity_name : entityName,
                entity_id   : entityItemId,
                fields      : fields
            },
            function(data) {
                that.entityInfo = data;
            }
        );
    }
})();