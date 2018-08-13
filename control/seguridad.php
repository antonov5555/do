<?php

class Security
{

    public function checkSession($item)
    {
    if (isset($item) && $item == true) {
    }else{
            header ("Location: ./?e=AccesoFallido&type=logIn&Security");
       exit;
    }

    } // End of Method checkSession
        
    public function getMenu($param,$usr,$app = 0) 
    {
		switch ($app){
			case 0:
				$nombreApp = 'GEAS-DO';
			break;
			case 1:
				$nombreApp = 'gestorXML';
			break;
			case 2:
				$nombreApp = 'cIncidencias';
			break;
			case 3:
				$nombreApp = 'cargaEspecial';
			break;
			/*case 4:
				$opts = array("inicio" => "","subir" => "","ordenar" => "","verError" => "", "verOUT" => "active","procesar" => "");
			break;
			case 4:
				$opts = array("inicio" => "","subir" => "","ordenar" => "","verError" => "", "verOUT" => "","procesar" => "active");
			break;*/
			default:
				$nombreApp = 'GEAS-DO';
			break;
		}// END Switch	
		
		switch ($param){
			case 0:
				$opts = array("inicio" => "active", "subir" => "", "ordenar" => "","verError" => "", "verOUT" => "");
			break;
			case 1:
				$opts = array("inicio" => "","subir" => "active","ordenar" => "","verError" => "", "verOUT" => "","procesar" => "");
			break;
			case 2:
				$opts = array("inicio" => "","subir" => "","ordenar" => "active","verError" => "", "verOUT" => "","procesar" => "");
			break;
			case 3:
				$opts = array("inicio" => "","subir" => "","ordenar" => "","verError" => "active", "verOUT" => "","procesar" => "");
			break;
			case 4:
				$opts = array("inicio" => "","subir" => "","ordenar" => "","verError" => "", "verOUT" => "active","procesar" => "");
			break;
			case 4:
				$opts = array("inicio" => "","subir" => "","ordenar" => "","verError" => "", "verOUT" => "","procesar" => "active");
			break;
			default:
				$opts = NULL;
			break;
		}// END Switch
		switch($usr){
			case 'luis.oviedo':
			case 'gerardo.sanchez':
			case 'adrian.ramirez':
				
				$btnAdmin = '<li class="nav-item">
								<a class="nav-link" href="./admin_do.php">Administraci&oacute;n</a>
							</li>  ';
							
				$btnBlockOpts = '<ul class="navbar-nav mr-auto">'.$btnAdmin;
				$btnBlockOpts .= '</ul>';
			break;
			default:
				$btnAdmin =''; $btnBlockOpts = '<ul class="navbar-nav mr-auto"></ul>';
			break;
		}// END Switch 
		if($param == -1){ 		
                $response = '
				<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
					  <a class="navbar-brand" href="#">'.$nombreApp.'</a>
					  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					  </button>

					  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
						'.$btnBlockOpts.'
						<a href="./" class="btn btn-outline-primary ">Salir del sistema</a>
					  </div>
				</nav>	
				<br><br><br> ';
                
		}
        echo $response;
		
    } // END OF Method getMenu


    public function getFoot($param) {
        
		$html = '<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
					<a class="navbar-brand text-light float-right">'.$param['nombre'].' '.$param['ap_paterno'].'</a>
					 <ul class="navbar-nav mr-auto"></ul>
					<a href="#" class="btn btn-outline-primary ">GEAS-DO</a>
				</nav>';
		echo $html;
		
    } // End Of Method getFoot
    
    
} // End of Class
$seguridad = new Security();



?>