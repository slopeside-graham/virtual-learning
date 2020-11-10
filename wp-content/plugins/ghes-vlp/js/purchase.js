$ = jQuery;

$(function () {
    var container = $(".purchase-vll-billing");
    kendo.init(container);
    container.kendoValidator({
        rules: {
            validmask: function (input) {
                console.log(input);
                if (input.is("[data-validmask-msg]") && input.val() != "") {
                    var maskedtextbox = input.data("kendoMaskedTextBox");
                    return maskedtextbox.value().indexOf(maskedtextbox.options.promptChar) === -1;
                }
                return true;
            }
        }
    });
});

$(document).ready(function () {

    $("#select-subscription-vll").submit(function (event) {
        var validator = $("#select-subscription-vll").kendoValidator({
            rules: {
                radio: function (input) {
                    if (input.filter("[type=radio]") && input.attr("required")) {
                        return $("#select-subscription-vll").find("[type=radio][name=" + input.attr("name") + "]").is(":checked");
                    }
                    return true;
                }
            },
            messages: {
                radio: "This is a required field"
            }
        }).getKendoValidator();

        event.preventDefault();

        if (validator.validate()) {
            displayLoading('form');
            createSubscription();
        } else {
            alert("invalid data");
            $(".k-invalid:first").scrollToMe();
            $(".k-invalid:first").focus();
            $(".k-invalid:first").click();
        }
    });


    $(".purchase-vll-billing").submit(function (event) {
        var validator = $(".purchase-vll-billing").kendoValidator().getKendoValidator();
        event.preventDefault();

        if (validator.validate()) {
            displayLoading('form');
            purchaseSubscription();
        } else {
            alert("invalid data");
            $(".k-invalid:first").scrollToMe();
            $(".k-invalid:first").focus();
            $(".k-invalid:first").click();
        }
    });

    var payment = getCookie("payment");
    if (payment == "true") {
        $(".successful-payment").show();
        document.cookie = "payment=false";
    }
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

    $("#current-due").text("Current Due: $" + currentDue);
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



$("input[name='subscription-select'").click(function (e) {

    $monthlyPrice = e.currentTarget.dataset.monthlyPrice;
    $yearlyPrice = e.currentTarget.dataset.yearlyPrice;

    console.log("Monthly Price: " + $monthlyPrice);
    console.log("Yearly Price: " + $yearlyPrice);

    $("#monthly").attr("data-price", $monthlyPrice);
    $("#yearly").attr("data-price", $yearlyPrice);

    $("label[for='monthly']").html('&nbsp;Monthly - $' + $monthlyPrice + ' per month');
    $("label[for='yearly']").html('&nbsp;Yearly - $' + $yearlyPrice + ' per year');

    if ($("input[name='payment-frequency']:checked").length > 0) {
        $price = $("input[name='payment-frequency']:checked").attr("data-price");
        $("#subscription-total-area").html("Total Due Today: $<span id='subscription-total'>" + $price + '</span>');
        console.log("Selected Price: " + $price);
    };

});

$("input[name='payment-frequency']").click(function (e) {
    $price = e.currentTarget.dataset.price;
    console.log("Selected Price: " + $price);
    $("#subscription-total-area").html("Total Due Today: $<span id='subscription-total'>" + $price + '</span>');
});


function createSubscription() {
    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/subscription",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        timeout: 60000,
        data: {
            ParentID: $("#parent-id").text(),
            StartDate: $("#sub-start-date").text(),
            EndDate: $("#sub-end-date").text(),
            PaymentFrequency: $("input[name='payment-frequency']:checked").val(),
            SubscriptionDefinition_id: $("input[name='subscription-select']:checked").val()
        },
        success: function (result) {
            console.log("Success:" + result);
            //Success!
            window.location.replace(manageSubscriptionPage);
        },
        error: function (result) {
            hideLoading('form');
            console.log("Failed");

            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.message);
            } else {
                alert(
                    "An unexpected error occured.  Please review your submission and try again."
                );
            }
            console.log(result.responseText);
        }
    })
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
            document.cookie = "payment=true";
            location.reload();
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
        cancelContent = "/wp-content/plugins/ghes-vlp/views/admin/templates/monthly-cancel-message.html";
    } else if (selectedSubscriptionType == "yearly") {
        cancelContent = "/wp-content/plugins/ghes-vlp/views/admin/templates/yearly-cancel-message.html";
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
    showLoading('.k-window');
    cancelSubscription(selectedSubscriptionId);
}
function cancelSubscription() {
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
            console.log("Success:" + result);
            hideLoading('.k-window');
            $("#cancel-subscription").data("kendoWindow").close();
            location.reload();
            //Success!
        },
        error: function (result) {
            console.log("Failed to Cancel Subscription");
            $("#cancel-subscription").data("kendoWindow").close();
            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.message);
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