<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KEBAB AL PASO</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/CssHeaders.css">
</head>
<body>
    <header id="header"></header>
    <section>
        <div id="cuerpo">
            <?php
            require_once './Vistas/Principal/enruta.php';
            ?>
        </div>
    </section>
    <?php
    require_once './Vistas/Principal/footer.php';
    ?> 
    <script src="./Js/header.js"></script> <!-- Este será nuestro script dinámico -->
</body>
</html>
