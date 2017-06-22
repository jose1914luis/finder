<!DOCTYPE html>
<html>
    <head>

        <title>:: SIGMIN :: Mining Properties</title>
        <?php
        include './Plantillas/head.php';
        ?>
    </head>

    <body class="login_bg">
        <?php echo $msgAcceso; // include_once("analyticstracking.php") ?>
       
        <div class="wrapper">
            <?php
            include './Plantillas/menu.php';
            ?>
            <div class="container">
                <div class="row">

                    <div class="<?= (in_array($ope, ['registro', 'olvide'])) ? "col-lg-8" : "col-lg-5" ?>" style="float: none;margin: 0 auto">
                        <?php
                        if (in_array($ope, ['ingresar', 'registro', 'olvide'])) {

                            include './ingresar.php';
                        }
                        ?>
                    </div>

                </div>
            </div>
            <div class="wrapper_div"></div> <!-- wrapper-->
        </div>

        <?php
        include './Plantillas/foot.php';
        ?>
    </body>
</html>
