$ = jQuery;
$(function () {
    $(document).ready(function () {
        dataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: wpApiSettings.root + "ghes-vlp/v1/payment",
                        dataType: "json",
                        method: "GET",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                        },
                        success: function (result) {
                            console.log("Done:" + result);
                            options.success(result);
                        },
                        error: function (result) {
                            options.error(result);
                        }
                    });
                }
            },
            batch: true,
            sort: {
                field: "DateCreated",
                dir: "desc"
            },
            schema: {
                model: {
                    id: "id",
                    fields: {
                        id: {
                            editable: false,
                            nullable: true
                        },
                        Amount: {
                            editable: false,
                            nullable: true,
                            type: "number"
                        },
                        ResponseCode: {
                            editable: false,
                            nullable: true
                        },
                        accountNumber: {
                            editable: false,
                            nullable: true
                        },
                        accountType: {
                            editable: false,
                            nullable: true
                        },
                        DateCreated: {
                            editable: false,
                            nullable: true,
                            type: "date",
                            format: "{0:yyyy-MM-dd}",
                            parse: parseVLPDate
                        }
                    }
                }
            }
        });

        function parseVLPDate(data) {
            if (data.date) {
                return kendo.parseDate(data.date, "yyyy-MM-dd");
            } else {
                return data;
            }
        }

        $("#vlp-payments-grid").kendoGrid({
            dataSource: dataSource,
            pageable: {
                numeric: false,
                previousNext: false,
                info: false
            },
            scrollable: false,
            resizable: true,
            height: 200,
            dataBound: function () {
                for (var i = 0; i < this.columns.length; i++) {
                    this.autoFitColumn(i);
                };
            },
            noRecords: {
                template: "No payments have been made."
            },
            columns: [
                {
                    field: "ResponseCode",
                    title: "Status",
                    template: function (dataItem) {
                        if (dataItem.ResponseCode == 1) {
                            return "Paid"
                        } else {
                            return "Payment Error"
                        }
                    }
                },
                {
                    field: "DateCreated",
                    title: "Payment Date",
                    format: "{0:MM/dd/yyyy}"
                },
                {
                    field: "Amount",
                    title: "Amount",
                    format: "{0:c}"
                },
                {
                    field: "accountNumber",
                    title: "Account",
                },
                {
                    field: "accountType",
                    title: "Payment Type",
                },
                {
                    command:
                    {
                        text: "View Receipt",
                        click: viewReceipt
                    },
                    title: "&nbsp;",
                    width: "180px"
                }
            ]
        });

        function viewReceipt() {

        }
    });
});