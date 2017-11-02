define([
    "jquery",
    "appStrings",
    "api",
    "locale",
    "template",
    "moment-localized",
    "jsgrid",
    "bootstrap-combobox",
    "bootstrap-datepicker",
    "lib/jquery.serializejson",
    "lib/jsurl"
],
function($, strings, api, locale, template, moment){

var quoteController = {
    loadData: function(filter) {
        var fromDate = getDatepickerIsoDate("#quote-date-min"),
            toDate   = getDatepickerIsoDate("#quote-date-max");
        function localizeDates(quotes) {
            quotes.data = quotes.data.map(function(quote) {
                quote.date = moment(quote.date).format("L");
                return quote;
            });
            return quotes;
        }
        if (fromDate) {
            filter['date'] = ['>=', fromDate];
        }
        if (toDate) {
            if (filter['date']) {
                filter['date'] = filter['date'].concat(['<=', toDate]);
            } else {
                filter['date'] = ['<=', toDate];
            }
        }
        if (filter.accepted !== undefined) {
            filter.accepted = Number(filter.accepted);
        }
        return api.getQuotes(filter).then(localizeDates);
    }
}, loaded;

function getDatepickerIsoDate(dp) {
    var date = $(dp).datepicker("getUTCDate");
    if (date) {
        return date.toISOString().substr(0, 10);
    } else {
        return $(dp).val();
    }

}

function init() {
    if (locale.locale != "en") {
        jsGrid.locale(locale.locale);
    }
    $("#quote-list-page [data-provide=datepicker]").datepicker();
    $("#quote-list-page").on("click", ".new-quote", function() {
        $("#quote-list-page").trigger("quoteList:newQuote");
        return false;
    });
    $("#quote-date-range input").on("blur", function() {
        $("#quote-list").jsGrid("loadData");
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
            $("#quote-list-page").trigger("quoteList:selectQuote", obj.item);;

        },

        fields: [
            {
                title: strings.quote_number,
                name: "number_of_day",
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
                width: 35
            },
            {
                title: strings.accepted,
                name: "accepted",
                type: "checkbox",
                filtering: true,
                width: 30
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
    $("#quote-list").jsGrid("sort", "date", "desc");

    // initialize combo box filters and add class to render jsGrid
    // filters with bootstrap styles.
    $(".jsgrid-filter-row select").combobox();
    $(".jsgrid-filter-row input").addClass("form-control");
    // hack to show jsGrid combo box filter in position
    function setPosAbsolute() {
        var $combo = $(this),
            pos    = $combo.position(),
            width  = $combo.width();
        $combo.css("position", "absolute");
        $combo.css("top", pos.top + "px");
        $combo.width(width);
        $(this).css("position", "absolute");
    }
    function setPosStatic() {
        $(this).css("position", "static");
        $(this).css("width"   , "100%");
    }
    $(document).on(
        "focus",
        ".jsgrid-filter-row td>div.combobox-container",
        setPosAbsolute);
    $(document).on(
        "blur",
        ".jsgrid-filter-row td>div.combobox-container",
        setPosStatic);
    (function() {
        // resize bootstrap-combobox at window resizing and at the
        // same time keep options visible.
        var resizeTimer,
            resizeBegin = true;
        $(window).on('resize', function(e) {
            if (resizeBegin) {
                $('.jsgrid-filter-row td>div.combobox-container').map(
                    function(i, combo) {
                        setPosStatic.bind(combo)();
                    });
                resizeBegin = false;
            }
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(function() {
                // Run code here, resizing has "stopped"
                resizeBegin = true;
            }, 250);
        });
    }());
};

function showQuoteList() {
    return loaded.then(function() {
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
