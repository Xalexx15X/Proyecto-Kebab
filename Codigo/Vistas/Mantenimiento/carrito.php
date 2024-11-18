<link rel="stylesheet" href="./css/CssCarrito.css">
<body>
    <div class="container">
        <h2 class="text-center">Carrito de Compras</h2>

        <!-- Importe a Pagar -->
        <div class="form-group">
            <label for="importePagar">A Pagar:</label>
            <div class="col-sm-10">
                <input type="text" id="importePagar" class="form-control" value="0.00" readonly>
            </div>
        </div>

        <!-- Crédito Actual -->
        <div class="form-group">
            <label for="creditoActual">Crédito Actual:</label>
            <div class="col-sm-10">
                <input type="text" id="creditoActual" class="form-control" value="0.00" readonly>
            </div>
        </div>

        <!-- Botón Añadir Crédito -->
        <div class="form-group">
            <button type="button" class="btn btn-outline-primary" onclick="añadirCredito()">+ Añadir Crédito</button>
        </div>

        <!-- Botón Tramitar Pedido -->
        <div class="form-group">
            <button type="button" class="btn btn-success" onclick="tramitarPedido()">Tramitar Pedido</button>
        </div>

        <!-- Crédito Después de Tramitar -->
        <div class="form-group">
            <label for="creditoFinal">Crédito Después de Tramitar:</label>
            <div class="col-sm-10">
                <input type="text" id="creditoFinal" class="form-control" value="0.00" readonly>
            </div>
        </div>
    </div>
</body>
