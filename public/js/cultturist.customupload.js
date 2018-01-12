kendo_module({
    id: "customUpload",
    name: "Custom Upload",
    category: "web",
    description: "",
    depends: [ "core", "pager", "listview"]
});

(function($, undefined) {
    var kendo = window.kendo,
        Widget = kendo.ui.Widget,
        isFunction = kendo.isFunction,
        extend = $.extend,
        proxy = $.proxy,
        each = $.each,
        DOCUMENT = $(document),
        isLocalUrl = kendo.isLocalUrl,
        OPEN = "open",
        CLOSE = "close",
        LOCALE = {
            cancel: "Отмена",
            dropFilesHere: "Перетащите файлы сюда",
            headerStatusUploaded: "Загружены",
            headerStatusUploading: "Загружаются...",
            remove: "Удалить",
            retry: "Попробовать ещё раз",
            select: "Выберите",
            statusFailed: "Ошибка загрузки",
            statusUploading: "Загружаются",
            uploadSelectedFiles: "Выберите файл"
        },
        HTMLTEMPLATE = '<div class="customUpload">'+
                '<div class="imageContainer"></div>'+
                '<div class="uploadplace"><input type="file" /></div>'+
                '<br class="clear" />'+
            '</div>',
        NS = ".CustomUpload";

    var relations = Widget.extend({
        init: function(element, options) {
            var that = this;
            
            Widget.fn.init.call(that, element, options);
            
            that._buildContainer();
            
            that._buildImageView();
        },
        
        options: {
            name: "CustomUpload",
            model: false,
            modelField: false,
            modelData: false,
            container: false,
            dataSource: false,
            upload: false
        },
        events: [],
        _buildContainer: function() {
            var that = this,
                model = that.options.model,
                options = $.extend({}, {
                            multiple: false,
                            async: {
                                saveUrl: model.url,
                                removeUrl: model.url,
                                saveField: 'files'
                            },
                            localization: LOCALE,
                            success: that._onUploadSuccess(),
                            progress: that._onProgress()
                        });
            
            that.options.container = $(HTMLTEMPLATE).appendTo(that.element);
            
            that.options.upload = that.options.container.find('input[type=file]').eq(0).kendoUpload(options).data('kendoUpload');
        },
        _buildImageView: function(data) {
            var that = this,
                modelField = that.options.modelField,
                modelData = that.options.modelData,
                imageContainer = that.options.container.find('.imageContainer').eq(0);
            
            data = data || modelData[modelField];
            
            if(typeof data === 'object') {
                
                var files = data.files,
                    selected = false;
                
                for(var index in files) {
                    if(!selected) {
                        selected = files[index];
                        continue;
                    }
                    
                    if((parseInt(files[index].width) > parseInt(selected.width) || parseInt(files[index].height) > parseInt(selected.height)) && parseInt(files[index].height) < 300 && parseInt(files[index].width) < 900) {
                        selected = files[index];
                    }
                }
                
                if(selected) {
                    var ext = /.+\.(\w+)$/i.exec(selected.path);
                    
                    if(ext && ext[1]) {
                        if(/jpg|jpeg|png/i.test(ext[1])) {
                            imageContainer.css('backgroundImage', 'url('+selected.path+')');
                        } else {
                            imageContainer.css('backgroundImage', 'url(/images/mimi/'+ext[1]+'-icon-128x128.png)');
                        }
                    }
                    
                }
            }
        },
        _onUploadSuccess: function() {
            var that = this;
            
            return function(e) {
                var response = e.response,
                    model = that.options.model,
                    modelField = that.options.modelField,
                    modelData = that.options.modelData;
            
                if(response.id > 0) {
                    $.ajax(model.url+'/'+response.id+'/?fields=files', {
                        cache: true,
                        dataType: "json",
                        contentType: "application/json",
                        type: "GET"
                    }).done(function(data) {
                        if(typeof data === 'object') {
                            modelData.set(modelField, response.id+"");
                            that._buildImageView(data);
                        }
                    });
                }
            };
        },
        _onProgress: function() {            
            var that = this;
            
            return function(e) {
                var files = e.files,
                    imageContainer = that.options.container.find('.imageContainer').eq(0);
                
                if(files[0] && files[0].extension) {
                    var ext = files[0].extension.substring(1);
                    imageContainer.css('backgroundImage', 'url(/images/mimi/'+ext+'-icon-128x128.png)');
                }
            };
        },
        destroy: function() {
            if(this.options.upload) {
                this.options.upload.destroy();
                this.options.upload = false;
            }
            
            this.element.off(NS);
            Widget.fn.destroy.call(this);
        }
    });

    kendo.ui.plugin(relations);
})(window.kendo.jQuery);
