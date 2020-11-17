$ = jQuery;
var validator 
$(document).ready(function () {
    validator = $("form").kendoValidator({
        rules: {
            radio: function (input) {
                if (input.is(':radio')) {
                    if (input.filter("[type=radio]") && input.attr("required")) {
                        return $("form").find("[type=radio][name=" + input.attr("name") + "]").is(":checked");
                    }
                    return true;
                }
                return true;
            }
        },
        messages: {
            radio: "This is a required field"
        }
    }).data("kendoValidator");

    // Masked Text box rules
    $(".phone-number").kendoMaskedTextBox({
        mask: "(999) 000-0000"
    });

    $(".creditcard-number").kendoMaskedTextBox({
        mask: "0000 0000 0000 0000"
    });
});

function displayLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, true);
}
function hideLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, false);
}

