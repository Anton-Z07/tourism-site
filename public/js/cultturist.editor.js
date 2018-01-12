kendo_module({
    id: "htmleditor",
    name: "HTML Editor",
    category: "web",
    description: "",
    depends: [ "core", "window", "editor"]
});

(function($, undefined) {
    var kendo = window.kendo,
        Widget = kendo.ui.Widget,
        isFunction = kendo.isFunction,
        isPlainObject = $.isPlainObject,
        extend = $.extend,
        proxy = $.proxy,
        DOCUMENT = $(document),
        isLocalUrl = kendo.isLocalUrl,
        randomId = 'HtmlEditor'+Math.round(Math.round()*1000),
        OPEN = "open",
        CLOSE = "close",
        TEMPLATE = '<div width="100%" height="100%"></div>',
        HTMLTEMPLATE = '<textarea id="'+randomId+'"></textarea>',
        NS = ".HtmlEditor",
        popup,
        textarea,
        change = false,
        dt,
        ONCHANGE = function() {change = true;},
        ACCEPTEDTOOLS = [
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
            "fontName",
            "fontSize",
            "foreColor",
            "backColor"
        ];

    var htmlEditor = Widget.extend({
        init: function(element, options) {
            var that = this;
            
            Widget.fn.init.call(that, element, options);
            
            that._inputField();
            
            that._popupCreate();
            
            that._textareaCreate();
        },
        _onWindowClose: function() {
            var that = this;
            if(change) {
                that.options.model.set(that.options.field, textarea.value(), popup);
            }
        },
        _inputField: function() {
            var that = this;
            $('<input type="hidden" data-bind="value:' + that.options.field + '" />').appendTo(that.element);
        },
        _popupCreate: function() {
            var that = this;
            popup = $(TEMPLATE).appendTo(that.element)
                    .kendoWindow(extend(that.options.window, {close: proxy(that._onWindowClose, that)}));
                    
            dt = popup.data("kendoWindow");
                        
            dt.content(HTMLTEMPLATE);
        },
        _textareaCreate: function() {
            var that = this;
            textarea = popup.find('#'+randomId)
                .kendoEditor(that.options.textarea).data("kendoEditor");
                
            textarea.value(that.options.model[that.options.field]);
        },
        options: {
            name: "HtmlEditor",
            field: '',
            model: null,
            window: {
                width: "700px",
                height: "500px",
                title: "Редактирование",
                iframe: true,
                modal: true,
                visible: false
            },
            textarea: {
                tools: ACCEPTEDTOOLS,
                encoded: false,
                change: ONCHANGE,
                execute: ONCHANGE,
                keydown: ONCHANGE,
                keyup: ONCHANGE,
                paste: ONCHANGE
            }
        },

        events: [ OPEN, CLOSE],

        open: function() {           
            if(dt) {
                dt.center().open();
                this.trigger(OPEN);
            }
        },

        close: function() {
            if(dt) {
                dt.close();
                this.trigger(CLOSE);
            }
        },

        destroy: function() {
            var popup = this.popup,
                textarea = this.textarea;
            
            if (textarea) {
                textarea.element.off(NS);
                textarea.destroy();
            }
            
            if (popup) {
                popup.element.off(NS);
                popup.destroy();
            }

            this.element.off(NS);
            Widget.fn.destroy.call(this);
        }
    });

    kendo.ui.plugin(htmlEditor);
})(window.kendo.jQuery);
