$ = jQuery;
$(function () {
    $(document).ready(function () {
        kendo.ui.progress($(".loading-window"), true);
        $(".loading-window").show();

        var crudServiceBaseUrl = wpApiSettings.root + "ghes-vlp/v1",
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
                    field: "Title",
                    dir: "asc"
                },
                schema: {
                    model: {
                        id: "id",
                        fields: {
                            id: { editable: false, nullable: true },
                            Title: { validation: { required: true } },
                            StartDate: { validation: { required: false } },
                            EndDate: { validation: { required: false } },
                            Gameboard_id: { validation: { required: true } },
                            DateCreated: { editable: false, validation: { required: true } },
                            DateModified: { editable: false, validation: { required: true } },
                        }
                    }
                }
            });

        $("#theme-grid").kendoGrid({
            dataSource: dataSource,
            selectable: false,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            toolbar: ["create"],
            editable: "inline",
            dataBound: onDataBound,
            change: onChange,
            columns: [
                { field: "id", title: "Theme ID", width: "100px" },
                { field: "Title", title: "Title" },
                { field: "StartDate", title: "Start Date" },
                { field: "EndDate", title: "End Date" },
                { field: "Gameboard_id", title: "Gameboard ID" },
                { field: "DateCreated", title: "Date Created" },
                { field: "DateModified", title: "Date Modified" },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });
        function onDataBound() {
            console.log("Theme ListView data bound");
            $('.loading-window').hide();
        }

        function onChange(e) {
        }
    })
});