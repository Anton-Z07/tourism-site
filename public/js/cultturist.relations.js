kendo_module({
    id: "relations",
    name: "Multiple Relationship",
    category: "web",
    description: "",
    depends: [ "core", "pager", "listview"]
});

(function($, undefined) {
    var kendo = window.kendo,
        Widget = kendo.ui.Widget,
        isFunction = kendo.isFunction,
        isArray = $.isArray,
        extend = $.extend,
        proxy = $.proxy,
        each = $.each,
        DOCUMENT = $(document),
        isLocalUrl = kendo.isLocalUrl,
        ObservableObject = kendo.data.ObservableObject,
        ObservableArray = kendo.data.ObservableArray,
        OPEN = "open",
        CLOSE = "close",
        LOCALE = {
            display: "{0} - {1} из {2} записей",
            empty: "Элементы отсутствуют",
            page: "Страницы",
            of: "из {0}",
            itemsPerPage: "Элементов на стрнице",
            first: "К первой странице",
            previous: "К предыдущей странице",
            next: "К следущей странице",
            last: "К последней странице",
            refresh: "Обновить"
        },
        HTMLTEMPLATE = '<div class="relations">'+
                            '<div class="tools"></div>'+
                            '<div class="sideContainer">'+
                                '<div id="filterSlide"></div>'+
                                '<div class="listView leftListView"></div>'+
                                '<div class="leftPager" class="k-pager-wrap"></div>'+
                            '</div>'+
                            '<div class="sideContainer">'+
                                '<div class="pivotSlide"></div>'+
                                '<div class="listView rightListView"></div>'+
                                '<div class="rightPager" class="k-pager-wrap"></div>'+
                            '</div>'+
                            '<br class="clear" />'+
                        '</div>';
        NS = ".Relation";

    var relations = Widget.extend({
        init: function(element, options) {
            var that = this;
            
            Widget.fn.init.call(that, element, options);
            that._buildDataSource();
            
            that._buildContainer();
            that._buildRightDataSource();
            
            that._buildRightList();
            
            if(that.options.panelData) {
                that.options.panelData.bind("activate", function(e) {
                    if(!e.item) {
                        return;
                    }
                    
                    var point = $(e.item).find('#'+that.options.elementId),
                        rightList = that.options.rightList;
                    
                    if(point[0]) {                        
                        if(rightList) {
                            rightList.unbind("dataBound");
                            rightList.setDataSource(that.options.rightDataSource);
                            var fg = that._onRightDataSourceBound();
                            rightList.bind("dataBound", fg);
                            fg();
                        }
                        
                        if(that.options.builded) {
                            return;
                        }
                        
                        if(!that.options.view.single) {
                            that._buildLeftDataSource();
                            that._buildLeftList();
                        }        
            
                        if(that.options.tools) {
                            that._buildTools();
                        }
                        that.options.builded = true;
                    }
                });
            }
            
            that._parseModelData();
        },
        
        options: {
            name: "Relations",
            multiple: undefined,
            readonly: false,
            view: {
                single: false,
                delete: true
            },
            model: false,
            remote: false,
            modelField: false,
            modelData: false,
            panelData: false,
            elementId: false,
            dataSchema:false,
            pivotList: false,
            pivotFields: [],
            pivotFilter: false,
            /*container: false,*/
            leftList: false,
            rightList: false,
            leftDataSource: false,
            leftDataSourceOptions: false,
            rightDataSource: false,
            dataSource: false,
            onModelChange: false,
            tools: false,
            leftPager: false,
            rightPager: false,
            builded: false
        },
        events: [],
        _createLeftTemplate: function() {
            var template = '<div class="item">',
                idField = this.options.remote.id,
                imageField = this.options.remote.image,
                nameField = this.options.remote.name;
            
            if(imageField) {
                template += '<img src="#:('+imageField+' ? '+imageField+'[0].path : "")#" />';
            }
            
            template += '<p><b>#:'+idField+'#</b>';
            
            if(nameField) {
                template += ' #:' + nameField + '#';
            }
            
            template += '</p></div>';
            
            
            return template;
        },
        _createRightTemplate: function() {
            var template = '<div class="item">',
                idField = this.options.remote.id,
                imageField = this.options.remote.image,
                nameField = this.options.remote.name;
            
            if(imageField) {
                template += '<img src="#:('+imageField+' ? '+imageField+'[0].path : "")#" />';
            }
            
            template += '<p><b>#:'+idField+'#</b>';
            
            if(nameField) {
                template += ' #:' + nameField + '#';
            }
            template += '<div class="edit-buttons">';
            if(this.options.view.delete) {
                template += '<a class="k-icon k-delete k-delete-button" href="\\#"></a>';
            }
            template += '</div><div class="skatch#:(data.system ? " system" : "")#"></div></p></div>';
            
            return template;
        },
        _buildContainer: function() {
            this.options.elementId = this.element.attr('id');
            this.element.empty();
            this.element.off(NS);
            this.element.html(HTMLTEMPLATE);
        },
        _buildDataSource: function() {
            var that = this,
                modelField = that.options.modelField,
                model = that.options.model,
                pivotList = that.options.pivotList,
                remoteSchema = that.options.remote.schema,
                dataSchema = {
                    id: remoteSchema.id,
                    fields: {}
                };
        
            dataSchema.fields[remoteSchema.id] = remoteSchema.fields[remoteSchema.id];
            
            if(model.fields[modelField]) {
                if(model.fields[modelField].pivot) {
                    var pivotData = extend(true, model.fields[modelField].pivot, pivotList),
                        pivotFields = [],
                        pivotField;
                
                    for(var field in pivotData) {
                        pivotField = {field: "", title: "", visible: true};
                        if(pivotData[field].title !== undefined) {
                            pivotField.title = pivotData[field].title;
                            delete pivotData[field].title;
                        }
                        if(pivotData[field].visible !== undefined) {
                            pivotField.visible = pivotData[field].visible;
                            delete pivotData[field].visible;
                        }
                        pivotField.field = field;
                        pivotFields.push(pivotField);
                    }
                    
                    extend(dataSchema.fields, pivotData);
                    that.options.pivotFields = pivotFields;
                }
                
                if(model.fields[modelField].multiple && that.options.multiple === undefined) {
                    that.options.multiple = !!model.fields[modelField].multiple;
                }
            }
            
            that.options.dataSchema = dataSchema;
            
            if(!this.options.dataSource) {
                this.options.dataSource =  new kendo.data.DataSource({
                    type: "cultturist",
                    schema: {
                        model: that.options.dataSchema
                    },
                    change: that._onDataChange()
                });
            }
        },
        _buildLeftDataSource: function() {
            
            if(this.options.leftDataSource) {
                return;
            }
            
            this.options.leftDataSource = this.options.remote.getDataSource(extend(true, {
                columns: {
                    list: {
                        columns: [
                            { field: "name", transport: 'name.ru'},
                            { field: "image", transport: 'image.files.path'}
                        ]
                    }
                },
                pageSize: 12
            }, this.options.leftDataSourceOptions));
        },
        _buildLeftList: function() {
            if(this.options.leftList) {
                return;
            }
            
            var left = this.element.find('.sideContainer').eq(0),
                leftPager = left.children('.leftPager').data("kendoPager"),
                leftList = left.children(".leftListView").data("kendoListView");
            
            
            if(!leftPager) {
                leftPager = left.children('.leftPager').kendoPager({
                    buttonCount: 8,
                    messages: LOCALE,
                    autoBind: false,
                }).data("kendoPager");
            }
            
            leftPager.setDataSource(this.options.leftDataSource);
            
            if(!leftList) {
                leftList = left.children(".leftListView").kendoListView({
                    selectable: (this.options.multiple ? "multiple" : "single"),
                    change: this._onLeftChange(),
                    dataBound: this._onLeftDataSourceBound(),
                    template: kendo.template(this._createLeftTemplate())
                }).data("kendoListView");
            }
            
            leftList.setDataSource(this.options.leftDataSource);
            
            left.width('50%');
            
            this.options.leftList = leftList;
            this.options.leftPager = leftPager;
        },
        _buildRightDataSource: function() {
            
            if(this.options.rightDataSource) {
                return;
            }
            
            this.options.rightDataSource =  new kendo.data.DataSource({
                type: "cultturist",
                pageSize: this.options.view.single ? 40 : 12,
                schema: {
                    model: this.options.remote.schema
                }
            });
        },
        _buildRightList: function() {
            
            if(this.options.rightList) {
                return;
            }
            
            var right = this.element.find('.sideContainer').eq(1),
                rightPager = right.children('.rightPager').data("kendoPager"),
                rightList = right.children(".rightListView").data("kendoListView");
            
            if(!rightPager) {
                rightPager = right.children('.rightPager').kendoPager({
                    buttonCount: 8,
                    messages: LOCALE
                }).data("kendoPager");
            }            
            rightPager.setDataSource(this.options.rightDataSource);
            
            if(!rightList) {
                rightList = right.children(".rightListView").kendoListView($.extend({
                    template: kendo.template(this._createRightTemplate())
                }, !this.options.view.single ? {
                    selectable: "single",
                    change: this._onRightChange()
                } : {}, this.options.view.delete ? {
                    remove: this._onRightRemove()
                } : {})).data("kendoListView");
            }
            
            right.width(this.options.view.single ? '100%' : '50%');
            this.options.rightList = rightList;
            this.options.rightPager = rightPager;
        },
        _buildTools: function() {
            var that = this,
                idField = that.options.remote.id,
                tools = that.options.tools,
                container = that.element.find('.tools').eq(0).html('');
                
                if(!isArray(tools)) {
                    return;
                }
                
                tools.forEach(function(item, k) {
                    if(typeof item !== "object") {
                        return;
                    }
                    item.el.prependTo(container);
                    if(item.callback && isFunction(item.callback)) {
                        item.callback.call(that, item.el);
                    }
                    /*$('<a class="k-button k-button-icontext" href="#"><span class="k-icon '+(item.icon ? item.icon : '')+'"></span>'+(item.title ? item.title : '')+'</a>')
                            .prependTo(container)
                            .bind('click', function(e) {
                                if(item.callback && isFunction(item.callback)) {
                                    item.callback.call(that, e);
                                }
                                return false;
                    });*/
                });
                
        },
        _onRightChange: function() {
            var that = this;
            
            return function(e) {
                var touches = this.selectable.userEvents.touches;
                if(touches instanceof ObservableArray || isArray(touches)) {
                    if(touches.length > 0) {
                        if($(touches[0].initialTouch).hasClass('k-delete')) {
                            return;
                        }
                    }
                }
                
                var dataSchema = that.options.dataSchema,
                    dataSource = that.options.dataSource,
                    pivotFields = that.options.pivotFields,
                    container = that.element.find('.pivotSlide').eq(0),
                    data = that.options.rightDataSource.view(),
                    idField = that.options.remote.id,
                    selected,
                    editorPivotFields = [],
                    selectedId = $.map(this.select(), function(item) {
                        return data[$(item).index()][idField];
                    })[0];

                if(selectedId) {
                    selected = dataSource.get(selectedId);

                    var html = '';

                    
                    pivotFields.forEach(function(column) {
                        if(!column.visible) {
                            return;
                        }
                        editorPivotFields.push(column);
                        html+='<div><label for="'+column.field+'">'+(column.title || column.field)+'</label>';
                        html+='<div ' + kendo.attr("container-for") + '="' + column.field + '" class="k-edit-field"></div></div>';
                    });

                    html+='<br class="clear" />';
                    
                    if(editorPivotFields.length < 1) {
                        return;
                    }
                    
                    container.html('');
                    $(html).appendTo(container);

                    if(!container.hasClass('expanded')) {
                        kendo.fx(container).expand("vertical").stop().play().then(function() {
                            container.addClass('expanded');
                        });
                    }

                    container.kendoEditable({
                        fields: editorPivotFields,
                        model: selected,
                        clearContainer: false,
                        change: function(m) {
                            that._onPivotChange(m, selected);
                        }
                    });
                }
            };
        },
        _onRightRemove: function() {
            var that = this;
            
            return function(e) {
                var idField = that.options.remote.id,
                    remoteId = e.model[idField],                            
                    dataSource = that.options.dataSource,
                    item = dataSource.get(remoteId);
            
                if(item) {
                    dataSource.remove(item);
                }
            };
        },
        _onPivotChange: function(e, selected) {
            each(e.values, function(field, value){
                selected.set(field, value);
            });
        },
        _onLeftChange: function() {
            var that = this;
            return function(e) {
                var data = that.options.leftDataSource.view(),
                    idField = that.options.remote.id,
                    selected = $.map(this.select(), function(item) {
                        return data[$(item).index()][idField];
                    }),                            
                    pivotFields = that.options.pivotFields, 
                    dataSchema = that.options.dataSchema,                            
                    dataSource = that.options.dataSource,
                    multiple = that.options.multiple;
                    
                
                data.forEach(function(left) {
                    var id = left[idField];

                    if(selected.indexOf(id) >= 0) {
                        if(!dataSource.get(id)) {
                            var fd = {};
                            fd[dataSchema.id] = id;
                            if(pivotFields.length > 0) {
                                pivotFields.forEach(function(pivot, index) {
                                    if(!pivot.field) {
                                        return;
                                    }
                                    
                                    if(dataSchema.fields[pivot.field].defaultValue !== undefined) {
                                        fd[pivot.field] = dataSchema.fields[pivot.field].defaultValue;
                                    }
                                });
                            }
                            if(multiple) {
                                dataSource.add(fd);
                            } else {
                                dataSource.data([fd]);
                            }
                            
                        }                             
                    }
                });

                var container = that.element.find('.pivotSlide').eq(0);
                if(container.hasClass('expanded')) {
                    kendo.fx(container).expand("vertical").stop().reverse().then(function() {
                        container.removeClass('expanded');
                    });
                }
            };
        },
        _onLeftDataSourceBound: function() {
            var that = this;
            
            return function() {
                var leftDataSource = that.options.leftDataSource,
                    rightDataSource = that.options.rightDataSource,
                    idField = that.options.remote.id,
                    found;
                    
                
                leftDataSource.data().forEach(function(item) {
                    found = rightDataSource.get(item[idField]);
                    if(found) {
                        item.forEach(function(n,m) {
                            found.set(m, n);
                        });
                    }
                });
                
                that._selectOnChange();
            };
        },
        _onRightDataSourceBound: function() {
             var that = this;
            
            return function() {
                var rightDataSource = that.options.rightDataSource,
                    remote = that.options.remote,
                    idField = remote.id,
                    imageField = remote.image,
                    nameField = remote.name,
                    found, list = [];
                
                rightDataSource.view().forEach(function(item) {
                    if(!that._recursiveTreeVal(item, nameField) || !that._recursiveTreeVal(item, imageField)) {
                        list.push(item[idField]);
                    }
                });
                
                if(list.length > 0) {
                    $.ajax(remote.url+'/?pagesize=40&fields='+[nameField, imageField].join()+'&filter=' + idField + ':in:' + list.join(), {
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        type: "GET"
                    }).done(function(data) {
                        if(!isArray(data)) {
                            return;
                        }
                        data.forEach(function(it, k) {
                            found = rightDataSource.get(it[idField]);
                            if(found) {
                                it[nameField] && found.set(nameField, it[nameField]);
                                var sep = imageField.split('.', 1)[0];
                                
                                it[sep] && found.set(sep, it[sep]);
                                /*for(var i in it) {
                                    found.set(i, it[i]);
                                }*/
                            }
                        });
                    });
                }
            };
        },
        _onDataChange: function() {
            var that = this;
            
            return function(e) {
                var remoteId = 0,
                    pared,
                    list = [],
                    modelData = that.options.modelData,
                    modelField = that.options.modelField,
                    imageField = that.options.remote.image,
                    nameField = that.options.remote.name,
                    dataSource = that.options.dataSource,
                    leftDataSource = that.options.leftDataSource,
                    rightDataSource = that.options.rightDataSource,
                    dataSchema = that.options.dataSchema,
                    onModelChange = that.options.onModelChange;
            
                if(e.action === 'add' || e.action === undefined) {
                    
                    if(e.action === undefined) {
                        rightDataSource.data([]);
                    }
                    
                    e.items.forEach(function(item) {
                        remoteId = parseInt(item[dataSchema.id]);

                        if(rightDataSource.get(remoteId)) {
                            return;
                        }

                        var leftItem = leftDataSource ? leftDataSource.get(remoteId) : false;

                        pared = {};

                        if(leftItem) {
                            leftItem.forEach(function(n,m) {
                                pared[m] = n;
                            });
                        } else {
                            pared[dataSchema.id] = remoteId;
                        }

                        //HACK
                        that._recursiveTree(pared, imageField, []);
                        that._recursiveTree(pared, nameField, '');

                        rightDataSource.add(pared);
                    });
                } else if(e.action === 'remove') {                     
                    e.items.forEach(function(item) {
                        remoteId = parseInt(item[dataSchema.id]);
                        var rightItem = rightDataSource.get(remoteId);
                        if(rightItem) {
                            rightDataSource.remove(rightItem);
                        }
                    });
                }
                
                if(['add', 'remove'].indexOf(e.action) >= 0) {
                    that._selectOnChange();
                }
                
                if(that.options.readonly) {
                    return;
                }
                
                dataSource.data().forEach(function(item) {
                    pared = {};

                    item.forEach(function(n,m) {
                        pared[m] = n;
                    });

                    list.push(pared);
                });
                
                onModelChange = onModelChange || that._onModelChange;
                
                onModelChange.call(this, modelData, modelField, list, that.options.multiple);
                
            };
        },
        _onModelChange: function(model, field, data, multiple) {
            if(multiple) {
                model.set(field, data);
            } else if(data.length > 0) {
                model.set(field, data);
            }
        },
        _selectOnChange: function() {
            var that = this,
                selected = '',
                dataSource = that.options.dataSource,
                leftDataSource = that.options.leftDataSource,
                leftList = that.options.leftList,
                idField = that.options.remote.id;
            
            if(leftDataSource) {
                dataSource.data().forEach(function(item) {
                    var found = leftDataSource.get(item[idField]);
                    if(found) {
                        selected += (selected.length > 0 ? ',' : '') +  'div[data-uid='+found.uid+']';                          
                    }
                });
            }

            if(leftList) {
                leftList.clearSelection();
                var itemHtml = leftList.wrapper.find(selected);
                if(itemHtml.size() > 0) {
                    leftList.select(selected);
                }
            }
        },
        _recursiveTree: function(obj, field, def) {
            
            if(!field) {
                return;
            }
            
            if(def === undefined) {
                def = null;
            }
            
            var list = field.split('.');
            
            if(list.length > 0 && !obj[list[0]]) {
                var fg = list.shift();
                
                if(list.length < 1) {
                    obj[fg] = def;
                } else {
                    obj[fg] = {};
                    this._recursiveTree(obj[fg], list.join('.'));
                }
            }
        },
        _recursiveTreeVal: function(obj, field) {
            
            if(!field) {
                return;
            }
            
            var list = field.split('.');
            
            if(list.length > 0 && obj[list[0]] !== undefined) {
                var fg = list.shift();
                
                if(list.length < 1) {
                    return obj[fg];
                } else {
                    return this._recursiveTreeVal(obj[fg], list.join('.'));
                }
            }
            
            return;
        },
        _parseModelData: function() {
            var that = this,
                remoteId = 0,
                dataSchema = that.options.dataSchema,
                pivotFields = that.options.pivotFields,
                modelData = that.options.modelData,
                modelField = that.options.modelField,
                dataSource = that.options.dataSource,
                pivotFilter = that.options.pivotFilter,
                pared = {};
        
            if(typeof modelData[modelField] !== 'object') {
                if(parseInt(modelData[modelField]) > 0) {
                    remoteId = parseInt(modelData[modelField])+"";
                    
                    if(!dataSource.get(remoteId)) {
                        pared = {};
                        pared[dataSchema.id] = remoteId;
                        dataSource.add(pared);
                    }                    
                } 
            } else {
                if(isArray(modelData[modelField]) || modelData[modelField] instanceof ObservableArray) {
                    var list = [];
                    modelData[modelField].forEach(function(item) {
                       remoteId = parseInt(item[dataSchema.id]);

                       if(remoteId < 0) {
                           return;
                       }

                       if(dataSource.get(remoteId)) {
                           return;
                       }

                       pared = {};
                       pared[dataSchema.id] = remoteId;

                       if(pivotFields.length > 0) {
                        for(var index in pivotFields) {
                            var pivot = pivotFields[index];

                            if(!pivot.field) {
                                return;
                            }

                            if(item[pivot.field] !== undefined) {

                                if(typeof pivotFilter === 'object') {
                                    if(pivotFilter[pivot.field] !== undefined) {
                                        if(isArray(pivotFilter[pivot.field])) {
                                            if(pivotFilter[pivot.field].indexOf(item[pivot.field]) < 0) {
                                                return;
                                            }
                                        } else {
                                            if(pivotFilter[pivot.field] !== item[pivot.field]) {
                                                return;
                                            }
                                        }

                                    }
                                }

                                pared[pivot.field] = item[pivot.field];
                            }
                        }
                       }
                       
                       list.push(pared);
                    });
                    
                    list.forEach(function(item) {
                        dataSource.add(item);
                    });                    
               } else {
                   remoteId = parseInt(modelData[modelField][dataSchema.id]);

                   if(remoteId < 0) {
                       return;
                   }

                   if(dataSource.get(remoteId)) {
                       return;
                   }

                   pared = {};
                   pared[dataSchema.id] = remoteId;
                   dataSource.add(pared);
               }
           }
        },
        destroy: function() {
            if(this.options.leftList) {
               this.options.leftList.destroy();
               this.options.leftList = false;
            }
            
            if(this.options.rightList) {
               this.options.rightList.destroy();
               this.options.rightList = false;
            }
            
            if(this.options.panelData) {
                this.options.panelData.unbind("activate");
                this.options.panelData = false;
            }
            
            if(this.options.leftPager) {
               this.options.leftPager.destroy();
               this.options.leftPager = false;
            }
            
            if(this.options.rightPager) {
               this.options.rightPager.destroy();
               this.options.rightPager = false;
            }
            
            this.options.pivotFields = [];            
            this.options.dataSource = false;            
            this.options.leftDataSource = false;
            this.options.rightDataSource = false;
            this.element.off(NS);
            Widget.fn.destroy.call(this);
        }
    });

    kendo.ui.plugin(relations);
})(window.kendo.jQuery);
