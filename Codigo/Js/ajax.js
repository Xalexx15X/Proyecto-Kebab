window.addEventListener('load', function() {
   var form = document.forms[0];
   var fichero = form.elements["fichero"];
   form.elements["enviar"].addEventListener("click", function() {
    var datos = new FormData(form);
    datos.append("nombre", "kebab de la casa");
    datos.append("ingredientes", "12,234,245,14");

    var solicitud = new Request("info.php",{
        method: "POST",
        body: datos
    });
        fetch(solicitud)
        .then(respuesta=>respuesta.text()).then()
        .then(texto=>{document.getElementById("resultado").innerHTML = texto;});
    });
    })

    //si solo quiero quiero recoger datos no hace falta el form data se pone get directo en el fetch
    // si queremos solicitud y respuesta necesitamos el form data 