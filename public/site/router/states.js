(function () {  //using angular-ui-router
    'use strict';

    app.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {

        $urlRouterProvider.otherwise('/countries');

        var partialPath = 'views/countrypage/partials/',
            basePath    = 'views/',
            areaHeader  = 'areaHeader.html';

        $stateProvider
            .state('countries', {
                url: '/countries',
                templateUrl: basePath + 'countrieslist/countrieslist.html',
                controller: 'countrieslistCtrl',
                controllerAs: 'listCtrl'
            })
            .state('countrypage', {
                url: '/area/:countryAlias',
                views: {
                    '': {
                        templateUrl: basePath + 'countrypage/countrypage.html'
                    },
                    'area_header@countrypage': {
                        templateUrl: partialPath + areaHeader,
                        controller: 'countrypageCtrl',
                        controllerAs: 'countryCtrl'
                    },
                    'area_body@countrypage': {
                        templateUrl: 'modules/country/main/view/areaMain.html'
                    }
                }
            })
            .state('arealist', {
                url         : '/area/:countryAlias/list',
                templateUrl : 'modules/country/arealist/view/arealist.html',
                controller  : 'arealistController',
                controllerAs: 'arealistCtrl'
            })
            .state('countrypage.general', {
                url: "/general/:generalType",
                views: {
                    'area_body@countrypage': {
                        templateUrl : 'modules/country/general/view/general.html',
                        controller  : 'generalController',
                        controllerAs: 'gCtrl'
                    }
                }
            })
            .state('countrypage.landmark', {
                url: "/landmark?v",
                views: {
                    'area_body@countrypage': {
                        templateUrl: 'modules/country/landmark/view/landmarks.html',
                        controller: 'landmarksCtrl',
                        controllerAs: 'lmCtrl'
                    }
                }
            })
            .state('countrypage.entityList', {
                url: "/:entityName",
                views: {
                    'area_body@countrypage': {
                        templateUrl: "modules/country/entity/view/entityList.html",
                        controller: 'entityListController',
                        controllerAs: 'entListCtrl'
                    }
                }
            })
            .state('countrypage.entityPage', {
                url: '/:entityName/:entityItemId',
                views: {
                    'area_body@countrypage': {
                        templateUrl: "modules/country/entity/view/entityPage.html",
                        controller: 'entityPageController',
                        controllerAs: 'entPageCtrl'
                    }
                }
            });

        //$locationProvider.html5Mode(true); // Removes index.html in URL
    });
})();
