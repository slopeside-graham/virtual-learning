$ = jQuery;
var relatedMaterialsData;
var currentLessonID;
var customMediaLibrary;

var validator = $("#lesson-grid").kendoValidator().data("kendoValidator");

// Validate the input when the Save button is clicked
$(".k-grid-update").on("click", function() {
    if (validator.validate()) {
        // If the form is valid, the Validator will return true
        save();
    }
});

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
            $("#newResourceMediaButton").text("Change Media");

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
                        data: options.data,
                        success: function (result) {
                            // notify the data source that the request succeeded
                            console.log("Done:" + result);
                            options.success(result);
                            newLessonid = result.id;
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
                        data: options.data,
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
                        data: options.data,
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
            sort: {
                field: "ThemeStartDate",
                dir: "desc"
            },
            schema: {
                model: {
                    id: "id",
                    fields: {
                        id: { editable: false, nullable: true },
                        Title: { validation: { required: true } },
                        Type: { validation: { required: true } },
                        MainContent: { validation: { required: false } },
                        VideoURL: { validation: { required: false } },
                        Image_id: { editable: true, validation: { required: false } },
                        Theme_id: { editable: true, validation: { required: true } },
                        ThemeTitle: { editable: false },
                        ThemeAgeGroupName: { editable: false },
                        ThemeStartDate: { editable: false, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate },
                        ThemeEndDate: { editable: false, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate }
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

        $("#lesson-grid").kendoGrid({
            dataSource: lessondataSource,
            selectable: false,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            filterable: true,
            sortable: true,
            resizable: true,
            groupable: true,
            toolbar: [
                { name: "create" },
                { name: "search" }
            ],
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
                { field: "id", title: "ID", editable: false },
                { field: "Title", title: "Title", required: true },
                { field: "ThemeTitle", title: "Theme", editable: false, template: '#: ThemeTitle #: #: kendo.format("{0:MM/dd/yyyy}", ThemeStartDate)# - #: kendo.format("{0:MM/dd/yyyy}", ThemeEndDate)#' },
                { field: "ThemeAgeGroupName", title: "Age Group", editable: false },
                { field: "Type", title: "Type", required: true },
                { field: "VideoURL", title: "Video URL" },
                //{ field: "Image_id", title: "Image ID", width: "100px" },
                { command: ["edit", "destroy"], title: "&nbsp;" }
            ],
        });
        var grid = $("#lesson-grid").data("kendoGrid");
        grid.autoFitColumn("id");
        grid.autoFitColumn("Type");

        function onDataBound() {
            console.log("Lesson ListView data bound");
            hideLoading("#lesson-grid");

        }

        function onChange(e) {
        }

        function onEdit(e) {

            currentLessonID = e.model.id;

            if (e.model.id == null ) {
                $("#LessonRelatedMaterials").hide();
                $(".resources").hide();
                $(".lesson-not-saved").show();
            } else {
                $("#LessonRelatedMaterials").show();
                $(".resources").show();
                $(".lesson-not-saved").hide();
            }
            //console.log(currentLessonId);

            // Open the media uploader.
            $('#newResourceMediaButton').on('click', function (e) {
                $("#newResourceLinkButton").hide();
                e.preventDefault();
                customMediaLibrary.open();
            });

            $('#LessonTheme')
                .kendoDropDownList({
                    dataTextField: "Title",
                    dataValueField: "id",
                    template: '#: Title #: #: kendo.format("{0:MM/dd/yyyy}", StartDate)# - #: kendo.format("{0:MM/dd/yyyy}", EndDate)# - #: AgeGroupTitle #',
                    valueTemplate: '#: Title #: #: kendo.format("{0:MM/dd/yyyy}", StartDate)# - #: kendo.format("{0:MM/dd/yyyy}", EndDate)# - #: AgeGroupTitle #',
                    value: e.model.Theme_id,
                    dataSource: ThemeData
                });

            $('#LessonType')
                .kendoDropDownList({
                    dataTextField: "text",
                    dataValueField: "text",
                    dataSource: activityData,
                    valuePrimitive: true
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
        { text: "Play" },
        { text: "Learn" },
        { text: "Art" },
        { text: "Nurture" }
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
        },
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
    }
    function parseDate(data) {
        if (data.date) {
            return kendo.parseDate(data.date, "yyyy-MM-dd");
        } else {
            return data;
        }
    }
});

function AddNewResourceLink() {
    $("#newResourceLink").show();
    $("#newResourceLinkButton").hide();
    $("#newResourceMediaButton").hide()
}

function AddNewResource() {
    displayLoading("#LessonRelatedMaterials");
    if ($("#newResourceTitle").val() != "" && ($("#newResourceId").val() != "") || $("#newResourceLink").val() != "") {
        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/resource",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Title: $("#newResourceTitle").val(),
                Media_id: $("#newResourceId").val(),
                Link: $("#newResourceLink").val(),
                Lesson_id: currentLessonID
            },
            success: function (result) {
                hideLoading("#LessonRelatedMaterials");
                console.log("Done:" + result);
                $("#newResourceTitle").val('');
                $("#newResourceId").val('');
                $("#newResourceURL").hide();
                $("#newResourceLink").val('');
                $("#newResourceLink").hide();
                $("#newResourceMediaButton").text("Add Media");
                $("#newResourceMediaButton").show();
                $("#newResourceLinkButton").show();
                $("#addResource").addClass("k-state-disabled");
                var listView = $("#LessonRelatedMaterials").data("kendoListView");
                // refreshes the ListView
                listView.dataSource.read();
                listView.refresh();
                $(".k-grid-update").removeClass("k-state-disabled");
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
    if (($("#newResourceTitle").val() != "") && ($("#newResourceId").val() != "") || $("#newResourceLink").val() != "") {
        $("#addResource").removeClass("k-state-disabled");
        $("#addResource").addClass("k-primary");
    } else if ($("#newResourceTitle").val() == "") {
        $("#addResource").addClass("k-state-disabled");
        $(".k-grid-update").addClass("k-state-disabled");
    }
}