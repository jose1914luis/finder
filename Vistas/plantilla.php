<!DOCTYPE html>
<html>
    <head>
         <?php
        include './Plantillas/head.php';
        ?>
        <script src="./Javascript/plantilla.js?v=<?=$_VERSION?>" type="text/javascript"></script>
        <link rel="stylesheet" href="Javascript/sigmin_account.css?v=<?=$_VERSION?>">
        <!--<script type="text/javascript" src="Javascript/validarFinder.js?v=3"></script>-->
        <title>:: SIGMIN :: Mining Properties</title>
       
    </head>

    <body class="<?= (isset($_SESSION['id_usuario']))?'':'login_bg' ?>">
        <?php // echo $msgAcceso; // include_once("analyticstracking.php") ?>

        <div class="wrapper">
            <?php
            include './Plantillas/menu.php';
            ?>
            <div class="container">
                <div id="creditos" style="display:none"></div>
                <?php
//                            echo $paginaCargue;
                if ($paginaCargue != "reporteAreasAccount.php")
                    include("Vistas/" . $paginaCargue);
                else
                    include("reporteAreasAccount.php");
                ?>
            </div>
            <div class="wrapper_div"></div> <!-- wrapper-->
        </div>

        <?php
        include './Plantillas/foot.php';
        ?>
    </body>
</html>
