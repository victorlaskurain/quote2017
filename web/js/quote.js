require([
    "jquery",
    "appStrings",
    "api",
    "locale",
    "template",
    "jsgrid",
    "jsgrid-es",
    "bootstrap-combobox",
    "lib/jquery.serializejson"
],
function($, strings, api, locale, template){

var quoteController = {
    loadData: function(filter) {
        return api.getQuotes(filter);
        // breturn $.getJSON("api/quotes", filter);
    },
    insertItem: function(item) {
        console.log(["insertItem", item]);
    },
    updateItem: function(item) {
        console.log(["updateItem", item]);
    },
    deleteItem: function(item) {
        console.log(["deleteItem", item]);
    }
};

function cancelQuoteEdit() {
    $("#quote-list-page").show();
    $("#quote-edit-page").hide();
}

function init() {
    if (locale.locale != "en") {
        jsGrid.locale(locale.locale);
    }
    $("#quote-list-page").hide();
    $("#quote-edit-page").hide();
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
    $(document).on("click", ".remove-generic", function() {
        var trId = $(this).attr("data-tr-id");
        $("#" + trId).remove();;
        updateTotal();
        return false;
    });
    $(".bootstrap-combobox").combobox();
    $("#quote-edit-cancel").click(function() {
        cancelQuoteEdit();
        return false;
    });
    $("#quote-edit-save").click(function() {
        saveQuote();
        return false;
    });
    $(".new-quote").click(newQuote);
    $(".show-quote-list").click(showQuoteList);
    $("#quote_price,#quote_weight").change(updateUnitPrice);
    $("#quote-edit-form").on("change", ".add-for-total", updateTotal);
    $("#quote-list-page").hide();
    $("#quote-edit-page").hide();
    api.getCustomers().then(function(customers) {
        function toOption(c) {
            // :TODO: escape id and name correctly
            return '<option value="' + c.id + '">' + c.name + '</option>';
        }
        var customerOptions = customers.data.map(toOption).join("");
        $("#quote_customer_id").html(customerOptions);
        $("#quote_customer_id").combobox("refresh");
        loadQuoteGrid([{id: "", name: ""}].concat(customers.data));
        $("#quote-list-page").show();
    });
}

function loadQuoteGrid(customers) {
    $("#quote-list").jsGrid({
        width: "100%",
        height: "100%",

        sorting: true,
        paging: true,
        pageLoading: true,
        filtering: true,

        controller: quoteController,
        autoload: true,

        rowClick: function(obj) {
            console.log(["rowClick", obj.item, obj.itemIndex, obj.event]);
            showQuote(obj.item);
        },

        fields: [
            {
                title: strings.quote_number,
                name: "id",
                type: "number",
                width: 50
            },
            {
                title: strings.customer,
                name: "customer_id",
                type: "select",
                items: customers,
                valueField: "id",
                textField: "name",
                width: 100
            },
            {
                title: strings.quote_description,
                name: "description",
                type: "text",
                width: 200
            },
            {
                title: strings.shipping,
                name: "shipping",
                itemTemplate: function(value) {
                    return locale.formatNumber(value, 2);
                },
                type: "number",
                filtering: false,
                width: 50
            }
        ]
    });
    // initialize combo box filters and add class to render jsGrid
    // filters with bootstrap styles.
    $(".jsgrid-filter-row select").combobox();
    $(".jsgrid-filter-row input").addClass("form-control");
    (function() {
        // resize bootstrap-combobox at window resizing and at the
        // same time keep options visible.
        var resizeTimer,
            beginResize = true;
        $(window).on('resize', function(e) {
            if (beginResize) {
                $('.jsgrid-filter-row td>div.combobox-container').css(
                    'position',
                    'static');
                beginResize = false;
            }
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(function() {
                // Run code here, resizing has "stopped"
                beginResize = true;
                $('.jsgrid-filter-row td>div.combobox-container')
                    .map(function(i, combo) {
                        var $combo = $(combo),
                            pos    = $combo.position(),
                            width  = $combo.width();
                        $combo.css('position', 'absolute');
                        $combo.css('top', pos.top + 'px');
                        $combo.width(width);
                        console.log("kaixo");
                    });
            }, 250);
        });
    }());
};

function saveQuote() {
    var quote = $('#quote-edit-form').serializeJSON();
    if (quote.id == "") {
        quote.id = "new";
    }
    return api.saveQuote(quote.id, quote)
        .then(function() {
            $("#quote-list-page").show();
            $("#quote-edit-page").hide();
            return $("#quote-list").jsGrid("loadData");
        });
}

function showQuote(quote) {
    api.getQuoteById(quote.id).then(function(quote) {
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
        $("#quote-list-page").hide();
        $("#quote-edit-page").show();
    });
}

function showQuoteList() {
    $("#quote-list-page").show();
    $("#quote-edit-page").hide();
    return $("#quote-list").jsGrid("loadData");
}

function newQuote() {
    var quote = $('#quote-edit-form').serializeJSON(), key;
    $("tr.generic-concept").remove();
    for (key in quote) {
        $("#quote_" + key).val(null);
        $("select#quote_" + key).val(null).combobox("refresh");
    }
    updateUnitPrice();
    updateTotal();
    $("#quote-list-page").hide();
    $("#quote-edit-page").show();
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

});
