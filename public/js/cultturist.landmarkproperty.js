/**
 * LandmarkProperty Model
 **/
(function($, cultturist) {
    cultturist.addModel('landmarkproperty', {
        image: 'icon.files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", validation: { required: true}},
            icon: { type: "values", defaultValue: 1, validation: {min:1}},
            landmark: { type: "values", multiple: true},     
            lower: { type: "values", multiple: true},     
            upper: { type: "values"},
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Название", transport: 'name.ru', sortable: false},
            { field: "icon", title: "Иконка", width:100, atype: 'image', transport: 'icon.files.id', filterable: false, sortable: false},
            { field: "landmark", title: "Достопримечательность", width:50, transport: 'landmark.id', filterable: false, sortable: false, hidden:true},
            { field: "lower", title: "Под-свойство", width:50, transport: 'lower.id', filterable: false, sortable: false, hidden:true},
            { field: "upper", title: "Над-свойство", width:50, transport: 'upper.id', filterable: false, sortable: false, hidden:true},
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
                    '<br class="clear" />'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Иконка</span>'+
                '<div>'+
                    '<div id="roleIcon"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Связанные достопримечательности</span>'+
                '<div>'+
                    '<div id="roleLandmark"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Свойство-дети, вложенные в данное свойство</span>'+
                '<div>'+
                    '<div id="roleLower"></div>'+
                '</div>'+
            '</li>'+
            '<li>'+
                '<span class="k-link">Свойство родитель, к которому относится данное свойство</span>'+
                '<div>'+
                    '<div id="roleUpper"></div>'+
                '</div>'+
            '</li>'+
            '</ul>',
        onedit: function(content, model, dataModel, panelData) {
            
            content.find('#roleIcon').eq(0).kendoCustomUpload({
                model: cultturist.getModel('filegroup'),
                modelField: 'icon',
                modelData: dataModel
            });
            
            content.find('#roleLandmark').eq(0).kendoRelations({
                model: model,
                modelField: 'landmark',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('landmark')
            });
            
            content.find('#roleLower').eq(0).kendoRelations({
                model: model,
                modelField: 'lower',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('landmarkproperty')
            });
            
            content.find('#roleUpper').eq(0).kendoRelations({
                model: model,
                modelField: 'upper',
                modelData: dataModel,
                panelData: panelData,
                remote: cultturist.getModel('landmarkproperty')
            });
        }
    });
})(jQuery, window.cultturist);


