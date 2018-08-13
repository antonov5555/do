<?php

	//var_dump($_REQUEST);
	// Recibe las peticiones 
if(isset($_REQUEST['taskType'])){
	
	// Se define la Tarea en Cuestión
	$taskType = $_REQUEST['taskType'];
	
	// Con respecto a la comparación switch 
	//se ejecutará la tarea deseada
    switch ($taskType) {
		
    case 'T001': // CASE:  login iniciar sesion 
	
		//Se incluyen configuraciones
		//include "./../modulo/configs.php";
		include "./../modulo/BD_class.php";
		//$ip = $bd->get_ip();
		$u = $_REQUEST['cveUser'];
		$chkUsr['string'] = $p = NULL;
		$p = $_REQUEST['inputPassword'];
		
		//var_dump($_REQUEST); 
		//exit;
		//Se evalua si existe el usuario
		$bd->_resultados = NULL;		
		$chkUsr = $usr_info = $bd->info_user($u);
		//var_dump($chkUsr);exit;
		$userFound = count($bd->_resultados);
		//var_dump($userFound);exit;
		$chkUsr['string'] = $p;
		//Se evalua password
		$chkPass = $bd->check_pass($chkUsr);

		
		if(!isset($chkUsr['password']))
		{ 
			//$bd->log_bitacora(1,'acceso',$u,'NA');
			header('Location: ./../?error=acceso&t=1&token='.md5(date("dmY")).' '); 
			return false;
		}
		
		//var_dump($chkUsr, $userFound, $chkPass);exit;
		
		if($userFound >= 1  && $chkPass == 0)
		{
		
	
			// Todo OK se procede a inicializar variables de SESSION
			if($usr_info){ 
			
				session_start();
				$_SESSION['acceso'] = true;		
				$_SESSION['informacion'] = $usr_info;
				// 0 - No error | acceso TipoProceso | Que usuario | archivo
				$bd->log_bitacora(0,'acceso',$chkUsr['clave'],'NA');
				//$_SESSION['start'] = time();
				//$_SESSION['expire'] = $_SESSION['start'] + (5 * 60);
				//var_dump($chkUsr);exit;
				header('Location: ./../inicio.php?init=success ');
				return false;
			}
		}else{
			
			$bd->log_bitacora(1,'acceso',$u,'NA');
			header('Location: ./../?error=acceso&t=1&token='.md5(date("dmY")).' '); 
			return false;
		}
		
		
		//var_dump($userFound,$chkPass);
		//return false;
		
    break;
		
    case 'T002':  // CASE: Subir ARCHIVOS XML      
		// Vemos cuantos archivos hemos subido
		include "./../modulo/BD_class.php";
		$ip = $bd->get_ip();
		$numTotalFiles=count($_FILES["archivo"]["name"]);
		$text = $_FILES["archivo"]["name"][0];
		$user = $_REQUEST['cveUser'];
		
		// Revision si no han enviado NADA de Archivos XML
		if($numTotalFiles <= 1 && $text == ''){ header('Location: ./../subir_xml?noFilesDetected');exit;}
		//var_dump($numTotalFiles, $text, $user );
		//var_dump($_FILES);exit;
		$ftp_server = "pegasotecnologiacfdi.net";

		$ftp_user = "Angeles_Nomina_PROD";
		$ftp_pass = "kK5VsPgkb36DoX";
	  /*$ftp_user = "Angeles_Nomina_QA";
		$ftp_pass = "ORDGu541tioV5c"; */
		$logText = '';
		$fechaLog = date("dmY-Hi");
		$nameLog = "uploadXML-".$fechaLog.".log";
		$filename = "./../files/logs/".$nameLog;
		$fileUserlog = "./../files/logs/".$user."/".$nameLog;
		
		//$file_name=str_replace(" ","", $_FILES["archivo"]["name"][0]);
		//var_dump($file_name);exit;
		
		$fh = fopen($filename, "a") or die("Could not open log file.");
		$fuser = fopen($fileUserlog, "a") or die("Could not open log file.");


		// establecer una conexión o finalizarla
		$conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server"); 
		$fecha = date('d-m-Y H:m:s');
		// intentar iniciar sesión
		if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) 
		{
			$dirPegasoIN = '/Proceso_Excel/IN';
			ftp_chdir($conn_id, $dirPegasoIN);
			
			$logText .=  $fecha."|CON:0|".$ip."|".PHP_EOL;
			//echo "<br><b>".ftp_pwd($conn_id)."</b><br>"; 	
			
		} else {
			$logText .=  $fecha."|CON:1-NOLogin|".$ip."|".PHP_EOL;
			//echo "No se pudo conectar como $ftp_user <br>";
		}
		
		//Path para subir a pegaso
		$pathPegaso = '.\..\files\xml\toPegaso';
		
		fwrite($fh, ''.$logText) or die("Could not write file!");
		fwrite($fuser, ''.$logText) or die("Could not write file!");
		/// Empezamos el array
		$item = 0;
		for ($i=0; $i<$numTotalFiles; $i++) 
		{
			$oldName = $_FILES["archivo"]["name"][$i];
			// VALIDACION DE ESPACIOS VACIOS EN EL NOMBRE DEL ACHIVO
			$file_name=str_replace(" ","",$_FILES["archivo"]["name"][$i]);
			// VALIDACION DE DOBLE guion bajo EN EL NOMBRE DEL ACHIVO
			$file_name=str_replace("__","_",$file_name);
			if($oldName != $file_name){ $strgName = "|old_name:".$oldName; }else{$strgName = "";}
			$temp_name=$_FILES["archivo"]["tmp_name"][$i];
			$file_size=$_FILES["archivo"]["size"][$i];
			$file_type=$_FILES["archivo"]["type"][$i];

			$pathFile = $pathPegaso.'\\'.$file_name;
		
			if(copy($temp_name, $pathFile))
			{
				$fecha = date('d-m-Y H:m:s');$item++;
				$logText =  "|".$item."|".$fecha."|".$user."@".$ip."|";
				//Si se copia el archivo en cuestion ahora lo envia por FTP a la carpta IN
				
					if (ftp_put($conn_id, $file_name, $pathFile, FTP_ASCII)) 
					{
						
							$logText .= "COPY-OK|".$file_name.$strgName." |".PHP_EOL;
							fwrite($fh, $logText) or die("Could not write file!");
							fwrite($fuser, $logText) or die("Could not write file!");
							
					} else {
							$logText .= "COPY-ERROR|".$file_name."|old_name:".$oldName." |".PHP_EOL;
							fwrite($fh, $logText) or die("Could not write file!");
							fwrite($fuser, $logText) or die("Could not write file!");
							//$item++;
						//echo "Hubo un problema durante la transferencia de $file\n";
					}
				
			} //END IF COPY ->  toPegaso
		
	
		} // END FOR repasa TODOS LOS ARCHIVOS SUBIDOS
		
		// Cierra el Log de cambios
		fclose($fh);fclose($fuser);
		// cerrar la conexión ftp
		ftp_close($conn_id); 
		
		header('Location: ./../subir_xml?process=ok&s='.$numTotalFiles.'&c='.$i.'&nl='.$nameLog.'&tsk='.md5($i).' ');
		//echo "Numero de archivos seleccionados: ".$numTotalFiles." Numero de archivos copiados".$i;
		exit;
       
    break;
	/*	
    case 'T003':  // CASE: Crear una NUEVA Cita.
        
        include "./../modulo/BD_class.php";

        $info = $bd->agenda_cita($_POST);   
        echo json_encode($info);
        
    break;

    case 'T004':  // CASE: Disponibiilidad de Cita.
        
        include "./../modulo/BD_class.php";
        $data = $_POST['fecha']; 
        $fecha = $bd->format_fecha($data);
        $info = $bd->disponibilidad_cita($fecha);   
        echo $info;
        
    break;

    case 'T005':  // CASE: Disponibiilidad de Cita.
        
        include "./../modulo/BD_class.php";
        $data = $_POST['fecha']; 
        
        $info = $bd->consultar_cita($data);   
        echo $info;
        
    break;

    case 'T006':  // CASE: Disponibiilidad de Cita.

        include "./../modulo/BD_class.php";
        $data = $_POST; 
        
        $info = $bd->editar_cita($data);   
        echo ($info);
        
    break;

    case 'T007':  // CASE: Crear nuevo Usuario.

        include "./../modulo/BD_class.php";
        $data = $_POST; 
        
        $info = $bd->crear_usuario($data);        
        echo json_encode($info);
        
    break;
*/

    } // END of Switch.
    
	
	
} // END of TaskType 
	


?>