(function () {
    'use strict';

    app.controller('generalController', generalController);

    /**
     *
     * @param general
     * @param $stateParams
     */
    function generalController(general, $stateParams) {
        var that = this,
            countryAlias = $stateParams.countryAlias,
            generalType  = $stateParams.generalType;

        if (generalType != 'detail'){
            generalType = generalType + '_text';
        }

        general.query(
            {
                area_id: countryAlias,
                fields : generalType
            },
            function (data) {
                that.text = data[generalType];
            });
    }
})();