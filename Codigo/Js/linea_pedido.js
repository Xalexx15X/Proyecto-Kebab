document.addEventListener("DOMContentLoaded", function () {
    // Inicializar funciones al cargar la página
    cargarCarrito();
    cargarDirecciones();
    mostrarCredito(); // Añadimos esta función para mostrar el crédito
});

const ticketCarrito = document.getElementById('ticketCarrito');
const importePagar = document.getElementById('importePagar');
const creditoActual = document.getElementById('creditoActual');
const creditoFinal = document.getElementById('creditoFinal');
const añadirCreditoInput = document.getElementById('añadirCredito');
const direccionUsuario = document.getElementById('direccionUsuario');

// API URL 
const apiURLDireccion = 'http://localhost/ProyectoKebab/codigo/index.php?route=direccion'; 
const apiURLUsarios = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios';
const apiURLPedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=pedido';
const apiURLLinea_Pedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=lineaPedido';

// Cargar el carrito del localStorage y mostrarlo
function cargarCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const carritoAgrupado = agruparCarrito(carrito);
    mostrarCarrito(carritoAgrupado);
    calcularTotal(carritoAgrupado);
}

// Agrupar los kebabs por nombre y sumar cantidades
function agruparCarrito(carrito) {
    const agrupado = {};
    carrito.forEach(kebab => {
        const key = kebab.nombre;
        if (!agrupado[key]) {
            agrupado[key] = { ...kebab, cantidad: 1, precio: kebab.precio };
        } else {
            agrupado[key].cantidad++;
            agrupado[key].precio = agrupado[key].cantidad * kebab.precio;
        }
    });
    return Object.values(agrupado);
}

// Mostrar los kebabs 
function mostrarCarrito(carrito) {
    ticketCarrito.innerHTML = ''; // Limpiar contenido anterior
    const tableHeader = `
        <div class="table-row">
            <div class="column">Cantidad</div>
            <div class="column">Kebab</div>
            <div class="column">Precio €</div>
            <div class="column">Acciones</div>
        </div>
    `;
    ticketCarrito.innerHTML = tableHeader;

    carrito.forEach((kebab, index) => {
        const tableRow = document.createElement('div');
        tableRow.classList.add('table-row');
        tableRow.innerHTML = `
            <div class="column">${kebab.cantidad}</div>
            <div class="column">${kebab.nombre} - Ingredientes: ${kebab.ingredientes.join(', ')}</div>
            <div class="column">${kebab.precio + "€"}</div>
            <div class="column">
                <button class="btn" onclick="disminuirCantidad(${index})">-</button>
            </div>
        `;
        ticketCarrito.appendChild(tableRow);
    });
}

// Disminuir cantidad de un kebab
function disminuirCantidad(index) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const kebab = carrito[index];

    if (kebab) {
        if (kebab.cantidad > 1) {
            kebab.cantidad--;
            kebab.precio = kebab.cantidad * (kebab.precio / (kebab.cantidad + 1)); // Actualizar precio
            carrito[index] = kebab;
        } else {
            carrito.splice(index, 1);
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));
        cargarCarrito(); // Recargar el carrito
    }
}

function calcularTotal(carrito) {
    const total = carrito.reduce((sum, kebab) => sum + kebab.precio, 0);
    importePagar.value = total.toFixed(2); // Formatear a dos decimales correctamente
}

// Función para añadir crédito
window.añadirCredito = function () {
    const cantidad = parseFloat(añadirCreditoInput.value);
    if (isNaN(cantidad) || cantidad <= 0) {
        alert('Por favor, ingrese un valor válido.');
        return;
    }

    const usuario = JSON.parse(localStorage.getItem("usuario"));
    if (!usuario) {
        alert('No se encontró información del usuario.');
        return;
    }

    // Actualizar el saldo del monedero local
    const nuevoMonedero = (usuario.monedero || 0) + cantidad;

    // Realizar la petición PUT para actualizar el monedero
    actualizarMonederoEnServidor(nuevoMonedero);
    console.log(actualizarMonederoEnServidor);

    // Guardar el nuevo monedero en localStorage
    usuario.monedero = nuevoMonedero;
    localStorage.setItem("usuario", JSON.stringify(usuario));

    // Actualizar la vista del monedero en la interfaz
    actualizarMonedero(nuevoMonedero);

    // Limpiar el campo input
    añadirCreditoInput.value = '';
};

function actualizarMonederoEnServidor(nuevoSaldo) {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));
    if (!usuarioSesion || !usuarioSesion.id_usuario) {
        console.error("Usuario no válido para actualizar el monedero en el servidor.");
        return;
    }

    const datosActualizados = {
        id: usuarioSesion.id_usuario,
        nombre: usuarioSesion.nombre,
        contrasena: usuarioSesion.contrasena,
        carrito: usuarioSesion.carrito,
        monedero: nuevoSaldo, // Aquí usamos el nuevo saldo
        foto: usuarioSesion.foto,
        telefono: usuarioSesion.telefono,
        ubicacion: usuarioSesion.ubicacion,
        correo: usuarioSesion.correo,
        tipo: usuarioSesion.tipo,
    };

    fetch(`${apiURLUsarios}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datosActualizados),
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error(`Error al actualizar el monedero en el servidor: ${response.status}`);
        }
        return response.json();
    })
    .then((data) => {
        if (data.success) {
            console.log("Monedero actualizado correctamente en el servidor.");
        } else {
            console.error("El servidor devolvió un error al actualizar el monedero:", data);
        }
    })
    .catch((error) => {
        console.error("Error al realizar la petición de actualización del monedero:", error);
    });
}


// Función para actualizar el monedero en la vista
function actualizarMonedero(cantidad) {
    const monederoSpan = document.querySelector('.nav-item span');
    if (monederoSpan) {
        monederoSpan.textContent = `${cantidad.toFixed(2)}€`; // Mostrar el saldo actualizado
    }

    // Actualizamos también el crédito actual en el input
    if (creditoActual) {
        creditoActual.value = cantidad.toFixed(2);
    }
}

// Cargar direcciones del usuario
function cargarDirecciones() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (!usuario) {
        console.error("No se encontró información del usuario en localStorage.");
        return;
    }

    if (!usuario.id_usuario) { 
        console.error("El usuario no tiene un ID válido:", usuario);
        return;
    }

    fetch(`${apiURLDireccion}&id_usuario=${usuario.id_usuario}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({}) 
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }
        return response.json();
    })
    .then(json => {
        const direccionSelect = document.getElementById('direccionUsuario');
        direccionSelect.innerHTML = ''; // Limpiar opciones anteriores

        if (json && Array.isArray(json) && json.length > 0) {
            json.forEach(direccion => {
                const option = document.createElement('option');
                option.value = direccion.id_direccion;
                option.textContent = `${direccion.direccion}, ${direccion.estado}`;
                direccionSelect.appendChild(option);
            });
        } else {
            const noOption = document.createElement('option');
            noOption.value = '';
            noOption.textContent = "No tienes direcciones guardadas.";
            noOption.disabled = true;
            noOption.selected = true;
            direccionSelect.appendChild(noOption);
        }
    })
    .catch(error => {
        console.error('Error al cargar las direcciones:', error);
    });
}

// Función para actualizar el crédito restante después de pagar
function actualizarCreditoRestante() {
    const creditoActualValue = parseFloat(creditoActual.value); // Obtener el valor del crédito actual
    const importePagarValue = parseFloat(importePagar.value); // Obtener el valor del total a pagar
    
    if (isNaN(creditoActualValue) || isNaN(importePagarValue)) {
        console.error("Los valores de crédito actual o importe a pagar no son válidos.");
        creditoFinal.value = "0.00"; // Si los valores no son válidos, mostramos 0.00
        return;
    }

    // Calcular el crédito restante
    const creditoRestante = creditoActualValue - importePagarValue;

    // Asegurarnos de que no mostramos un valor negativo (puede ajustarse según la lógica que desees)
    const creditoRestanteFinal = Math.max(0, creditoRestante);

    // Actualizar el valor del campo de crédito final
    creditoFinal.value = creditoRestanteFinal.toFixed(2); // Mostrar con dos decimales
}

// Llamar a esta función después de actualizar el carrito o total a pagar
function calcularTotal(carrito) {
    const total = carrito.reduce((sum, kebab) => sum + kebab.precio, 0);
    importePagar.value = total.toFixed(2); // Formatear a dos decimales correctamente

    // Después de calcular el total, actualizamos el crédito restante
    actualizarCreditoRestante();
}

// Llamar a la función `mostrarCredito` para mostrar el crédito inicial cuando cargue la página
function mostrarCredito() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    
    if (usuario && typeof usuario.monedero === 'number') {
        // Mostrar el crédito actual en el campo input
        creditoActual.value = usuario.monedero.toFixed(2); // Mostramos el crédito con 2 decimales
    } else {
        console.error("No se encontró el crédito del usuario en localStorage o el valor de 'monedero' no es un número.");
        creditoActual.value = "0.00"; // Si no se encuentra, asignar 0.00
    }

    // Después de mostrar el crédito, también actualizamos el crédito restante
    actualizarCreditoRestante();
}

window.tramitarPedido = function () {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    const precioTotal = parseFloat(importePagar.value);

    // Validación: Verificar si el carrito está vacío
    if (carrito.length === 0) {
        alert("No hay nada en el carrito para procesar el pedido.");
        return;
    }

    // Validación: Verificar si el usuario está registrado
    if (!usuario || !usuario.id_usuario) {
        alert("Para procesar el pedido necesitamos que te registres o inicies sesión.");
        window.location.href = "index.php?menu=registro"; // Redirigir a la ventana de registro
        return;
    }

    // Validación: Verificar si el usuario tiene una dirección seleccionada
    const direccionSeleccionada = direccionUsuario.value;
    if (!direccionSeleccionada || direccionSeleccionada.trim() === '') {
        alert("Por favor, selecciona una dirección para continuar.");
        return;
    }

    const monedero = parseFloat(usuario.monedero) || 0;

    // Validación: Verificar si el usuario tiene suficiente crédito
    if (monedero < precioTotal) {
        alert("No tienes suficiente saldo en tu monedero para tramitar el pedido.");
        return;
    }

    const fechaHora = formatoFechaMysql();

    const pedidoData = {
        estado: "Pendiente",
        precio_total: precioTotal.toFixed(2),
        fecha_hora: fechaHora,
        usuario_id_usuario: usuario.id_usuario,
    };

    console.log("Creando pedido con datos:", pedidoData);

    // Crear el pedido
    fetch(apiURLPedido, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(pedidoData),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error en la creación del pedido.");
            }
            return response.json(); // Aquí esperamos el ID del pedido recién creado
        })
        .then((data) => {
            if (data && data.id_pedido) {
                console.log("Pedido creado con éxito. ID del pedido:", data.id_pedido);

                // Crear líneas de pedido con el ID recién creado
                crearLineasPedido(data.id_pedido);

                // Restar el crédito del monedero después de procesar el pedido
                const nuevoMonedero = monedero - precioTotal;

                // Actualizar el monedero en localStorage
                usuario.monedero = nuevoMonedero;
                localStorage.setItem("usuario", JSON.stringify(usuario));

                // Actualizar el monedero en la interfaz
                actualizarMonedero(nuevoMonedero);

                // Actualizar el monedero en la base de datos
                actualizarMonederoEnServidor(nuevoMonedero);

                // Borrar el carrito del localStorage
                localStorage.removeItem("carrito");

                // Actualizar el total a pagar y crédito restante
                calcularTotal([]);

                console.log("Carrito borrado después de tramitar el pedido.");
            } else {
                throw new Error("El servidor no devolvió un ID de pedido válido.");
            }
        })
        .catch((error) => {
            console.error("Error al tramitar el pedido:", error);
        });
};

// Función para crear las líneas de pedido
function crearLineasPedido(id_pedido) {
    const lineas = document.querySelectorAll("#ticketCarrito .table-row");

    if (!lineas.length) {
        console.warn("No se encontraron líneas en el ticket para crear líneas de pedido.");
        return;
    }

    console.log("Creando líneas para el pedido ID:", id_pedido);

    lineas.forEach((linea, index) => {
        const columnas = linea.querySelectorAll(".column");

        if (columnas.length < 3) {
            console.error(`Fila ${index + 1}: No contiene las columnas necesarias.`);
            return;
        }

        const cantidadText = columnas[0]?.textContent.trim();
        const detallesKebab = columnas[1]?.textContent.trim();
        const precioText = columnas[2]?.textContent.replace("€", "").trim();

        const cantidad = parseInt(cantidadText, 10);
        const precio = parseFloat(precioText);

        if (isNaN(cantidad) || isNaN(precio)) {
            console.error(`Fila ${index + 1}: Datos inválidos para cantidad o precio.`);
            return;
        }

        let nombre = "Desconocido";
        let ingredientes = [];
        if (detallesKebab) {
            const [nombreText, ingredientesText] = detallesKebab.split(" - Ingredientes: ");
            nombre = nombreText?.trim() || "Desconocido";
            ingredientes = ingredientesText ? ingredientesText.split(", ").map((ing) => ing.trim()) : [];
        }

        const lineaPedidoData = {
            cantidad: cantidad,
            precio: precio,
            linea_pedidos: {
                nombre: nombre,
                ingredientes: ingredientes.join(", "),
            },
            id_pedidos: id_pedido,
        };

        console.log(`Creando línea ${index + 1} con datos:`, lineaPedidoData);

        fetch(apiURLLinea_Pedido, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(lineaPedidoData),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    console.log(`Línea ${index + 1} creada correctamente:`, data);
                } else {
                    console.error(`Error al crear la línea ${index + 1}:`, data);
                }
            })
            .catch((error) => {
                console.error(`Error al enviar la línea ${index + 1}:`, error);
            });
    });
}

// Función para formatear la fecha en formato MySQL
function formatoFechaMysql() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // +1 porque getMonth() empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, '0');
    const horas = String(fecha.getHours()).padStart(2, '0');
    const minutos = String(fecha.getMinutes()).padStart(2, '0');
    const segundos = String(fecha.getSeconds()).padStart(2, '0');

    return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
}

