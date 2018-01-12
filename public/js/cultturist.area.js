/**
 * Area Model
 **/
(function($, cultturist) {    
    cultturist.addModel('area', {
        image: 'image.files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", defaultValue: "", validation: { required: true}},
            alias: { type: "string" },
            hash: { type: "string" },
            area_type: { type: "number", defaultValue: 0, validation: { required: true, min:0, max:2, step:1}},
            rating: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            detail: { type: "string", defaultValue: ""},
            transport_text: { type: "string", defaultValue: ""},
            history_text: { type: "string", defaultValue: ""},
            landmark_text: { type: "string", defaultValue: ""},
            kitchen_text: { type: "string", defaultValue: ""},
            people_text: { type: "string", defaultValue: ""},
            event_text: { type: "string", defaultValue: ""},
            status: { type: "number", defaultValue: 1, validation: {min:0, max:127, step:1}},
            like_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            look_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            latitude: { type: "string", defaultValue: 0},
            longitude: { type: "string", defaultValue: 0},
            map: { type: "values", defaultValue: 1, validation: {min:1}},
            flag: { type: "values", defaultValue: 1, validation: {min:1}},
            image: { type: "values", defaultValue: 1, validation: {min:1}},
            mobile_image: { type: "values", defaultValue: 1, validation: {min:1}},
            mobile_map_file: { type: "values", defaultValue: 1, validation: {min:1}},
            empty_image: { type: "values", defaultValue: 1, validation: {min:1}},
            popular: { type: "boolean", defaultValue: false},
            people: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},            
            landmark: { type: "values", pivot:{
                'vicinity': { type: "boolean", defaultValue: false, title: "Окрестность"},
                'distance' :{ type: "number", defaultValue: 0, validation: {min:0, max:500, step:10}, title: "Расстояние"},
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},            
            event: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},            
            tour: { type: "values", multiple: true},            
            routepoint: { type: "values", multiple: true},            
            feature: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},            
            plusandminus: { type: "values", multiple: true},            
            kitchen: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},            
            lower: { type: "values", pivot: {
                'capital': { type: "boolean", defaultValue: false, title: "Выбранный гео-элемент является столицей редактируймого"}
            }, multiple: true},            
            upper: { type: "values", pivot: {
                'capital': { type: "boolean", defaultValue: false, title: "Редактируемый гео-элемент является столицей для выбронного"}
            }, multiple: true},            
            gallery: { type: "values", multiple: true},            
            post: { type: "values", multiple: true},            
            like: { type: "values", multiple: true},            
            look: { type: "values", multiple: true},        
            package: { type: "values"}, 
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Название", transport: 'name.ru', sortable: false},
            { field: "alias", title: "Алиас"},
            { field: "hash", title: "Хеш покупок"},
            { field: "area_type", title: "Тип", width:80, atype: 'areaType'},
            { field: "rating", title: "Рейтинг", width:80},
            { field: "detail", title: "Детальный текст", atype: 'text', transport: 'detail.ru', sortable: false},
            { field: "transport_text", title: "Т. 'Транспорт'", atype: 'text', transport: 'transport_text.ru', sortable: false, hidden:true},
            { field: "history_text", title: "Т. История", atype: 'text', transport: 'history_text.ru', sortable: false, hidden:true},
            { field: "landmark_text", title: "Достопримечательности", atype: 'text', transport: 'landmark_text.ru', sortable: false, hidden:true},
            { field: "kitchen_text", title: "Т. Кухня", atype: 'text', transport: 'kitchen_text.ru', sortable: false, hidden:true},
            { field: "people_text", title: "Т. Великие люди", atype: 'text', transport: 'people_text.ru', sortable: false, hidden:true},
            { field: "event_text", title: "Т. События", atype: 'text', transport: 'event_text.ru', sortable: false, hidden:true},
            { field: "status", title: "Статус", width:60, hidden:true},
            { field: "like_count", title: "Кол-во Лайков", hidden:true},
            { field: "look_count", title: "Кол-во 'Я был здесь'", hidden:true},
            { field: "latitude", title: "Широта"},
            { field: "longitude", title: "Долгота"},
            { field: "map", title: "Карта", width:100, atype: 'image', transport: 'map.files.path', filterable: false, sortable: false, hidden:true},
            { field: "flag", title: "Флаг", width:100, atype: 'image', transport: 'flag.files.path', filterable: false, sortable: false, hidden:true},
            { field: "image", title: "Главная картинка", width:100, atype: 'image', transport: 'image.files.path', filterable: false, sortable: false, hidden:true},
            { field: "mobile_image", title: "Картинка для моб.", width:100, atype: 'image', transport: 'mobile_image.files.path', filterable: false, sortable: false, hidden:true},
            { field: "mobile_map_file", title: "Оффлайн карта", transport: 'mobile_map_file.files.path', filterable: false, sortable: false, hidden:true},
            { field: "empty_image", title: "Пустая картинка", width:100, atype: 'image', transport: 'empty_image.files.path', filterable: false, sortable: false, hidden:true},
            { field: "popular", title: "Популярное", width:60, hidden:true},
            { field: "people", title: "Великие люди", width:50, atype: 'people', transport: 'people.id', filterable: false, sortable: false, hidden:true},
            { field: "landmark", title: "Достопримечательности", width:50, atype: 'landmark', transport: 'landmark.id', filterable: false, sortable: false, hidden:true},
            { field: "event", title: "События", width:50, atype: 'event', transport: 'event.id', hidden:true},
            { field: "tour", title: "Туры", width:50, atype: 'tour', transport: 'tour.id', hidden:true},
            { field: "routepoint", title: "Точки остановок", width:50, atype: 'routepoint', transport: 'routepoint.id', filterable: false, sortable: false, hidden:true},
            { field: "feature", title: "Фишки", width:50, atype: 'feature', transport: 'feature.id', filterable: false, sortable: false, hidden:true},
            { field: "plusandminus", title: "Плюсы и Минусы", width:50, atype: 'plusandminus', transport: 'plusandminus.id', filterable: false, sortable: false, hidden:true},
            { field: "kitchen", title: "Кухня", width:50, atype: 'kitchen', transport: 'kitchen.id', filterable: false, sortable: false, hidden:true},
            { field: "lower", title: "Влож-е регионы(города)", width:50, atype: 'area', transport: 'lower.id', filterable: false, sortable: false, hidden:true},
            { field: "upper", title: "Родит-е страны(регионы)", width:50, atype: 'area', transport: 'upper.id', filterable: false, sortable: false, hidden:true},
            { field: "gallery", title: "Галерея", width:50, atype: 'area_gallery', transport: 'gallery.id', filterable: false, sortable: false, hidden:true},
            { field: "post", title: "Посты", width:50, atype: 'post', transport: 'post.id', filterable: false, sortable: false, hidden:true},
            { field: "like", title: "Лайки", width:50, atype: 'like', transport: 'like.id', filterable: false, sortable: false, hidden:true},
            { field: "look", title: "Я здесь был", width:50, atype: 'look', transport: 'look.id', filterable: false, sortable: false, hidden:true},
            { field: "package", title: "Пакеты", width:90, transport: 'package.id', filterable: false, sortable: false},
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
                        '<div class="leftpin">Алиас: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="alias" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Тип: </div>'+
                        '<div class="rightpin"><input required data-text-field="name" data-value-field="id" name="area_type" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Рейтинг: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="rating" data-format="\\#" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Статус: </div>'+
                        '<div class="rightpin"><input required data-text-field="name" data-value-field="id" name="status" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Лайки: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="like_count" data-format="\\#" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Я здесь был: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="look_count" data-format="\\#" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Популярный: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="popular" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Пакет: </div>'+
                        '<div class="rightpin"><div id="rolePackage"></div></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Хеш пакета: </div>'+
                        '<div class="rightpin"><div id="roleHash"><input type="text" class="k-input k-textbox" name="hash" readonly /><input type="button" class="k-button" /></div></div>'+
                    '</div>'+
                    '<br class="clear" />'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Детальный текст</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="detail" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "Транспорт"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="transport_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "История"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="history_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "Достопримечательности"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="landmark_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "Кухня"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="kitchen_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "Великие люди"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="people_text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текс в разделе "События"</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="event_text" style="width: 100%; height:400px"></textarea>'+
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
                '<span class="k-link">Главная картинка</span>'+
                '<div>'+
                    '<div id="roleImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка для мобильных устройств</span>'+
                '<div>'+
                    '<div id="roleMobileImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка-карта</span>'+
                '<div>'+
                    '<div id="roleMapImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка-флаг</span>'+
                '<div>'+
                    '<div id="roleFlagImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка-заглушка</span>'+
                '<div>'+
                    '<div id="roleEmptyImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Офлаин карта(формат mbtiles)</span>'+
                '<div>'+
                    '<div id="roleMobileMapImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Гео привязка верхнего уровня</span>'+
                '<div>'+
                    '<div id="roleUpper"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Гео привязка нижнего уровня</span>'+
                '<div>'+
                    '<div id="roleLower"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Достопримечательности</span>'+
                '<div>'+
                    '<div id="roleLandmarks"></div>'+
                '</div>'+
            '</li>'+
            /*'<li>'+
                '<span class="k-link">Достопримечательности в окресностях</span>'+
                '<div>'+
                    '<div id="roleLandmarksVicinity"></div>'+
                '</div>'+
            '</li>'+*/
            '<li>'+
                '<span class="k-link">Великие люди</span>'+
                '<div>'+
                    '<div id="rolePeople"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">События</span>'+
                '<div>'+
                    '<div id="roleEvents"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Фишки</span>'+
                '<div>'+
                    '<div id="roleFeatures"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Кухня</span>'+
                '<div>'+
                    '<div id="roleKitchen"></div>'+
                '</div>'+
            '</li>'+
            '</ul>',
        templates: {
            area_type: function (value, model) {
                var result = '';
                $.each(model.options.areaType, function(index, item) {
                    if(item.id === value) {
                        result = item.name;
                    }
                });
                return result;
            },
            status: function (value, model) {
                var result = '';
                $.each(model.options.status, function(index, item) {
                    if(item.id === value) {
                        result = item.name;
                    }
                });
                return result;
            },
            package: function(value, model) {
                return value ? 'Пакет сформирован: ' + value['updated_at'] : 'Пакета нет';
            }
        },
        filterables: {
            area_type: function(element, model) {
                element.kendoDropDownList({
                    dataTextField: "name",
                    dataValueField: "id",
                    dataSource: {
                        data: model.options.areaType
                    },
                    optionLabel: "--Выберите значение--"
                });
            },
            status: function(element, model) {
                element.kendoDropDownList({
                    dataTextField: "name",
                    dataValueField: "id",
                    dataSource: {
                        data: model.options.status
                    },
                    optionLabel: "--Выберите значение--"
                });
            }
        },
        onedit: function(content, model, dataModel, panelData) {
            console.log(dataModel);
            var areaType = content.find('input[name=area_type]').eq(0);
            areaType.kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: this.options.areaType
                }
            });
            
            var status = content.find('input[name=status]').eq(0);
            status.kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: this.options.status
                }
            });
            
            var element = content.find('#rolePackage').eq(0),
                inf = $('<i></i>').appendTo(element),
                inp = $('<input type="button" class="k-button" />').appendTo(element),
                onDone = function(data) {
                    inp.removeAttr("disabled");
                    if(!data) {
                        return;
                    }
                    inf.text(data['updated_at']);
                    inp.val('Пересобрать');
                    dataModel.set('package', data);
                    dataModel.dirty = false;
                },
                onError = function(jq, type, message) {
                    inp.removeAttr("disabled");
                    if(jq.status === 504) {
                        alert('Создание пакета занимает слишком много времяни. Пакет будет сформирован позже! Не следует запускать создание пакета сново - дождитесь!');
                    } else if(jq.status === 423) {
                        alert('Нельзя запускать формирование пакета, недождавшийсь завершения ранее начатого! Дождитесь.');
                    } else {
                        alert(message);
                    }
                },
                gen = function rand(){
                    var text = "";
                    var possible = "abcdef0123456789";

                    for( var i=0; i < 8; i++ )
                        text += possible.charAt(Math.floor(Math.random() * possible.length));

                    return text;
                };
            
            if(dataModel['package'] && dataModel['package']['updated_at']) {
                inf.text(dataModel['package']['updated_at']);
                inp.val('Пересобрать');
            } else {
                inf.text('Пакета нет');
                inp.val('Собрать');
            }
            
            inp.bind('click', function(e) {
                inp.attr("disabled", true);
                if(dataModel['package']) {
                    var sendModel = dataModel['package'].toJSON();
                    delete sendModel.created_at;
                    delete sendModel.updated_at;
                    sendModel['area'] = sendModel.area.id+"";
                    
                    $.ajax('/api/package/' + dataModel['package']['id'], {
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        data: JSON.stringify(sendModel),
                        type: "PUT"
                    }).done(onDone).error(onError);
                } else {
                    $.ajax('/api/package', {
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        data: JSON.stringify({area: dataModel[model.id]+""}),
                        type: "POST"
                    }).done(onDone).error(onError);
                }                
            });
            
            $('#roleHash input[type=text]').bind('click', function(e) {
                var result = prompt('Введите хеш. Не забудьте создать соотвествующие пакеты в магазинах приложений', dataModel.hash || ''),
                    hash = result || '';
                
                if(confirm(hash ? 'Вы уверенны, что хотите установить хеш равный '+hash : 'Вы уверенны, что хотите обнулить хеш?')) {
                    dataModel.set('hash', hash);
                }
            });
            $('#roleHash input[type=button]').bind('click', function(e) {
                var hash = gen();
                
                if(confirm('Вы уверенны, что хотите установить хеш равный '+hash)) {
                    dataModel.set('hash', hash);
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
            
            content.find('#roleMobileImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'mobile_image',
                modelData: dataModel
            });
            
            content.find('#roleMapImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'map',
                modelData: dataModel
            });
            
            content.find('#roleFlagImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'flag',
                modelData: dataModel
            });
            
            content.find('#roleEmptyImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'empty_image',
                modelData: dataModel
            });
            
            content.find('#roleMobileMapImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'mobile_map_file',
                modelData: dataModel
            });
            
            var upperRelations = content.find('#roleUpper').eq(0).kendoRelations({
                model: model,
                modelField: 'upper',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('area'),
                leftDataSourceOptions: {
                    filter: [{ field: "area_type", operator: "lt", value: dataModel.area_type+"" }]
                }
            }).data('kendoRelations');
            
            var lowerRelations = content.find('#roleLower').eq(0).kendoRelations({
                model: model,
                modelField: 'lower',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('area'),
                leftDataSourceOptions: {
                    filter: [{ field: "area_type", operator: "gt", value: dataModel.area_type+"" }]
                }
            }).data('kendoRelations');
            
            
            dataModel.bind("change", function(e) {
                if(e.field === 'area_type') {
                    var uds = upperRelations.options.leftDataSource,
                        lds = lowerRelations.options.leftDataSource;
                        
                    uds.filter([{ field: "area_type", operator: "lt", value: this.area_type+"" }]);
                    lds.filter([{ field: "area_type", operator: "gt", value: this.area_type+"" }]);
                    uds.fetch();
                    lds.fetch();
                }
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
            
            content.find('#roleLandmarks').eq(0).kendoRelations({
                model: model,
                modelField: 'landmark',
                modelData: dataModel,
                panelData: panelData,
                readonly: true,
                view: {
                    single: true,
                    delete: false
                },
                remote: cultturist.getModel('landmark')/*,
                pivotFilter: {'vicinity': [false, 0]},
                pivotList: {
                    'vicinity': { visible: false},
                    'distance': { visible: false}
                },
                onModelChange: function(modelData, field, data, multiple) {
                    relationChange(false, modelData, field, data);
                }*/
            });
            
            /*content.find('#roleLandmarksVicinity').eq(0).kendoRelations({
                model: model,
                modelField: 'landmark',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('landmark'),
                pivotFilter: {'vicinity': [true, 1]},
                pivotList: {
                    'vicinity': { visible: false, defaultValue: true}
                },
                onModelChange: function(modelData, field, data, multiple) {
                    relationChange(true, modelData, field, data);
                }
            });*/
            
            content.find('#rolePeople').eq(0).kendoRelations({
                model: model,
                modelField: 'people',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('people')
            });
            
            content.find('#roleFeatures').eq(0).kendoRelations({
                model: model,
                modelField: 'feature',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('feature')
            });
            
            content.find('#roleKitchen').eq(0).kendoRelations({
                model: model,
                modelField: 'kitchen',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('kitchen')
            });
        },
        options:{
            areaType: [
                { name: "Страна", id: 0 },
                { name: "Регион", id: 1 },
                { name: "Город", id: 2 }
            ],
            status: [
                { name: "Не опубликован", id: 0 },
                { name: "Опубликован", id: 1 }
            ]
        }
    });
})(jQuery, window.cultturist);


