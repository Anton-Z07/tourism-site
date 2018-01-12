var topMenu = {
    structure: {
        "Гео. элементы" : {
            "Список": { _action:"arealist", _model:"area"}
        },
        "Достопримечательности": {
            "Список": { _action:"landmarklist", _model:"landmark"},
            "Свойства достопримечательностей": {_action:"landmarkpropertylist", _model:"landmarkproperty"}
        },
        "Другие элементы": {
            "Великие люди": {_action:"peoplelist", _model:"people"},
            "Кухня": {_action:"kitchenlist", _model:"kitchen"},
            "Фишки": {_action:"featurelist", _model:"feature"}
        }, 
        "Утилиты": {
            "Создать пакет 'Ядро'": {_action:"getcorepackage"},
            "Перепривязка достопримечательностей": {_action:"bindlandmark"},
            "Привязка статей": {_action:"bindentities"}
        },
        "Тесты": {
            "Загрузка фото": {_action:"uploadtest"}
        }
    },
    init: function(container) {
        var tmp = 'menu'+Math.round(Math.random() * 1000);
        this._build(container, this.structure, tmp);
        $('#'+tmp).kendoMenu({
            select: this._select(),
            animation: {
                close: {
                    effects: "slideIn:up"
                }
            }
        });
        return this;
    },
    select: function(action, model) {
        var content = this.getcontent(action),
            grid = false,
            data = false;
        
        switch(action) {
            case 'arealist':
            case 'landmarklist':
            case 'landmarkpropertylist':
            case 'peoplelist':
            case 'kitchenlist':
            case 'featurelist':
                grid = cultturist.getModel(model).getGridConfig();
                data = content.data("kendoGrid");
                if(!data) {
                    data = content.kendoGrid(grid).data("kendoGrid");
                }
                break;
            case 'uploadtest':
                if(content.find('div').size() > 0) {
                    return;
                }
        
                $('<div style="width:100%; height: 50px">'+
                    '<form method="post" class="form-upload">'+
                        '<input class="file" type="file" />'+
                    '</form>'+
                '</div>'+
                '<div class="testuploadcontainer loadingcontainer"></div>').appendTo(content);        
                
                var files,
                container = content.find('.testuploadcontainer');
        
                content.find('.form-upload').bind('submit', function() {
                    var buildList = function(r) {
                        container.removeClass('loading');
                        if(!r.id) {
                            return;
                        }

                        for(var key in r.files) {
                            var file = r.files[key];
                            $('<div class="cimg"><img src="'+file.path+'" /><p>'+file.width+'x'+file.height+'</p></div>').prependTo(container);
                        }
                    }, data = new FormData();

                    $.each(files, function(index, file) {
                        data.append(file.name, file);
                    });

                    $.ajax('/api/filegroup/', {
                        type: 'POST',
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        error: function(e) {
                            container.removeClass('loading');
                            alert(e.code);
                        },
                        success: function(r) {
                            if(r.id > 0) {
                                $.ajax('/api/filegroup/' + r.id + '?fields=files', {
                                    cache: false,
                                    dataType: "json",
                                    contentType: "application/json",
                                    success: buildList,
                                    error: function(e) {
                                        container.removeClass('loading');
                                    }
                                });
                            }
                        }
                    });
                    return false;
                });
        
                content.find('input.file').bind('change', function() {
                    files = this.files;
                    if(files.length > 0) {
                        container.html('<br class="clear" />');
                        if(!container.hasClass('loading')) {
                            container.addClass('loading');
                        }

                        content.find('.form-upload').submit();
                    }
                });
                break;
            case 'getcorepackage':
                if(content.find('div').size() > 0) {
                    return;
                }
        
                $('<div style="width:100%; height: 50px">'+
                    '<form method="post" class="form-corepackage">'+
                        '<input type="submit" value="Создать"/>'+
                    '</form>'+
                '</div>'+
                '<div class="ttsd loadingcontainer"></div>').appendTo(content);
        
                var container = content.find('.ttsd');
        
                content.find('.form-corepackage').bind('submit', function() {
                    if(!container.hasClass('loading')) {
                        container.addClass('loading');
                    }
                    
                    $.ajax('/admin/corepackage/', {
                        type: 'POST',
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        error: function(e) {
                            container.removeClass('loading');
                            alert(e.code);
                        },
                        success: function(r) {
                            container.removeClass('loading');
                            window.open(r.path);
                        }
                    });
                    return false;
                });
                break;
            case 'bindentities':
                if(content.find('div').size() > 0) {
                    return;
                }

                $('<div style="width:100%; height: 50px; margin: 20px;">'+
                    '<form method="post" class="form-bindentities">'+
                        '<input type="submit" value="Привязать"/>'+
                    '</form>'+
                '</div>'+
                '<div class="ttsd loadingcontainer"></div>').appendTo(content);

                var container = content.find('.ttsd');

                content.find('.form-bindentities').bind('submit', function() {
                    if(!container.hasClass('loading')) {
                        container.addClass('loading');
                    }

                    $.ajax('/admin/bindentities/', {
                        type: 'GET',
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        error: function(e) {
                            container.removeClass('loading');
                            alert(e.code);
                        },
                        success: function(r) {
                            container.removeClass('loading');
                            //window.open(r.path);
                        }
                    });
                    return false;
                });
                break;
            case 'bindlandmark':
                if(content.find('div').size() > 0) {
                    return;
                }
                $('<div style="width:100%; height: 50px">'+
                    '<p>Связывание всех достопримечательностей с городами(регионами и странами) длится очень долго.'+
                    'Рекомендуется запускать его во время наименьшей активности пользователей(ночью) и не вести других работ в админ панели.'+
                    'Ориентировочно длительность процедуры: 30 минут. Не запускать повторно, раньше чем через 30 минут.</p>'+
                    '<form method="post" class="form-bindlandmark">'+
                        '<input type="submit" value="Пересвязать"/>'+
                    '</form>'+
                '</div>'+
                '<div class="dsfg loadingcontainer"></div>').appendTo(content);
        
                var container = content.find('.dsfg');
        
                content.find('.form-bindlandmark').bind('submit', function() {
                    if(!container.hasClass('loading')) {
                        container.addClass('loading');
                    }
                    
                    $.ajax('/admin/bindlandmark/', {
                        type: 'GET',
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        error: function(e) {
                            container.removeClass('loading');
                            alert(e.code);
                        },
                        success: function(r) {
                            container.removeClass('loading');
                            alert('Количество итерацей: '+r.iteration);
                        }
                    });
                    return false;
                });
                break;
        }
        return this;
    },
    url: function(url) {
        return this;
    },
    _build: function(container, structure, ulId) {
        var p = container, action = false, url = false, model = false;
        for(var title in structure) {
            var val = structure[title];
            
            if(title === '_action') {
                action = val;
                continue;
            }
            
            if(title === '_url') {
                url = val;
                continue;
            }
            
            if(title === '_model') {
                model = val;
                continue;
            }
            
            if(!container.is('ul')) {
                container = $('<ul>').appendTo(container);
                if(ulId) {
                    container.attr('id', ulId);
                }
            }
            this._build($('<li>'+title+'</li>').appendTo(container), val);
        }
        
        if(action) {
            p.attr('menu-action', action);
        }
        
        if(url) {
            p.attr('menu-url', url);
        }
        
        if(model) {
            p.attr('menu-model', model);
        }
    },
    _select: function() {
        var that = this;
        
        return function(e) {
            var item = $(e.item),
                action = item.attr('menu-action'),
                model = item.attr('menu-model'),
                url = item.attr('menu-url');
        
            if(action) {
                that.select(action, model);
            }
            if(url) {
                that.url(url);
            }
        };
    },
    getcontent: function(action) {
        $.each($('#content>div'), function(it) {
            if($(this).attr('id') !== action) {
                $(this).hide();
            }
        });
        
        var content = $('#content>div#'+action);
        
        if(!content.is('div')) {
            content = $('<div></div>').appendTo($('#content'));
            content.attr('id', action);
        } else {
            content.show();
        }
        
        return content;
    }
};

jQuery(function() {
    topMenu.init($('#topmenu')).select('arealist','area');
});