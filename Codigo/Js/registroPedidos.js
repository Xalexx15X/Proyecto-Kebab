document.addEventListener('DOMContentLoaded', async () => { 
    const tablaPedidos = document.querySelector('.tabla-pedidos'); // busco el contenedor de pedidos
    const filtroTiempo = document.getElementById('filtro-tiempo'); // busco el filtro de tiempo
    const botonFiltro = document.querySelector('.boton-aplicar-filtro'); // busco el boton de filtro

    let pedidos = []; // almaceno todos los pedidos obtenidos de la API
    
    const apiURLPedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=pedido'; // ruta de la api de pedidos
    
    // funcion para obtener los pedidos de la api
    const obtenerTodosLosPedidos = async () => { 
        try { // intento realizar la peticion ajax
            const response = await fetch(apiURLPedido, { // hago la peticion ajax para obtener los pedidos
                method: 'POST', // uso el metodo POST
                headers: {  // le digo que lo que voy a enviar en el body es json
                    'Content-Type': 'application/json', // le digo que lo que voy a enviar en el body es json
                },
                body: JSON.stringify({
                    mostrar_todos: true, // envio este parámetro para activar la funcionalidad en el servidor
                }),
            });
 
            if (!response.ok) { // si el servidor respondio con un error
                throw new Error(`Error en la solicitud: ${response.status}`);   // lanzo un error
            }

            // parseo la respuesta como json
            pedidos = await response.json();
            console.log('Pedidos obtenidos:', pedidos); // muestro los pedidos en la tabla

            crearTabla(); // crea la estructura de la tabla
            mostrarPedidos(pedidos); // mostro los pedidos en la tabla
        } catch (error) { // si no es válido lanzo un error
            console.error('Error al obtener los pedidos:', error); // lanzo un error
        }
    };

    // funcion para crear la estructura de la tabla
    const crearTabla = () => {
        // limpio contenido previo
        tablaPedidos.innerHTML = '';

        // crea encabezado con lo que voy a mostrar
        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>Fecha</th>
                <th>Número de Pedido</th>
                <th>Cliente</th>
                <th>Total (€)</th>
            </tr>
        `;
        tablaPedidos.appendChild(thead); // lo agrego al contenedor

        // Crear cuerpo
        const tbody = document.createElement('tbody'); // crea el cuerpo
        tablaPedidos.appendChild(tbody); // lo agrego al contenedor
    };

    // funcion para mostrar los pedidos en la tabla
    const mostrarPedidos = (pedidosFiltrados) => { 
        const tablaCuerpo = tablaPedidos.querySelector('tbody'); // busco el cuerpo de la tabla
        tablaCuerpo.innerHTML = ''; // limpio contenido previo
    
        pedidosFiltrados.forEach((pedido) => { // recorro el array de pedidos
            const fila = document.createElement('tr'); // creo un div para cada fila
            fila.innerHTML = ` 
                <td>${pedido.fecha_hora}</td>
                <td>${pedido.id_pedidos}</td>
                <td>${pedido.nombre_usuario}</td> <!-- Mostrar el nombre del usuario -->
                <td>${pedido.precio_total + "€"}</td>
            `;
            tablaCuerpo.appendChild(fila); // lo agrego al cuerpo
        });
    };    

    // funcion para filtrar los pedidos según el rango de tiempo
    const filtrarPedidos = (criterio) => {
        const ahora = new Date(); // obtengo la fecha actual
        let fechaInicio;

        switch (criterio) { // cambio el valor de la fecha según el criterio
            case 'semana': // si es la semana
                fechaInicio = new Date(ahora); // creo una nueva fecha con la fecha actual
                fechaInicio.setDate(ahora.getDate() - 7); // cambio el día de la fecha para que sea el día de la semana
                break;
            case 'mes': // si es el mes
                fechaInicio = new Date(ahora.getFullYear(), ahora.getMonth() - 1, ahora.getDate()); // creo una nueva fecha con la fecha actual
                break;
            case 'ano': // si es el año
                fechaInicio = new Date(ahora.getFullYear() - 1, ahora.getMonth(), ahora.getDate()); // creo una nueva fecha con la fecha actual
                break;
            case 'todos':
            default:
                mostrarPedidos(pedidos); // muestro los pedidos
                return;
        }

        // filtro los pedidos
        const pedidosFiltrados = pedidos.filter((pedido) => {
            const fechaPedido = new Date(pedido.fecha_hora); // obtengo la fecha del pedido
            return fechaPedido >= fechaInicio; // si es mayor o igual a la fecha inicial, lo agrego a la lista
        });

        mostrarPedidos(pedidosFiltrados); // muestro los pedidos
    };

    // event listener para el boton de aplicar filtro
    botonFiltro.addEventListener('click', () => { 
        const criterio = filtroTiempo.value; // obtengo el criterio
        filtrarPedidos(criterio); // filtro los pedidos
    });


    //PDF
    // importar la libreria jsPDF
    const { jsPDF } = window.jspdf; 

    // boton para generar el pdf
    const botonPDF = document.querySelector('.boton-ver-pdf');

    // funcion para generar el pdf
    const generarPDF = (criterio) => {
        // creo una nueva instancia de jsPDF
        const doc = new jsPDF();

        // agrego título en la parte superior
        const titulo = `PDF POR ${criterio.toUpperCase()}`;
        doc.setFontSize(16); // establezco el tamaño de fuente
        doc.text(titulo, 20, 20); // agrego el título

        // agrego encabezados de la tabla
        const encabezados = ['Fecha', 'Número de Pedido', 'Cliente', 'Total (€)'];
        const datosTabla = []; // almaceno los datos de la tabla

        // obtengo los datos visibles en la tabla
        const filas = tablaPedidos.querySelectorAll('tbody tr');
        let sumaTotal = 0; // inicializo el total a 0

        filas.forEach((fila) => { // recorro el array de filas
            const columnas = fila.querySelectorAll('td'); // obtengo las columnas de la fila
            const datosFila = Array.from(columnas).map((columna) => columna.innerText); // obtengo los datos de la fila

            // sumo el total
            const precio = parseFloat(datosFila[3].replace('€', ''));
            sumaTotal += precio; // sumo el total

            // agrego fila a los datos de la tabla
            datosTabla.push(datosFila);
        });

        // renderizo la tabla
        doc.autoTable({
            startY: 30, // posiciono inicial en el eje Y
            head: [encabezados], // agrego encabezados
            body: datosTabla, // agrego datos
        });

        // agrego el total al final del pdf
        doc.setFontSize(12); // establezco el tamaño de fuente
        doc.text(`El total ${criterio.toLowerCase()} es de: ${sumaTotal.toFixed(2)}€`, 20, doc.lastAutoTable.finalY + 10); // agrego el total

        // guardo el archivo como pdf
        doc.save(`Pedidos_${criterio}.pdf`); 
    };

    // event listener para el boton pdf
    botonPDF.addEventListener('click', () => {
        const criterio = filtroTiempo.options[filtroTiempo.selectedIndex].text; // obtengo texto del filtro
        generarPDF(criterio);
    });

    // llamo a la funcion para obtener pedidos al cargar la pagina
    await obtenerTodosLosPedidos();
});
