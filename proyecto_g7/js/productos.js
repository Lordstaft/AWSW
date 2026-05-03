$(".validar-producto").on("input", function(){

    const campo = $(this);
    const producto = campo.val().trim();
    const base = window.RUTA_APP || '';

    campo[0].setCustomValidity("");
    $("#productoCorrecto").text("");

    if (producto === "") return;

    const url = base + "/comprobarProducto.php?producto=" + encodeURIComponent(producto);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#productoCorrecto").text("❌ El producto ya existe");
            campo[0].setCustomValidity("Modificar el producto existente o elegir otro nombre");
        } else {
            $("#productoCorrecto").text("");
            campo[0].setCustomValidity("");
        }
    });
});

$(".validar-categoria").on("input", function(){

    const campo = $(this);
    const categoria = campo.val().trim();
    const base = window.RUTA_APP || '';

    campo[0].setCustomValidity("");
    $("#categoriaCorrecta").text("");

    if (categoria === "") return;

    const url = base + "/comprobarCategoria.php?categoria=" + encodeURIComponent(categoria);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#categoriaCorrecta").text("❌ La categoría ya existe");
            campo[0].setCustomValidity("Modificar la categoría existente o elegir otra");
        } 
        else {
            $("#categoriaCorrecta").text("");
            campo[0].setCustomValidity("");
        }
    });
});

$(".validar-categoria-editar").on("input", function(){

    const campo = $(this);
    const categoria = campo.val().trim();
    const id = $("#idCategoria").length ? $("#idCategoria").val() : "";
    const base = window.RUTA_APP || '';

    campo[0].setCustomValidity("");
    $("#categoriaEditarCorrecta").text("");

    if (categoria === "") return;

    const url = base + "/comprobarCategoriaEditar.php?categoria=" 
        + encodeURIComponent(categoria) 
        + "&id=" + encodeURIComponent(id);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#categoriaEditarCorrecta").text("❌ La categoría ya existe");
            campo[0].setCustomValidity("Modificar la categoría existente o elegir otra");
        } 
        else {
            $("#categoriaEditarCorrecta").text("");
            campo[0].setCustomValidity("");
        }
    });
});

$(".validar-producto-editar").on("input", function(){

    const campo = $(this);
    const producto = campo.val().trim();
    const id = $("#idProducto").length ? $("#idProducto").val() : "";
    const base = window.RUTA_APP || '';

    campo[0].setCustomValidity("");
    $("#productoEditarCorrecto").text("");

    if (producto === "") return;

    const url = base + "/comprobarProductoEditar.php?producto=" 
        + encodeURIComponent(producto) 
        + "&id=" + encodeURIComponent(id);

    $.get(url, function(data){

        if (data.trim() === "existe") {
            $("#productoEditarCorrecto").text("❌ El producto ya existe");
            campo[0].setCustomValidity("Modificar el producto existente o elegir otro nombre");
        } 
        else {
            $("#productoEditarCorrecto").text("");
            campo[0].setCustomValidity("");
        }
    });
});