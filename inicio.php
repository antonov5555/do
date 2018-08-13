<?php 
session_start();
include "./control/seguridad.php";
$seguridad->checkSession($_SESSION['acceso']);
include "./control/deploy.php";
$userInfo = $_SESSION['informacion'];

?>
<!DOCTYPE HTML>
<html lang="es">

<?php echo $site->displayHeaders(); ?>

<body>

<?php echo $seguridad->getMenu(-1,$userInfo['clave'],0); ?>   

<main role="main" class="container">


<?php echo $site->getUser_apps($userInfo['clave']);  ?>


<br><br><br>	
</main>

<?php echo $seguridad->getFoot($userInfo);  ?>

	
</body>

</html>