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

var customerController = {
    loadData: function(filter) {
        return api.getCustomers(filter);
    }
}, loaded;

function init() {
    if (locale.locale != "en") {
        jsGrid.locale(locale.locale);
    }
    $("#customer-list-page").on("click", ".new-customer", function() {
        $("#customer-list-page").trigger("customerList:newCustomer");
        return false;
    });
    loaded = api.getCustomers().then(function(customers) {
        loadCustomerGrid([{id: "", name: ""}].concat(customers.data));
    });
}

function loadCustomerGrid(customers) {
    $("#customer-list").jsGrid({
        width: "100%",
        height: "100%",

        sorting: true,
        paging: true,
        pageLoading: true,
        filtering: true,

        controller: customerController,
        autoload: false,

        rowClick: function(obj) {
            $("#customer-list-page").trigger("customerList:selectCustomer", obj.item);;

        },

        fields: [
            {
                title: strings.customer_number,
                name: "id",
                type: "number",
                width: 50
            },
            {
                title: strings.customer_name,
                name: "name",
                type: "text",
                width: 150
            }
        ]
    });
};

function showCustomerList() {
    return loaded.then(function() {
        location.hash = "";
        $(".app-page").addClass("hide");
        $("#customer-list-page").removeClass("hide");
        return $("#customer-list").jsGrid("loadData");
    });
}

init();

return {
    events: ["customerList:selectCustomer", "customerList:newCustomer"],
    show: showCustomerList
};

});
