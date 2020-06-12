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
                            url: wpApiSettings.root + "ghes-vlp/v1/lesson",
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
                            url: wpApiSettings.root + "ghes-vlp/v1/lesson",
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
                            url: wpApiSettings.root + "ghes-vlp/v1/lesson",
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
                            url: wpApiSettings.root + "ghes-vlp/v1/lesson",
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
                            Type: { validation: { required: false } },
                            MainContent: { validation: { required: false } },
                            VideoURL: { validation: { required: false } },
                            Image_id: { editable: true, validation: { required: false } },
                            Theme_id: { editable: true, validation: { required: true } },
                            AgeGroup_id: { editable: true, validation: { required: true } },
                            DateCreated: { editable: false, validation: { required: true } },
                            DateModified: { editable: false, validation: { required: true } },
                        }
                    }
                }
            });

        $("#lesson-grid").kendoGrid({
            dataSource: dataSource,
            selectable: false,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            toolbar: ["create"],
            editable: {
                mode: "popup",
                template: kendo.template($("#lesson-editor").html())
            },
            dataBound: onDataBound,
            change: onChange,
            columns: [
                { field: "id", title: "ID", width: "100px" },
                { field: "Title", title: "Title" },
                { field: "Type", title: "Type" },
                { field: "MainContent", title: "Main Content" },
                { field: "VideoURL", title: "Video URL" },
                { field: "Image_id", title: "Image ID" },
                { field: "Theme_id", title: "Theme ID" },
                { field: "AgeGroup_id", title: "AgeGroup ID" },
                { field: "DateCreated", title: "Date Created" },
                { field: "DateModified", title: "Date Modified" },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });
        function onDataBound() {
            console.log("Lesson ListView data bound");
            $('.loading-window').hide();
        }

        function onChange(e) {
        }
    })
});