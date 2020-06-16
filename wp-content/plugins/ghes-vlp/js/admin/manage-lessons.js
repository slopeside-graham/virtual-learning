$ = jQuery;
var relatedMaterialsData;
var currentLessonID;
var customMediaLibrary;

function displayLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, true);
}
function hideLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, false);
}

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
            $("#newResourceId").val(selectedMediaID).change();
            $("#newResourceURL").text(selectedMediaTitle);
            $("#newResourceURL").attr("href", selectedMediaURL);
            $("#newResourceURL").show();
            $("#newResourceButton").text("Change Media");

        });

        displayLoading("#lesson-grid");

        lessondataSource = new kendo.data.DataSource({
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
                        ThemeTitle: { editable: true, validation: { required: true } },
                        AgeGroup_id: { editable: true, validation: { required: true } },
                        AgeGroupName: { editable: true, validation: { required: true } },
                    }
                }
            }
        });

        $("#lesson-grid").kendoGrid({
            dataSource: lessondataSource,
            selectable: false,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            toolbar: ["create"],
            editable: {
                mode: "popup",
                template: kendo.template($("#lesson-editor").html()),
                window: {
                    title: "Edit Lesson",
                    width: "70%"
                }
            },
            edit: onEdit,
            dataBound: onDataBound,
            change: onChange,
            columns: [
                { field: "id", title: "ID", width: "100px" },
                { field: "Title", title: "Title" },
                { field: "Type", title: "Type" },
                { field: "MainContent", title: "Main Content", encoded: false },
                { field: "VideoURL", title: "Video URL" },
                { field: "Image_id", title: "Image ID" },
                { field: "ThemeTitle", title: "Theme Title" },
                { field: "AgeGroupName", title: "Age Group" },
                { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }
            ],
        });
        function onDataBound() {
            console.log("Lesson ListView data bound");
            hideLoading("#lesson-grid");
        }

        function onChange(e) {
        }

        function onEdit(e) {

            currentLessonID = e.model.id;

            // Open the media uploader.
            $('#newResourceButton').on('click', function (e) {
                e.preventDefault();
                customMediaLibrary.open();
            });

            $('#LessonTheme')
                .kendoDropDownList({
                    dataTextField: "Title",
                    dataValueField: "id",
                    value: e.model.Theme_id,
                    dataSource: ThemeData
                });

            $('#LessonAgeGroup')
                .kendoDropDownList({
                    dataTextField: "Name",
                    dataValueField: "id",
                    dataSource: AgeGroupData,
                    value: e.model.AgeGroup_id,
                });

            $('#LessonType')
                .kendoDropDownList({
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: activityData,
                    index: 0,
                })
            $('#LessonTitle')
                .kendoTextBox({

                });
            $('#newResourceTitle')
                .kendoTextBox({

                });
            $('#LessonMainContent')
                .kendoEditor(
                    {
                        resizable: true,
                        pasteCleanup: {
                          all: true
                        }
                      }
                );

            $('#LessonVideo')
                .kendoTextBox({
                });


            relatedMaterialsData = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        displayLoading("#LessonRelatedMaterials");
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/resource",
                            dataType: "json",
                            method: "GET",
                            data: {
                                lesson_id: e.model.id,
                            },
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            success: function (result) {
                                options.success(result);
                                hideLoading("#LessonRelatedMaterials");
                            },
                            error: function (result) {
                                hideLoading("#LessonRelatedMaterials");

                                if (typeof result.responseJSON !== "undefined") {
                                    alert(result.responseJSON.message);
                                }
                                console.log(result.responseText);
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    },
                    create: function (options) {
                        displayLoading("#LessonRelatedMaterials");
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/resource",
                            method: "POST",
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            data: options.data,
                            success: function (result) {
                                hideLoading("#LessonRelatedMaterials");
                                // notify the data source that the request succeeded
                                console.log("Done:" + result);
                                options.success(result);
                            },
                            error: function (result) {
                                hideLoading("#LessonRelatedMaterials");
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
                        displayLoading("#LessonRelatedMaterials");
                        $.ajax({
                            url: wpApiSettings.root + "ghes-vlp/v1/resource",
                            method: "DELETE",
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                            },
                            data: options.data,
                            success: function (result) {
                                hideLoading("#LessonRelatedMaterials");
                                // notify the data source that the request succeeded
                                console.log("Done:" + result);
                                options.success(result);
                            },
                            error: function (result) {
                                hideLoading("#LessonRelatedMaterials");
                                if (typeof result.responseJSON !== "undefined") {
                                    alert(result.responseJSON.message);
                                }
                                console.log(result.responseText);
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    }
                }
            });

            $("#LessonRelatedMaterials").kendoListView({
                dataSource: relatedMaterialsData,
                template: kendo.template($("#RelatedMaterialstemplate").html())
            });

        }
    })
    var activityData = [
        { text: "Play", value: "Play" },
        { text: "Learn", value: "Learn" },
        { text: "Art", value: "Art" },
        { text: "Nurture", value: "Nurture" }
    ];

    var ThemeData = {
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
            }
        }
    }
    var AgeGroupData = {
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
    }
});

function AddNewResource() {
    displayLoading("#LessonRelatedMaterials");
    if ($("#newResourceTitle").val() != "" && $("#newResourceId").val() != "") {
        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/resource",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Title: $("#newResourceTitle").val(),
                Media_id: $("#newResourceId").val(),
                Lesson_id: currentLessonID
            },
            success: function (result) {
                hideLoading("#LessonRelatedMaterials");
                console.log("Done:" + result);
                $("#newResourceTitle").val('');
                $("#newResourceId").val('');
                $("#newResourceURL").hide();
                $("#newResourceButton").text("Add Media");
                $("#addResource").addClass("k-state-disabled");
                var listView = $("#LessonRelatedMaterials").data("kendoListView");
                // refreshes the ListView
                listView.dataSource.read();
                listView.refresh();
            },
            error: function (result) {
                hideLoading("#LessonRelatedMaterials");
                if (typeof result.responseJSON !== "undefined") {
                    alert(result.responseJSON.message);
                }
                console.log(result.responseText);
                // notify the data source that the request failed
            }
        });
    }
}
function DeleteResource(e) {
    displayLoading("#LessonRelatedMaterials");
    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/resource",
        method: "DELETE",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        data: {
            id: $("#LessonRelatedMaterials").data("kendoListView").dataItem($(e).closest("div").parent("div"))['id']
        },
        success: function (result) {

            console.log("Done:" + result);
            var listView = $("#LessonRelatedMaterials").data("kendoListView");
            // refreshes the ListView
            listView.dataSource.read();
            listView.refresh();
        },
        error: function (result) {
            hideLoading("#LessonRelatedMaterials");
            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.message);
            }
            console.log(result.responseText);
            // notify the data source that the request failed
        }
    });
}

function UpdateVideo(video) {
    var videourl = $(video).val();
    console.log(videourl);

    $("#LessonVideoiFrame").attr("src", videourl);

}
function ResourceRequired() {
    if( ($("#newResourceTitle").val() != "") && ($("#newResourceId").val() != "") ) {
        $("#addResource").removeClass("k-state-disabled");
    } 
}