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

var customerForm = $("#customer-edit-form");

function cancelCustomerEdit() {
    customerForm.trigger("customerEdit:done", [{saved: false}]);
}

function editCustomer(customer) {
    if (customer) {
        return showCustomer(customer).then(function() {
            location.hash = JSURL.stringify({page: "edit", id: customer.id});
        });
    } else {
        return newCustomer().then(function() {
            location.hash = JSURL.stringify({page: "edit"});
        });
    }
}

function init() {
    $("#customer-edit-cancel").click(function() {
        cancelCustomerEdit();
        return false;
    });
    $("#customer-edit-save").click(function() {
        saveCustomer();
        return false;
    });
}

function saveCustomer() {
    var customer = $('#customer-edit-form').serializeJSON();
    if (customer.id == "") {
        customer.id = "new";
    }
    return api.saveCustomer(customer.id, customer)
        .then(function() {
            customerForm.trigger("customerEdit:done", [{saved: true}]);
        });
}

function showCustomer(customer) {
    return api.getCustomerById(customer.id).then(function(customer) {
        var key, $element;
        for (key in customer) {
            $("#customer_"  + key).val(customer[key]);
            $("select#customer_" + key).combobox("refresh");
        }
        $(".app-page").addClass("hide");
        $("#customer-edit-page").removeClass("hide");
    });
}

function newCustomer() {
    var customer = $('#customer-edit-form').serializeJSON(), key;
    for (key in customer) {
        $("#customer_" + key).val(null);
        $("select#customer_" + key).val(null).combobox("refresh");
    }
    $(".app-page").addClass("hide");
    $("#customer-edit-page").removeClass("hide");
    return $.when();
}

init();

return {
    edit: editCustomer,
    events: ["customerEdit:done"]
};

});
