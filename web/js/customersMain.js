require([
    "jquery",
    "appStrings",
    "customerList",
    "customerEdit",
    "bootstrap-combobox",
    "lib/jsurl"
],
function(
    $,
    strings,
    customerList,
    customerEdit) {

function init() {
    var hash = JSURL.parse(location.hash.substr(1));
    $(".bootstrap-combobox").combobox();
    $(document).on("click", ".new-customer"      , customerEdit.edit.bind(customerEdit, null));
    $(document).on("click", ".show-customer-list", customerList.show.bind(customerList));
    $(document).on("customerList:selectCustomer", function(evt, customer) {
        console.log(["kaixo 10", customer]);
        customerEdit.edit(customer);
    });
    $(document).on("customerList:newCustomer", function(evt) {
        console.log("kaixo 20");
        customerEdit.edit();
    });
    $(document).on("customerEdit:done", function(evt) {
        customerList.show();
    });

    if (hash.page == "edit") {
        if (hash.id) {
            customerEdit.edit(hash);
        } else {
            customerEdit.edit();
        }
    } else {
        customerList.show();
    }

}

init();

});
