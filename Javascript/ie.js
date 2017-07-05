function mover_capas() {
    if (document.getElementById('capas').title == "inicio") {
        $('#capas').animate({
            right: "0px"
        }, 500);
        document.getElementById('capas').title = "segundo";
    } else {
        $('#capas').animate({
            right: "-277px"
        }, 500);
        document.getElementById('capas').title = "inicio";
    }
}
function mover_tools() {
    if (document.getElementById('tools').title == "inicio") {
        $('#tools').animate({
            right: "0px"
        }, 500);
        document.getElementById('tools').title = "segundo";
    } else {
        $('#tools').animate({
            right: "-201px"
        }, 500);
        document.getElementById('tools').title = "inicio";
    }
}
$(function () {
    $('#menu').Fisheye(
            {
                maxWidth: 40,
                items: 'a',
                itemsText: 'span',
                container: '.dock-container',
                itemWidth: 101,
                proximity: 90,
                alignment: 'left',
                valign: 'bottom',
                halign: 'center'
            }
    );
});