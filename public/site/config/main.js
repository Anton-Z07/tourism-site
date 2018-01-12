angular.module('myApp.dev', []).constant('mainConfig', {
    apiUrl          : 'http://t.localhost/api/',
    basePublicUrl   : 'http://t.localhost',
    pagesize        : 9, // size
    googleMapsApiKey    : 'AIzaSyCRTh7wjjRr7ZpbnF55M5rlTUD2GGSFacY',
    filterSeparator : ';',
    areaPageArticleListSize : 200,
    areaPageStickedListSize : 6
});

angular.module('myApp.prod', []).constant('mainConfig', {
    apiUrl          : 'http://t.localhost/api/',
    basePublicUrl   : 'http://t.localhost',
    pagesize        : 9,
    googleMapsApiKey    : 'AIzaSyCRTh7wjjRr7ZpbnF55M5rlTUD2GGSFacY',
    filterSeparator : ';',
    areaPageArticleListSize : 200,
    areaPageStickedListSize : 6
});
