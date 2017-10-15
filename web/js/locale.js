define([
    "currentlocale"
],
function(currentlocale){

function formatNumber(v, decimals) {
    return Number(v).toLocaleString(
        currentlocale,
        {
            minimumFractionDigits: decimals
        });
}

return {
    locale: currentlocale,
    formatNumber: formatNumber
};

});
