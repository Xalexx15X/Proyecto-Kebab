<link rel="stylesheet" href="./css/CssLogin.css">
<div class='w-50 p-3 container'>
    <div class='login-form'>
        <form action='' method='post' novalidate>
            <h2 class='text-center'>Identifícate</h2>
            <div class='form-group'>
                <input type='text' class='form-control' name='usuario' placeholder='Usuario' required='required'>
            </div>
            <div class='form-group'>
                <input type='password' class='form-control' name='contrasena' placeholder='Contraseña' required='required'>
            </div>
            <div class='form-group'>
                <button type='submit' name='submit' class='btn btn-primary btn-block'>Iniciar Sesión</button>
            </div>
            <div class='clearfix'>
                <label class='pull-left checkbox-inline'>
                    <input type='checkbox' name='recuerdame'> Recuérdame
                </label>
            </div>
        </form>
        <p class='text-center'><a href='#'>Crear una Cuenta</a></p>
    </div>
</div>
