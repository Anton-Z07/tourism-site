(function () {
    'use strict';

    app.controller('areaAlbumController', areaAlbumController);

    function areaAlbumController(generalInfo, $stateParams, mainConfig) {

        var that         = this,
            countryId    = $stateParams.countryAlias;

        that.baseUrl = mainConfig.basePublicUrl;

        generalInfo.query(
            {
                area_id  : countryId,
                fields   : 'image.files'
            },
            function(data) {
                that.area = data;
            }
        );
    }
})();