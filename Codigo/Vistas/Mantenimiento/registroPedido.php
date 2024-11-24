<link rel="stylesheet" href="./css/CssRegistroPedido.css">
<body>
    <main class="contenedor">
        <section class="seccion-tabla">
            <h2>Pedidos Realizados</h2>

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
                <button class="boton-ver-pdf">Ver PDF de los Pedidos</button>

            </div>

            <table class="tabla-pedidos">
                <thead>
                </thead>
                <tbody>        
                </tbody>
            </table>
        </section>
    </main>
    <script src="./Js/registroPedidos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</body>
