$ = jQuery;


$(function () {



    $(document).ready(function () {

        var playicon = $('#play-icon').children('svg').get(0);
        var articon = $('#art-icon').children('svg').get(0);
        var learnicon = $('#learn-icon').children('svg').get(0);
        var nurtureicon = $('#nurture-icon').children('svg').get(0);


        var ageGroupid = getCookie("VLPAgeGroupId");
        var VLPSelectedChild = getCookie("VLPSelectedChild");

        displayLoading("#lessons-listView");

        lessondataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: wpApiSettings.root + "ghes-vlp/v1/lesson",
                        dataType: "json",
                        method: "GET",
                        data: {ageGroupid: ageGroupid},
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                        },
                        success: function (result) {
                            hideLoading("#lessons-listView");
                            options.success(result);
                        },
                        error: function (result) {
                            hideLoading("#lessons-listView");
                            options.error(result);
                        }
                    });
                },
            },
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
                        Type: { validation: { required: true } },
                        MainContent: { validation: { required: false } },
                        VideoURL: { validation: { required: false } },
                        Image_id: { editable: true, validation: { required: false } },
                        Theme_id: { editable: true, validation: { required: true } },
                        ThemeTitle: { editable: true, validation: { required: true } },
                        ThemeAgeGroupName: { editable: true, validation: { required: true } },
                        ThemeStartDate: { editable: true, validation: { required: true }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate },
                        ThemeEndDate: { editable: true, validation: { required: true }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate }
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

        $(function () {
            var dataSource = lessondataSource
            $("#lessons-listView").kendoListView({
                dataSource: dataSource,
                template: kendo.template($("#lesson-template").html()),
                dataBound: onDataBound,
            });
        });

        function onDataBound() {
            $('.Play-icon').html(playicon.outerHTML);
            $('.Art-icon').html(articon.outerHTML);
            $('.Learn-icon').html(learnicon.outerHTML);
            $('.Nurture-icon').html(nurtureicon.outerHTML);

            if(this.dataSource.data().length == 0){
                $("#lessons-listView").append("<h2>No Lessons for this Age</h2>");
            }
        }


    });
});