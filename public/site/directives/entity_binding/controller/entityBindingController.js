(function () {
    'use strict';

    app.controller('EntityBindingController', entityBindingController);

    function entityBindingController(generalInfo, $stateParams) {

        var that         = this,
            filter       = '',
            entity,
            entityItemId,
            countryId = $stateParams.countryAlias;

        //в будущем лучше использовать наследование контроллеров
        if (typeof $stateParams.generalType != 'undefined') {
            entity       = 'area';
            //entityItemId = $stateParams.countryAlias;
        }
        else if (typeof $stateParams.landmarkId != 'undefined') {
            entity       = 'landmark';
            //entityItemId = $stateParams.landmarkId;
        }
        else {
            entity       = $stateParams.entityName;
            //entityItemId = $stateParams.entityItemId;
        }


        if (entity != 'landmark')
        {
            filter = 'area.system:eq:0';
        }

        generalInfo.query(
            {
                area_id: countryId,
                fields   : 'upper',
                filter   : filter
            },
            function(data) {
                that.bind_areas = data.upper;

                var current_area = {
                    id        : data.id,
                    area_type : data.area_type,
                    name      : data.name
                };

                that.bind_areas.push(current_area);
            }
        );
    }
})();