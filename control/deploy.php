<?php


class webSite
{
    // property declaration
	public $_charSet = 'utf-8';
	public $_tagTitle = 'GEAS - SDO';
     
	// Basic headers 

    // method declaration
public function displayHeaders() 
    {
		$tagHeaders = 
			'<head>
				<meta charset="'.$this->_charSet.'">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>'.$this->_tagTitle.'</title>
				<link href="jquery-ui.css" rel="stylesheet">
				<link href="./libs/estilo.css" rel="stylesheet">
				<link href="./libs/css/jquery-ui.theme.css" rel="stylesheet">
				<link href="./libs/css/mainStyle.css" rel="stylesheet">
				<link href="./libs/css/jquery-ui.css" rel="stylesheet">
				<link href="./libs/css/bootstrap.min.css" rel="stylesheet">
				<link href="./libs/css/bootstrap.css" rel="stylesheet">
                <link rel="icon" href="./libs/images/do-icon.png">
				<!-- Dev -->
				<script src="./libs/jquery.js"></script>
				<script src="./libs/js/jquery-ui.js"></script>
				<script src="./libs/js/jquery-ui.min.js"></script>
				<script src="./libs/js/bootstrap.js"></script>
				<script src="./libs/js/bootstrap.min.js"></script>
				<script src="jquery-ui.js"></script>
			</head>';
        return $tagHeaders;
    }

public function getUser_apps($user)
{
	include "./modulo/BD_class.php";
	$htmlApps = $bd->apps_user($user);
	echo ($htmlApps);
	//exit;
	
	
} // END Of Method getMyApps

public function getResults($p, $s, $c, $usr, $nl = '#')
{
	
	switch($usr){
		case 'luis.oviedo':
		case 'USR004':
		case 'USR001':
			$btnLog = '<a href="./logs_xml" class="btn btn-primary">Log de Archivos</a>';
		break;
		default:
			$btnLog = '';
		break;
	}
	
	switch ($p){
		
		case 'ok':
		//var_dump($p);exit;
			$html = '<div class="row">
						<div class="col-lg-1"></div>
						<div class="col-lg-10">
							<div class="card mb-9 box-shadow">
								<div class="card-body alert-success">
								  <p class="card-text">Total de archivos seleccionados: '.$s.'<br>
								  Total de archivos copiados:  '.$c.'<br>
								  </p>
								  <div class="d-flex justify-content-between align-items-center">
									
									  '.$btnLog.'
									  <a href="./files/logs/'.$nl.'" target="_new" class="btn btn-info ">Ver XML copiados</a>
									  <a href="./verError"  target="_new" class="btn btn-warning ">Ver XML ERRROR</a>
									  <a href="https://pegasotecnologiacfdi.net/AngelesNominaPROD/" target="_new" class="btn btn-success">Ir a Pegaso</a>
									
									<small class="text-muted">'.date('d-m-Y, g:i a').'</small>
								  </div>
								</div>
							</div>
						</div>
						<div class="col-lg-1"></div>
					</div>';
					return $html;
		
		break;
		case 'error':
		
		break;
	} // END SWITCH
	
}// End Of Method	getResults
    
    
} // End of Class webSite

$site = new webSite();


?>