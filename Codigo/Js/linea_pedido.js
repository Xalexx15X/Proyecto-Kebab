document.addEventListener("DOMContentLoaded", function () {
    // inicializa funciones al cargar la pagina
    cargarCarrito(); // carga el carrito
    cargarDirecciones(); // carga las direcciones
    mostrarCredito(); // añado esta funcion para mostrar el credito
});

const ticketCarrito = document.getElementById('ticketCarrito'); // busco el ticket de carrito
const importePagar = document.getElementById('importePagar'); // busco el input de importe a pagar
const creditoActual = document.getElementById('creditoActual'); // busco el credito actual
const creditoFinal = document.getElementById('creditoFinal'); // busco el credito final
const añadirCreditoInput = document.getElementById('añadirCredito'); // busco el input de añadir credito
const direccionUsuario = document.getElementById('direccionUsuario'); // busco el input de direccion

// api url
const apiURLDireccion = 'http://localhost/ProyectoKebab/codigo/index.php?route=direccion';  
const apiURLUsarios = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios';
const apiURLPedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=pedido';
const apiURLLinea_Pedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=lineaPedido';

// funcion para cargar el carrito del localStorage y mostrarlo
function cargarCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || []; // recupero el carrito del localStorage
    const carritoAgrupado = agruparCarrito(carrito); // agrupo los kebabs por nombre y sumo cantidades
    mostrarCarrito(carritoAgrupado); // mostro el carrito
    calcularTotal(carritoAgrupado); // calcula el total
}

// funcion para agrupar los kebabs por nombre y sumar cantidades
function agruparCarrito(carrito) {
    const agrupado = {}; // creo un objeto para almacenar los kebabs agrupados
    carrito.forEach(kebab => { // recorro el array de kebabs
        const key = kebab.nombre; // obtengo el nombre del kebab
        if (!agrupado[key]) { // si el nombre no esta en el objeto
            agrupado[key] = { ...kebab, cantidad: 1, precio: kebab.precio }; // creo un objeto con el kebab y su cantidad y precio
        } else { // si ya existe
            agrupado[key].cantidad++; // sumo la cantidad
            agrupado[key].precio = agrupado[key].cantidad * kebab.precio; // sumo el precio
        }
    }); 
    return Object.values(agrupado); // devuelvo el array de objetos agrupados
}

// funcion para mostrar los kebabs
function mostrarCarrito(carrito) {
    ticketCarrito.innerHTML = '';  // limpio contenido anterior
    const tableHeader = `
        <div class="table-row">
            <div class="column">Cantidad</div>
            <div class="column">Kebab</div>
            <div class="column">Precio €</div>
            <div class="column">Acciones</div>
        </div>
    `;
    ticketCarrito.innerHTML = tableHeader; // lo agrego al ticket

    carrito.forEach((kebab, index) => { // recorro el array de kebabs
        const tableRow = document.createElement('div'); // creo un div para cada fila
        tableRow.classList.add('table-row'); // le asigno la clase table-row
        tableRow.innerHTML = ` 
            <div class="column">${kebab.cantidad}</div>
            <div class="column">${kebab.nombre} - Ingredientes: ${kebab.ingredientes.join(', ')}</div>
            <div class="column">${kebab.precio + "€"}</div>
            <div class="column">
                <button class="btn" onclick="disminuirCantidad(${index})">-</button>
            </div>
        `;
        ticketCarrito.appendChild(tableRow); // lo agrego al ticket
    });
}

// funcion para disminuir la cantidad de un kebab
function disminuirCantidad(index) { 
    const carrito = JSON.parse(localStorage.getItem('carrito')) || []; // recupero el carrito del localStorage
    const kebab = carrito[index]; // obtengo el kebab

    if (kebab) { // si existe
        if (kebab.cantidad > 1) { // si la cantidad es mayor a 1
            kebab.cantidad--; // sumo la cantidad
            kebab.precio = kebab.cantidad * (kebab.precio / (kebab.cantidad + 1)); // actualizo el precio
            carrito[index] = kebab; // actualizo el kebab en el carrito
        } else { // si es 1
            carrito.splice(index, 1); // lo quito del carrito
        } 
        localStorage.setItem('carrito', JSON.stringify(carrito)); // actualizo el carrito en el localStorage
        cargarCarrito(); // recargo el carrito
    }
}

// funcion para calcular el total del carrito
function calcularTotal(carrito) { 
    const total = carrito.reduce((sum, kebab) => sum + kebab.precio, 0); // sumo el total
    importePagar.value = total.toFixed(2); // formateo a dos decimales correctamente
}

// funcion para añadir crédito
window.añadirCredito = function () {
    const cantidad = parseFloat(añadirCreditoInput.value); // obtengo el valor del campo de crédito
    if (isNaN(cantidad) || cantidad <= 0) {  // si el valor no es válido
        alert('Por favor, ingrese un valor válido.'); 
        return;
    }

    const usuario = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario del localStorage
    if (!usuario) {  // si no existe el usuario
        alert('No se encontró información del usuario.');  // muestro un mensaje de error
        return;
    }

    // actualizo el saldo del monedero local
    const nuevoMonedero = (usuario.monedero || 0) + cantidad;

    // realizo la peticion put para actualizar el monedero
    actualizarMonederoEnServidor(nuevoMonedero); // llamo a la funcion para actualizar el monedero en el servidor
    console.log(actualizarMonederoEnServidor); // muestro la respuesta del servidor

    // guardo el nuevo monedero en localStorage
    usuario.monedero = nuevoMonedero;
    localStorage.setItem("usuario", JSON.stringify(usuario)); // actualizo el usuario en el localStorage

    // actualizo la vista del monedero en la interfaz
    actualizarMonedero(nuevoMonedero);

    // limpio el campo input
    añadirCreditoInput.value = '';
};

// funcion para actualizar el saldo del monedero en el servidor
function actualizarMonederoEnServidor(nuevoSaldo) {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario del localStorage
    if (!usuarioSesion || !usuarioSesion.id_usuario) { // si no existe el usuario o no tiene un id válido
        console.error("Usuario no válido para actualizar el monedero en el servidor.");
        return;
    }

    // creamos un objeto con los datos actualizados pero solo actualizo el monedero 
    const datosActualizados = { 
        id: usuarioSesion.id_usuario,
        nombre: usuarioSesion.nombre,
        contrasena: usuarioSesion.contrasena,
        carrito: usuarioSesion.carrito,
        monedero: nuevoSaldo, // usamos el nuevo saldo
        foto: usuarioSesion.foto,
        telefono: usuarioSesion.telefono,
        ubicacion: usuarioSesion.ubicacion,
        correo: usuarioSesion.correo,
        tipo: usuarioSesion.tipo,
    };

    // realizo la peticion put para actualizar el monedero
    fetch(`${apiURLUsarios}`, {
        method: "PUT", // uso el metodo PUT
        headers: { "Content-Type": "application/json" }, // le digo que lo que voy a enviar en el body es json
        body: JSON.stringify(datosActualizados),  // envio el dato actualizado
    })
    .then((response) => { // ahora segun lo que me responda el servidor proceso la respuesta como json
        if (!response.ok) { // si el servidor respondio con un error
            throw new Error(`Error al actualizar el monedero en el servidor: ${response.status}`); // lanzo un error
        }
        return response.json(); // proceso la respuesta como json
    })
    .then((data) => { // si la respuesta es válida
        if (data.success) { // si la respuesta indica éxito
            console.log("Monedero actualizado correctamente en el servidor.");
        } else {
            console.error("El servidor devolvió un error al actualizar el monedero:", data);
        }
    })
    .catch((error) => {
        console.error("Error al realizar la petición de actualización del monedero:", error);
    });
}


// funcion para actualizar el monedero en la vista
function actualizarMonedero(cantidad) {
    const monederoSpan = document.querySelector('.nav-item span'); // busco el span de la navegación
    if (monederoSpan) { // si existe
        monederoSpan.textContent = `${cantidad.toFixed(2)}€`; // muestro el saldo actualizado
    }

    // actualizo también el crédito actual en el input
    if (creditoActual) {
        creditoActual.value = cantidad.toFixed(2); // muestro el crédito actualizado
    }
}

// funcion para cargar las direcciones del usuario
function cargarDirecciones() {
    const usuario = JSON.parse(localStorage.getItem("usuario")); // obtengo el usuario

    fetch(`${apiURLDireccion}&id_usuario=${usuario.id_usuario}`, { // llamo a la url de direcciones y le paso la id del usuario por la url
        method: 'POST', // ha post
        headers: {
            'Content-Type': 'application/json' // le digo que lo que voy a enviar en el body es json
        },
        body: JSON.stringify({})  // envio un objeto vacio como body para que el servidor responda con la lista de direcciones
    })
    .then(response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
        if (!response.ok) { // si el servidor respondio con un error
            throw new Error(`Error en la respuesta: ${response.status}`); // lanzo un error con la respuesta del servidor
        }
        return response.json(); // proceso la respuesta como json
    })
    .then(json => { // si la respuesta es válida
        const direccionSelect = document.getElementById('direccionUsuario'); // busco el select de direcciones
        direccionSelect.innerHTML = ''; // limpio opciones anteriores 

        if (json && Array.isArray(json) && json.length > 0) { // si la respuesta es válida y tiene al menos una dirección
            json.forEach(direccion => { // recorro las direcciones
                const option = document.createElement('option'); // creo un elemento opción
                option.value = direccion.id_direccion; // le asigno el id de la direccion
                option.textContent = `${direccion.direccion}, ${direccion.estado}`; // le asigno el texto de la direccion
                direccionSelect.appendChild(option); // lo agrego al select
            });
        } else { // si no hay direcciones
            const noOption = document.createElement('option'); // creo un elemento opción
            noOption.value = ''; // le asigno un valor vacio
            noOption.textContent = "No tienes direcciones guardadas."; // le asigno un texto vacio
            noOption.disabled = true; // lo deshabilito
            noOption.selected = true; // lo selecciono
            direccionSelect.appendChild(noOption); // lo agrego al select
        }
    })
    .catch(error => { // si ocurre algún error
        console.error('Error al cargar las direcciones:', error); // muestro el error
    });
}

// funcion para actualizar el crédito restante después de pagar
function actualizarCreditoRestante() { 
    const creditoActualValue = parseFloat(creditoActual.value); // obtengo el valor del crédito actual
    const importePagarValue = parseFloat(importePagar.value);  // obtengo el valor del total a pagar
    
    if (isNaN(creditoActualValue) || isNaN(importePagarValue)) { // si los valores no son válidos
        console.error("Los valores de crédito actual o importe a pagar no son válidos."); // muestro un mensaje de error
        creditoFinal.value = "0.00"; // asigno un valor de crédito final de cero
        return;
    }

    // calculo el crédito restante
    const creditoRestante = creditoActualValue - importePagarValue; 

    // me aseguro de que no muestro un valor negativo 
    const creditoRestanteFinal = Math.max(0, creditoRestante); // si es negativo, lo cambio a cero

    // actualizo el valor del campo de crédito final
    creditoFinal.value = creditoRestanteFinal.toFixed(2); // muestro el crédito restante con dos decimales
}

// llamo a esta funcion despues de actualizar el carrito o total a pagar
function calcularTotal(carrito) {
    const total = carrito.reduce((sum, kebab) => sum + kebab.precio, 0); // sumo el total de el precio de todos los kebabs del tiket
    importePagar.value = total.toFixed(2); // formateo a dos decimales correctamente

    // despues de calcular el total, actualizo el crédito restante
    actualizarCreditoRestante();
}

// llamo a la funcion mostrarCredito para mostrar el crédito inicial cuando cargue la pagina
function mostrarCredito() {
    const usuario = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario del localStorage
    
    if (usuario && typeof usuario.monedero === 'number') { // si existe el usuario y el valor de monedero es un número
        // mostro el crédito actual en el campo input
        creditoActual.value = usuario.monedero.toFixed(2); // muestro el crédito con 2 decimales
    } else { // si no existe el usuario o el valor de monedero no es un número
        console.error("No se encontró el crédito del usuario en localStorage o el valor de 'monedero' no es un número.");
        creditoActual.value = "0.00"; // asigno un valor de crédito de cero
    }

    // despues de mostrar el crédito, tambien actualizo el crédito restante
    actualizarCreditoRestante();
}

// funcion para tramitar el pedido
window.tramitarPedido = function () {
    const usuario = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario del localStorage
    const carrito = JSON.parse(localStorage.getItem("carrito")) || []; // recupero el carrito del localStorage y lo convierto a un array si es un objeto
    const precioTotal = parseFloat(importePagar.value); // obtengo el valor del total a pagar

    // verifico si el carrito esta vacio
    if (carrito.length === 0) {
        alert("No hay nada en el carrito para procesar el pedido.");
        return;
    }

    // verifico si el usuario esta registrado
    if (!usuario || !usuario.id_usuario) {
        alert("Para procesar el pedido necesitamos que te registres o inicies sesión.");
        window.location.href = "index.php?menu=registro"; // Redirigir a la ventana de registro
        return;
    }

    // verifico si el usuario tiene una direccion seleccionada
    const direccionSeleccionada = direccionUsuario.value; // obtengo el valor del select de direccion
    if (!direccionSeleccionada || direccionSeleccionada.trim() === '') { // si no existe o es un espacio en blanco
        alert("Por favor, selecciona una dirección para continuar."); // muestro un mensaje de error
        return;
    }

    
    const monedero = parseFloat(usuario.monedero) || 0; // obtengo el valor del monedero del usuario

    // verifico si el usuario tiene suficiente crédito
    if (monedero < precioTotal) { 
        alert("No tienes suficiente saldo en tu monedero para tramitar el pedido.");
        return;
    }

    const fechaHora = formatoFechaMysql(); // obtengo la fecha y hora en formato mysql

    const pedidoData = { // creo el objeto con los datos del pedido
        estado: "Pendiente", // el estado del pedido siempre es pendiente hasta que el admin lo procese
        precio_total: precioTotal.toFixed(2), // el precio total del pedido
        fecha_hora: fechaHora, // la fecha y hora del pedido
        usuario_id_usuario: usuario.id_usuario, // el id del usuario que realizo el pedido
    };

    fetch(apiURLPedido, { // hago la peticion ajax con la url de pedidos
        method: "POST", // uso el metodo POST
        headers: {
            "Content-Type": "application/json",  // le digo que lo que voy a enviar en el body es json
        },
        body: JSON.stringify(pedidoData),  // envio el objeto creado
    }) 
        .then((response) => { // ahora segun lo que me responda el servidor proceso la respuesta como json
            if (!response.ok) { // si el servidor respondio con un error
                throw new Error("Error en la creación del pedido."); 
            }
            return response.json(); // proceso la respuesta como json
        })
        .then((data) => { // si la respuesta es válida
            if (data && data.id_pedido) { 
                // creo las líneas de pedido con el id del pedido recién creado
                crearLineasPedido(data.id_pedido); // llamo a la funcion para crear las líneas de pedido

                // restar el crédito del monedero despues de procesar el pedido
                const nuevoMonedero = monedero - precioTotal; 

                // actualizo el monedero en localStorage
                usuario.monedero = nuevoMonedero;
                localStorage.setItem("usuario", JSON.stringify(usuario));

                // actualizo el monedero en la interfaz
                actualizarMonedero(nuevoMonedero);

                // actualizo el monedero en la base de datos
                actualizarMonederoEnServidor(nuevoMonedero);

                // borro el carrito del localStorage
                localStorage.removeItem("carrito");

                // actualizo el total a pagar y crédito restante
                calcularTotal([]); // llamo a la funcion para actualizar el total a pagar y crédito restante
                cargarCarrito(); // recargo el carrito
            } else {
                throw new Error("El servidor no devolvió un ID de pedido válido.");
            }
        })
        .catch((error) => {
            console.error("Error al tramitar el pedido:", error);
        });
};

// funcion para crear las lineas de pedido
function crearLineasPedido(id_pedido) {
    const lineas = document.querySelectorAll("#ticketCarrito .table-row"); // obtengo todas las líneas del ticket

    if (!lineas.length) { // si no hay líneas mustro un mensaje de error
        console.warn("No se encontraron líneas en el ticket para crear líneas de pedido.");
        return;
    }

    lineas.forEach((linea, index) => { // recorro las líneas
        const columnas = linea.querySelectorAll(".column");

        if (columnas.length < 3) { // si no hay columnas mustro un mensaje de error
            console.error(`Fila ${index + 1}: No contiene las columnas necesarias.`);
            return;
        }

        const cantidadText = columnas[0]?.textContent.trim(); // obtengo el valor de la cantidad
        const detallesKebab = columnas[1]?.textContent.trim(); // obtengo el valor de los detalles del kebab
        const precioText = columnas[2]?.textContent.replace("€", "").trim(); // obtengo el valor de precio

        const cantidad = parseInt(cantidadText, 10); // convierto el valor de cantidad a un número
        const precio = parseFloat(precioText); // convierto el valor de precio a un número

        if (isNaN(cantidad) || isNaN(precio)) { // si los valores no son válidos muestro un mensaje de error
            console.error(`Fila ${index + 1}: Datos inválidos para cantidad o precio.`);
            return;
        }

        let nombre = "Desconocido"; // si no hay detalles del kebab asigno un nombre desconocido
        let ingredientes = []; // si no hay detalles del kebab asigno un array vacio de ingredientes
        if (detallesKebab) { // si existe
            const [nombreText, ingredientesText] = detallesKebab.split(" - Ingredientes: "); // separo el nombre del ingrediente del detalle del kebab
            nombre = nombreText?.trim() || "Desconocido"; // si el nombre del ingrediente es vacio asigno un nombre desconocido
            ingredientes = ingredientesText ? ingredientesText.split(", ").map((ing) => ing.trim()) : []; 
        }

        const lineaPedidoData = { 
            cantidad: cantidad, // la cantidad del kebab
            precio: precio, // el precio del kebab
            linea_pedidos: { // los datos del pedido
                nombre: nombre, // el nombre del kebab
                ingredientes: ingredientes.join(", "), // los ingredientes del kebab
            },
            id_pedidos: id_pedido, // el id del pedido
        };

        fetch(apiURLLinea_Pedido, { // hago la peticion ajax con la url de lineas de pedidos
            method: "POST", // uso el metodo POST
            headers: {
                "Content-Type": "application/json", // le digo que lo que voy a enviar en el body es json
            },
            body: JSON.stringify(lineaPedidoData),  // envio el objeto creado
        })
            .then((response) => response.json()) // ahora segun lo que me responda el servidor proceso la respuesta como json
            .then((data) => { // si la respuesta es válida
                if (data.success) { 
                    console.log(`Línea ${index + 1} creada correctamente:`, data); // muestro el resultado del servidor
                } else { // si el servidor respondio con un error
                    console.error(`Error al crear la línea ${index + 1}:`, data); // muestro el error
                }
            })
            .catch((error) => {
                console.error(`Error al enviar la línea ${index + 1}:`, error);
            });
    });
}

// funcion para formatear la fecha en formato MySQL
function formatoFechaMysql() {
    const fecha = new Date(); // obtengo la fecha actual
    const año = fecha.getFullYear(); // obtengo el año
    const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // obtengo el mes y lo convierto a un string con dos digitos
    const dia = String(fecha.getDate()).padStart(2, '0'); // obtengo el día y lo convierto a un string con dos digitos
    const horas = String(fecha.getHours()).padStart(2, '0'); // obtengo las horas y lo convierto a un string con dos digitos
    const minutos = String(fecha.getMinutes()).padStart(2, '0'); // obtengo los minutos y lo convierto a un string con dos digitos
    const segundos = String(fecha.getSeconds()).padStart(2, '0'); // obtengo los segundos y lo convierto a un string con dos digitos

    return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`; // devuelvo la fecha en formato mysql
}

