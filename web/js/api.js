define([
    "jquery"
],
function($) {

function getCustomers(jsGridFilter) {
    return $.getJSON("/api/customers", jsGridFilter || {});
}

function getCustomerById(id) {
    return $.getJSON("/api/customers/" + id);
}

function getQuoteById(id) {
    return $.getJSON("/api/quotes/" + id);
}

function getQuotes(jsGridFilter) {
    return $.getJSON("/api/quotes", jsGridFilter || {});
}

function saveCustomer(id, customer) {
    return $.post({
        url: "/api/customers/" + id,
        data: JSON.stringify(customer)
    });
}

function saveQuote(id, quote) {
    return $.post({
        url: "/api/quotes/" + id,
        data: JSON.stringify(quote)
    });
}

return {
    getCustomerById: getCustomerById,
    getCustomers: getCustomers,
    getQuoteById: getQuoteById,
    getQuotes: getQuotes,
    saveCustomer: saveCustomer,
    saveQuote: saveQuote
};

});
