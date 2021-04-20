<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
	<head>
        <meta charset="utf-8">
        
        <title>Colegio | Usuarios</title>
        
        <!--icono de sistema-->
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/usuarios/css/usuarios.css">
        <link rel="stylesheet" type="text/css" href="modulos/usuarios/css/responsivo.css">
        
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
            require_once('classes/Paginator.php');
            
            $colegio = new Colegio();

            $paginator = new Paginator();

            $user = new User();

            $user -> paginator = $paginator;

            $user -> colegio = $colegio;

            $get = "";

            $filter = "";

            $numberRows = 0;   

            $search = "";               

        ?>
            <div class="main-container">
                
                <?php                    

                    require_once('inc/menu.php');

                ?>

                <section class="content">
                  
                    <section class="cabezera letraNegrita letraBlanca borde-10 centrarDiv cabezeraAlumnos">
                        
                        <div class="title-pageWrap">

                            <h2><span class="fas fa-users">&nbsp Usuarios</h2>
                        
                        </div>
                        
                        <div class="search-wrap borderBox">
    
                            <div id="open-addUser" class="header-addBtn cursorPointer fondoAzul-2 letraBlanca letraNegrita alinear-horizontal">
                                    
                                Ingresar nuevo usuario
                                    
                            </div>
                            
                            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="get" name="FormBuscar" id="formBuscar" class="search-form">
                                
                                <input type="search" name="search" placeholder="Ingrese su busqueda" size="20%" id="buscarNombre" class="search-formTxt borde-5 alinear-horizontal">
                                
                                <button type="submit" name="" id="botonBuscar" class="btn-search borde-5 alinear-horizontal cursorPointer">
                                    <span class="fas fa-search btn-searchIcon"></span>
                                </button>
                            
                            </form>

                        </div>

                    </section>

                    <section>

                        <div class="msg-page" id="msg-page"></div>
                        <?php
                        
                            $dataUser = "";
                            
                            if(isset($_POST['add-user'])){
                            
                                $dataUser = $_POST;
                            
                            }

                            $user -> get_add_form($dataUser); 

                            $user -> get_set_form();
                        
                        ?>

                    </section>
                    
                    <section class="contenedorTablaResultados centrarDiv">
                     
                        <table class="tablaResultados">
                            
                            <tr class="letraBlanca letraNegrita"><th>Nombre</th><th>Usuario</th><th>Cedula</th><th>Estado</th><th>Tipo</th><th></th></tr>
                            <?php

                                if(isset($_GET['page'])){
                            
                                    $paginator -> inicio = $_GET['page'];

                                    $paginator -> page = $_GET["page"];
                                    
                                }

                                if(!isset($_GET['search']) &&
                                   !isset($_GET['delete']) &&
                                   !isset($_GET['status'])){

                                    //************mostrar todos los alumnos*******************
                                    
                                    $filter = "all";

                                    $search = $user -> get_all(true);

                                    $numberRows = $user -> get_number_rows($get, "all");

                                    $paginator -> counter = $numberRows["data"];

                                }

                                if(isset($_GET['id']) && isset($_GET['status'])){
                                    
                                    //***************actualizar estado***********************************

                                    $user -> set_status($_GET['status'], $_GET["id"]);

                                    $search = $user -> get_one($_GET["id"]);
                                    
                                }
                                
                                if(isset($_GET['search']) || (isset($_GET['search']) && isset($_GET['page']))){

                                    //*****************busqueda de usuarios***************************

                                    $get = $_GET["search"];

                                    $filter = "name"; 

                                    $search = $user -> get_by_name($get, true);                                

                                    $numberRows = $user -> get_number_rows($_GET["search"], "search");

                                    $paginator -> counter = $numberRows["data"];                                     

                                }

                                if(isset($_GET['delete']) || (isset($_GET['delete']) && isset($_GET['page']))){

                                    //*********************Borrar usuario***********************

                                    $get = $_GET["user"];

                                    $filter = "delete"; 

                                    $del = $user -> del_user($get);

                                    $del = $user -> status_operation("done", $user -> success_msg("Usuario  eliminado correctamente"), "");

                                    if($del["status"] == "done"){

                                        $search = $user -> status_query("info", $user -> success_msg($del["msg"]), "");

                                    }else{

                                        $search = $user -> status_query("error", $user -> danger_msg("Error al editar Usuario"), "");

                                    }                                


                                }  
        
                                if(isset($search["status"]) && $search["status"] == "done"){

                                    //*****************tabla de resultados de busqueda****************
                                    echo "<div class='number-resultsWrap'>Numero de coincidencias encontradas: <span class='number-results'>".$paginator -> counter."</span></div>";

                                    $user -> table($search["data"]); 
                                
                                }else{

                                    if(isset($search["status"])){
                                        
                                        $notice = $user -> info_msg($search["notice"]);
                                    
                                    }else{
                                    
                                        $notice = "";
                                    
                                    }

                                    echo "<div class='number-resultsWrap'><strong>0</strong> coincidencias con su busqueda <strong>$_GET[search]</strong></div>".
                                        $notice;

                                }

                            ?>

                        </table>

                    </section><!--tabla de resultados-->
                    
                    <!--paginador-->
                    <section class='paginator-wrap'>
                        <?php
                            
                            $paginator -> get_paginator($get, $filter, 'Usuarios'); 
                                    

                        ?>
                    </section>
                    
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
        <script src="modulos/usuarios/js/users.js"></script>

    </body>

</html>