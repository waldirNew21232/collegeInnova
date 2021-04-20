<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
    <head>
        
        <meta charset="utf-8">
        
        <title>Colegio | Salones</title>
        
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/salones/css/salones.css">
        <link rel="stylesheet" type="text/css" href="modulos/salones/css/responsivo.css">

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
            require_once('modulos/salones/classes/Classrooms.php');
            require_once('modulos/cursos/classes/Courses.php');
            require_once('modulos/colegio/classes/Colegio.php');
            require_once('classes/Paginator.php');

            $course = new Courses();
                    
            $classroom = new Classroom();

            $colegio = new Colegio();

            $paginator = new Paginator();

            $classRoom = new Classroom();

            $classRoom -> paginator = $paginator;

            $busqueda = "";

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
                        
                        <div class="title-pageWrap alinear-horizontal">

                            <h2><span class="fas fa-school"></span>&nbspSalones</h2>
                        
                        </div>
                        
                        <div class="search-wrap alinear-horizontal borderBox">
    
                            <div id="open-addCr" class="header-addBtn cursorPointer fondoAzul-2 letraBlanca letraNegrita alinear-horizontal">
                                    
                                Agregar Salon
                                    
                            </div>
                            
                            <div id="search-coursesBtn" class="search-coursesBtn cursorPointer letraBlanca letraNegrita alinear-horizontal">
                                    
                                <div class="search-coursesBtnTxt fondoAzul-2" id="searchCoursesBtnTxt">
                                
                                   Consultar por cursos
                                
                                </div>
                                
                                <?php 
                                    $getCourses = $course -> get_all();

                                    if($getCourses["status"] = "done"){

                                       echo $course -> menu_search_byCourses($getCourses["data"], "Salones"); 
                                    
                                    }else{

                                        echo $getCourses["status"];
                                    
                                    }

                                ?>   
                                
                                
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
                        
                            $dataClassRoom = "";
                            
                            if(isset($_POST['add-cr'])){
                            
                                $dataClassRoom = $_POST;
                            
                            }

                            $classRoom -> get_add_form($dataClassRoom); 

                            $classRoom -> get_set_form();
                        
                        ?>

                    </section>
                    
                    <section class="contenedorTablaResultados centrarDiv">
                     
                        <table class="tablaResultados">
                            
                            <tr class="letraBlanca letraNegrita"><th>Nombre</th><th>Estado</th><th>Curso</th><th></th></tr>
                            <?php

                                if(isset($_GET['page'])){
                            
                                    $paginator -> inicio = $_GET['page'];

                                    $paginator -> page = $_GET["page"];
                                    
                                }

                                if(!isset($_GET['curso']) &&
                                   !isset($_GET['search']) &&
                                   !isset($_GET['delete']) &&
                                   !isset($_GET['status'])){

                                    //************mostrar todos los salones*******************
                                    $filter = "all"; 

                                    $search = $classRoom -> get_all(true);

                                    $numberRows = $classRoom -> get_number_rows($get, "all");

                                    $paginator -> counter = $numberRows["data"];

                                }

                                if(isset($_GET['id']) && isset($_GET['status'])){
                                    
                                    //***************actualizar estado***********************************

                                    $classRoom -> set_status($_GET['status'], $_GET["id"]);

                                    $search = $classRoom -> get_one($_GET["id"]);
                                    
                                }

                                if(isset($_GET['curso']) || (isset($_GET['curso']) &&  isset($_GET['page']))){

                                    //**********************consulta por cursos**************************
                                    $get = $_GET["curso"];

                                    $filter = "course"; 

                                    $search = $classRoom -> get_by_courses($get, true);

                                    $numberRows = $classRoom -> get_number_rows($get, "course");

                                    $paginator -> counter = $numberRows["data"];

                                }
                                
                                if(isset($_GET['search']) || (isset($_GET['search']) && isset($_GET['page']))){

                                    //*****************busqueda de salones***************************

                                    $get = $_GET["search"];

                                    $filter = "name"; 

                                    $search = $classRoom -> get_by_name($get, true);                                

                                    $numberRows = $classRoom -> get_number_rows($_GET["search"], "search");

                                    $paginator -> counter = $numberRows["data"];                                     

                                }

                                if(isset($_GET['delete']) || (isset($_GET['delete']) && isset($_GET['page']))){

                                    //*****************busqueda de salones***************************

                                    $get = $_GET["id"];

                                    $filter = "delete"; 

                                    $del = $classRoom -> del_classroom($get);

                                    $del = $classRoom -> status_operation("done", $classRoom -> success_msg("Salon eliminado correctamente"), "");

                                    if($del["status"] == "done"){

                                        $search = $classRoom -> status_query("info", $classRoom -> success_msg($del["msg"]), "");

                                    }else{

                                        $search = $classRoom -> status_query("error", $classRoom -> danger_msg("Error al editar salon"), "");

                                    }                                


                                }  
        
                                if(isset($search["status"]) && $search["status"] == "done"){

                                    //*****************tabla de resultados de busqueda****************
                                    echo "<div class='number-resultsWrap'>Numero de coincidencias encontradas: <span class='number-results'>".$paginator -> counter."</span></div>";

                                    $classRoom -> table($search["data"]); 
                                
                                }else{

                                    if(isset($search["notice"])){
                                        
                                        $notice = $classRoom -> info_msg($search["notice"]);
                                    
                                    }else{
                                    
                                        $notice = "";
                                    
                                    }

                                    echo "<div class='number-resultsWrap'>0 coincidencias con su busqueda</div>".$notice;

                                }

                            ?>

                        </table>

                    </section><!--tabla de resultados-->
                    
                    <!--paginador-->
                    <section class='paginator-wrap'>
                        <?php
                            
                            $paginator -> get_paginator($get, $filter, 'Salones'); 
                                    

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
        <script src="modulos/salones/js/classRoom.js"></script>

    </body>

</html>