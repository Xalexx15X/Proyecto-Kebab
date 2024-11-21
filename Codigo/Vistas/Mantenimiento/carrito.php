<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulación de Ticket</title>
    <link rel="stylesheet" href="./css/CssCarrito.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Carrito</h2>

        <!-- Ticket de Pedido -->
        <div class="div3">
            <div class="table-container">
                <div class="table-header">Tickets</div>
                <div class="table-body" id="ticketCarrito">
                    <!-- Aquí se inyectarán las filas con los kebabs -->
                </div>
            </div>
        </div>


        <!-- Selección de Dirección -->
        <div class="form-group">
            <label for="direccionUsuario">Elige tu dirección de entrega:</label>
            <select id="direccionUsuario" class="form-control">
                <!-- Opciones cargadas dinámicamente -->
            </select>
        </div>

        <!-- Total a Pagar -->
        <div class="form-group">
            <label for="importePagar">Total a Pagar:</label>
            <input type="text" id="importePagar" class="form-control" value="0.00" readonly>
        </div>

        <!-- Crédito Actual -->
        <div class="form-group">
            <label for="creditoActual">Crédito Actual:</label>
            <input type="text" id="creditoActual" class="form-control" value="0.00" readonly>
        </div>

        <!-- Añadir Crédito -->
        <div class="form-group">
            <label for="añadirCredito">Añadir Crédito:</label>
            <div class="input-group">
                <input type="number" id="añadirCredito" class="form-control" placeholder="Ingrese cantidad">
                <button type="button" class="btn btn-primary" onclick="añadirCredito()">Añadir</button>
            </div>
        </div>

        <!-- Botón Tramitar Pedido -->
        <div class="form-group">
            <button type="button" class="btn btn-success btn-block" onclick="tramitarPedido()">Tramitar Pedido</button>
        </div>

        <!-- Crédito Restante -->
        <div class="form-group">
            <label for="creditoFinal">Crédito Después de Tramitar:</label>
            <input type="text" id="creditoFinal" class="form-control" value="0.00" readonly>
        </div>
    </div>

    <script src="./Js/linea_pedido.js"></script>
</body>
</html>
