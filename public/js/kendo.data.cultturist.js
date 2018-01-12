kendo_module({
    id: "data.cultturist",
    name: "Cultturist",
    category: "framework",
    depends: [ "core" ],
    hidden: true
});

(function($, undefined) {
    var kendo = window.kendo,
        cultturist = window.cultturist,
        extend = $.extend,
        cultFilters = {
            eq: "eq",
            neq: "not",
            gt: "gt",
            gte: "gte",
            lt: "lt",
            lte: "lte",
            contains : "like",
            doesnotcontain: "notlike",
            endswith: "like",
            startswith: "like"
        },
        mappers = {
            skip: $.noop,
            take: $.noop,
            filter: function(params, filter) {
                if(params.modelName) {
                    if (filter) {
                        params.filter = toCultFilter(filter, cultturist.getModel(params.modelName));
                    }
                    delete params.modelName;
                }
            },
            sort: function(params, orderby) {
                var model = params.modelName ? cultturist.getModel(params.modelName) : false,
                    expr = $.map(orderby, function(value) {
                        var order = value.field.replace(/\./g, "/"),
                            column = model.getColumnFromField(order);

                        if(column.transport) {
                            order = column.transport;
                        }

                        if (value.dir === "desc") {
                            order += ":desc";
                        } else if(value.dir === "asc") {
                            order += ":asc";
                        }

                        return order;
                    }).join(";");

                if (expr) {
                    params.order = expr;
                }
            },
            pageSize: function(params, pageSize) {
                if (pageSize) {
                    params.pagesize = pageSize;
                }
            },
            page: function(params, page) {
                if (page) {
                    params.page = page;
                }
            }
        },
        defaultDataType = {
            read: {
                dataType: "jsonp"
            }
        };

    function toCultFilter(filter, model) {
        var result = [],
            logic = filter.logic || "and",
            idx,
            length,
            field,
            type,
            format,
            operator,
            value,
            filters = filter.filters,
            column;

        for (idx = 0, length = filters.length; idx < length; idx++) {
            filter = filters[idx];
            field = filter.field;
            value = filter.value;
            operator = filter.operator;

            if (filter.filters) {
                filter = toCultFilter(filter, model);
            } else {
                field = field.replace(/\./g, "/");
                column = model.getColumnFromField(field);
                if(column.transport) {
                    field = column.transport;
                }
                filter = cultFilters[operator];

                if (filter && value !== undefined) {
                    type = $.type(value);
                    if (type === "string") {
                        value = value.replace(/'/g, "''");
                        
                        if(operator === 'endswith') {
                            value = '%'+value;
                        } else if(operator === 'startswith') {
                            value = value+'%';
                        } else if(operator === 'contains') {
                            value = '%' + value + '%';
                        }
                    } else if(type === 'date') {
                        if(value !== false) {
                            value = value.getTime() / 1000;
                        }
                    }
                    format = "{2}:{0}:{1}";

                    filter = kendo.format(format, filter, value, field);
                }
            }

            result.push(filter);
        }

        filter = result.join(";");

        /*if (result.length > 1) {
            filter = "(" + filter + ")";
        }*/

        return filter;
    }

    extend(true, kendo.data, {
        schemas: {
            cultturist: {
                type: "json",
                pageSize: "pagesize",
                page: "page",
                data: "results",
                total: function(response) {
                    return response.total;
                }/*,
                parse: function(response, param1, param2) {
                    return response;
                }*/
            }
        },
        transports: {
            cultturist: {
                read: {
                    cache: true, // to prevent jQuery from adding cache buster
                    accepts: {
                        json: 'application/hal+json'
                    },
                    dataType: "json"
                },
                create: {
                    cache: true,
                    dataType: "json",
                    contentType: "application/json",
                    type: "POST" // must be POST to create new entity
                },
                update: {
                    cache: true,
                    dataType: "json",
                    contentType: "application/json",
                    type: "PUT"
                },
                destroy: {
                    cache: true,
                    dataType: "json",
                    type: "DELETE"
                },
                parameterMap: function(data, type) {
                    var params,
                        value,
                        option,
                        dataType;

                    data = data || {};
                    type = type || "read";
                    dataType = (this.options || defaultDataType)[type];
                    dataType = dataType ? dataType.dataType : "json";

                    if (type === "read") {
                        params = {};

                        for (option in data) {
                            if (mappers[option]) {
                                mappers[option](params, data[option]);
                            } else {
                                params[option] = data[option];
                            }
                        }
                    } else {
                        if (dataType !== "json") {
                            throw new Error("Only json dataType can be used for " + type + " operation.");
                        }

                        if (type !== "destroy") {
                            for (option in data) {
                                value = data[option];
                                
                                if(value === null || value === undefined) {
                                    delete data[option];
                                    continue;
                                }
                                
                                if (typeof value === "number") {
                                    data[option] = value + "";
                                }
                            }

                            params = kendo.stringify(data);
                        }
                    }

                    return params;
                }
            }
        }
    });
})(window.kendo.jQuery);
