<?php
if($_POST){
    header('Location:inicio.php');
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    if($usuario == 'admin' && $password == 'admin'){
        session_start();
        $_SESSION['usuario'] = 'admin';
        header('Location:inicio.php');
    }else{
        echo 'Usuario o contraseña incorrectos';
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                
            </div>
            
            <div class="col-md-4">
                <br/>
                <br/>
                <div class="card">
                    <div class="card-header">
                       Login
                    </div>
                    <div class="card-body">
                        <form method="POST">
                        <div class = "form-group">
                        <label for="exampleInputEmail1">Usuario</label>
                        <input type="email" class="form-control" name="usuario" aria-describedby="emailHelp" placeholder="Escribe tu usuario">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Escribe tu contraseña">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Sign In</button>
                        </form>
                        
                        

                    </div>
    
                </div>
            </div>
            
        </div>
    </div>
  </body>
</html>