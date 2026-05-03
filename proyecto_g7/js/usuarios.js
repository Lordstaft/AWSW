$("#email").change(function() {

    const campo = $("#email");
    const email = campo.val().trim();

    // Reset
    $("#formatoEmail").text("");
    $("#emailCorrecto").text("");
    campo[0].setCustomValidity("");

    if (email === "") return;

    // Validar formato
    if (!esCorreoValido(email)) {
        $("#formatoEmail").text("❌ Formato incorrecto");
        campo[0].setCustomValidity(
            "El correo debe tener formato @dominio.com o @dominio.es"
        );
        return;
    }

    $("#formatoEmail").text("✔ Formato correcto");

    const url = "/proyecto_g7/comprobarEmail.php?email=" + encodeURIComponent(email);

    $.get(url, function(data, status) {

        if (status === "success") {

            if (data.trim() === "existe") {
                $("#emailCorrecto").text("❌ No disponible");
                campo[0].setCustomValidity("El email ya está en uso");
            } 
            else {
                $("#emailCorrecto").text("✅ Disponible");
                campo[0].setCustomValidity("");
            }
        }
    });
});

function esCorreoValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.(com|es)$/i;
    return regex.test(email);
}

$("#nombreUsuario").change(function(){

    const usuario = $("#nombreUsuario").val().trim();

    if (usuario === "") {
        $("#usuarioCorrecto").text("");
        $("#nombreUsuario")[0].setCustomValidity("");
        return;
    }

    var url = "/proyecto_g7/comprobarUsuario.php?user=" + encodeURIComponent(usuario);

    $.get(url, usuarioExiste);
});

function usuarioExiste(data, status){

    const campo = $("#nombreUsuario");

    if (status === "success") {

        if (data.trim() === "existe") {
            $("#usuarioCorrecto").text("No disponible");

            campo[0].setCustomValidity("El usuario ya está en uso");

        } 
        else {
            $("#usuarioCorrecto").text("Disponible");

            campo[0].setCustomValidity("");
        }
    }
}