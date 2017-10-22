define([
    "jquery",
    "appStrings",
    "api",
    "locale",
    "template",
    "jsgrid",
    "jsgrid-es",
    "bootstrap-combobox",
    "lib/jquery.serializejson",
    "lib/jsurl"
],
function($, strings, api, locale, template){

var quoteController = {
    loadData: function(filter) {
        return api.getQuotes(filter);
    }
}, loaded;

function init() {
    if (locale.locale != "en") {
        jsGrid.locale(locale.locale);
    }
    $("#quote-list-page").on("click", ".new-quote", function() {
        $("#quote-list-page").trigger("quoteList:newQuote");
        return false;
    });
    loaded = api.getCustomers().then(function(customers) {
        loadQuoteGrid([{id: "", name: ""}].concat(customers.data));
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
        autoload: false,

        rowClick: function(obj) {
            console.log(["rowClick", obj.item, obj.itemIndex, obj.event]);
            $("#quote-list-page").trigger("quoteList:selectQuote", obj.item);;

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
                title: strings.quote_date,
                name: "date",
                type: "text",
                filtering: false,
                width: 50
            },
            {
                title: strings.quote_description,
                name: "description",
                type: "text",
                width: 150
            },
            {
                title: strings.total,
                name: "total",
                itemTemplate: function(value) {
                    return locale.formatNumber(value, 3);
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
                    });
            }, 250);
        });
    }());
};

function showQuoteList() {
    return loaded.then(function() {
        console.log("showQuoteList");
        location.hash = "";
        $(".app-page").addClass("hide");
        $("#quote-list-page").removeClass("hide");
        return $("#quote-list").jsGrid("loadData");
    });
}

init();

return {
    events: ["quoteList:selectQuote", "quoteList:newQuote"],
    show: showQuoteList
};

});
