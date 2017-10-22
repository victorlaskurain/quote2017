define([
    "jquery",
    "api",
    "locale",
    "template",
    "bootstrap-combobox",
    "lib/jquery.serializejson",
    "lib/jsurl"
],
function($, api, locale, template){

var quoteForm = $("#quote-edit-form");

function cancelQuoteEdit() {
    quoteForm.trigger("quoteEdit:done", [{saved: false}]);
}

function editQuote(quote) {
    if (quote) {
        return showQuote(quote).then(function() {
            location.hash = JSURL.stringify({page: "edit", id: quote.id});
        });
    } else {
        return newQuote().then(function() {
            location.hash = JSURL.stringify({page: "edit"});
        });
    }
}

function init() {
    $("#add-generic").data("tr-id", -1);
    $("#add-generic").click(function() {
        var trId = $("#add-generic").data("tr-id");
        $("#add-generic").data("tr-id", trId - 1);
        template.render(
            {
                amount: 0,
                id: trId
            },
            $("#generic-concept-tpl").html(),
            $("#quote-generic-concept-footer"),
            "before");
        return false;
    });
    quoteForm.on("click", ".remove-generic", function() {
        var trId = $(this).attr("data-tr-id");
        $("#" + trId).remove();;
        updateTotal();
        return false;
    });
    $("#quote-edit-cancel").click(function() {
        cancelQuoteEdit();
        return false;
    });
    $("#quote-edit-save").click(function() {
        saveQuote();
        return false;
    });
    // $(".show-quote-list").click(showQuoteList);// :TODO:
    $("#quote_price,#quote_weight").change(updateUnitPrice);
    quoteForm.on("change", ".add-for-total", updateTotal);
    api.getCustomers().then(function(customers) {
        function toOption(c) {
            // :TODO: escape id and name correctly
            return '<option value="' + c.id + '">' + c.name + '</option>';
        }
        var customerOptions = customers.data.map(toOption).join("");
        $("#quote_customer_id").html(customerOptions);
        $("#quote_customer_id").combobox("refresh");
    });
}

function saveQuote() {
    var quote = $('#quote-edit-form').serializeJSON();
    if (quote.id == "") {
        quote.id = "new";
    }
    return api.saveQuote(quote.id, quote)
        .then(function() {
            quoteForm.trigger("quoteEdit:done", [{saved: true}]);
        });
}

function showQuote(quote) {
    return api.getQuoteById(quote.id).then(function(quote) {
        var key, $element;
        for (key in quote) {
            $("#quote_"  + key).val(quote[key]);
            $("select#quote_" + key).combobox("refresh");
        }
        $("tr.generic-concept").remove();
        template.render(
            quote.generic_concepts,
            $("#generic-concept-tpl").html(),
            $("#quote-generic-concept-footer"),
            "before");
        updateUnitPrice();
        updateTotal();
        $(".app-page").addClass("hide");
        $("#quote-edit-page").removeClass("hide");
    });
}

function newQuote() {
    console.log("newQuote");
    var quote = $('#quote-edit-form').serializeJSON(), key;
    $("tr.generic-concept").remove();
    for (key in quote) {
        $("#quote_" + key).val(null);
        $("select#quote_" + key).val(null).combobox("refresh");
    }
    updateUnitPrice();
    updateTotal();
    $(".app-page").addClass("hide");
    $("#quote-edit-page").removeClass("hide");
    return $.when();
}

function updateTotal() {
    var total = $("#quote-edit-form input.add-for-total").map(function(i, e){
        return Number($(e).val());})
        .toArray()
        .reduce(function(a, b){
            return a + b;});
    $("#quote_total").val(total);
}
window.updateTotal = updateTotal;

function updateUnitPrice() {
    $("#quote_unit_price").val(
        Number($("#quote_price").val()) *
        Number($("#quote_weight").val())
    ).change();
}

init();

return {
    edit: editQuote,
    events: ["quoteEdit:done"]
};

});
