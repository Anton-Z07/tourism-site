/**
 * People Model
 **/
(function($, cultturist) {
    cultturist.addModel('people', {
        image: 'image.files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", validation: { required: true}},
            original_name: { type: "string"},
            detail: { type: "string"},
            alias: { type: "string" },
            born: { type: "date"},
            born_year: { type: "number", defaultValue: 2014, validation: {min:-3000, max:2100, step:1}},
            born_abt: { type: "boolean", defaultValue: false},
            death: { type: "date"},
            death_year: { type: "number", defaultValue: 2014, validation: {min:-3000, max:2100, step:1}},
            death_abt: { type: "boolean", defaultValue: false},
            alive: { type: "boolean", defaultValue: false},
            status: { type: "number", defaultValue: 1, validation: {min:0, max:127, step:1}},
            sticky: { type: "boolean", defaultValue: false},
            like_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            view_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            comment_count: { type: "number", defaultValue: 0, validation: {min:0, step:1}},
            image: { type: "values", defaultValue: 13, validation: {min:1}},
            web_image: { type: "values", defaultValue: 1, validation: {min:1}},
            area: { type: "values", pivot:{
                'system': { type: "boolean", defaultValue: false, title: "Системная"}
            }, multiple: true},
            comment: { type: "values", multiple: true},
            like: { type: "values", multiple: true},
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Имя", transport: 'name.ru', sortable: false},
            { field: "original_name", title: "Оригинальное имя", transport: 'original_name.ru', sortable: false, hidden:true},
            { field: "detail", title: "Текст", atype: 'text', transport: 'detail.ru', sortable: false, hidden:true},
            { field: "alias", title: "Алиас"},
            { field: "born", title: "Родился день\/месяц", format: "{0:dd.MM}"},
            { field: "born_year", title: "Родился год", format: "{0:yyyy}"},
            { field: "born_abt", title: "Родился примерно"},
            { field: "death", title: "Умер день\/месяц", format: "{0:dd.MM}"},
            { field: "death_year", title: "Умер год", format: "{0:yyyy}"},
            { field: "death_abt", title: "Умер примерно"},
            { field: "alive", title: "Ещё жив"},
            { field: "status", title: "Статус", width:60, hidden:true},
            { field: "sticky", title: "Выбор редакции"},
            { field: "like_count", title: "Кол-во Лайков", hidden:true},
            { field: "view_count", title: "Кол-во просмотров", hidden:true},
            { field: "comment_count", title: "Кол-во комментариев", hidden:true},
            { field: "image", title: "Главная картинка", width:100, atype: 'image', transport: 'image.files.id', filterable: false, sortable: false, hidden:true},
            { field: "web_image", title: "Картинка для моб.", width:100, atype: 'image', transport: 'web_image.files.path', filterable: false, sortable: false, hidden:true},
            { field: "area", title: "Страна(город, регион)", width:100, transport: 'area.id',/* atype: 'area', filterable: false, hidden:true,*/ sortable: false},
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
                        '<div class="leftpin">Дата рождения(день/месяц): </div>'+
                        '<div class="rightpin"><input data-role="datepicker" data-format="{0:dd.MM}" name="born" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Год рождения: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="born_year" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Дата рождения примерна: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="born_abt" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Дата смерти(день/месяц): </div>'+
                        '<div class="rightpin"><input data-role="datepicker" data-format="{0:dd.MM}" name="death" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Год смерти: </div>'+
                        '<div class="rightpin"><input data-role="numerictextbox" data-format="\\#" name="death_year" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Дата смерти примерна: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="death_abt" /></div>'+
                    '</div>'+
                    '<div class="cellpin">'+
                        '<div class="leftpin">Ещё жив: </div>'+
                        '<div class="rightpin"><input type="checkbox" name="alive" /></div>'+
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
            status: function (value, model) {
                var result = '';
                $.each(model.options.status, function(index, item) {
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
            
            var defaultView = function(alive) {
                var death = content.find('input[name=death]').eq(0).data('kendoDatePicker');
                var death_year = content.find('input[name=death_year]').eq(0).data('kendoNumericTextBox');
                var death_abt = content.find('input[name=death_abt]').eq(0);
                
                if(alive) {
                    death_abt.attr('disabled', 'disabled').addClass('k-state-disabled');
                } else {
                    death_abt.removeAttr('disabled').removeClass('k-state-disabled');
                }
                death.enable(!alive);
                death_year.enable(!alive);
            };
            
            defaultView(dataModel.alive);
            
            dataModel.bind("change", function(e) {
                if(e.field === 'alive') {
                    defaultView(this.alive);
                }
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


