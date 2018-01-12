/**
 * FileGroup Model
 **/
(function($, cultturist) {
    cultturist.addModel('filegroup', {
        image: 'files',
        name: 'name',
        fields: {
            id: { type: "number"},
            name: { type: "string", validation: { required: true}},
            resource_type: { type: "number"},
            files: { type: "values", multiple: true},
            created_at: { type: "date", untouchable: false},
            updated_at: { type: "date", untouchable: false}
        },
        columns: [
            { field: "id", title: "ID", width:60},
            { field: "name", title: "Название", transport: 'name.ru', sortable: false},
            { field: "resource_type", title: "Тип", width:80},
            { field: "files", title: "Текст", atype: 'file', transport: 'files', filterable: false, sortable: false, hidden:true},
            { field: "created_at", title: "Создан", format: "{0:dd.MM.yyyy HH:mm:ss}", hidden:true},
            { field: "updated_at", title: "Обновлён", format: "{0:dd.MM.yyyy HH:mm:ss}", hidden:true},
            { command: [{ name: "destroy", text: "Удалить" }], width: 120}
        ],
        templates: {
            area_type: function (value, model) {
                var result = '';
                $.each(model.options.resourceType, function(index, item) {
                    if(item.id == value) {
                        result = item.name;
                    }
                });
                return result;
            }
        },
        filterables: {
            area_type: function(element, model) {
                element.kendoDropDownList({
                    dataTextField: "name",
                    dataValueField: "id",
                    dataSource: {
                        data: model.options.resourceType
                    },
                    optionLabel: "--Выберите значение--"
                });
            }
        },
        options:{
            resourceType : [
                { name: "Неустановлен", id: 0 },
                { name: "Страна(Регион, Город), ", id: 1 },
                { name: "Достопримечательность", id: 2 }
            ]
        }
    });
})(jQuery, window.cultturist);

function standartAreFiltering(element, model) {
    var countriesInput = $('<input type="text" id="countries" />'),
        regionsInput = $('<input type="text" />'),
        citiesInput = $('<input type="text" />'),
        areaModel = cultturist.getModel('area'),
        countrySelected = 0,
        regionSelected = 0,
        citySelected = 0;

    element.after(citiesInput)
            .after(regionsInput)
            .after(countriesInput).attr('type', 'hidden');

    var countries = countriesInput.kendoDropDownList({
        dataTextField: "name",
        dataValueField: "id",
        optionLabel: "--Выберите страну--",
        dataSource: areaModel.getDataSource({
            filter: [{ field: "area_type", operator: "eq", value: "0" }]
        }),
        cascade: function(e) {
            var rds = regions.dataSource,
                cds = cities.dataSource;

            countrySelected = parseInt(this.value());
            cds.data([]);
            rds.data([]);
            cities.enable(false);
            regions.enable(false);

            if(countrySelected > 0) {

                element.val(countrySelected);

                $.ajax(areaModel.url+'/'+countrySelected+'/?fields=lower', {
                    cache: true,
                    dataType: "json",
                    contentType: "application/json",
                    type: "GET"
                }).done(function(data) {
                    if($.isArray(data.lower)) { 
                        rds.data(data.lower);
                        regions.enable(true);
                    }
                });
            } else {
                element.val("");
            }
            element.trigger("change");
        }
    }).data("kendoDropDownList");

    var regions = regionsInput.kendoDropDownList({
        autoBind: false,
        enable: false,
        dataTextField: "name",
        dataValueField: "id",
        optionLabel: "--Выберите регион--",
        dataSource: new kendo.data.DataSource({
            schema: {
                model: areaModel.schema
            }
        }),
        cascade: function(e) {
            var ds = cities.dataSource;

                regionSelected = parseInt(this.value());
                ds.data([]);
                cities.enable(false);

            if(regionSelected > 0) {
                element.val(regionSelected);
                $.ajax(areaModel.url+'/'+regionSelected+'/?fields=lower', {
                    cache: true,
                    dataType: "json",
                    contentType: "application/json",
                    type: "GET"
                }).done(function(data) {
                    if($.isArray(data.lower)) {
                        ds.data(data.lower);
                        cities.enable(true);
                    }
                });
            } else {
                element.val(countrySelected || "");
            }

            element.trigger("change");
        }
    }).data("kendoDropDownList");

    var cities = citiesInput.kendoDropDownList({
        autoBind: false,
        enable: false,
        dataTextField: "name",
        dataValueField: "id",
        optionLabel: "--Выберите город--",
        dataSource: new kendo.data.DataSource({
            schema: {
                model: areaModel.schema
            }
        }),
        cascade: function(e) {
            citySelected = parseInt(this.value());

            if(citySelected > 0) {
                element.val(citySelected);
            } else {
                element.val(regionSelected || countrySelected || "");
            }

            element.trigger("change");
        }
    }).data("kendoDropDownList");
};


if (kendo.ui.Editor) {
    kendo.ui.Editor.prototype.options.tools = [
        "bold",
        "italic",
        "underline",
        "strikethrough",
        "justifyLeft",
        "justifyCenter",
        "justifyRight",
        "justifyFull",
        "insertUnorderedList",
        "insertOrderedList",
        "indent",
        "outdent",
        "createLink",
        "unlink",
        "insertImage",
        "insertFile",
        "subscript",
        "superscript",
        "createTable",
        "addRowAbove",
        "addRowBelow",
        "addColumnLeft",
        "addColumnRight",
        "deleteRow",
        "deleteColumn",
        "viewHtml",
        "formatting",
        "cleanFormatting",
        "fontName",
        "fontSize",
        "foreColor",
        "backColor"
    ];
}