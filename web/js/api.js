/**
 * AMD module defining the REST client API for the service implemented
 * in ApiController.php.
 */

/**
 * To create an AMD module call the define function. It takes two
 * parameters, the first one is the list of dependencies of the module
 * (a list of strings), the second one is the definition of the module
 * which should be a function literal. The return value of the module
 * is the public part of the module, e.g., what you get when you
 * require the module.
 */
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
