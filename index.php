<?php 

session_start();
unset($_SESSION['acceso']);
unset($_SESSION['informacion']);
session_destroy();


$html_error = (isset($_GET['error']) && $_GET['error'] == 'acceso')? '<div class="alert alert-danger" role="alert">Verifique sus credenciales de acceso</div>':'';
$html_error .= (isset($_GET['e']) && $_GET['type'] == 'logIn')? '<div class="alert alert-danger" role="alert">Inicie sesi&oacute;n para acceder al sistema</div>':'';
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GEAS - DO</title>
    <link rel="icon" href="./libs/images/do-icon.png">
    <link href="jquery-ui.css" rel="stylesheet">
    <link href="./libs/estilo.css" rel="stylesheet">
    <link href="./libs/css/indexStyle.css" rel="stylesheet">
    <link href="./libs/css/jquery-ui.theme.css" rel="stylesheet">
    <link href="./libs/css/jquery-ui.css" rel="stylesheet">
    <link href="./libs/css/bootstrap.min.css" rel="stylesheet">
    <link href="./libs/css/bootstrap.css" rel="stylesheet">

    <script src="./libs/jquery.js"></script>
    <script src="./libs/js/jquery-ui.js"></script>
    <script src="./libs/js/jquery-ui.min.js"></script>
    <script src="./libs/js/bootstrap.js"></script>
    <script src="./libs/js/bootstrap.min.js"></script>
    <script src="jquery-ui.js"></script>
<script>
    $( function(){
          
          $("#cveUser").focus();
		  $('#frmLogin')[0].reset();
		  
		$( "#btnSubmit001" ).click(function() {
		  $( "#frmLogin" ).submit();
		});  
		$("#inputPassword").keyup(function(e){ 
			var code = e.which; // recommended to use e.which, it's normalized across browsers
			if(code==13)e.preventDefault();
			if(code==32||code==13||code==188||code==186){
				$( "#frmLogin" ).submit();
			} // missing closing if brace
		});  
		  
    });
</script>
	
</head>

<body class="text-center">

<form class="form-signin" id="frmLogin" name="frmLogin" action="./control/task.php?taskType=T001" method="POST">
      <img class="mb-4" src="https://www.grupoempresarialangeles.com/wp-content/themes/GEA2.0/assets/img/logo.svg" alt="" width="300" height="250">
      <h1 class="h3 mb-3 font-weight-normal">Desarrollo Organizacional</h1>
      <label for="cveUser" class="sr-only">Clave de usuario</label>
      <input type="text" id="cveUser" name="cveUser" class="form-control" placeholder="Clave de usuario" required="" autofocus="">
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required="">
		<br>
		<?php echo $html_error;?>
		<br>
      <a class="btn btn-lg btn-primary btn-block text-light" id="btnSubmit001" name="btnSubmit001">Entrar</a>
      <p class="mt-5 mb-3 text-muted">© GEAS -  Desarrollo Organizacional - 2018 v1.0</p>
    </form>


</body>

</html>