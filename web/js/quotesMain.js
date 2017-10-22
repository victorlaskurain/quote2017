require([
    "jquery",
    "appStrings",
    "quoteList",
    "quoteEdit",
    "bootstrap-combobox",
    "lib/jsurl"
],
function(
    $,
    strings,
    quoteList,
    quoteEdit) {

function init() {
    var hash = JSURL.parse(location.hash.substr(1));
    $(".bootstrap-combobox").combobox();
    $(document).on("click", ".new-quote"      , quoteEdit.edit.bind(quoteEdit, null));
    $(document).on("click", ".show-quote-list", quoteList.show.bind(quoteList));
    $(document).on("quoteList:selectQuote", function(evt, quote) {
        quoteEdit.edit(quote);
    });
    $(document).on("quoteList:newQuote", function(evt) {
        quoteEdit.edit();
    });
    $(document).on("quoteEdit:done", function(evt) {
        quoteList.show();
    });

    if (hash.page == "edit") {
        if (hash.id) {
            quoteEdit.edit(hash);
        } else {
            quoteEdit.edit();
        }
    } else {
        quoteList.show();
    }

}

init();

});
