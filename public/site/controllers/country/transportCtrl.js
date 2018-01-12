(function () {
    'use strict';

    app.controller('transportCtrl', transportCtrl);

    /**
     *
     * @param generalInfo
     * @param $stateParams
     */
    function transportCtrl(generalInfo, $stateParams) {
        var that = this,
            countryAlias = $stateParams.countryAlias;

        generalInfo.query(
            {
                area_id: countryAlias,
                fields: 'transport_text'
            },
            function (data) {
                that.transport_text = data.transport_text;
            });
    }
})();