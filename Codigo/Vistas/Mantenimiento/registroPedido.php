<link rel="stylesheet" href="./css/CssRegistroPedido.css">
<body>
    <main class="contenedor">
        <!-- Tabla de pedidos -->
        <section class="seccion-tabla">
            <h2>Pedidos Realizados</h2>
            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Número de Pedido</th>
                        <th>Cliente</th>
                        <th>Total (€)</th>
                        <th>Desglose</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se rellenarán dinámicamente -->
                    <tr>
                        <td>2024-11-01</td>
                        <td>12345</td>
                        <td>Juan Pérez</td>
                        <td>45.50</td>
                        <td><button class="boton-ver-detalle" onclick="verDetallePedido(12345)">Ver Detalle</button></td>
                    </tr>
                    <tr>
                        <td>2024-11-02</td>
                        <td>12346</td>
                        <td>Ana López</td>
                        <td>32.75</td>
                        <td><button class="boton-ver-detalle" onclick="verDetallePedido(12346)">Ver Detalle</button></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Sección de gráficos -->
        <section class="seccion-graficos">
            <h2>Gráficos de Ventas Mensuales</h2>
            <div class="contenedor-graficos">
                <!-- Gráfico de Ventas -->
                <div class="grafico-contenedor">
                    <div class="grafico" id="grafico-ventas">
                        <!-- Aquí se generará el gráfico -->
                        <p>Gráfico de ventas mensuales</p>
                    </div>
                    <p class="texto-grafico">Gráficos de ventas mensuales</p>
                </div>

                <!-- Gráfico de Tipos de Kebab -->
                <div class="grafico-contenedor">
                    <div class="grafico" id="grafico-tipos-kebab">
                        <!-- Aquí se generará el gráfico -->
                        <p>Gráfico de tipos de kebab</p>
                    </div>
                    <p class="texto-grafico">Gráficos por tipos de kebab</p>
                </div>

                <!-- Gráfico de Ingredientes -->
                <div class="grafico-contenedor">
                    <div class="grafico" id="grafico-ingredientes">
                        <!-- Aquí se generará el gráfico -->
                        <p>Gráfico de ingredientes</p>
                    </div>
                    <p class="texto-grafico">Gráficos por ingredientes</p>
                </div>
            </div>
        </section>
    </main>
    <script src="./Js/RegistroPedidos.js"></script>
</body>
