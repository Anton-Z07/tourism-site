(function () {
    'use strict';

    app.service(
        'landmarksHal',
        [
            'hResource',
            function(hResource) {
                return hResource('http://cultturist.kode/api/area/');
            }
        ]
    );
})();