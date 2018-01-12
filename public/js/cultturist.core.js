(function($, kendo) {
    var dataSourceDefault =  {
            type: "cultturist",
            //batch: true,
            serverAggregates: true,
            serverPaging: true,
            serverSorting: true,
            serverFiltering: true,
            pageSize: 20
        },
        defaultErrorHandler = function (ajax, status, statusMessage) {
            try {
                var errorData = JSON.parse(ajax.responseText);
                console.log(ajax.status+": "+errorData.error.message);
            } catch(e) {
                console.log(ajax.status+": "+statusMessage);
            }
        },
        isEquealObjects = function(obj1, obj2) {
            
            if(obj1 === obj2) {
                return true;
            }
            
            if(typeof obj1 !== 'object' || typeof obj2 !== 'object') {
                return false;
            }
            
            for(var i in obj1) {
                if(!obj2.hasOwnProperty(i)) {
                    return false;
                }
                if(typeof obj1[i] !== typeof obj2[i]) {
                    return false;
                } else {
                    if(typeof obj1[i] === 'object') {
                        if(!isEquealObjects(obj1[i], obj2[i])) {
                            return false;
                        }
                    } else {
                        if(obj1[i] !== obj2[i]) {
                            return false;
                        }
                    }
                }
            }
            
            for(var i in obj2) {
                if(!obj1.hasOwnProperty(i)) {
                    return false;
                }
            }
            return true;
        },
        defaultStatusMap = {
            400: defaultErrorHandler,
            401: defaultErrorHandler,
            402: defaultErrorHandler,
            403: defaultErrorHandler,
            404: defaultErrorHandler,
            405: defaultErrorHandler,
            406: defaultErrorHandler,
            407: defaultErrorHandler,
            408: defaultErrorHandler,
            409: defaultErrorHandler,
            410: defaultErrorHandler,
            411: defaultErrorHandler,
            412: defaultErrorHandler,
            413: defaultErrorHandler
        },
        gridDefault = {
            //height: 800,
            sortable: true,
            filterable: {
                extra: false,
                messages: {
                    info: "Фильтры:",
                    filter: "Применить",
                    clear: "Очистить",
                    isTrue: "Да",
                    isFalse: "Нет",
                    and: "И",
                    or: "Или"
                },
                operators: {
                    values: {
                        eq: "равно",
                        neq: "не равно"
                    }
                }
            },
            pageable: true,
            columnMenu: true,
            resizable: true,
            reorderable: true,
            toolbar: [
                { name: "create", text: "Создать" }/*,
                { name: "save", text: "Сохранить" },
                { name: "cancel", text: "Отменить" }*/
            ]
        },
        cultturist = window.cultturist = window.cultturist || {
            addModel : function(modelName, data) {
                var extend = $.extend,
                    each = $.each,
                    proxy = $.proxy,
                    isFunction = $.isFunction,
                    onGridColumnChange = function() {
                        this.dataSource.transport.options.read.url = model.getTransport({
                            list:{
                                columns: this.columns
                            }
                        }).read.url;
                        this.dataSource.page(this.dataSource.page());
                    },
                    model = {
                        getTransportList: function(options) {
                            options = options || {};
                            
                            var that = this,
                                list = [],
                                columns = options.columns || that.columns;
                        
                            for(var i = 0, length = columns.length; i < length; i++) {
                                if(!columns[i].field) {
                                    continue;
                                }
                                
                                if(!that.transports[columns[i].field]) {
                                    continue;
                                }
                                
                                if(options.all || (!options.all && !columns[i].hidden)) {
                                    list.push(that.transports[columns[i].field]);
                                }
                            }
                            return list;
                        },
                        getTransport: function(options) {
                            options = options || {};
                            
                            var that = this,
                                result = {},
                                transportList = that.getTransportList(options.list);
                        
                            for(var i = 0, methods = ['read', 'create', 'update', 'destroy'], length = methods.length; i < length;i++) {
                                result[methods[i]] = {
                                    statusCode : extend({}, defaultStatusMap, options.statusMap)
                                };
                                if(methods[i] === 'read') {
                                    result[methods[i]].data = function() {
                                        return {modelName: modelName ? modelName : undefined};
                                    };
                                    result[methods[i]].url = transportList.length > 0 ? function() {
                                        return that.url + "?fields=" + transportList.join();
                                    } : that.url;
                                } else if(methods[i] === 'update' || methods[i] === 'destroy') {
                                    if(that.id) {
                                        result[methods[i]].url = function(resp) {
                                            return kendo.format(that.url+"/{0}", resp[that.id]);
                                        };
                                    } 
                                } else {
                                    result[methods[i]].url = that.url;
                                }
                            }
                            return result;
                        },
                        getDataSource: function(options) {
                            
                            options = options || {};
                            
                            var settings = extend({}, dataSourceDefault, {
                                transport: this.getTransport(options.columns),
                                schema: {
                                    model: this.schema
                                },
                                filter: [].concat($.isArray(options.filter) ? options.filter : []),
                                sort: [{ field: model.id, dir: "desc" }].concat($.isArray(options.sort) ? options.sort : [])
                            });
                            
                            if(options.pageSize > 0) {
                                settings.pageSize = options.pageSize;
                            }
                            
                            if(options.change) {
                                settings.change = options.change;
                            }
                            if(options.sync) {
                                settings.sync = options.sync;
                            }
                            return new kendo.data.DataSource(settings);
                        },
                        getGridConfig: function(options) {
                            options = options || {};
                            var that = this,
                                modelSnapshot = null,
                                modelSnapshotJSON = null,
                                grid,
                                saveing = false;
                            
                            return extend({}, gridDefault, {
                                dataSource: that.getDataSource(options.dataSource),
                                columns: that.columns,
                                columnShow: onGridColumnChange,
                                columnHide: onGridColumnChange,
                                editable: {
                                    mode: "popup",
                                    template: kendo.template(that.template),
                                    window: {
                                        width: "1000px",
                                        height: "800px",
                                        title: "Редактирование " + modelName,
                                        iframe: true,
                                        resize: true,
                                        close: function(e) {
                                            if(grid) {
                                                if(e.userTriggered) {
                                                    grid.cancelRow(); 
                                                }
                                                grid.dataSource.fetch(function() {
                                                    grid.refresh();
                                                });     
                                            }
                                            
                                            if(modelSnapshot) {
                                                modelSnapshot.dirty = false;
                                            }
                                            
                                            modelSnapshot = null;
                                            modelSnapshotJSON = null;
                                        }
                                    }
                                },
                                edit: function(e) {
                                    var dataModel = e.model,
                                        dataContainer = e.container,
                                        win = dataContainer.data('kendoWindow'),
                                        content = win.wrapper.children('.k-window-content'),
                                        scrollContainer = content.children(".km-scroll-container"),
                                        afterDone = function() {
                                            var panelbar = content.find('#panelbar').eq(0).kendoPanelBar({
                                                    expandMode: "single"
                                                }),
                                                panelData = panelbar.data('kendoPanelBar');
                                        
                                                panelbar.show();

                                            that.onedit(content, that, dataModel, panelData);
                                            modelSnapshot = dataModel;
                                            modelSnapshotJSON = modelSnapshot.toJSON();
                                        };

                                    saveing = false;
                                    grid = e.sender;
                                
                                    content = scrollContainer[0] ? scrollContainer : content;
                                    
                                    var id = dataModel[that.id],
                                        include = that.getTransportList({all:true});
                                    
                                    if(!dataModel.isNew()) {
                                        $.ajax(that.url+'/'+id+'/?fields='+include.join(), {
                                            cache: true,
                                            dataType: "json",
                                            contentType: "application/json",
                                            type: "GET"
                                        }).done(function(data) {
                                            if($.isPlainObject(data)) {
                                                $.each(data, function(index, item) {
                                                    if(dataModel.fields[index]) {
                                                        dataModel.set(index, item);
                                                    }
                                                });
                                                afterDone();
                                            }
                                        });
                                    } else {
                                        afterDone();
                                    }
                                },
                                save: function(e) {
                                    if (saveing) {
                                        e.preventDefault();
                                        return;
                                    }
                                    
                                    saveing = true;
                                    
                                    var sendModelSnapshotJSON = e.model.toJSON(),
                                        isNew = e.model.isNew();
                                    
                                    $.each(sendModelSnapshotJSON, function(field, value) {
                                        if(!that.fields[field]) {
                                            return;
                                        }
                                        
                                        var property = that.fields[field];
                                        
                                        if(property['untouchable'] === false) {
                                            e.model.set(field, null);
                                        }
                                        
                                        if(isNew || field === model.id) {
                                            return;
                                        } else if(value === modelSnapshotJSON[field]) {
                                            if(property['validation'] && property['validation']['required']) {
                                                return;
                                            }
                                            e.model.set(field, null);
                                        } else {
                                            if(value === undefined || value === null) {
                                                e.model.set(field, null);
                                                return;
                                            }
                                            
                                            if([true, false, 'true', 'false'].indexOf(value) >=0) {
                                                e.model.set(field, value ? 1 : 0);
                                                return;
                                            }
                                            
                                            if(property.type === 'values') {
                                                if(typeof value === 'object' && typeof modelSnapshotJSON[field] === 'object') {
                                                    if(isEquealObjects(value, modelSnapshotJSON[field])) {
                                                        e.model.set(field, null);
                                                        return;
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        getColumnFromField: function(field) {
                            var columns = this.columns;
                            
                            for(var i in columns) {
                                if(columns[i].field && columns[i].field === field) {
                                    return columns[i];
                                }
                            }
                            return false;
                        }
                    };

                model.id = data.id || 'id';
                model.image = data.image || false;
                model.name = data.name || false;
                model.url = data.url || "/api/"+modelName;
                model.fields = data.fields || {};
                model.columns = [];
                model.templates = data.templates || {};
                model.template = data.template || '';
                model.filterables = data.filterables || {};
                model.transports = {};
                model.onedit = data.onedit || function(){};
                model.options = data.options || {};
                model.schema = {id: model.id, fields: model.fields};
                
                if(model.fields) {
                    for(var i in model.fields) {
                        var opt = model.fields[i];
                        if(!opt.type || opt.type !== "boolean") {
                            continue;
                        }
                        if(!opt.parse || !$.isFunction(opt.parse)) {
                            model.fields[i].parse = function(value) {
                                if(typeof value === "string") {
                                    return ["true", "1", 1, true].indexOf(value.toLowerCase()) >= 0;
                                }
                                return value !== null ? !!value : value;
                            };
                        }
                    }
                }
                
                if(!data.columns || !$.isArray(data.columns)) {

                } else {
                   each(data.columns, function(field, column) {
                        if(column.field) {
                            if(!column.template) {
                                if(model.templates[column.field]) {
                                    if(isFunction(model.templates[column.field])) {
                                        column.template = function(data) {
                                            return model.templates[column.field](data[column.field], model);
                                        };
                                    } else {
                                        column.template = model.templates[column.field];
                                    }                                
                                } else if(column.atype === 'text') {
                                    column.template = function (data) {
                                        var str = kendo.htmlEncode(data[column.field]);
                                        return str ? str.toString().substring(0, 15) : '';
                                    };
                                } else if(column.atype === 'image') {
                                    column.template = function (data) {
                                        var src = '',
                                            altsrc = src;

                                        if(data[column.field] && data[column.field].files) {
                                            if(data[column.field].files[0]) {
                                                src = data[column.field].files[0].path;
                                            }
                                            if(data[column.field].files[1]) {
                                                altsrc = data[column.field].files[1].path;
                                            }
                                        }
                                        return '<img src="'+src+'" width="80px" alt-src="'+altsrc+'" />';
                                    };
                                } else if(model.fields[column.field].type === 'boolean') {
                                    column.template = function (data) {
                                        return data[column.field] ? 'Да' : 'Нет';
                                    };
                                }
                            }

                            if(!column.filterable && model.filterables[column.field]) {
                                if(isFunction(model.filterables[column.field])) {
                                    column.filterable =  {
                                        ui: function(element, fg) {
                                            return model.filterables[column.field](element, model);
                                        }
                                    };
                                } else {
                                    column.filterable = model.filterables[column.field];
                                }

                            }

                            if(column.transport) {
                                model.transports[column.field] = column.transport;
                                delete column.transport;
                            }
                        }

                        model.columns.push(column);

                   });
                }

                cultturist._models[modelName] = model;
            },
            getModel: function (modelName) {
                return cultturist._models[modelName] ? cultturist._models[modelName] : false;
            },
            _models: {}
        };
})(jQuery, kendo);