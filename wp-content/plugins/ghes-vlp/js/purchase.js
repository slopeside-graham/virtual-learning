$ = jQuery;


$(document).ready(function () {
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


    $("#select-subscription-vll").submit(function (event) {
        event.preventDefault();

        if (validator.validate()) {
            displayLoading('form');
            createSubscription();
        } else {
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
    $("#total-due").text("Total Due: $" + totalDue);

    console.log("Total Checked Current Payment: " + currentDue);
    console.log("Total Checked Future Payment: " + futureDue);
    console.log("Total Payment: " + totalDue);
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

function purchaseSubscription() {
    displayLoading('.purchase-vll-billing');
    // Collect the total

    //Charge payment

    // If successful payment, do the following:
    // Collect the id's of checked items

    var idSelector = function () { return this.dataset.id; };
    var selectedPayments = $(".subscription-payment:checked").map(idSelector).get();
    var x;

    // Run a payment status update on each of the id's.
    for (x of selectedPayments) {
        updatePaymentStatus(x);
    }
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

