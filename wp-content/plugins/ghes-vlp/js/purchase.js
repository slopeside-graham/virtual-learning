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
});

window.onload = function () {
    calculateTotal();
};

$(".subscription-payment").click(function () {
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
        $("#subscription-total-area").html("Total: $<span id='subscription-total'>" + $price + '</span>');
        console.log("Selected Price: " + $price);
    };

});

$("input[name='payment-frequency']").click(function (e) {
    $price = e.currentTarget.dataset.price;
    console.log("Selected Price: " + $price);
    $("#subscription-total-area").html("Total: $<span id='subscription-total'>" + $price + '</span>');
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
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                conversionValue: $("#subscription-total").val(),
                event: "new_vll_subscription"
            });
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

    // Collect the total

    //Charge payment - payment_rest.php
    createPayment(selectedPayments);

    // If successful payment, do the following:
    // Collect the id's of checked items



    // Run a payment status update on each of the id's.

}

function updatePaymentStatus(x) {
    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/subscriptionpayment",
        method: "PUT",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        timeout: 60000,
        data: {
            id: x,
            Status: "Paid"
        },
        success: function (result) {
            console.log("Success:" + result);
            //Success!
            window.dataLayer = window.dataLayer || [];
            hideLoading('.purchase-vll-billing');
            location.reload();
        },
        error: function (result) {
            console.log("Failed");

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

function createPayment() {
    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/payment",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        timeout: 60000,
        data: {
            Amount: calculateTotal(),
            CardNumber: $("#vlp-bill-CardNumber").val().replace(/\s/g,''),
            ExpirationDate: $("#vlp-bill-ExpirationYear").val()+"-"+$("#vlp-bill-ExpirationMonth").val(),
            CardCode: $("#vlp-bill-CardCode").val(),
            FirstName: $("#vlp-bill-FirstName").val(),
            LastName: $("#vlp-bill-LastName").val(),
            Address: $("#vlp-bill-Address").val(),
            City: $("#vlp-bill-City").val(),
            State: $("#vlp-bill-State").val(),
            Zip: $("#vlp-bill-Zip").val(),
            PhoneNumber: $("#vlp-bill-PhoneNumber").val(),
            Email: $("#vlp-bill-Email").val()
        },
        success: function (result) {
            console.log("Success:" + result);
            //Success!
            window.dataLayer = window.dataLayer || [];
            hideLoading('.purchase-vll-billing');
            var x;
            for (x of selectedPayments) {
                updatePaymentStatus(x);
            }
        },
        error: function (result) {
            console.log("Failed");

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