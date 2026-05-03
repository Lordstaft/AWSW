$("#email").on("input", function() {

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
            "El correo no tiene un formato válido"
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
    }).fail(function() {
        $("#emailCorrecto").text("❌ Error al validar el email");
        campo[0].setCustomValidity("No se pudo comprobar el email");
    });
});

function esCorreoValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/i;
    return regex.test(email);
}

$(".validar-usuario").on("input", function(){

    const campo = $(this);
    const usuario = campo.val().trim();

    campo[0].setCustomValidity("");
    $("#usuarioCorrecto").text("");

    if (usuario === "") return;

    const url = "/proyecto_g7/comprobarUsuario.php?user=" + encodeURIComponent(usuario);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#usuarioCorrecto").text("❌ No disponible");
            campo[0].setCustomValidity("El usuario ya está en uso");
        } else {
            $("#usuarioCorrecto").text("");
            campo[0].setCustomValidity("");
        }
    }).fail(function() {
        $("#usuarioCorrecto").text("❌ Error al validar el usuario");
        campo[0].setCustomValidity("No se pudo comprobar el usuario");
    });
});

$(".validar-usuario-admin").on("input", function(){

    const campo = $(this);
    const usuario = campo.val().trim();
    const id = $("#id").val();

    campo[0].setCustomValidity("");
    $("#usuarioCorrecto").text("");

    if (usuario === "") return;

    const url = "/proyecto_g7/comprobarUsuarioAdmin.php?user=" 
        + encodeURIComponent(usuario) 
        + "&id=" + encodeURIComponent(id);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#usuarioCorrecto").text("❌ No disponible");
            campo[0].setCustomValidity("El usuario ya está en uso");
        } else {
            $("#usuarioCorrecto").text("");
            campo[0].setCustomValidity("");
        }
    }).fail(function() {
        $("#usuarioCorrecto").text("❌ Error al validar el usuario");
        campo[0].setCustomValidity("No se pudo comprobar el usuario");
    });
});