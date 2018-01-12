/**
 * Landmark Model
 **/
(function($, cultturist) {
    cultturist.addModel('landmark', {
        image: 'image.files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", validation: { required: true}},
            original_name: { type: "string"},
            text: { type: "string"},
            full_text: { type: "string"},
            alias: { type: "string" },
            rating: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            like_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            view_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            look_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            comment_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            build: { type: "date"},
            build_year: { type: "number", defaultValue: 2014, validation: {min:-3000, max:2100, step:1}},
            build_abt: { type: "boolean", defaultValue: false},
            adress: { type: "string"},
            status: { type: "number", defaultValue: 1, validation: {min:0, max:127, step:1}},
            sticky: { type: "boolean", defaultValue: false},
            latitude: { type: "string", defaultValue: 0},
            longitude: { type: "string", defaultValue: 0},
            regional: { type: "boolean", defaultValue: false},
            image: { type: "values", defaultValue: 10, validation: {min:1}},          
            gallery: { type: "values", multiple: true},     
            property: { type: "values", multiple: true}, 
            childiblock: { type: "values", multiple: true},            
            parentiblock: { type: "values", multiple: true},
            routepoint: { type: "values", multiple: true},
            route: { type: "values", multiple: true},          
            area: { type: "values", pivot:{
                'vicinity': { type: "boolean", defaultValue: false, title: "Окрестность", readonly: true},
                'distance' :{ type: "number", defaultValue: 0, validation: {min:0, max:500, step:10}, title: "Расстояние"},
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }},
            comment: { type: "values", multiple: true},
            like: { type: "values", multiple: true},
            look: { type: "values", multiple: true},
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Название", transport: 'name.ru', sortable: false},
            { field: "original_name", title: "Оригинальное наз.", transport: 'original_name.ru', sortable: false, hidden:true},
            { field: "text", title: "Текст", atype: 'text', transport: 'text.ru', sortable: false, hidden:true},
            { field: "full_text", title: "Детальный текст", atype: 'text', transport: 'full_text.ru', sortable: false, hidden:true},
            { field: "alias", title: "Алиас", hidden:true},
            { field: "rating", title: "Оценка", width:80},
            { field: "like_count", title: "Кол-во Лайков", hidden:true},
            { field: "view_count", title: "Кол-во просмотров", hidden:true},
            { field: "look_count", title: "Кол-во 'Я был здесь'", hidden:true},
            { field: "comment_count", title: "Кол-во комментариев", hidden:true},
            { field: "build", title: "Построен", format: "{0:dd.MM.yyyy}", hidden:true},
            { field: "build_year", title: "Год постройки", hidden:true},
            { field: "build_abt", title: "Построен примерно", hidden:true},
            { field: "adress", title: "Адрес", hidden:true},
            { field: "status", title: "Статус", hidden:true},
            { field: "sticky", title: "Выбор редакции"},
            { field: "property", title: "Тип", transport: 'property.id', filterable: false, sortable: false},
            { field: "latitude", title: "Широта"},
            { field: "longitude", title: "Долгота"},
            { field: "regional", title: "Региональная"},
            { field: "image", title: "Главная картинка", atype: 'image', transport: 'image.files.id', filterable: false, sortable: false, hidden:true},
            { field: "gallery", title: "Галерея", atype: 'landmark_gallery', transport: 'gallery.id', filterable: false, sortable: false, hidden:true},
            { field: "childiblock", title: "Привязанные Инф. блоки", atype: 'iblock', transport: 'childiblock.id', filterable: false, sortable: false, hidden:true},
            { field: "parentiblock", title: "Родительский Инф. блоки", atype: 'iblock', transport: 'parentiblock.id', filterable: false, sortable: false, hidden:true},
            { field: "routepoint", title: "Точки остановок", atype: 'routepoint', transport: 'routepoint.id', filterable: false, sortable: false, hidden:true},
            { field: "route", title: "Маршруты", atype: 'route', transport: 'route.id', filterable: false, sortable: false, hidden:true},
            { field: "area", title: "Страна(город, регион)", width:100, atype: 'area', transport: 'area.id'/*, filterable: false*/, sortable: false},
            { field: "like", title: "Лайки", atype: 'like', transport: 'like.id', filterable: false, sortable: false, hidden:true},
            { field: "look", title: "Я здесь был", atype: 'look', transport: 'look.id', filterable: false, sortable: false, hidden:true},
            { field: "comment", title: "Комментарии", atype: 'comment', transport: 'comment.id', filterable: false, sortable: false, hidden:true},
            { field: "created_at", title: "Создан", format: "{0:dd.MM.yyyy HH:mm:ss}", hidden:true},
            { field: "updated_at", title: "Обновлён", format: "{0:dd.MM.yyyy HH:mm:ss}", hidden:true},
            { command: [{ name: "destroy", text: "Удалить" },{ name: "edit", text: {
                            "edit": "Редактировать",
                            "update": "Обновить",
                            "cancel": "Отмена"
            }}], width: 160}
        ],
        template: '<ul id="panelbar">'+
            '<li class="k-state-active">'+
                '<span class="k-link k-state-selected">Основные параметры</span>'+
                '<div class="pan-in">'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">ID: </div>'+
                        '<div class="rightpin"><input type="text" readonly class="k-input k-textbox" name="id" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Название: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="name" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Оригинальное Название: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="original_name" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Алиас: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="alias" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Оценка: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="rating" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Статус: </div>'+
                        '<div class="rightpin"><input required data-text-field="name" data-value-field="id" name="status" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Выбор редакции: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="sticky" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Адрес: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="adress" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Лайки: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="like_count" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Просмотры: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="view_count" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Комменты: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="comment_count" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Дата постройки день/месяц: </div>'+
                        '<div class="rightpin"><input data-role="datepicker" data-format="{0:dd.MM}" name="build" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Год постройки: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="build_year" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Дата постройки примерна: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="build_abt" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Региональная: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="regional" /></div>'+
                    '</div>'+
                    '<br class="clear" />'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Краткий текст</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Полный текст</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="full_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Местоположение</span>'+
                '<div>'+
                    '<div id="mapPosition"></div>'+
                    '<div class="pan-in">'+
                        '<div class="cellpin">'+
                            '<div class="leftpin">Широта: </div>'+
                            '<div class="rightpin"><input data-role="numerictextbox" data-spinners="false" data-decimals="13" name="latitude" /></div>'+
                        '</div>'+
                        '<div class="cellpin">'+
                            '<div class="leftpin">Долгота: </div>'+
                            '<div class="rightpin"><input data-role="numerictextbox" data-spinners="false" data-decimals="13" name="longitude" /></div>'+
                        '</div>'+
                    '</div><br class="clear" />'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка</span>'+
                '<div>'+
                    '<div id="roleImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Тип</span>'+
                '<div>'+
                    '<div id="roleProperty"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Привязка к гео объектам</span>'+
                '<div>'+
                    '<div id="roleArea"></div>'+
                '</div>'+
            '</li>'+
            '</ul>',
        templates: {
            status: function (value, model) {
                var result = '';
                $.each(model.options.status, function(index, item) {
                    if(item.id === value) {
                        result = item.name;
                    }
                });
                return result;
            },
            property: function (value, model) {
                var result = '';
                if(value instanceof kendo.data.ObservableArray) {
                    value.forEach(function(v, k) {
                        result += v.name+"<br />";
                    });
                }
                return result;
            },
            area: function (value, model) {
                var result = '';
                if(value instanceof kendo.data.ObservableArray) {
                    value.forEach(function(v, k) {
                        if(k > 10) {
                            return;
                        }
                        result += v.name+"<br />";
                    });
                    if(value.length > 10) {
                        result += 'и ещё '+(value.length - 10)+'объектов.';
                    }
                }
                return result;
            }
        },
        filterables: {
            status: function(element, model) {
                element.kendoDropDownList({
                    dataTextField: "name",
                    dataValueField: "id",
                    dataSource: {
                        data: model.options.status
                    },
                    optionLabel: "--Выберите значение--"
                });
            },
            area: standartAreFiltering
        },
        onedit: function(content, model, dataModel, panelData) {
            
            var status = content.find('input[name=status]').eq(0);
            status.kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: this.options.status
                }
            });
            
            content.find('#mapPosition').eq(0).kendoIMap({
                modelData: dataModel,
                panelData: panelData
            });
            
            content.find('#roleImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'image',
                modelData: dataModel
            });

            /*var relationChange = function(vicinity, modelData, field, data) {
                if(modelData[field] instanceof kendo.data.ObservableArray) {
                    var list = modelData[field];
                    list = list.filter(function(item) {
                        return !!item['vicinity'] !== vicinity;
                    });
                    list.push.apply(list, data);
                    modelData.set(field, list);
                } else {
                    modelData.set(field, data);
                }                
            };*/
            
            var areaModel = cultturist.getModel('area'),
                countrySelected = 0,
                regionSelected = 0,
                citySelected = 0,
                countries,
                regions,
                cities,
                linkButton = $('<a class="k-button k-button-icontext k-state-disabled" href="#"><span class="k-icon k-update"></span>Пересвязать</a>'),
                selectedArea = function(rel, area) {
                    if(rel.options.dataSource.get(area[areaModel.id])) {
                        return;
                    }
                    rel.options.dataSource.add({
                        id: area.id,
                        vicinity: 0,
                        distance: 0,
                        system: 0
                    });
                },
                deselectArea = function(rel, id) {
                    var item = rel.options.dataSource.get(id);
                    if(item) {
                        rel.options.dataSource.remove(item);
                    }                    
                },
                autoChangeAfterLoad = function(rel, items) {
                    if(!(items instanceof kendo.data.ObservableArray) || items.length < 1) {
                        return;
                    }
                    var ext = items.find(function(item) {
                        var id = parseInt(item[areaModel.id]);
                        if(id > 0) {
                            return rel.options.dataSource.get(id);
                        }
                        return false;
                    });
                    
                    if(ext) {
                        this.value(ext[areaModel.id]);
                    }
                },
                toggleTools = function(rel, level, mode) {
                    (level > 0 || !mode) && cities.enable(mode);
                    level < 1 && regions.enable(mode);
                    
                    
                    if(level > 0 && mode && dataModel.regional) {
                        linkButton.hasClass('k-state-disabled') && linkButton.removeClass('k-state-disabled');
                    } else {
                        !linkButton.hasClass('k-state-disabled') && linkButton.addClass('k-state-disabled');
                    }
                },
                areaRel = content.find('#roleArea').eq(0).empty().kendoRelations({
                    model: model,
                    modelField: 'area',
                    modelData: dataModel,
                    remote: cultturist.getModel('area'),
                    view: {
                        single: true,
                        delete: true
                    },
                    panelData: panelData,
                    tools: [
                        {
                            el: linkButton,
                            callback: function(el) {
                                var that = this;
                                
                                el.bind('click', function(e) {
                                    if(el.hasClass('k-state-disabled')) {
                                        return false;
                                    }
                                    $.ajax('/admin/roundlandmark/?id='+dataModel['id'], {
                                        type: 'GET',
                                        cache: true,
                                        dataType: "json",
                                        contentType: "application/json",
                                        success: function(r) {
                                            if(!$.isArray(r)) {
                                                return;
                                            }
                                            var items = [];
                                            r.forEach(function(item) {
                                                items.push({
                                                    id: item.id,
                                                    vicinity: 1,
                                                    distance: item.distance,
                                                    system: 1
                                                });
                                            });
                                            if(dataModel.area instanceof kendo.data.ObservableArray) {
                                                dataModel.area.forEach(function(item) {
                                                    if(item.system && item.area_type > 1) {
                                                        return;
                                                    }
                                                    items.push({
                                                        id: item.id,
                                                        vicinity: item.vicinity,
                                                        distance: item.distance,
                                                        system: 0
                                                    });
                                                });
                                            }
                                            that.options.dataSource.data(items);
                                        }
                                    });
                                    return false;
                                });
                            }
                        },                        
                        {
                            el: $('<input type="text" />'),
                            callback: function(el) {
                                var that = this;
                                cities = el.data("kendoDropDownList") || el.kendoDropDownList({
                                    autoBind: false,
                                    enable: false,
                                    dataTextField: "name",
                                    dataValueField: "id",
                                    optionLabel: "--Выберите город--",
                                    dataSource: new kendo.data.DataSource({
                                        schema: {
                                            model: areaModel.schema
                                        },
                                        change: function(e) {
                                            autoChangeAfterLoad.call(cities, that, e.items);
                                        }
                                    }),
                                    cascade: function(e) {
                                        
                                        if(citySelected > 0) {
                                            deselectArea(that, citySelected);
                                        }
                                        
                                        citySelected = parseInt(this.value());
                                        
                                        
                                        if(citySelected > 0) {
                                            selectedArea(that, this.dataSource.get(citySelected));
                                        }
                                    }
                                }).data("kendoDropDownList");
                            }
                        },
                        {
                            el: $('<input type="text" />'),
                            callback: function(el) {
                                var that = this;
                                regions = el.data("kendoDropDownList") || el.kendoDropDownList({
                                    autoBind: false,
                                    enable: false,
                                    dataTextField: "name",
                                    dataValueField: "id",
                                    optionLabel: "--Выберите регион--",
                                    dataSource: new kendo.data.DataSource({
                                        schema: {
                                            model: areaModel.schema
                                        },
                                        change: function(e) {
                                            autoChangeAfterLoad.call(regions, that, e.items);
                                        }
                                    }),
                                    cascade: function(e) {
                                        var ds = cities.dataSource;
                                            
                                        if(citySelected > 0) {
                                            deselectArea(cities, citySelected);
                                        }

                                        if(regionSelected > 0) {
                                            deselectArea(that, regionSelected);
                                        }

                                        regionSelected = parseInt(this.value());
                                        ds.data([]);
                                        toggleTools(that, 1, false);

                                        if(regionSelected > 0) {
                                            selectedArea(that, this.dataSource.get(regionSelected));
                                            $.ajax(areaModel.url+'/'+regionSelected+'/?fields=lower', {
                                                cache: true,
                                                dataType: "json",
                                                contentType: "application/json",
                                                type: "GET"
                                            }).done(function(data) {
                                                if($.isArray(data.lower)) {
                                                    ds.data(data.lower);
                                                    toggleTools(that, 1, true);
                                                }
                                            });
                                        }
                                    }
                                }).data("kendoDropDownList");
                            }
                        },
                        {
                            el: $('<input type="text" id="countries" />'),
                            callback: function(el) {
                                var that = this;
                                countries = el.data("kendoDropDownList") || el.kendoDropDownList({
                                    dataTextField: "name",
                                    dataValueField: "id",
                                    optionLabel: "--Выберите страну--",
                                    dataSource: areaModel.getDataSource({
                                        filter: [{ field: "area_type", operator: "eq", value: "0" }],
                                        change: function(e) {
                                            autoChangeAfterLoad.call(countries, that, e.items);
                                        }
                                    }),
                                    cascade: function(e) {
                                        var rds = regions.dataSource,
                                            cds = cities.dataSource;
                                        
                                        
                                        if(citySelected > 0) {
                                            deselectArea(cities, citySelected);
                                        }

                                        if(regionSelected > 0) {
                                            deselectArea(regions, regionSelected);
                                        }
                                        
                                        if(countrySelected > 0) {
                                            deselectArea(that, countrySelected);
                                        }
                                        
                                        countrySelected = parseInt(this.value());
                                        cds.data([]);
                                        rds.data([]);
                                        toggleTools(that, 0, false);

                                        if(countrySelected > 0) {
                                            selectedArea(that, this.dataSource.get(countrySelected));

                                            $.ajax(areaModel.url+'/'+countrySelected+'/?fields=lower', {
                                                cache: true,
                                                dataType: "json",
                                                contentType: "application/json",
                                                type: "GET"
                                            }).done(function(data) {
                                                if($.isArray(data.lower)) { 
                                                    rds.data(data.lower);
                                                    toggleTools(that, 0, true);
                                                }
                                            });
                                        } else {
                                        }
                                    }
                                }).data("kendoDropDownList");
                            }
                        }
                    ]
                    /*multiple: false,
                    pivotFilter: {'vicinity': [false, 0]},
                    pivotList: {
                        'vicinity': { visible: false},
                        'distance': { visible: false}
                    },
                    onModelChange: function(modelData, field, data, multiple) {
                        relationChange(false, modelData, field, data);
                    }*/
                }).data("kendoRelations");
            
            content.find('#roleProperty').eq(0).kendoRelations({
                model: model,
                modelField: 'property',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('landmarkproperty')
            });
        },
        options:{
            status: [
                { name: "Не опубликован", id: 0 },
                { name: "Опубликован", id: 1 }
            ]
        }
    });
})(jQuery, window.cultturist);


