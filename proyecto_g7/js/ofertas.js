let ofertaDuplicada = false;
let validando = false;

$(".cantidad-prod").on("change", function () {

    const campo = $(this);

    let productos = {};

    $(".cantidad-prod").each(function () {
        const cantidad = parseInt($(this).val()) || 0;
        const idMatch = $(this).attr("name").match(/\d+/);

        if (idMatch && cantidad > 0) {
            productos[idMatch[0]] = cantidad;
        }
    });

    // Si no hay productos → limpiar
    if (Object.keys(productos).length === 0) {
        $("#ofertaDuplicada").text("");
        campo[0].setCustomValidity("");
        ofertaDuplicada = false;
        return;
    }

    validando = true;

    $.post("/proyecto_g7/comprobarOferta.php", { productos: productos }, function (data) {

        if (data.trim() === "existe") {

            $("#ofertaDuplicada").text("❌ Ya existe una oferta con estos productos");

            $(".cantidad-prod").each(function () {
                this.setCustomValidity("Ya existe una oferta con estos productos");
            });

            ofertaDuplicada = true;

        } else {

            $("#ofertaDuplicada").text("");

            $(".cantidad-prod").each(function () {
                this.setCustomValidity("");
            });

            ofertaDuplicada = false;
        }

        validando = false;
    });
});
