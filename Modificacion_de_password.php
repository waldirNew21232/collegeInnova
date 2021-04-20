<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
	<head>
        <meta charset="utf-8">
        
        <title>Colegio | Modificacion de contraseña</title>
        
        <!--icono de sistema-->
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/usuarios/css/change_password.css">
        <link rel="stylesheet" type="text/css" href="modulos/usuarios/css/responsivo_chp.css">
        
        <!--fontawesone-->
        <link rel="stylesheet" type="text/css" href="css/all.css">
        
   </head>
    <body>
        <?php
        
        if(isset($_SESSION['colegio']['user'])){ 
  
            require_once('classes/config.php');
            require_once('classes/Forms.php');
            require_once('classes/Messages.php');
            require_once('classes/Status.php');
            require_once('classes/Server.php');
            
            require_once('modulos/colegio/classes/Colegio.php');
            require_once('modulos/usuarios/classes/User.php');
            
            $colegio = new Colegio();
            $user = new User();
             

        ?>
            <div class="main-container">
                
                <?php                    

                    require_once('inc/menu.php');

                ?>

                <section class="content">
                  
                    <section class="form-container">
                     
                        <form action="<?php $_SERVER["PHP_SELF"]?>" method="post" id="change-passForm" name="change-passForm"class="change-passForm">

                            <h1><span class="fas fa-key"></span>&nbspCambio de contraseña</h1>
                        
                            <div class="msg-form">
                            
                                <?php

                                    if(isset($_POST['change-passBtn'])){
                                        
                                        //echo var_dump($user);
                                        $data = $user -> change_password($_POST);

                                        echo $data;

                                    }
                                
                                ?>

                            </div>
                                                    
                            <div class="form-controlWrap">
                                <label for="pass" class="label-form">Password Anterior:</label>
                                <input type="password" name="lastPassword" class="form-control pass borde-5" id="pass" placeholder="Ingrese su password anterior" size="20%">
                            </div>

                            <div class="form-controlWrap">
                                <label for="pass" class="label-form">Nuevo Password:</label>
                                <input type="password" name="password" class="form-control pass borde-5" id="pass" placeholder="Ingrese su nuevo password" size="20%">
                            </div>

                            <div class="form-controlWrap">
                                <label for="pass" class="label-form">Repetir Password:</label>
                                <input type="password" name="repeatPassword" class="form-control pass borde-5" id="repeat-pass" placeholder="Repita su password" size="20%">
                            </div>

                            <input type="submit" name="change-passBtn" value="Aceptar" class="btn change-passBtn" id="change-passBtn">
                    
                        </form>

                    </section><!--tabla de resultados-->
                    
                </section>

                <?php

                    //footer
                    require_once("inc/footer.php");
            
                ?>
            
            </div><!--main-container-->
      <?php
		}else{
       		
            header("location:index.php");
       	   
        }
     ?>

        <script src="js/Ajax.js"></script>
        <script src="js/main.js"></script>
        <script src="js/menu.js"></script>

    </body>

</html>