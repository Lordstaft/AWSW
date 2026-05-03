$("#numeroTarjeta").change(function(){

    const campo = $("#numeroTarjeta");
    const valor = campo.val().trim();

    if (valor === "") {
        $("#formatoTarjeta").text("");
        campo[0].setCustomValidity("");
        return;
    }

    campo[0].setCustomValidity("");

    const esTarjetaValida = campo[0].checkValidity();

    if (esTarjetaValida && tarjetaValida(valor)) {
        $("#formatoTarjeta").text("");
        campo[0].setCustomValidity("");
    } else {
        $("#formatoTarjeta").text("❌");
        campo[0].setCustomValidity("El número de tarjeta debe tener 16 dígitos");
    }
});

function tarjetaValida(numero) {
    const regex = /^\d{16}$/;
    return regex.test(numero);
}

$("#cvv").change(function(){

    const campo = $("#cvv");
    const valor = campo.val().trim();

    if (valor === "") {
        $("#formatoCvv").text("");
        campo[0].setCustomValidity("");
        return;
    }

    campo[0].setCustomValidity("");

    const esCvvValido = campo[0].checkValidity();

    if (esCvvValido && cvvValido(valor)) {
        $("#formatoCvv").text("");
        campo[0].setCustomValidity("");
    } else {
        $("#formatoCvv").text("❌");
        campo[0].setCustomValidity("El CVV debe tener 3 dígitos");
    }
});

function cvvValido(numero) {
    const regex = /^\d{3}$/;
    return regex.test(numero);
}   