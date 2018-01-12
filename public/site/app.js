'use strict';
// Declare app level module which depends on views, and components
var app = angular.module(
    'myApp',
    [
        //'ngRoute',            // стандартный роутер
        'ngResource',           // запросы к API
        'ngSanitize',           // вывод html с разметкой
        'ui.router',            // замена стандартному роутеру
        'myApp.countrieslist',  // странца списка стран
        'myApp.countrypage',    // страница страны
        'myApp.landmarkpage',   // страница достопримечательности
        //'myApp.dev',            // development config, config\main.js
        'myApp.prod',         // production config, config\main.js
        'myApp.version',
        'myApp.phrases',        // надписи, часто встречающиеся на сайте
        'hyperResource',        // angular-hyper-resource для hal+json запросов
        'ngMap',                // google maps directives | https://github.com/allenhwkim/angularjs-google-maps
        'wu.masonry',           // Cascading grid layout library | https://github.com/passy/angular-masonry
        'ngLoadScript'
    ]);