<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
	<head>
        <meta charset="utf-8">
        
        <title>Colegio | Alumnos</title>
        
        <!--icono de sistema-->
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/alumnos/css/alumnos.css">
        <link rel="stylesheet" type="text/css" href="modulos/alumnos/css/responsivo.css">
        
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
            require_once('modulos/alumnos/classes/Student.php');
            require_once('classes/Paginator.php');
            
            $course = new Courses();
                    
            $classroom = new Classroom();

            $colegio = new Colegio();

            $paginator = new Paginator();

            $student = new Student();

            $student -> paginator = $paginator;

            $search = "";

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

                            <h2><span class="fas fa-user-graduate"></span> &nbspAlumnos</h2>
                        
                        </div>
                        
                        <div class="search-wrap borderBox">
    
                            <div id="open-addStudent" class="header-addBtn cursorPointer fondoAzul-2 letraBlanca letraNegrita alinear-horizontal">
                                    
                                Ingresar nuevo alumno
                                    
                            </div>
                            
                            <div id="search-coursesBtn" class="search-coursesBtn cursorPointer letraBlanca letraNegrita alinear-horizontal">
                                    
                                <div class="search-coursesBtnTxt fondoAzul-2" id="searchCoursesBtnTxt">
                            	
                                   Consultar por cursos
                            	
                                </div>
                                
                                <?php 
                                    $getCourses = $course -> get_all();

                                    if($getCourses["status"] = "done"){

                                       echo $course -> menu_search_byCourses($getCourses["data"], "Alumnos"); 
                                    
                                    }else{

                                        echo $getCourses["status"];
                                    
                                    }

                                ?>   
                                
                                
                            </div>
                            
                            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="get" name="FormBuscar" id="formBuscar" class="search-form">
                                
                                <input type="search" name="search" placeholder="ingrese su busqueda" size="20%" id="buscarNombre" class="search-formTxt borde-5 alinear-horizontal">
                                
                                <button type="submit" name="" id="botonBuscar" class="btn-search borde-5 alinear-horizontal cursorPointer">
                                    <span class="fas fa-search btn-searchIcon"></span>
                                </button>
                            
                            </form>

                        </div>

                    </section>

                    <section>

                        <div class="msg-page" id="msg-page"></div>
                        <?php
                        
                            $dataStudent = "";
                            
                            if(isset($_POST['add-student'])){
                            
                                $dataStudent = $_POST;
                            
                            }

                            $student -> get_add_form($dataStudent); 

                            $student -> get_set_form();
                        
                        ?>

                    </section>
                    
                    <section class="contenedorTablaResultados centrarDiv">
                     
                        <table class="tablaResultados">
                            
                            <tr class="letraBlanca letraNegrita"><th>Apellido y Nombre</th><th>Telefono</th><th>Estado</th><th>Salon</th><th>Curso</th><th></th></tr>
                            <?php

                                if(isset($_GET['page'])){
                            
                                    $paginator -> inicio = $_GET['page'];

                                    $paginator -> page = $_GET["page"];
                                    
                                }

                                if(!isset($_GET['curso']) &&
                                   !isset($_GET['search']) &&
                                   !isset($_GET['delete']) &&
                                   !isset($_GET['status'])){

                                    //************mostrar todos los alumnos*******************
                                    $filter = "all";

                                    $search = $student -> get_all(true);

                                    $numberRows = $student -> get_number_rows($get, "all");

                                    $paginator -> counter = $numberRows["data"];

                                }

                                if(isset($_GET['id']) && isset($_GET['status'])){
                                    
                                    //***************actualizar estado***********************************

                                    $student -> set_status($_GET['status'], $_GET["id"]);

                                    $search = $student -> get_one($_GET["id"]);
                                    
                                }

                                if(isset($_GET['curso']) || (isset($_GET['curso']) &&  isset($_GET['page']))){

                                    //**********************consulta por cursos**************************
                                    $get = $_GET["curso"];

                                    $filter = "course"; 

                                    $search = $student -> get_by_courses($get, true);

                                    $numberRows = $student -> get_number_rows($get, "course");

                                    $paginator -> counter = $numberRows["data"];

                                }
                                
                                if(isset($_GET['search']) || (isset($_GET['search']) && isset($_GET['page']))){

                                    //*****************busqueda de alumnos***************************

                                    $get = $_GET["search"];

                                    $filter = "name"; 

                                    $search = $student -> get_by_name($get, true);                                

                                    $numberRows = $student -> get_number_rows($_GET["search"], "search");

                                    $paginator -> counter = $numberRows["data"];                                     

                                }

                                if(isset($_GET['delete']) || (isset($_GET['delete']) && isset($_GET['page']))){

                                    //*****************busqueda de alumnos***************************

                                    $get = $_GET["student"];

                                    $filter = "delete"; 

                                    $del = $student -> del_student($get);

                                    $del = $student -> status_operation("done", $student -> success_msg("Alumno/a  eliminado correctamente"), "");

                                    if($del["status"] == "done"){

                                        $search = $student -> status_query("info", $student -> success_msg($del["msg"]), "");

                                    }else{

                                        $search = $student -> status_query("error", $student -> danger_msg("Error al editar alumno"), "");

                                    }                                


                                }  
        
                                if(isset($search["status"]) && $search["status"] == "done"){

                                    //*****************tabla de resultados de busqueda****************
                                    echo "<div class='number-resultsWrap'>Numero de coincidencias encontradas: <span class='number-results'>".$paginator -> counter."</span></div>";

                                    $student -> table($search["data"]); 
                                
                                }else{

                                    if(isset($search["notice"])){
                                        
                                        $notice = $student -> info_msg($search["notice"]);
                                    
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
        <script src="modulos/alumnos/js/add_student.js"></script>

    </body>

</html>