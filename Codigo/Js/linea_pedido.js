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
            <div class="column">€${kebab.precio}</div>
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
    actualizarMonederoEnServidor();

    // Guardar el nuevo monedero en localStorage
    usuario.monedero = nuevoMonedero;
    localStorage.setItem("usuario", JSON.stringify(usuario));

    // Actualizar la vista del monedero en la interfaz
    actualizarMonedero(nuevoMonedero);

    // Limpiar el campo input
    añadirCreditoInput.value = '';
};

function actualizarMonederoEnServidor() {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));
    const nuevaCantidad = parseFloat(añadirCreditoInput.value);
    const datosActualizados = {
        id: usuarioSesion.id_usuario,
        nombre: usuarioSesion.nombre,
        contrasena: usuarioSesion.contrasena,
        carrito: usuarioSesion.carrito,
        monedero: nuevaCantidad,
        foto: usuarioSesion.foto,
        telefono: usuarioSesion.telefono,
        ubicacion: usuarioSesion.ubicacion,
        correo: usuarioSesion.correo,
        tipo: usuarioSesion.tipo 
    };

    fetch(`${apiURLUsarios}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datosActualizados),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Monedero actualizado correctamente en el servidor.");
        } else {
            console.error("Error al actualizar el monedero en el servidor:", data);
        }
    })
    .catch(error => {
        console.error("Error al realizar la petición de actualización del monedero:", error);
    });
}

// Función para actualizar el monedero en la vista
function actualizarMonedero(cantidad) {
    // Buscar el elemento del header
    const monederoSpan = document.querySelector('.nav-item span');
    if (monederoSpan) {
        monederoSpan.textContent = `${cantidad.toFixed(2)}€`; // Mostrar el saldo actualizado
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
    const precioTotal = parseFloat(importePagar.value);

    if (!usuario || !usuario.id_usuario) {
        console.error("Usuario no encontrado en localStorage.");
        return;
    }

        // Obtener el monedero del usuario desde el objeto 'usuario' en el localStorage
    const monedero = parseFloat(usuario.monedero) || 0; // Si no existe, asumimos que es 0

    // Verificar si el monedero es suficiente para tramitar el pedido
    if (monedero < precioTotal) {
        alert("No tienes suficiente saldo en tu monedero para tramitar el pedido.");
        return;
    }

    // Usar la función para obtener la fecha en formato MySQL
    const fechaHora = formatoFechaMysql();

    const pedidoData = {
        estado: "Pendiente",
        precio_total: precioTotal.toFixed(2),
        fecha_hora: fechaHora,
        usuario_id_usuario: usuario.id_usuario
    };

    console.log("Pedido Data:", pedidoData); // Revisa la data para asegurarte de que todo está correcto

    fetch(apiURLPedido, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(pedidoData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Pedido creado correctamente:", data);
            crearLineasPedido(data.id_pedido);
        } else {
            console.error("Error al crear el pedido:", data);
        }
    })
    .catch(error => {
        console.error("Error al hacer el POST para crear el pedido:", error);
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


function crearLineasPedido(idPedido) {
    const lineas = document.querySelectorAll(".linea-tiket");

    lineas.forEach(linea => {
        const cantidad = parseInt(linea.querySelector(".cantidad").textContent);
        const precioTotal = parseFloat(linea.querySelector(".precio-total").textContent);
        const nombreKebab = linea.querySelector(".nombre-kebab").textContent;
        const ingredientes = linea.querySelector(".ingredientes").textContent;

        const lineaPedidoData = {
            cantidad: cantidad,
            precio: precioTotal,
            linea_pedidos: nombreKebab + " - " + ingredientes,
            id_pedidos: idPedido
        };

        fetch(apiURLLinea_Pedido, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(lineaPedidoData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                console.log("Línea de pedido creada correctamente:", data);
            } else {
                console.error("Error al crear la línea de pedido:", data);
            }
        })
        .catch(error => {
            console.error("Error al crear la línea de pedido:", error);
        });
    });
}




