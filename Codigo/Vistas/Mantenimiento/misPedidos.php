<link rel="stylesheet" href="./css/CssVerPedidos.css">
<body>
    <main class="contenedor">
        <section class="seccion-tabla">
            <h2>Mis Pedidos</h2>

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

            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Estado</th>
                        <th>Precio Total</th>
                        <th>Fecha y Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-pedidos-body">
                    <!-- Los pedidos se cargarán dinámicamente aquí -->
                </tbody>
            </table>
        </section>
    </main>
    <script src="./Js/verPedidos.js"></script>
</body>
