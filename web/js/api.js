define([
    "jquery"
],
function($) {

function getCustomers(jsGridFilter) {
    return $.getJSON("/api/customers", jsGridFilter || {});
}

function getQuoteById(id) {
    return $.getJSON("/api/quotes/" + id);
}

function getQuotes(jsGridFilter) {
    return $.getJSON("/api/quotes", jsGridFilter || {});
}

function saveQuote(id, quote) {
    return $.post({
        url: "/api/quotes/" + id,
        data: JSON.stringify(quote)
    });
}

return {
    getCustomers: getCustomers,
    getQuoteById: getQuoteById,
    getQuotes: getQuotes,
    saveQuote: saveQuote
};

});
