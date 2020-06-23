$ = jQuery;
var customMediaLibrary;

$(function () {
    $(document).ready(function () {

        customMediaLibrary = getcustomMediaLibrary();

        customMediaLibrary.on('select', function () {
            // write your handling code here.
            var selectedMedia = customMediaLibrary.state().get('selection');
            var selectedMediaID = '';
            var selectedMediaURL = '';
            var selectedMediaTitle = '';

            selectedMedia.each(function (attachment) {
                selectedMediaID = attachment['id'];
                selectedMediaURL = attachment.attributes['url'];
                selectedMediaTitle = attachment.attributes['title'];
            });
            $("input[name='Image_id']").val(selectedMediaID).change();
            $("input[name='Image_id']").siblings("img").attr("src", selectedMediaURL);
        });

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
                        Image_url: { validation: { required: true } },
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
            edit: onEdit,
            
            columns: [
                { field: "id", title: "Age Group ID", width: "100px" },
                { field: "Name", title: "Name" },
                { field: "AgeStart", title: "Age Start (months)" },
                { field: "AgeEnd", title: "Age End (months)" },
                { field: "Image_id", title: "Icon", editor: imageSelector, template: "<img src='#=Image_url#' width='100' height='100'>" },
                { field: "Position", title: "Position" },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });
        function imageSelector(container, options) {
            $('<input hidden required name="' + options.field + '"/>' + '<img src="' + options.model.Image_url + '" width="100" height="100"><br/><span class="k-button" id="icon-add-btn"><span class="k-icon k-i-plus"></span>Add Icon</span>')
                .appendTo(container)
        }

        function onEdit(e) {

            currentLessonID = e.model.id;

            // Open the media uploader.
            $('#icon-add-btn').on('click', function (e) {
                e.preventDefault();
                customMediaLibrary.open();
            });
            if ($("input[name='Image_id']").val() !="") {
                $("#icon-add-btn").text("Change Image");
            }

        }

        function onDataBound() {
            console.log("AgeGroups ListView data bound");
            hideLoading("#agegroups-grid");
        }

        function onChange(e) {

        }
    })
});