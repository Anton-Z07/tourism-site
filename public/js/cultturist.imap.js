kendo_module({
    id: "imap",
    name: "Interactive Map",
    category: "web",
    description: "",
    depends: [ "core"]
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
        HTMLTEMPLATE = '<div class="googlemap" style="width:100%;height:300px;"></div>',
        NS = ".IMap";

    var relations = Widget.extend({
        init: function(element, options) {
            var that = this;
            
            Widget.fn.init.call(that, element, options);
            
            that._buildContainer();
            
            that._initMap();
            
            that._creatMarkersAndEvents();
        },
        
        options: {
            name: "IMap",
            nameField: 'name',
            latitudeField: 'latitude',
            longitudeField: 'longitude',
            modelData: false,
            panelData: false,
            map: false,
            center: false,
            container: false,
            elementId: false,
            marker: false
        },
        events: [],
        _buildContainer: function() {
            var that = this;
            that.options.container = 'mapdiv' + Math.round(Math.random() * 100);
            $(HTMLTEMPLATE).appendTo(that.element).attr('id', that.options.container);
            that.options.elementId = that.element.attr('id');
        },
        _initMap: function() {
            var that = this,
                modelData = that.options.modelData,
                panelData = that.options.panelData,
                latitudeField = that.options.latitudeField,
                longitudeField = that.options.longitudeField;
        
            if(!modelData || !panelData) {
                return;
            }
            
            modelData.bind("change", that._onModelChange());
            
            var latitude = parseFloat(modelData[latitudeField]),
                longitude = parseFloat(modelData[longitudeField]);
        
            if(window.google === undefined) {
                return;
            }
            
            var latlng = that.options.center = new google.maps.LatLng(latitude, longitude);            
            
            var map = that.options.map = new google.maps.Map(document.getElementById(that.options.container), {
                zoom: 12,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            
            google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
                google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(latlng);
                });
            });
            
            panelData.bind("activate", function(e) {
                if(!e.item) {
                    return;
                }
                
                var point = $(e.item).find('#'+that.options.elementId);
                
                if(point[0]) {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(latlng);
                }
            });
        },
        _creatMarkersAndEvents: function() {
            var that = this,
                modelData = that.options.modelData,
                map = that.options.map,
                latlng = that.options.center,
                nameField = that.options.nameField,
                latitudeField = that.options.latitudeField,
                longitudeField = that.options.longitudeField,
                marker = that.options.marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: modelData[nameField] ? modelData[nameField] : 'Точька'
                });
                
                google.maps.event.addListener(map, 'click', function(e) {
                    var pos = e.latLng;
                    marker.setPosition(pos);
                    modelData.set(latitudeField, parseFloat(pos.lat()));
                    modelData.set(longitudeField, parseFloat(pos.lng()));
                });
        },
        _onModelChange: function() {
            var that = this;
            
            return function(e) {
                var marker = that.options.marker,
                field = e.field,
                modelData = that.options.modelData,
                latitudeField = that.options.latitudeField,
                longitudeField = that.options.longitudeField;
        
                if(marker) {
                    if(field === latitudeField || field === longitudeField) {
                        marker.setPosition({lat: parseFloat(modelData[latitudeField]), lng: parseFloat(modelData[longitudeField])});
                    }
                }
            };
        },
        destroy: function() {
            
            if(this.options.modelData) {
                this.options.modelData.unbind("change");
            }
                
            this.options.modelData = false;
            this.options.panelData = false;
            if(this.options.map && google) {
                google.maps.event.clearListeners(this.options.map, 'click');
            }
            this.options.map = false;
            
            this.element.empty();
            
            this.element.off(NS);
            Widget.fn.destroy.call(this);
        }
    });

    kendo.ui.plugin(relations);
})(window.kendo.jQuery);
