document.addEventListener('DOMContentLoaded', async () => {
    const tablaPedidos = document.querySelector('.tabla-pedidos');
    const filtroTiempo = document.getElementById('filtro-tiempo');
    const botonFiltro = document.querySelector('.boton-aplicar-filtro');

    let pedidos = []; // Almacena todos los pedidos obtenidos de la API
    
    const apiURLPedido = 'http://localhost/ProyectoKebab/codigo/index.php?route=pedido'; // Ruta de la API de pedidos
    
    // Función para obtener los pedidos de la API
    const obtenerTodosLosPedidos = async () => {
        try {
            // Realizamos la solicitud POST al servidor
            const response = await fetch(apiURLPedido, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    mostrar_todos: true, // Enviamos este parámetro para activar la funcionalidad en el servidor
                }),
            });

            if (!response.ok) {
                // Manejo de errores HTTP
                throw new Error(`Error en la solicitud: ${response.status}`);
            }

            // Parseamos la respuesta como JSON
            pedidos = await response.json();
            console.log('Pedidos obtenidos:', pedidos);

            crearTabla(); // Crear la estructura de la tabla
            mostrarPedidos(pedidos); // Mostrar los pedidos en la tabla
        } catch (error) {
            console.error('Error al obtener los pedidos:', error);
        }
    };

    // Función para crear la estructura de la tabla
    const crearTabla = () => {
        // Limpiar contenido previo
        tablaPedidos.innerHTML = '';

        // Crear encabezado
        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>Fecha</th>
                <th>Número de Pedido</th>
                <th>Cliente</th>
                <th>Total (€)</th>
            </tr>
        `;
        tablaPedidos.appendChild(thead);

        // Crear cuerpo
        const tbody = document.createElement('tbody');
        tablaPedidos.appendChild(tbody);
    };

    // Función para mostrar los pedidos en la tabla
    const mostrarPedidos = (pedidosFiltrados) => {
        const tablaCuerpo = tablaPedidos.querySelector('tbody');
        tablaCuerpo.innerHTML = ''; // Limpiar contenido previo
    
        pedidosFiltrados.forEach((pedido) => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${pedido.fecha_hora}</td>
                <td>${pedido.id_pedidos}</td>
                <td>${pedido.nombre_usuario}</td> <!-- Mostrar el nombre del usuario -->
                <td>${pedido.precio_total + "€"}</td>
            `;
            tablaCuerpo.appendChild(fila);
        });
    };    

    // Función para filtrar los pedidos según el rango de tiempo
    const filtrarPedidos = (criterio) => {
        const ahora = new Date();
        let fechaInicio;

        switch (criterio) {
            case 'semana':
                fechaInicio = new Date(ahora);
                fechaInicio.setDate(ahora.getDate() - 7);
                break;
            case 'mes':
                fechaInicio = new Date(ahora.getFullYear(), ahora.getMonth() - 1, ahora.getDate());
                break;
            case 'ano':
                fechaInicio = new Date(ahora.getFullYear() - 1, ahora.getMonth(), ahora.getDate());
                break;
            case 'todos':
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
    botonFiltro.addEventListener('click', () => {
        const criterio = filtroTiempo.value;
        filtrarPedidos(criterio);
    });


    //PDF
    // Importar la librería jsPDF
    const { jsPDF } = window.jspdf;

    // Botón para generar el PDF
    const botonPDF = document.querySelector('.boton-ver-pdf');

    // Función para generar el PDF
    const generarPDF = (criterio) => {
        // Crear una nueva instancia de jsPDF
        const doc = new jsPDF();

        // Agregar título en la parte superior
        const titulo = `PDF POR ${criterio.toUpperCase()}`;
        doc.setFontSize(16);
        doc.text(titulo, 20, 20);

        // Agregar encabezados de la tabla
        const encabezados = ['Fecha', 'Número de Pedido', 'Cliente', 'Total (€)'];
        const datosTabla = [];

        // Obtener los datos visibles en la tabla
        const filas = tablaPedidos.querySelectorAll('tbody tr');
        let sumaTotal = 0;

        filas.forEach((fila) => {
            const columnas = fila.querySelectorAll('td');
            const datosFila = Array.from(columnas).map((columna) => columna.innerText);

            // Sumar el total
            const precio = parseFloat(datosFila[3].replace('€', ''));
            sumaTotal += precio;

            // Agregar fila a los datos de la tabla
            datosTabla.push(datosFila);
        });

        // Renderizar la tabla
        doc.autoTable({
            startY: 30, // Posición inicial en el eje Y
            head: [encabezados],
            body: datosTabla,
        });

        // Agregar el total al final del PDF
        doc.setFontSize(12);
        doc.text(`El total ${criterio.toLowerCase()} es de: ${sumaTotal.toFixed(2)}€`, 20, doc.lastAutoTable.finalY + 10);

        // Guardar el archivo como PDF
        doc.save(`Pedidos_${criterio}.pdf`);
    };

    // Event Listener para el botón PDF
    botonPDF.addEventListener('click', () => {
        const criterio = filtroTiempo.options[filtroTiempo.selectedIndex].text; // Obtener texto del filtro
        generarPDF(criterio);
    });

    // Llama a la función para obtener pedidos al cargar la página
    await obtenerTodosLosPedidos();
});
