define([
    "jquery"
],
function($){

function render(objList, tpl, anchor, position) {
    if (!$.isArray(objList)) {
        objList = [objList];
    }
    objList.forEach(function(obj) {
        var txt = tpl.trim(),
            key;
        for (key in obj) {
            txt = txt.replace(new RegExp("\{" + key + "\}", "g"), obj[key]);
        }
        txt = txt.replace(/\{.*?\}/g, "");
        $(anchor)[position](txt);
    });
}

return {
    render: render
};
});
