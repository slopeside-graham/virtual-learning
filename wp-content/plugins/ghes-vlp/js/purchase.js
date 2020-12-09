$ = jQuery;

$(document).ready(function () {

    // This is important because it cancels a standard form submit event, and lets us do it via ajax.


    $("#purchasesubscription").on("click", function (event) {
        event.preventDefault();
        if (validator.validate()) {
            // If the form is valid, the Validator will return true
            purchaseSubscription();
        }
    });

    var payment = getCookie("payment");
    if (payment == "true") {
        $(".successful-payment").show();
        document.cookie = "payment=false";
    }

    var refund = getCookie("refund");
    if (refund == "true") {
        $(".successful-refund").show();
        document.cookie = "refund=false";
    }

    $("#vlp-bill-ExpirationMonth").kendoDateInput({
        format: "MM",
    });

    $("#vlp-bill-ExpirationYear").kendoDateInput({
        format: "yyyy",
    });

    $("#vlp-bill-CardCode").kendoMaskedTextBox({
        mask: "000"
    });

});

window.onload = function () {
    calculateTotal();

    var idselector = function () { return this.dataset.id; };
    var payments = $(".subscription-payment:checked").get();
    payments.forEach(function (payments) {
        pendingPayment(payments.dataset);
    })
};

$(".subscription-payment").click(function (e) {
    payment = e.currentTarget.dataset;
    if ($(e.target).prop("checked")) {
        pendingPayment(payment);
    } else {
        unpendingPayment(payment);
    }
    calculateTotal();
});

$(".payment-type").click(function (e) {
    if ($("#credit-card-select").prop("checked")) {
        $(".credit-card-section input").prop("disabled", false);
        $(".ach-section input").prop("disabled", true);

        $(".credit-card-section").show();
        $(".ach-section").hide();
    } else if ($("#ach-select").prop("checked")) {
        $(".ach-section input").prop("disabled", false);
        $(".credit-card-section input").prop("disabled", true);

        $(".ach-section").show();
        $(".credit-card-section").hide();
    }
});

function calculateTotal() {
    var currentDue = 0;
    var futureDue = 0;
    $(".current-due:checked").each(function () {
        currentDue += +$(this).val();
    });
    $(".future-due:checked").each(function () {
        futureDue += +$(this).val();
    });

    var totalDue = currentDue + futureDue;

    // $("#current-due").text("Current Due: $" + currentDue);
    $(".total-due").text("Total Due: $" + totalDue);

    if (totalDue > 0) {
        $("#showpaymentbtn").prop('disabled', false);
    } else {
        $("#showpaymentbtn").prop('disabled', true);
    }

    console.log("Total Checked Current Payment: " + currentDue);
    console.log("Total Checked Future Payment: " + futureDue);
    console.log("Total Payment: " + totalDue);

    return totalDue;
};

function showPurchase() {
    $("#showpaymentbtn").hide();
    $(".purchase-vll-billing").show();

}

function purchaseSubscription() {
    displayLoading('.purchase-vll-billing');
    var idSelector = function () { return this.dataset.id; };
    var selectedPayments = $(".subscription-payment:checked").map(idSelector).get();

    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/payment",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        timeout: 60000,
        data: {
            Amount: calculateTotal(),
            CardNumber: $("#vlp-bill-CardNumber").val().replace(/\s/g, ''),
            ExpirationDate: $("#vlp-bill-ExpirationYear").val() + "-" + $("#vlp-bill-ExpirationMonth").val(),
            CardCode: $("#vlp-bill-CardCode").val(),

            AccountType: $("input[name=AccountType]:checked").val(),
            EcheckType: 'WEB',
            RoutingNumber: $("#vlp-bill-RoutingNumber").val(),
            AccountNumber: $("#vlp-bill-AccountNumber").val(),
            NameOnAccount: $("#vlp-bill-NameOnAccount").val(),
            BankName: $("#vlp-bill-BankName").val(),

            FirstName: $("#vlp-bill-FirstName").val(),
            LastName: $("#vlp-bill-LastName").val(),
            Address: $("#vlp-bill-Address").val(),
            City: $("#vlp-bill-City").val(),
            State: $("#vlp-bill-State").val(),
            Zip: $("#vlp-bill-Zip").val(),
            PhoneNumber: $("#vlp-bill-PhoneNumber").val(),
            Email: $("#vlp-bill-Email").val(),
            SubscriptionPayments: selectedPayments
        },
        success: function (result) {
            console.log("Success:" + result);
            //Success!
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                conversionValue: calculateTotal(),
                event: "new_vll_subscription"
            });
            document.cookie = "paymentid=" + result.id + "; path=/";
            window.location.replace(paymentconfirmationlink);
        },
        error: function (result) {
            console.log("Purchase Subscription Failed");

            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.message);
            } else {
                alert(
                    "An unexpected error occured.  Please review your submission and try again."
                );
            }
            hideLoading('.purchase-vll-billing');
            console.log(result.responseText);
        }
    })
}

function pendingPayment(payment) {
    if (payment.status == 'Unpaid') {
        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/subscriptionpayment",
            method: "PUT",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            timeout: 60000,
            data: {
                id: payment.id,
                Status: "Pending"
            },
            success: function (result) {
                console.log("Success:" + result);
                //Success!
                window.dataLayer = window.dataLayer || [];
            },
            error: function (result) {
                console.log("Update Payment Status to Pending Failed");

                if (typeof result.responseJSON !== "undefined") {
                    alert(result.responseJSON.message);
                } else {
                    alert(
                        "An unexpected error occured.  Please review your submission and try again."
                    );
                }
                hideLoading('.purchase-vll-billing');
                console.log(result.responseText);
            }
        })
    }
}

function unpendingPayment(payment) {
    if (payment.status == "Pending") {
        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/subscriptionpayment",
            method: "PUT",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            timeout: 60000,
            data: {
                id: payment.id,
                Status: "Unpaid"
            },
            success: function (result) {
                console.log("Success:" + result);
                //Success!
                window.dataLayer = window.dataLayer || [];
            },
            error: function (result) {
                console.log("Update Status to Unpaid Failed");

                if (typeof result.responseJSON !== "undefined") {
                    alert(result.responseJSON.message);
                } else {
                    alert(
                        "An unexpected error occured.  Please review your submission and try again."
                    );
                }
                hideLoading('.purchase-vll-billing');
                console.log(result.responseText);
            }
        })
    }
}
var selectedSubscriptionId
function openCancelDialog(selectedSubscription) {
    selectedSubscriptionId = selectedSubscription.parentElement.dataset.subscriptionid;
    selectedSubscriptionType = selectedSubscription.parentElement.dataset.subscriptiontype;

    var cancelContent

    if (selectedSubscriptionType == "monthly") {
        cancelContent = "/wp-content/plugins/ghes-vlp/views/templates/monthly-cancel-message.html";
    } else if (selectedSubscriptionType == "yearly") {
        cancelContent = "/wp-content/plugins/ghes-vlp/views/templates/yearly-cancel-message.html";
    }
    $("#cancel-subscription").kendoWindow({
        visible: false,
        modal: true,
        pinned: true,
        width: "600px",
        title: "Confirm Subscription Cancel",
        visible: false,
        actions: [
            "Close"
        ],
        size: "medium",
        scrollable: false,
        content: cancelContent
    });
    $("#cancel-subscription").data("kendoWindow").center().open();
}
function closeCancelDialog() {
    $("#cancel-subscription").data("kendoWindow").close();
}
function aproveCancelSubscription(selectedSubscriptionId) {
    displayLoading($("#cancel-subscription"));
    cancelSubscription(selectedSubscriptionId);
}
function cancelSubscription() {
    displayLoading($("#cancel-subscription"));
    console.log("Confirm Cancel Subscription " + selectedSubscriptionId);

    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/subscription",
        method: "PUT",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        timeout: 60000,
        data: {
            id: selectedSubscriptionId,
            Status: "Cancelled"
        },
        success: function (result) {
            //Success!
            console.log("Success:" + result);
            
            document.cookie = "subscriptioncancelid=" + result.id + "; path=/";
            window.location.replace(cancelconfirmationlink);
        },
        error: function (result) {
            console.log("Failed to Cancel Subscription");
            $("#cancel-subscription").data("kendoWindow").close();
            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.code);
            } else {
                alert(
                    "An unexpected error occured.  Please review your submission and try again."
                );
            }
            hideLoading('.k-window');
            console.log(result.responseText);
        }
    })
}

// Get Customer Payment Profiles
// Use this for futere 
/*
$(function () {
    var dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: wpApiSettings.root + "ghes-vlp/v1/customerpaymentprofile",
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

    $("#customer-payment-methods").kendoListView({
        dataSource: dataSource,
        template: kendo.template($("#customer-payment-methods-list-template").html())
    });
});
*/