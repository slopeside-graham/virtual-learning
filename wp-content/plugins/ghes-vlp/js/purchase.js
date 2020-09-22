$ = jQuery;


$(document).ready(function () {
    var validator = $("#purchase-vll").kendoValidator({
        rules: {
            radio: function (input) {
                if (input.filter("[type=radio]") && input.attr("required")) {
                    return $("#purchase-vll").find("[type=radio][name=" + input.attr("name") + "]").is(":checked");
                }
                return true;
            }
        },
        messages: {
            radio: "This is a required field"
        }
    }).getKendoValidator();

    var validationSummary = $("#validation-summary");

    $("form").submit(function (event) {
        event.preventDefault();

        if (validator.validate()) {
            validationSummary.html("<div class='k-messagebox k-messagebox-success'>Form is Valid</div>");
            kendo.ui.progress($(".loading-window"), true);
            $(".loading-window").show();
            createSubscription();
        } else {
            validationSummary.html("<div class='k-messagebox k-messagebox-error'>Oops! There is invalid data in the form.</div>");
            $(".k-invalid:first").scrollToMe();
            $(".k-invalid:first").focus();
            $(".k-invalid:first").click();
        }
    });
});



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

})

$("input[name='payment-frequency']").click(function (e) {
    $price = e.currentTarget.dataset.price;
    console.log("Selected Price: " + $price);
    $("#subscription-total-area").html("Total: $<span id='subscription-total'>" + $price + '</span>');
})


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
            PaymentStatus: "",
            PaymentFrequency: $("input[name='payment-frequency']:checked").val(),
            SubscriptionDefinition_id: $("input[name='subscription-select']:checked").val(),
            Total: $("#subscription-total").text()
        },
        success: function (result) {
            console.log("Success:" + result);
            //Success!
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                conversionValue: $("#subscription-total").val(),
                event: "new_vll_subscription"
            });
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
            console.log(result.responseText);
        }
    })
}