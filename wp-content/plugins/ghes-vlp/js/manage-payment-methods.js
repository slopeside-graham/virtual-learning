$ = jQuery;
$(function () {
    $(document).ready(function () {
        displayLoading($("#vlp-payment-methods"));

        dataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: wpApiSettings.root + "ghes-vlp/v1/paymentmethod",
                        dataType: "json",
                        method: "GET",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                        },
                        success: function (result) {
                            hideLoading($("#vlp-payment-methods"));
                            console.log("Done:" + result);
                            options.success(result);
                            console.info(result);
                        },
                        error: function (result) {
                            hideLoading($("#vlp-payment-methods"));
                            options.error(result);
                        }
                    });
                }
            },
            batch: true,
            pageSize: 20,
            schema: {
                model: {
                    id: "id",
                    fields: {
                        // PaymentMethod Info
                        customerPaymentProfileId: { editable: false, nullable: false },
                        customerProfileId: { editable: false, nullable: false },
                        DefaultPayment: { editable: false, nullable: false },
                        //Billing Info
                        FirstName: { editable: false, nullable: false },
                        LastName: { editable: false, nullable: false },
                        PhoneNumber: { editable: false, nullable: false },
                        Address: { editable: false, nullable: false },
                        City: { editable: false, nullable: false },
                        State: { editable: false, nullable: false },
                        Zip: { editable: false, nullable: false },
                        Country: { editable: false, nullable: false },
                        //Bank Account Info
                        AccountNumber: { editable: false, nullable: false },
                        AccountType: { editable: false, nullable: false },
                        EcheckType: { editable: false, nullable: false },
                        RoutingNumber: { editable: false, nullable: false },
                        //CC Info
                        CardNumber: { editable: false, nullable: false },
                        CardType: { editable: false, nullable: false },
                        ExpirationDate: { editable: false, nullable: false }
                    }
                }
            }
        });

        /*
                $("#vlpManagePaymentMethodsList").kendoListView({
                    dataSource: dataSource,
                    template: kendo.template($("#payment-template").html())
                });
        */

        $("#vlpManagePaymentMethodsGrid").kendoGrid({
            dataSource: dataSource,
            toolbar: [
                {
                    name: "create",
                    text: "Add Payment Method"
                }
            ],
            editable: {
                mode: "popup",
                template: kendo.template($("#popup_editor").html())
            },
            edit: billingForm,
            scrollable: false,
            dataBound: function () {
                for (var i = 0; i < this.columns.length; i++) {
                    this.autoFitColumn(i);
                }
            },
            noRecords: {
                template: "No Saved Payment Methods, please add a new one."
            },
            columns: [
                {
                    field: "Name",
                    title: "Name",
                    template: "#: FirstName# #: LastName#"
                },
                {
                    field: "Account",
                    title: "Account",
                    template: '#if(CardNumber != null) {# #: CardType # - #: CardNumber# #: ExpirationDate # # }  else {# #: AccountType # - #: BankName # - #: AccountNumber# #}#'
                },
                {
                    field: "DefaultPayment",
                    title: "&nbsp;",
                    template: '#if(DefaultPayment == 1) {# Default Payment Method #} else {#  #}#'
                },
                {
                    command: ["edit"],
                    title: "&nbsp;",
                    width: "100px"
                }
            ]
        });

        function billingForm(e) {
            $("billing-form").kendoForm({
                orientation: "vertical",
                items: [{
                    type: "group",
                    label: "Billing Information",
                    items: [
                        { field: "FirstName", label: "FirstName" }
                    ]
                }]
            })
        }

        function mmyydatepicker(container, options) {
            $('<input type="text" />')
                .appendTo(container)
                .kendoDatePicker({
                    start: "year",
                    depth: "year",
                    format: "yyyy-MM",
                    dateInput: true,
                    min: new Date()
                });
        }
    });
});