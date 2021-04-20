<?php  

    session_start();
                            
?>
<!doctype html>
<html lang="es">
    <head>
    
        <meta charset="utf-8">
    
        <title>Colegio | Login</title>
    
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">

        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    
    </head>
    <body>
        <?php 

            require_once("classes/Messages.php");
            require_once("classes/Status.php");
            require_once("classes/config.php");
            require_once("classes/Server.php");
            require_once("classes/Forms.php");
            require_once("modulos/colegio/classes/Colegio.php");
            require_once("classes/Login.php");

            $colegio = new Colegio();

            $getData = $colegio -> get_info();

            $_SESSION['colegioInfo']['name'] = "vacio";

            if($getData["status"]){

                $data = mysqli_fetch_assoc($getData["data"]);

                $_SESSION['colegioInfo']['name'] = $data['nombre'];
            
            }


        ?>
        <div class="contenedorPrincipalIndex">
            <div class="contenedorInicio centrarDiv borde5 fondoBlanco">
                
                <div class="titulo">

                    <?php  

                        echo $_SESSION['colegioInfo']['name'];
                    
                    ?>
                
                </div>
                
                <form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="inicio borde-5 bordeSolido-1 centrarDiv" id="inicio" name="forminicio">

                        <h1>Login</h1>
                        
                        <div class="msg-form">
                        
                        <?php 
                            
                            $login = new Login();

                            $loginUser = "";

                            if(isset($_POST['login'])){
                               
                                $loginUser = $login -> login($_POST['user'], $_POST['password']);
                                
                                echo $loginUser["notice"];

                            }
                        
                        ?>
                        </div>
                        
                        <div class="form-controlWrap">
                            <label for="usuario" class="label-form">Usuario:</label>
                            <input type="text" name="user" class="form-control borde-5" id="usuario" 	placeholder="Ingrese su usuario" size="20%">
                        </div>
                        
                        <div class="form-controlWrap">
                            <label for="pass" class="label-form">Password:</label>
                            <input type="password" name="password" class="form-control pass borde-5" id="pass" placeholder="Ingrese su password" size="20%">
                        </div>
                        
                        <input type="submit" name="login" value="Login" class="btn-login letraNegrita letraBlanca fondoAzul-2 cursorPointer borde-5" id="login">
                    
                </form>

            </div><!--contenedorInicio-->
        </div><!--contenedorPrincipal-->
    </body>
</html>