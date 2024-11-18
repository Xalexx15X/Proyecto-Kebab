<link rel="stylesheet" href="./css/CssHistorialPedido.css">
<body>
    <div class="contenedor">
        <h1 class="titulo-centrado">Historial de Pedidos</h1>

        <!-- Filtros -->
        <div class="filtros">
            <label for="filtro-tiempo">Filtrar por:</label>
            <select id="filtro-tiempo">
                <option value="semana">Última semana</option>
                <option value="mes">Último mes</option>
                <option value="ano">Último año</option>
                <option value="todos">Todos</option>
            </select>
            <button class="boton-aplicar-filtro">Aplicar Filtro</button>
        </div>

        <!-- Tabla de pedidos -->
        <div class="tabla-contenedor">
            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Pedido</th>
                        <th>Importe</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de fila de pedido -->
                    <tr>
                        <td>17/11/2024</td>
                        <td>Kebab de Pollo, Kebab Al Gusto Y Kebab Falafet</td>
                        <td>22.50€</td>
                        <td>
                            <button class="boton-ver-ticket" onclick="mostrarTicket(1)">Ver Ticket</button>
                        </td>
                    </tr>
                    <tr>
                        <td>17/11/2024</td>
                        <td>Kebab de Pollo, Kebab Al Gusto Y Kebab Falafet</td>
                        <td>22.50€</td>
                        <td>
                            <button class="boton-ver-ticket" onclick="mostrarTicket(1)">Ver Ticket</button>
                        </td>
                    </tr>
                    <tr>
                        <td>17/11/2024</td>
                        <td>Kebab de Pollo, Kebab Al Gusto Y Kebab Falafet</td>
                        <td>22.50€</td>
                        <td>
                            <button class="boton-ver-ticket" onclick="mostrarTicket(1)">Ver Ticket</button>
                        </td>
                    </tr>
                    <tr>
                        <td>17/11/2024</td>
                        <td>Kebab de Pollo, Kebab Al Gusto Y Kebab Falafet</td>
                        <td>22.50€</td>
                        <td>
                            <button class="boton-ver-ticket" onclick="mostrarTicket(1)">Ver Ticket</button>
                        </td>
                    </tr>
                    <!-- Más filas se agregarán dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Modal para mostrar el ticket -->
        <div id="modal-ticket" class="modal">
            <div class="modal-contenido">
                <span class="cerrar" onclick="cerrarModal()">&times;</span>
                <h2>Ticket del Pedido</h2>
                <p id="contenido-ticket">Detalles del ticket aquí...</p>
            </div>
        </div>
    </div>
    <script src="./js/HistorialPedidos.js"></script>
</body>

