$(document).ready(function () {

    let ofertaDuplicada = false;
    let validando = false;
    let peticionOferta = null;
    let peticionId = 0;
    let ultimaPeticionId = 0;

    if ($(".cantidad-prod").length === 0) {
        return;
    }

    function recogeProductos() {
        const productos = {};

        $(".cantidad-prod").each(function () {
            const campo = $(this);
            const cantidad = parseInt(campo.val(), 10) || 0;
            const nombre = campo.attr("name") || "";
            const match = nombre.match(/^cantidades\[(\d+)\]$/);

            if (match && cantidad > 0) {
                productos[match[1]] = cantidad;
            }
        });

        return productos;
    }

    function limpiaErrores() {
        $("#ofertaDuplicada").text("");

        $(".cantidad-prod").each(function () {
            this.setCustomValidity("");
        });
    }

    function marcaOfertaDuplicada() {
        $("#ofertaDuplicada").text("❌ Ya existe una oferta con estos productos");

        $(".cantidad-prod").each(function () {
            this.setCustomValidity("Ya existe una oferta con estos productos");
        });
    }

    function validaOferta() {
        const url = window.urlOferta;
        if (!url) {
            console.error("window.urlOferta no está definido");
            return;
        }

        const productos = recogeProductos();

        if (Object.keys(productos).length === 0) {
            ofertaDuplicada = false;
            limpiaErrores();
            return;
        }

        validando = true;
        peticionId += 1;
        const idActual = peticionId;
        ultimaPeticionId = idActual;

        if (peticionOferta && peticionOferta.readyState !== 4) {
            peticionOferta.abort();
        }

        const data = { productos: productos };
        if (typeof window.ofertaId !== 'undefined') {
            data.ofertaId = window.ofertaId;
        }

        peticionOferta = $.post(url, data, function (data) {
            if (idActual !== ultimaPeticionId) {
                return;
            }

            if (data.trim() === "existe") {
                ofertaDuplicada = true;
                marcaOfertaDuplicada();
            } else {
                ofertaDuplicada = false;
                limpiaErrores();
            }
        }).fail(function () {
            if (idActual !== ultimaPeticionId) {
                return;
            }

            ofertaDuplicada = false;
            limpiaErrores();
        }).always(function () {
            if (idActual !== ultimaPeticionId) {
                return;
            }

            validando = false;
        });
    }

    $(".cantidad-prod").on("input", function () {
        validaOferta();
    });
    validaOferta();

    $("form").on("submit", function (e) {
        if (validando) {
            e.preventDefault();
            alert("Esperando validación de la oferta...");
            return;
        }

        if (ofertaDuplicada) {
            e.preventDefault();
            alert("Ya existe una oferta con esos productos");
            return;
        }
    });

});