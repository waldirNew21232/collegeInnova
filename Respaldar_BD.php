<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        
        <title>Colegio | Respaldar Base de Datos</title>
        
        <!--icono de sistema-->
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/respaldar_bd/css/respaldar_db.css">
        <link rel="stylesheet" type="text/css" href="modulos/respaldar_bd/css/responsivo.css">
        
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
            require_once('classes/PHP_Excel_1.8.0/classes/PHPExcel.php');
            require_once('classes/PHP_Excel_1.8.0/classes/PHPExcel/Reader/Excel2007.php');
            require_once('modulos/salones/classes/Classrooms.php');
            require_once('modulos/cursos/classes/Courses.php');
            require_once('modulos/colegio/classes/Colegio.php');
            require_once('modulos/alumnos/classes/Student.php');
            require_once('classes/Paginator.php');
            
            
            $course = new Courses();
                    
            $classroom = new Classroom();

            $colegio = new Colegio();

            $paginator = new Paginator();

            $student = new Student();

            $student -> paginator = $paginator;

            $colegio -> student = $student;

            $get = "";

            $filter = "";

            $numberRows = 0;   

        ?>
            <div class="main-container">
                
                <?php                    

                    require_once('inc/menu.php');

                ?>

                <section class="content">
                  
                    <section class="cabezera letraNegrita letraBlanca borde-10 centrarDiv cabezeraAlumnos">
                        
                        <div class="title-pageWrap">

                            <h2><span class="fas fa-database"></span>&nbspRespaldar BD</h2>
                        
                        </div>
                        
                        <div class="search-wrap borderBox">
    
                            <form action="Respaldar_BD.php" method="post" name="load-dbForm" id="load-dbForm" class="load-dbForm" enctype="multipart/form-data">
                            
                                <input type="file" class="db-btn load-file" name="excel-fileBtn" style="cursor:pointer;" title="Click en el boton para seleccionar el archivo que se cargara en la base de datos"><br>

                                <input type="submit" name="load-excelFile" class="db-btn load-excelBtn" value="Cargar Respaldo" id="load-excelBtn"><br>

                            </form>

                            <form action="Respaldar_BD.php" method="post" name="get-dbForm" id="get-dbForm" class="get-dbForm">
                            
                                <input type="submit" name="get-dbBtn" class="db-btn get-dbBtn" value="Ver Base de Datos" id="ver"  title="Click en el boton para ver la base de datos"><br>
                            </form>
                            
                            <form action="Respaldos_Excel/RespaldarBDExcel.php" method="post" name="save-dbForm" id="save-dbForm" class="save-dbForm">
                            
                                <input type="submit" name="save-dbBtn" class="db-btn save-dbBtn" value="Generar Respaldo" id="save-dbBtn" title="Click para respaldar la base de datos">
                            </form>     

                        </div>

                    </section>

                    <section>

                        <div class="msg-page" id="msg-page"></div>

                    </section>
                    
                    <section class="contenedorTablaResultados centrarDiv">
                     
                        <table class="tablaResultados">
                            
                            <tr class="letraBlanca letraNegrita"><th>Apellido y Nombre</th><th>Telefono</th><th>Estado</th><th>Salon</th><th>Curso</th></tr>
                            <?php

                                //respaldo en excel
                                if(isset($_GET["backup"])){

                                    if($_GET["backup"]){

                                        echo $student -> success_msg("Respaldo en excel realizado correctamente");

                                    }else{

                                        echo $student -> info_msg("El respaldo no se realizo correctamente");

                                    }

                                }

                                if(isset($_GET['page'])){
                            
                                    $paginator -> inicio = $_GET['page'];

                                    $paginator -> page = $_GET["page"];
                                    
                                }
                                
                                if(isset($_POST['get-dbBtn'])){

                                    //************mostrar todos los alumnos*******************
                                    $filter = "all";

                                    $search = $student -> get_all(true);

                                    $numberRows = $student -> get_number_rows("", "all");

                                    $paginator -> counter = $numberRows["data"];

                                }elseif(isset($_POST['load-excelFile'])){

                                    if(isset($_FILES["excel-fileBtn"])){
                                    
                                        $files = $_FILES["excel-fileBtn"];
                                    
                                    }else{

                                        $files = "";

                                        echo $student -> info_msg("Cargue el archivo de respaldo");

                                    }
                                   
                                    if($files != ""){

                                        $loadDb = $colegio -> load_db($files);

                                        echo $loadDb;

                                    }                                
                                    
                                }else{

                                    echo $student -> info_msg("Respaldo de base de datos");

                                }  
        
                                if(isset($search["status"]) && $search["status"] == "done"){

                                    //*****************tabla de resultados de busqueda****************
                                    echo "<div class='number-resultsWrap'>Numero de coincidencias encontradas: <span class='number-results'>".$paginator -> counter."</span></div>";

                                    echo $student -> table_reports($search["data"]); 
                                
                                }else{

                                    if(isset($search["status"])){

                                        echo $student -> info_msg($search["notice"]);

                                    }else{

                                        $notice = "";                                        

                                    }

                                }

                            ?>

                        </table>

                    </section><!--tabla de resultados-->
                    
                    <!--paginador-->
                    <section class='paginator-wrap'>
                        <?php
                            
                            $paginator -> get_paginator($get, $filter, 'Alumnos'); 
                                    

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
        <script src="modulos/respaldar_bd/js/respaldar_db.js"></script>

    </body>

</html>