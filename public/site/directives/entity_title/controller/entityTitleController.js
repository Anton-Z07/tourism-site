(function () {
    'use strict';

    app.controller('EntityTitleController', entityTitleController);

    function entityTitleController(entityTitleInfo, $stateParams) {

        var that         = this,
            showWasHere  = false,
            entity,
            entityItemId;

        that.showTitle = false;
        that.isLandmark = false;

        // в будущем лучше использовать наследование контроллеров
        if (typeof $stateParams.generalType != 'undefined') {
            entity       = 'area';
            entityItemId = $stateParams.countryAlias;
        }
        else if (typeof $stateParams.landmarkId != 'undefined') {
            entity       = 'landmark';
            entityItemId = $stateParams.landmarkId;
            showWasHere  = true;
        }
        else {
            entity       = $stateParams.entityName;
            entityItemId = $stateParams.entityItemId;
            if (entity == 'landmark')
                that.isLandmark = true;
        }

        that.showWasHere = showWasHere;

        entityTitleInfo.query(
            {
                entity   : entity,
                entity_id: entityItemId
            },
            function(data) {
                that.entityInfo = data;
            }
        ).$promise.then(function(){
                that.showTitle = true;
            });
    }
})();