$ = jQuery;

$(function () {

    $(document).ready(function () {
        ageGroupid = getCookie("AgeGroupid");

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
                field: "Title",
                dir: "asc"
            }
        });

        $(function() {
            var dataSource = themedataSource
            $("#themes-listView").kendoListView({
                dataSource: dataSource,
                template: kendo.template($("#template").html())
            });
        });
    });
});