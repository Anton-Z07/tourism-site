(function () {
    'use strict';

    app.controller('historyCtrl', historyCtrl);

    /**
     *
     * @param generalInfo
     * @param $stateParams
     */
    function historyCtrl(generalInfo, $stateParams) {
        var that = this,
            countryAlias = $stateParams.countryAlias;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'history_text'
            },
            function (data) {
                that.history_text = data.history_text;
            });
    }
})();