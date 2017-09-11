
var controlConfig = 1, divAnterior = "creditos";

function confirmaCreditoMapa(url) {
    if (confirm("Visualizar el Mapa equivale a 1 Cr\u00E9dito, Desea consumir Cr\u00E9ditos?"))
        document.location.href = '?pagina=map';
    else
        document.location.href = '?pagina=account';
}
                