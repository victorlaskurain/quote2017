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
    "jquery",
    "baseurl"
],
function($, baseurl) {

function getCustomers(jsGridFilter) {
    return $.getJSON(baseurl + "/api/customers", jsGridFilter || {});
}

function getCustomerById(id) {
    return $.getJSON(baseurl + "/api/customers/" + id);
}

function getQuoteById(id) {
    return $.getJSON(baseurl + "/api/quotes/" + id);
}

function getQuotes(jsGridFilter) {
    return $.getJSON(baseurl + "/api/quotes", jsGridFilter || {});
}

function saveCustomer(id, customer) {
    return $.post({
        url: baseurl + "/api/customers/" + id,
        data: JSON.stringify(customer)
    });
}

function saveQuote(id, quote) {
    return $.post({
        url: baseurl + "/api/quotes/" + id,
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
