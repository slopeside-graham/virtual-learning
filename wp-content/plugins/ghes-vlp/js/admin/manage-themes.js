$ = jQuery;
$(function () {
    $(document).ready(function () {
        kendo.ui.progress($(".loading-window"), true);

        displayLoading("#theme-grid");

            dataSource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/theme",
                            dataType: "json",
                            method: "GET",
                            data: options.data,
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            success: function (result) {
                                options.success(result);
                            },
                            error: function (result) {
                                options.error(result);
                            }
                        });
                    },
                    create: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/theme",
                            method: "POST",
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            data: options.data.models[0],
                            success: function (result) {
                                // notify the data source that the request succeeded
                                console.log("Done:" + result);
                                options.success(result);
                            },
                            error: function (result) {
                                if (typeof result.responseJSON !== "undefined") {
                                    alert(result.responseJSON.message);
                                }
                                console.log(result.responseText);
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    },
                    update: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/theme",
                            method: "PUT",
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            data: options.data.models[0],
                            success: function (result) {
                                // notify the data source that the request succeeded
                                console.log("Done:" + result);
                                options.success(result);
                            },
                            error: function (result) {
                                if (typeof result.responseJSON !== "undefined") {
                                    alert(result.responseJSON.message);
                                }
                                console.log(result.responseText);
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    },
                    destroy: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/theme",
                            method: "DELETE",
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            data: options.data.models[0],
                            success: function (result) {
                                // notify the data source that the request succeeded
                                console.log("Done:" + result);
                                options.success(result);
                            },
                            error: function (result) {
                                if (typeof result.responseJSON !== "undefined") {
                                    alert(result.responseJSON.message);
                                }
                                console.log(result.responseText);
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    }
                },
                batch: true,
                pageSize: 10,
                sort: {
                    field: "StartDate",
                    dir: "asc"
                },
                schema: {
                    model: {
                        id: "id",
                        fields: {
                            id: { editable: false, nullable: true },
                            Title: { validation: { required: true } },
                            StartDate: { validation: { required: false }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate },
                            EndDate: { validation: { required: false }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate },
                            Gameboard_id: { validation: { required: true } },
                            GameboardTitle: { validation: { required: true } },
                            AgeGroup_id: { validation: { required: true } },
                            AgeGroupTitle: { validation: { required: true } }
                        }
                    }
                }
            });

        function parseDate(data) {
            if (data.date) {
                return kendo.parseDate(data.date, "yyyy-MM-dd");
            } else {
                return data;
            }
        }

        $("#theme-grid").kendoGrid({
            dataSource: dataSource,
            selectable: false,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            sortable: {
                mode: "multiple"
              },
            toolbar: ["create"],
            editable: "inline",
            dataBound: onDataBound,
            change: onChange,
            columns: [
                { field: "id", title: "Theme ID", width: "100px" },
                { field: "Title", title: "Title" },
                { field: "StartDate", title: "Start Date", format: "{0:MM/dd/yyyy}", },
                { field: "EndDate", title: "End Date", format: "{0:MM/dd/yyyy}", },
                { field: "Gameboard_id", title: "Gameboard", template:"#: GameboardTitle#", editor: GameboardDropDown },
                { field: "AgeGroup_id", title: "Age Group", template:"#: AgeGroupTitle#", editor: AgeGroupDropDown },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });

        function GameboardDropDown(container, options) {
            var gameboarddatasource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/gameboard",
                            dataType: "json",
                            method: "GET",
                            data: options.data,
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            success: function (result) {
                                options.success(result);
                            },
                            error: function (result) {
                                options.error(result);
                            }
                        });
                    }
                }
            });
            $('<input required name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                    dataTextField: "Title",
                    dataValueField: "id",
                    dataSource: gameboarddatasource
                });
        }

        function AgeGroupDropDown(container, options) {
            var agegroupdatasource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/agegroup",
                            dataType: "json",
                            method: "GET",
                            data: options.data,
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            success: function (result) {
                                options.success(result);
                            },
                            error: function (result) {
                                options.error(result);
                            }
                        });
                    }
                }
            });
            $('<input required name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                    dataTextField: "Name",
                    dataValueField: "id",
                    dataSource: agegroupdatasource
                });
        }

        function onDataBound() {
            console.log("Theme ListView data bound");
            hideLoading("#theme-grid");
        }

        function onChange(e) {
            
        }
    })
});