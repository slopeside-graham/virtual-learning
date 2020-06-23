$ = jQuery;
$(function () {
    $(document).ready(function () {
        kendo.ui.progress($(".loading-window"), true);

        displayLoading("#agegroups-grid");

            dataSource = new kendo.data.DataSource({
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
                    },
                    create: function (options) {
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/agegroup",
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
                            url: wpApiSettings.root + "ghes-vlp/v1/agegroup",
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
                            url: wpApiSettings.root + "ghes-vlp/v1/agegroup",
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
                            Name: { validation: { required: true } },
                            AgeStart: { validation: { required: false } },
                            AgeEnd: { validation: { required: false } },
                            Image_id: { validation: { required: true } },
                            Position: { validation: { required: true } },
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

        $("#agegroups-grid").kendoGrid({
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
                { field: "id", title: "Age Group ID", width: "100px" },
                { field: "Name", title: "Name" },
                { field: "AgeStart", title: "Age Start (months)" },
                { field: "AgeEnd", title: "Age End (months)" },
                { field: "Image_id", title: "Icon" },
                { field: "Position", title: "Position" },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });

        function onDataBound() {
            console.log("AgeGroups ListView data bound");
            hideLoading("#agegroups-grid");
        }

        function onChange(e) {
            
        }
    })
});