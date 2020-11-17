$ = jQuery;

var selectvalidator = $("#select-subscription-vll").kendoValidator({
    rules: {
        radio: function (input) {
            if (input.filter("[type=radio]") && input.attr("required")) {
                return $("#select-subscription-vll").find("[type=radio][name=" + input.attr("name") + "]").is(":checked");
            }
            return true;
        }
    }
}).data("kendoValidator");

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

    if ($("#monthly").is(':checked')) {
        $(".recurring-billing").show();
    } else {
        $(".recurring-billing").hide();
        $("#recurring").prop("checked", false);
    }
});

$("#continue-payment-vlp").on("click", function () {
    if (selectvalidator.validate()) {
        // If the form is valid, the Validator will return true
        createSubscription();
    }
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
            SubscriptionDefinition_id: $("input[name='subscription-select']:checked").val(),
            RecurringBilling: $("#recurring").prop("checked")
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