(function() {
    'use strict';

    app.controller('landmarkpageCtrl', landmarkpageCtrl);

    function landmarkpageCtrl (landmarkInfo, $stateParams, mainConfig) {
        var that            = this,
            countryAlias    = $stateParams.countryAlias,
            landmarkId      = $stateParams.landmarkId,
            fieldsArray    = [
                'full_text',
                'alias',
                'image.files',
                //'gallery.files',
                'property.icon.files'
            ],
            fields          = fieldsArray.join();

        that.baseUrl = mainConfig.basePublicUrl;

        landmarkInfo.query(
            {
                landmark_id     : landmarkId,
                fields          : fields
            },
            function(data) {
                that.landmarkInfo = data;
            }
        );
    }
})();