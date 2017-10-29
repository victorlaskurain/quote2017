define([
    "lib/moment-with-locales",
    "currentlocale"
],
function(moment, currentlocale) {
    moment.locale(currentlocale);
    return moment;
}
);
