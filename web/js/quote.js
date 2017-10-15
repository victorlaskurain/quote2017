require([
    "jquery",
    "appStrings",
    "api",
    "locale",
    "jsgrid",
    "jsgrid-es",
    "lib/jquery.serializejson"
],
function($, strings, api, locale){

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
    $("#quote-edit-cancel").click(function() {
        cancelQuoteEdit();
        return false;
    });
    $("#quote-edit-save").click(function() {
        saveQuote();
        return false;
    });
    $(".new-quote").click(newQuote);
    $("#quote-list-page").hide();
    $("#quote-edit-page").hide();
    api.getCustomers().then(function(customers) {
        function toOption(c) {
            // :TODO: escape id and name correctly
            return '<option value="' + c.id + '">' + c.name + '</option>';
        }
        var customerOptions = customers.data.map(toOption).join("");
        $("#quote_customer_id").html(customerOptions);
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
        var key;
        for (key in quote) {
            $("#quote_" + key).val(quote[key]);
        }
        $("#quote-list-page").hide();
        $("#quote-edit-page").show();
    });
}

function newQuote() {
    var quote = $('#quote-edit-form').serializeJSON(), key;
    for (key in quote) {
        $("#quote_" + key).val(null);
    }
    $("#quote-list-page").hide();
    $("#quote-edit-page").show();
}

init();

});
