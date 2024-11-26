document.addEventListener("DOMContentLoaded", function () {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));
    const usuarioId = usuarioSesion.id_usuario;

    if (!usuarioId) {
        alert("Usuario no identificado. Por favor, inicie sesión.");
        return;
    }

    const apiUrl = "http://localhost/ProyectoKebab/codigo/index.php?route=pedido";
    const tablaBody = document.getElementById("tabla-pedidos-body");
    const filtroTiempo = document.getElementById("filtro-tiempo");
    const botonFiltro = document.querySelector(".boton-aplicar-filtro");
    let pedidos = []; // Variable global para almacenar los pedidos

    // Función para cargar los pedidos
    const cargarPedidos = async () => {
        try {
            const response = await fetch(apiUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id_usuario_pedido: usuarioId }),
            });

            if (!response.ok) {
                throw new Error("Error al cargar los pedidos.");
            }

            pedidos = await response.json(); // Guardar pedidos en la variable global
            mostrarPedidos(pedidos);
        } catch (error) {
            console.error(error);
        }
    };

    // Renderizar los pedidos en la tabla
    const mostrarPedidos = (pedidos) => {
        tablaBody.innerHTML = ""; // Limpiar tabla

        pedidos.forEach((pedido) => {
            const tr = document.createElement("tr");
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

            tablaBody.appendChild(tr);
        });

        // Agregar eventos a los botones de cancelar
        document.querySelectorAll(".boton-cancelar").forEach((boton) => {
            boton.addEventListener("click", async (event) => {
                const pedidoId = event.target.getAttribute("data-id");
                cancelarPedido(pedidoId);
            });
        });
    };

    // Función para cancelar un pedido
    const cancelarPedido = async (pedidoId) => {
        try {
            const response = await fetch(apiUrl, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id_pedido: pedidoId }),
            });

            if (!response.ok) {
                throw new Error("Error al cancelar el pedido.");
            }

            alert("Pedido cancelado exitosamente.");
            cargarPedidos(); // Recargar los pedidos
        } catch (error) {
            console.error(error);
        }
    };

    // Función para filtrar los pedidos según el rango de tiempo
    const filtrarPedidos = (criterio) => {
        const ahora = new Date();
        let fechaInicio;

        switch (criterio) {
            case "semana":
                fechaInicio = new Date(ahora);
                fechaInicio.setDate(ahora.getDate() - 7);
                break;
            case "mes":
                fechaInicio = new Date(ahora.getFullYear(), ahora.getMonth() - 1, ahora.getDate());
                break;
            case "ano":
                fechaInicio = new Date(ahora.getFullYear() - 1, ahora.getMonth(), ahora.getDate());
                break;
            case "todos":
            default:
                mostrarPedidos(pedidos);
                return;
        }

        // Filtrar pedidos
        const pedidosFiltrados = pedidos.filter((pedido) => {
            const fechaPedido = new Date(pedido.fecha_hora);
            return fechaPedido >= fechaInicio;
        });

        mostrarPedidos(pedidosFiltrados);
    };

    // Event Listener para el botón de aplicar filtro
    botonFiltro.addEventListener("click", () => {
        const criterio = filtroTiempo.value;
        filtrarPedidos(criterio);
    });

    // Cargar pedidos al inicio
    cargarPedidos();
});
