(function() {
    'use strict';

    app.controller('countrypageCtrl', countrypageCtrl);

    function countrypageCtrl (generalInfo, $stateParams, mainConfig, landmarksHal) {
        var that = this,
            countryAlias = $stateParams.countryAlias;

        that.baseUrl = mainConfig.basePublicUrl;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'flag,upper'
            },
            function(data) {
                that.generalInfo = data;
            }
        );

        //landmarksHal.query(
        //    {
        //        //id: countryAlias
        //    },
        //    function(data) {
        //        that.lmHal = data;
        //    }
        //);
    }
})();