<?php  

    require_once('classes/config.php');
    require_once('classes/Forms.php');
    require_once('classes/Messages.php');
    require_once('classes/Request.php');
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

    $student = new Student();

    $paginator = new Paginator();

    $student -> paginator = $paginator;

    // esto le indica al navegador que muestre el diálogo de descarga aún sin haber descargado todo el contenido
    header("Content-type: application/octet-stream");
    //indicamos al navegador que se está devolviendo un archivo
    header("Content-Disposition: attachment; filename=Reporte.xls");
    //con esto evitamos que el navegador lo grabe en su caché
    header("Pragma: no-cache");
    header("Expires: 0");
    //damos salida a la tabla


?>


<!doctype html>
<html lang="es">
    <head>

        <meta charset="utf-8">
        
        <title>Reportes</title>
        
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        
       
        <style type="text/css">
        
            .contenedorTablaResultados{
                border:  solid 1px #E0E0E0;
                width: 100%;
                height: auto;
                min-height: 70vh;
            }
            .tablaResultados{
                text-align:center;
                width:100%;
            }
            .tablaResultados td a{
                text-decoration:none;
            }
            .tablaResultados th{
                width: 10%;
                padding: 0.5%;
                background-color: #FF8E5B;
            }
            .tablaResultados td{
                border-bottom: solid 1px #00CC99;
            }
            .tablaResultados .s,
            .tablaResultados .n,{
                border: solid 1px white;
                box-sizing: border-box;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 100%;
                width: 90px;
                height: 30px;
                margin: 0px auto;
                padding: 3px;
                
            }
            .tablaResultados .s,
            .tablaResultados .n,{
                color: ;
            }
            .tablaResultados .s{
                color:#060;  
            }
            .tablaResultados .n{
                color:#900;
            }
            

        </style>
    
    </head>
    <body>
        <table class="tablaResultados">
            <tr class="letraBlanca letraNegrita"><th>Apellido y Nombre</th><th>Telefono</th><th>Estado</th><th>Salon</th><th>Curso</th></tr>
                    
        <?php  

            extract($_POST);
            
            if($student -> forms -> empty_data($get)){

                switch ($filter) {
                    case 'course':
                        
                            //**********************consulta por cursos**************************
                        
                            $search = $student -> get_by_courses($get);

                    break;

                    case 'classRoom':

                            //**********************consulta por cursos*************************/

                            $search = $student -> get_by_classroom($get);

                    break;

                    case 'status':

                            //**********************consulta por cursos*************************/

                            $search = $student -> get_by_status($get);

                    break;
                    
                    default:

                        echo "Opcion no reconocida para reportes";
                    
                    break;

                }

            }

            if(isset($search)){

               if(isset($search["status"]) && $search["status"] == "done"){

                    //*****************tabla de resultados de busqueda****************

                    $table = $student -> table_reports($search["data"]); 

                    echo $table;
                    
                }else{

                    if(isset($search["status"])){

                        $notice = $student -> switch_status_query($search["status"], "", 
                                                        $student -> info_msg("Sin datos que mostrar"),
                                                       $student -> error_msg("Error en busqueda"));    
                        echo $notice;

                    }
                    
                }  
            
            }else{

                echo "Sin busqueda";

            }
        
        ?>
        </table>
    </body>
 </html>