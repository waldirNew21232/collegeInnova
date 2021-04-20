<?php  

    session_start();

?>
<!doctype html>
<html lang="es">
    <head>

        <meta charset="utf-8">

        <title>Colegio | Home</title>
        
        <!--icono de sistema-->
        <link rel="shortcut icon" href="ico/Iconshock-Real-Vista-Transportation-School-bus.ico">
        
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="css/principal.css">
        <link rel="stylesheet" type="text/css" href="css/responsivo.css">
        
        <!--fontawesone-->
        <link rel="stylesheet" type="text/css" href="css/all.css">
        
        <!--tipo de fuente-->
        <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    
    </head>
    <body>
        
        <?php
            
            if(isset($_SESSION['colegio']['user'])){ 
				
                require_once("classes/Messages.php");
                require_once("classes/Status.php");
                require_once("classes/config.php");
                require_once("classes/Server.php");
                require_once("classes/Forms.php");
                require_once("modulos/colegio/classes/Colegio.php");

                $colegio = new Colegio();
        
        ?>

        <div class="main-container">           
        	
            <?php
            	
                require_once('inc/menu.php');
        	
            ?>

            <section class="content">

                <div class="portada">
                    
                    <div class="portada-item">
                        
                        <div class="tituloColegio">
                            
                            <span class="fas fa-graduation-cap"></span>    
                            <span>Bienvenidos</span>
                            <span><?php echo  $_SESSION['colegioInfo']['name'];?></span>
                        
                        </div>
                        
                    </div>

                    <div class="portada-item">
                    
                        <div class="estadisticas alinear-horizontal borde-10">
                            
                            <?php 
                                    
                                $data = $colegio -> get_statistics_values();

                                $statistics = $data["data"];

                                $studentsNumber = $statistics["studentsNumber"];

                                $coursesNumber = $statistics["coursesNumber"];

                                $classroomsNumber = $statistics["classroomsNumber"];

                            ?>
                            <div class="estadistica-item">
                                <div class="esta borde-5">
                                	Alumnos Registrados
                                </div>
                                <div class="numero borde-5">
        							<?php echo $studentsNumber; ?>
                               	</div>
                            </div>
                            <div class="estadistica-item">
                                <div class="esta borde-5">
                                	Numero de salones Registrados
                                </div>
                                <div class="numero borde-5 ">
        							<?php echo $classroomsNumber;?>
                                </div>
                            </div>
                            <div class="estadistica-item">
                                <div class="esta borde-5">
                                	Numero de cursos Registrados
                                </div>
                                <div class="numero borde-5">
        							<?php echo $coursesNumber;?>
                                </div>
                            </div>
                        </div>
                        <div class="clearFix"></div>
                    
                    </div>

                </div>

            </section>
            <?php
            
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
    
    </body>
</html>