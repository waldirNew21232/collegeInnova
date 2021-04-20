<?php   

     session_start();
            

?>
<!doctype html>
<html lang="es">
    <head>

        <meta charset="utf-8">
        
        <title>Colegio | Reportes</title>
        
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="modulos/reportes_alumnos/css/reportes.css">
        <link rel="stylesheet" type="text/css" href="modulos/reportes_alumnos/css/responsivo.css">

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

            $url = "";

            $filter = "";

            $numberRows = 0;   

            $table = ""; 

            $search = "";

            $searchBy = "";


        ?>
        
        <div class="main-container">
            
            <header>
                <?php

                	require_once('inc/menu.php');
				
                ?>
            </header>

            <section class="content">
                
                <div class="cabezera letraNegrita letraBlanca borde-10 centrarDiv">
                    
                    <div class="title-pageWrap">
                        
                        <h3><span class="fas fa-user-graduate"></span>&nbspReportes de alumnos</h3>
                    
                    </div>
                    
                    <div class="search-wrap borderBox">
                        
                        <form action="Reportes.php" method="get" name="form-reports" id="form-reports" class="form-reports borderBox">
                            
                            <div class="reports-optionsWrap">
                                
                                <label for="options" class="label-options letraNegrita letraBlanca" id="label-options">Reporte de alumnos por:</label>
                                <select name="report-options" id="report-options" class="reports-options letraAzul-2">
                                    <option value="0">Seleccione una opcion</option>
                                    <option value="course">Curso</option>
                                    <option value="classRoom">Salon</option>
                                    <option value="status">Estado</option>
                                    <option value="all">Todos</option>
                                </select>

                                <div class="options-space">
                                    
                                    <select name="courses-list" id="courses-list" class="report-option letraAzul-2 hide">
                                        <?php

                                            echo $course -> get_select_list();
                                            
                                        ?>
                                    </select>
                                    <select name="classRoom" id="classRoom" class="report-option letraAzul-2 hide">
                                        <?php

                                            echo $classroom -> get_select_list();
                                            
                                        ?>
                                    </select>
                                    <select name="status" id="status" class="report-option letraAzul-2  hide">
                                        <option value="">Seleccione un Estado</option>
                                        <option value="s">Activo</option>
                                        <option value="n">Inactivo</option>
                                    </select>

                                </div>

                                <input type="submit" name="show-report" id="report-btn" class="report-btn" value="Ver Reporte">
                                
                            </div><!--opcionesReporte-->
                        
                        </form>
        
                        <form action="modulos/alumnos/alumnos_exel.php" method="post" name="do-reportForm" id="do-reportForm" class="do-reportForm">
                            
                            <input type="submit" name="save-report" value="Guardar Reporte" id="guardar" class="save-btn">
                        
                        </form>
                         
                    </div><!--consultas-->

                </div><!--cabezera-->
                
                <section class="contenedorTablaResultados centrarDiv scroll">
                    
                    <table class="tablaResultados">
                        <tr class="letraBlanca letraNegrita"><th>Apellido y Nombre</th><th>Telefono</th><th>Estado</th><th>Salon</th><th>Curso</th></tr>
                        <?php

                            //paginador
                            if(isset($_GET['page'])){
                            
                                $paginator -> inicio = $_GET['page'];

                                $paginator -> page = $_GET["page"];
                                
                            }

                            //busqueda
                            if(isset($_GET['show-report'])){

                                if($_GET['report-options'] == "0"){

                                    echo $student -> info_msg("Seleccione una opcion de reporte");
                              
                                }else if($_GET['report-options'] == "course"){
                                    
                                    //**********************consulta por cursos**************************
                                    
                                    if($_GET['courses-list'] == "0"){

                                        echo $student -> info_msg("Seleccione un curso");

                                    }else{

                                        $search = $_GET['courses-list'];//el curso seleccionado

                                        $searchBy = "course";

                                        $url = "&report-options=$searchBy&courses-list=$search&classRoom=&status=&show-report=Ver+Reporte";//parametros para paginador

                                        $filter = "reports";//filtro por curso para paginador

                                        $getData = $student -> get_by_courses($search, true);

                                        $numberRows = $student -> get_number_rows($search, $searchBy);

                                        $paginator -> counter = $numberRows["data"];

                                    }
                                    


                                }else if($_GET['report-options'] == "classRoom"){
                                    
                                    //**********************alumnos por salon**************************
                                    if($_GET['classRoom'] == ""){

                                        echo $student -> info_msg("Seleccione un salon");

                                    }else{

                                        $search = $_GET["classRoom"];//el curso seleccionado

                                        $searchBy = "classRoom";

                                        $url = "&report-options=$searchBy&courses-list=&classRoom=$search&status=&show-report=Ver+Reporte";//parametros para paginador

                                        $filter = "reports";//filtro por curso para paginador 

                                        $getData = $student -> get_by_classroom($search, true);

                                        $numberRows = $student -> get_number_rows($search, $searchBy);

                                        $paginator -> counter = $numberRows["data"];

                                    }

                                }else if($_GET['report-options'] == "status"){

                                    //**********************alumnos por estado**************************
   
                                    if($_GET['status'] == ""){

                                        echo $student -> info_msg("Seleccione un estado");

                                    }else{

                                        $search = $_GET["status"];//el curso seleccionado

                                        $searchBy = "status";

                                        $url = "&report-options=$searchBy&courses-list=&classRoom=&status=$search&show-report=Ver+Reporte";//parametros para paginador

                                        $filter = "reports";//filtro por curso para paginador
                                        
                                        $getData = $student -> get_by_status($search, true);

                                        $numberRows = $student -> get_number_rows($search, $searchBy);

                                        $paginator -> counter = $numberRows["data"];

                                    }


                                }else if($_GET['report-options'] == "all"){
    
                                    //**********************todos los alumnos**************************
                                    $searchBy = "all";

                                    $search = "all";                                    

                                    $url = "&report-options=all&course&courses-list=&classRoom=&status=&show-report=Ver+Reporte";

                                    $filter = "reports"; 

                                    $getData = $student -> get_all(true);

                                    $numberRows = $student -> get_number_rows("", $searchBy);

                                    $paginator -> counter = $numberRows["data"];

                                }else{

                                    return;

                                } 


                            }else if(isset($_POST['save-report'])){
                                //
                                

                            }else{

                                echo $student -> info_msg("Seleccione las  opciones necesarias para ver y guardar el reporte");

                            }


                            if(isset($getData["status"]) && $getData["status"] == "done"){

                                    //*****************tabla de resultados de busqueda****************
                                echo "<div class='number-resultsWrap'>Numero de coincidencias encontradas: <strong><span class='number-results'>".$paginator -> counter."</span></strong></div>";

                                    $table = $student -> table_reports($getData["data"]); 

                                    echo $table;
                                
                            }else{

                                if(isset($getData["status"])){

                                    echo $getData["notice"];

                                }
                                
                            }

                        ?>
                        <input type="hidden" form="do-reportForm" name="searchBy" value="<?php echo $searchBy; ?>" id="" class="">

                        <input type="hidden" form="do-reportForm" name="search" value="<?php echo $search; ?>" id="" class="">
                                   
                    </table>

                </section><!--tabla-->

                <!--paginador-->
                <section class='paginator-wrap'>    
                    <?php
                        
                        $paginator -> get_paginator($url, $filter, 'Reportes');                           

                    ?>
                </section>

            </section>
            
            <?php

                //footer
                require_once("inc/footer.php");
            
            ?>

        </div>

        <?php
        }else{
            
            header("location:index.php");
           
        }
     ?>

        <script src="js/Ajax.js"></script>
        <script src="js/main.js"></script>
        <script src="js/menu.js"></script>
        <script src="modulos/reportes_alumnos/js/student_reports.js"></script>

    </body>
</html>