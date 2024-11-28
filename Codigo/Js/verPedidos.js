document.addEventListener("DOMContentLoaded", function () {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario desde el localstorage
    const usuarioId = usuarioSesion.id_usuario; // obtengo el id del usuario

     // si el usuario no tiene un id válido muestro un mensaje y detengo la ejecución
    if (!usuarioId) {
        alert("Usuario no identificado. Por favor, inicie sesión."); // muestro un mensaje de error
        return;
    }

    const apiUrl = "http://localhost/ProyectoKebab/codigo/index.php?route=pedido"; // ruta de la api de pedidos
    const tablaBody = document.getElementById("tabla-pedidos-body"); // busco el contenedor de pedidos
    const filtroTiempo = document.getElementById("filtro-tiempo"); // busco el filtro de tiempo
    const botonFiltro = document.querySelector(".boton-aplicar-filtro"); // 
    let pedidos = []; // Variable global para almacenar los pedidos

    // funcion para cargar los pedidos
    const cargarPedidos = async () => {
        try { // intento realizar la peticion ajax
            const response = await fetch(apiUrl, {  // hago la peticion ajax para obtener los pedidos
                method: "POST", // uso el metodo POST
                headers: {  // le digo que lo que voy a enviar en el body es json
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id_usuario_pedido: usuarioId }), // envio el id del usuario a pedidos
            });

            if (!response.ok) { 
                throw new Error("Error al cargar los pedidos.");
            }

            pedidos = await response.json(); // guardo los pedidos en la variable global
            mostrarPedidos(pedidos);
        } catch (error) {
            console.error(error);
        }
    };

    // funcion para mostrar los pedidos en la tabla
    const mostrarPedidos = (pedidos) => {
        tablaBody.innerHTML = ""; // limpio tabla

        pedidos.forEach((pedido) => { // recorro el array de pedidos
            const tr = document.createElement("tr"); // creo un div para cada fila
            tr.innerHTML = ` 
                <td>${pedido.id_pedido}</td>
                <td>${pedido.estado}</td>
                <td>${pedido.precio_total}</td>
                <td>${pedido.fecha_hora}</td>
                <td>
                    ${
                        pedido.estado === "Pendiente"
                            ? `<button class="boton-cancelar" data-id="${pedido.id_pedido}">Cancelar</button>`
                            : ""
                    }
                </td>
            `;

            tablaBody.appendChild(tr);  // lo agrego al contenedor
        });

        // agrego eventos a los botones de cancelar
        document.querySelectorAll(".boton-cancelar").forEach((boton) => { // recorro los botones de cancelar
            boton.addEventListener("click", async (event) => {  // configura el evento al boton de cancelar
                const pedidoId = event.target.getAttribute("data-id"); // obtengo el id del pedido
                cancelarPedido(pedidoId); // llamo a la funcion para cancelar el pedido
            });
        });
    };

    // funcion para cancelar un pedido
    const cancelarPedido = async (pedidoId) => { // funcion para cancelar un pedido
        try { // intento realizar la peticion ajax
            const response = await fetch(apiUrl, {  // hago la peticion ajax para obtener los pedidos
                method: "DELETE", // uso el metodo DELETE
                headers: {  // le digo que lo que voy a enviar en el body es json
                    "Content-Type": "application/json",  
                },
                body: JSON.stringify({ id_pedido: pedidoId }), // envio el id del pedido a cancelar
            });

            if (!response.ok) { // si el servidor respondio con un error
                throw new Error("Error al cancelar el pedido."); // lanzo un error
            }

            alert("Pedido cancelado exitosamente."); // muestro un mensaje de confirmacion
            cargarPedidos(); // recargo los pedidos
        } catch (error) {
            console.error(error);
        }
    };

    // funcion para filtrar los pedidos según el rango de tiempo
    const filtrarPedidos = (criterio) => { 
        const ahora = new Date(); // obtengo la fecha actual
        let fechaInicio; // variable para almacenar la fecha inicial

        switch (criterio) { // cambio el valor de la fecha según el criterio
            case "semana": // si es la semana
                fechaInicio = new Date(ahora); // creo una nueva fecha con la fecha actual
                fechaInicio.setDate(ahora.getDate() - 7); // cambio el día de la fecha para que sea el día de la semana
                break; 
            case "mes": // si es el mes
                fechaInicio = new Date(ahora.getFullYear(), ahora.getMonth() - 1, ahora.getDate()); // creo una nueva fecha con la fecha actual
                break;
            case "ano": // si es el año
                fechaInicio = new Date(ahora.getFullYear() - 1, ahora.getMonth(), ahora.getDate()); // creo una nueva fecha con la fecha actual
                break;
            case "todos":
            default:
                mostrarPedidos(pedidos); // muestro los pedidos
                return;
        }

        // filtro los pedidos
        const pedidosFiltrados = pedidos.filter((pedido) => { // recorro el array de pedidos
            const fechaPedido = new Date(pedido.fecha_hora); // obtengo la fecha del pedido
            return fechaPedido >= fechaInicio; // si es mayor o igual a la fecha inicial, lo agrego a la lista
        });

        mostrarPedidos(pedidosFiltrados); // muestro los pedidos
    };

    // event listener para el boton de aplicar filtro
    botonFiltro.addEventListener("click", () => {
        const criterio = filtroTiempo.value;
        filtrarPedidos(criterio);
    });

    // cargo los pedidos al inicio
    cargarPedidos();
});
