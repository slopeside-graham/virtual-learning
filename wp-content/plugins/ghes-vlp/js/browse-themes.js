$ = jQuery;

function SetTheme(clicked_item) {
        setCookie("VLPThemeId", clicked_item.dataset.themeId, 0, '/');
}

$(function () {
    $(document).ready(function () {
        ageGroupid = getCookie("VLPAgeGroupId");

        displayLoading("#themes-listView");

        themedataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: wpApiSettings.root + "ghes-vlp/v1/theme",
                        dataType: "json",
                        method: "GET",
                        data: { ageGroupid: ageGroupid },
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                        },
                        success: function (result) {
                            hideLoading("#themes-listView");
                            options.success(result);
                        },
                        error: function (result) {
                            hideLoading("#themes-listView");
                            options.error(result);
                        }
                    });
                },
            },
            sort: {
                field: "StartDate",
                dir: "desc"
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

        $(function() {
            var dataSource = themedataSource
            $("#themes-listView").kendoListView({
                dataSource: dataSource,
                template: kendo.template($("#template").html()),
                dataBound: function(e) {
                    if(this.dataSource.data().length == 0){
                        //custom logic
                        $("#themes-listView").parent().append("<p>&nbsp;</p><h2>No Themes found</h2><p>&nbsp;</p>");
                        $("#themes-listView").hide();
                    }
                },
            });
        });
    });
});