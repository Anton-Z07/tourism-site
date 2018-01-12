(function () {
    'use strict';

    app.controller('generalInfoCtrl', generalInfoCtrl);

    /**
     *
     * @param generalInfo
     * @param $stateParams
     */
    function generalInfoCtrl(generalInfo, $stateParams) {
        var that = this,
            countryAlias = $stateParams.countryAlias;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'detail'
            },
            function (data) {
                that.generalInfo = data.detail;
            });
    }
})();