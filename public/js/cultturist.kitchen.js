/**
 * Kitchen Model
 **/
(function($, cultturist) {
    cultturist.addModel('kitchen', {
        image: 'image.files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", validation: { required: true}},
            original_name: { type: "string"},
            text: { type: "string"},
            type: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            alias: { type: "string" },
            status: { type: "number", defaultValue: 1, validation: {min:0, max:127, step:1}},
            sticky: { type: "boolean", defaultValue: false},
            like_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            view_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            comment_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            image: { type: "values", defaultValue: 11, validation: {min:1}},
            web_image: { type: "values", defaultValue: 1, validation: {min:1}},
            area: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},
            iblock: { type: "values", multiple: true},
            comment: { type: "values", multiple: true},
            like: { type: "values", multiple: true},
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Название", transport: 'name.ru', sortable: false},
            { field: "original_name", title: "Оригинальное наз.", transport: 'original_name.ru', sortable: false, hidden:true},
            { field: "text", title: "Текст", atype: 'text', transport: 'text.ru', sortable: false, hidden:true},
            { field: "type", title: "Тип", width:80},
            { field: "alias", title: "Алиас", width:80},
            { field: "status", title: "Статус", width:60, hidden:true},
            { field: "sticky", title: "Выбор редакции"},
            { field: "like_count", title: "Кол-во Лайков", hidden:true},
            { field: "view_count", title: "Кол-во просмотров", hidden:true},
            { field: "comment_count", title: "Кол-во комментариев", hidden:true},
            { field: "image", title: "Главная картинка", width:100, atype: 'image', transport: 'image.files.id', filterable: false, sortable: false, hidden:true},
            { field: "web_image", title: "Картинка для моб.", width:100, atype: 'image', transport: 'web_image.files.path', filterable: false, sortable: false, hidden:true},
            { field: "area", title: "Страна(город, регион)", width:100, transport: 'area.id'/*, atype: 'area', filterable: false, hidden:true*/, sortable: false},
            { field: "iblock", title: "Инф. блок", width:50, atype: 'iblock', transport: 'iblock.id', filterable: false, sortable: false, hidden:true},
            { field: "like", title: "Лайки", width:50, atype: 'like', transport: 'like.id', filterable: false, sortable: false, hidden:true},
            { field: "comment", title: "Комментарии", width:50, atype: 'comment', transport: 'comment.id', filterable: false, sortable: false, hidden:true},
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
                        '<div class="leftpin">Оригинальное название: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="original_name" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Алиас: </div>'+
                        '<div class="rightpin"><input type="text" class="k-input k-textbox" name="alias" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Тип: </div>'+
                        '<div class="rightpin"><input required data-text-field="name" data-value-field="id" name="type" /></div>'+
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
                        '<div class="leftpin">Лайки: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="like_count" data-format="\\#" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Просмотры: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="view_count" data-format="\\#" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Комменты: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" name="comment_count" data-format="\\#" /></div>'+
                    '</div>'+
                    '<br class="clear" />'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Текст</span>'+
                '<div>'+
                    '<textarea data-role="editor" name="text" style="width: 100%; height:400px"></textarea>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка для мобильных устройств</span>'+
                '<div>'+
                    '<div id="roleImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Картинка для сайта</span>'+
                '<div>'+
                    '<div id="roleWebImage"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Гео объекты привязки(страны, регионы, города)</span>'+
                '<div>'+
                    '<div id="roleArea"></div>'+
                '</div>'+
            '</li>'+
            '</ul>',
        templates: {
            type: function (value, model) {
                var result = '';
                $.each(model.options.type, function(index, item) {
                    if(item.id === value) {
                        result = item.name;
                    }
                });
                return result;
            },
            area: function (value, model) {
                var result = '';
                if(value instanceof kendo.data.ObservableArray) {
                    value.forEach(function(v, k) {
                        result += v.name+"<br />";
                    });
                }
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
            }
        },
        filterables: {
            type: function(element, model) {
                element.kendoDropDownList({
                    dataTextField: "name",
                    dataValueField: "id",
                    dataSource: {
                        data: model.options.type
                    },
                    optionLabel: "--Выберите значение--"
                });
            },
            area: standartAreFiltering,
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
            
            var type = content.find('input[name=type]').eq(0);
            type.kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: this.options.type
                }
            });
            
            var status = content.find('input[name=status]').eq(0);
            status.kendoDropDownList({
                autoBind: false,
                dataSource: {
                    data: this.options.status
                }
            });
            
            content.find('#roleImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'image',
                modelData: dataModel
            });

            content.find('#roleWebImage').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'web_image',
                modelData: dataModel
            });
            
            content.find('#roleArea').eq(0).kendoRelations({
                model: model,
                modelField: 'area',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('area')
            });
        },
        options:{
            type: [
                { name: "Неопределено", id: 0 },
                { name: "Блюдо", id: 1 },
                { name: "Напиток", id: 2 },
                { name: "Продукт", id: 3 }
            ],
            status: [
                { name: "Не опубликован", id: 0 },
                { name: "Опубликован", id: 1 }
            ]
        }
    });
})(jQuery, window.cultturist);


