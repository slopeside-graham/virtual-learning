$ = jQuery;

$(document).ready(function () {

    // This is kendo stuff, dont use it now
    /*
    $(function () {
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: wpApiSettings.root + "ghes-vlp/v1/payment",
                    dataType: "json",
                    method: "GET",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                    },
                    success: function (result) {
                        options.success(result);
                        console.log(result);
                    },
                    error: function (result) {
                        options.error(result);
                        console.log(result);
                    }
                }
            }
        });

        $("#payments-list").kendoListView({
            dataSource: dataSource,
            template: kendo.template($("#payment-list-template").html())
        });
    });
    */
});