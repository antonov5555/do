<?php

//include "configs.php";	
	
class baseDatos
{	

	public $_resultados = "";

	public function bd_conexion() 
	{ 
            //$db = mysqli_connect('localhost','root','','gestorXML');
            //return $db;
			//var_dump($db); exit;
			$dsn = 'mysql:dbname=geas_do;host=127.0.0.1';
			$usuario = 'user_do';
			$contraseña = 'A7T5BwLPaK4xrJNm';

			try {
				$dataBase = new PDO($dsn, $usuario, $contraseña);
				return $dataBase;
			/*
				var_dump($dataBase);
				$sth = $dataBase->prepare('SELECT * FROM usuario WHERE id_usuario = ? AND clave = ?');
				$sth->execute(array(1, 'luis.oviedo'));
				$red = $sth->fetchAll();
				$sth->execute(array(4, 'adrian.ramirez'));
				$yellow = $sth->fetchAll();

				echo var_dump($red);
				echo "<hr>";
				echo var_dump($yellow);
			*/	
				
			} catch (PDOException $e) {
				echo 'Fall&ocute; la conexi&oacute;n: ' . $e->getMessage();
			}
	} // End of Method -  bd_conexion
        
	public function bd_desConexion($db)
	{
            if($db=null){
                return true;
            }else{
                return false;
            }
		
	} // End of Method -  bd_desConexion
        
	public function getArray($resultado)
	{ // $resultado array 
            
		$row = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
		return $row;
	} // End of Method - getArray   
     /*   
        function getAllarray($resultado)
	{ // $resultado array 
            $return_arr = array();
            
            while ($row = mysqli_fetch_array($resultado, MYSQLI_ASSOC)){
		$id_paciente=$row['id_paciente'];
		
		$row_array['value'] = utf8_encode($row['nombrePaciente']);
		$row_array['id_paciente']=$id_paciente;
		array_push($return_arr,$row_array);
                
            }

            //var_dump($row_array); return false;
            return $return_arr;
	} // End of Method - getArray
       */ 
	public function info_user($usr)
	{ // $usr string
            $link = $this->bd_conexion();//$db
            $sql = "SELECT * FROM usuario WHERE clave = ? ";
			$sth = $link->prepare($sql);
			$sth->execute(array($usr));
			$datos = $sth->fetchAll();
			$this->bd_desConexion($link);
			if(empty($datos)){
				return false;
			}else{
				//var_dump($datos);exit;
				$this->_resultados = $datos[0];
				return $datos[0];
			}
			
			
            
	} // End of Method - user_info	
        
	public function check_pass($dato)
	{ // $dato array
			
			$dato1 = md5($dato['string']);
			$dato2 = $dato['password'];
			//var_dump($dato);exit;
            if($dato1 == $dato2) {
                $response =  0; 
            }else {
                $response =  1;  
            }
            return $response;
		
	} // End of Method - check_pass	
        
public function log_bitacora($tipo,$proceso,$user, $archivo = '')
{
		$link = $this->bd_conexion();
		
		$sql = "INSERT INTO bitacora (id_bit, tipo, proceso, fecha, user, ip, archivo) "
			 . "VALUES (NULL, ?, ?, NOW(), ?, ?, ?)"; 
			 
		$sth = $link->prepare($sql);
		$ip = $this->get_ip();
		$sth->execute(array($tipo, $proceso, $user, $ip, $archivo));
		//var_dump()
		$this->bd_desConexion($link);
		return true;
} // End Of Method log_bitacora
		
public function apps_user($dato) 
{ 			// $dato string
		$link = $this->bd_conexion();
		//phpinfo();exit;
		$sql = "SELECT a.* FROM apps a, usuario_apps up, usuario u WHERE 1=1 AND u.id_usuario = up.id_usuario AND up.id_app = a.id_app AND u.clave = '$dato' ";
		$sth = $link->prepare($sql);
		$sth->execute(array('luis.oviedo'));
		$resultado = $sth->fetchAll();
		$this->bd_desConexion($link);
		$countApp = 0;
		$htmlApps = '<div class="row">';
		foreach($resultado as $app)
		{
			$countApp++;
			
			if($app['id_app'] != 1 && $app['activo'] != 0 )
			{
			$htmlApps .= '<div class="col-md-4 mb-3">';
			
			$block = '<!-- Card -->
						<a href="'.$app["ubicacion"].$app["nombre"].'" class="nolink-style ">
							<div class="card box-shadow">
								  <div class="card-body">
										<h5 class="card-title text-center">'.$app["nombre"].'</h5>
								  </div>
							</div>
						</a>
						<!-- Card -->';
			$htmlApps .= $block;  
				
				
			$htmlApps .= ' </div>'; 	
			}
			 
			if($countApp > 3){
					$htmlApps .= '</div><div class="row">';$countApp = 0;
				}
			//var_dump($app);

		
		} // End Foreach
		
	return $htmlApps;
	//exit;    
} 
// End of Method - apps_user


/*        
	function agenda_cita($dato)
	{ // $dato array
            $link = $this->bd_conexion();
            
            switch ($dato['taskReq']) {
                case 'newCita':

                    $fecha = $dato['fechaCita'];$fec = explode("-", $fecha);
                    $dia = $fec[0]; // dia
                    $mes = $fec[1]; // mes
                    $año = $fec[2]; // año                    
                    $fecha = $año."-".$mes."-".$dia;
                    
                    $hora  = $dato['horaCita']; $idPaciente = intval($dato['idPaciente']);
                    $sede = $dato['sede']; $motivo = $dato['motivo']; $response = array();

                    $sql = "INSERT INTO cita (id_cita, fecha, hora, sede, motivo, id_paciente) "
                         . "VALUES (NULL, '$fecha', '$hora', '$sede', '$motivo', '$idPaciente')";   
                
                if ($resultado = mysqli_query($link, $sql)){
                    
                        $lastId = mysqli_query($link, "SELECT id_cita FROM cita ORDER by id_cita DESC LIMIT 1");
                        $info = $this->getArray($lastId);
                        
                    $response['error'] = 0;
                    $response['mensaje'] = " Cita registrada correctamente.";
                    $response['infoCita'] = "[ ".$motivo." | ".$sede." | ".$dato['fechaCita']." | ".$dato['horaCita']." ]";
                    $response['idCita'] = $info['id_cita'];
                    //var_dump($resultado);
                  
                }else{
                    $response['error'] = 1;
                    $response['mensaje'] = " Error al registrar cita. ";
                    $response['debug'] = $sql;
                    $response['debugNotice'] = mysqli_error($link);
                }
                
                $this->bd_desConexion($link);
                return $response;
                    
                break;
                case 'upCita':

                    
                break;
            }
            
            

	} // End of Method - agenda_cita
*/        
		function format_fecha($fecha)
		{                                                 
            $fec = explode("-", $fecha);
            $dia = $fec[0]; // dia
            $mes = $fec[1]; // mes
            $año = $fec[2]; // año                    
            $newFecha = $año."-".$mes."-".$dia;
            return $newFecha;
            
	} // End of Method - format_fecha 
        
        function Afecha_normal($fecha)
        {                                                 
            $fec = explode("-", $fecha);
            $dia = $fec[2]; // dia
            $mes = $fec[1]; // mes
            $año = $fec[0]; // año                    
            $newFecha = $dia."-".$mes."-".$año;
            return $newFecha;
            
        } // End of Method - format_fecha 
        
        function disponibilidad_cita($fecha)
        {
            $info = array();
            $horario = array('08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00');
            $horaDisp='<thead><tr>
                  <th>Hora</th>
                  <th>Disponibilidad</th>
                </tr></thead><tbody>';
            $link = $this->bd_conexion();
            $sql = "SELECT DISTINCT(hora), COUNT(motivo) AS cita "
                 . "FROM cita WHERE fecha = '$fecha' GROUP BY hora ";
            if ($resultado = mysqli_query($link, $sql)){

                    $data = $this->getDispCitas($resultado);
                    
                    foreach ($horario as $hora) {
                        $noCitas = 0;
                        //var_dump($hora);
                         $horaDisp .= "<tr><td>$hora</td>";
                        
                        foreach ($data as $disp){
                            //var_dump($hora, $disp['hora']);
                            if($hora == $disp['hora'])
                            {
                               $num = intval($disp['cita']);
                                $noCitas = 1;
                               if($num >= 1 && $num <= 2){
                                 $horaDisp .= '<td class="text-success alert-success" title=" '.$disp['cita'].' citas registradas" >Disponible</td>'; 
                                 break;
                               }elseif($num >= 3 && $num <= 4){
                                 $horaDisp .= '<td class="text-warning alert-warning" title=" '.$disp['cita'].' citas registradas" >Poco disponible</td>'; 
                                 break;
                               }elseif($num >= 5 && $num <= 100){
                                 $horaDisp .= '<td class="text-danger alert-danger" title=" '.$disp['cita'].' citas registradas" >No disponible</td>'; 
                                 break;
                               }
                             //break;  
                            } // END IF
                           //$horaDisp .= "<td class=\"text-success alert-success\">Alta ".$disp['cita']."</td>"; 
                              
                        }
                        if($noCitas == 0){
                            $noCitas = 1;
                            $horaDisp .= '<td class="text-success alert-success" title="0 citas registradas" >Disponible</td>'; 
                        }
                        
                        $horaDisp .="</tr>";
                    }
                    $horaDisp .="</tbody>";
                    
                    //var_dump($data); 
                    $info['table'] = $horaDisp;
                    
                    
                    
                    $datos = json_encode($info);
                    $this->bd_desConexion($link);
                    return $datos;

            }else{
                var_dump($info);
            }  
            $this->bd_desConexion($link);
            
        } // End of Method - disponibilidad_cita
        
        function getDispCitas($resultado)
		{ // $resultado array 
            $return_arr = array();
            
            while ($row = mysqli_fetch_array($resultado, MYSQLI_ASSOC)){
		
		
		$row_array['hora'] = substr($row['hora'], 0, -3);
		$row_array['cita']=$row['cita'];
		array_push($return_arr,$row_array);
                
            }

            //var_dump($row_array); return false;
            return $return_arr;
	} // End of Method - getArray
        
        function consultar_cita($data) 
        {         
            $fecha = $this->format_fecha($data);
            $info = array();$titleVenc ="";$listCita ='<thead class="thead-dark">
                <tr>
                  <th>HORA</th>                  
                  <th>MOTIVO</th>
                  <th>SEDE</th>
                  <th>PACIENTE</th>
                  <th>MEMBRESIA</th>
                  <th>OPCIONES</th>
                </tr>
              </thead><tbody>';
            $link = $this->bd_conexion();
            $sql = "SELECT * FROM vw_allcitas WHERE fecha = '$fecha' ";
                if ($resultado = mysqli_query($link, $sql)){  
                    
                    $numPacientes = $resultado->num_rows;
                    if($numPacientes == 0){
                        $listCita .= "<tr>"
                                    . '<td colspan="6"><center>No hay citas registradas</center></td>'                                                                    
                                . "</tr>";
                        $listCita .="</tbody>";
                        /* var_dump($numPacientes);                    
                        return false;*/
                    }else{
                        
                        while ($row = mysqli_fetch_array($resultado, MYSQLI_ASSOC)){

                            //var_dump($row['fec_vencimiento']); return false;
                            if($row['fec_vencimiento'] != '' || $row['fec_vencimiento'] != NULL){ 
                                $fechaVenc = $this->Afecha_normal($row['fec_vencimiento']); $titleVenc = "Vigencia hasta ".$fechaVenc;
                            }else{ $titleVenc = "";}

                              $listCita .= "<tr>"
                                           . "<td>".substr($row['hora'], 0, -3)."</td>"
                                           . "<td>".ucfirst(strtolower($row['motivo']))."</td>"
                                           . "<td>".ucfirst(strtolower($row['sede']))."</td>" 
                                           . "<td>".utf8_encode($row['nombrePaciente'])."</td>"
                                           . "<td Title=\"".$titleVenc."\">".strtolower($row['tipo_membresia'])."</td>"
                                            ."<td>"                                                
                                                .'<button type="button" class="btn btn-warning btn-sm" title="Editar cita"  id="btnEdit'.$row['id_cita'].'"><span class="ui-icon ui-icon-pencil btn-dialogo"></span></button>'
                                                .'<button type="button" class="btn btn-danger btn-sm" title="Eliminar cita" id="btnDel'.$row['id_cita'].'" ><span class="ui-icon ui-icon-closethick btn-dialogo"></span></button>'
                                                .'<script>'
                                                .'$("#btnEdit'.$row['id_cita'].'").on("click", function() {
                                                    $("#frmUpCita")[0].reset(); 
                                                    $("#idCitaedit").val("'.$row['id_cita'].'");
                                                    $("#nombrePaciente_up").html("");$("#nombrePaciente_up").html("'.utf8_encode($row['nombrePaciente']).'");
                                                    $("#'.$row['sede'].'").prop(\'checked\', true); 
                                                    $("#'.$row['motivo'].'").prop(\'checked\', true);
                                                    $("#UpfechaCita").val("'.$data.'");
                                                    $("#UphoraCita").val("'.$row['hora'].'");
                                                    $( ".input-radio" ).checkboxradio( "refresh" );
                                                    $("#divEdit").show();
                                                    $("#divEdit").focus();
                                                  });
                                                  </script>'
                                            ."</td>"
                                       . "</tr>";
                        } // END WHILE  
                                          
                    } // END ELSE SI HAY REGISTROS.
 
                } // END IF SI LA CONSULTA SE HIZO CORRECTA.
                else{
                    
                    $listCita .= "<tr>"
                                . '<td colspan="6">SIN DATOS DISPONIBLES</td>'                                                                    
                               . "</tr>";
                      
                } 
            $listCita .="</tbody>";      
            $info['table'] = $listCita;
            //var_dump($info['table']); return false;        
                    
                    
                    $datos = json_encode($info);
                    $this->bd_desConexion($link);
                    return $datos;
            
         
        } // End of Method - consultar_cita
        
        function editar_cita($data)
        {
            /*
             * UpfechaCita "dd-mm-yyyy" UphoraCita "hh:mm:ss" Upmotivo "text" Upsede "text" idCitaedit "##"
             */
            $response = array(); $fecha = $this->format_fecha($data['UpfechaCita']);$hora = $data['UphoraCita'];
            $motivo = $data['Upmotivo'] ;$sede = $data['Upsede'];
            $idCita = intval($data['idCitaedit']);
            $link = $this->bd_conexion();
            $sql = "UPDATE cita SET fecha = '$fecha', hora = '$hora', motivo = '$motivo', sede = '$sede' "
                 . "WHERE cita.id_cita = $idCita;";
            
            if ($resultado = mysqli_query($link, $sql))
            {  
               $response['error'] = 0;$response['mensaje'] = "Cita actualizada correctamente.";
               $response['infoCita'] = "".$data['UpfechaCita']." | " .substr($data['UphoraCita'], 0, -3)." | " .ucfirst($motivo)." | " .ucfirst($sede)." | " ;
               $response['table'] ='<thead class="thead-dark"><tr><th>HORA</th><th>MOTIVO</th><th>SEDE</th><th>MEMBRESIA</th><th>OPCIONES</th></tr></thead><tbody></tbody>';
            }else
            {
                $response['error'] = 1;
                $response['mensaje'] = "La cita no se actualizó.";
                $response['infoCita'] = "".$data['UpfechaCita']." | " .substr($data['UphoraCita'], 0, -3)." | " .ucfirst($motivo)." | " .ucfirst($sede)." | " ;
                
            } // END else
            
            
            
            $response = json_encode($response);
            return $response;
        }
        
        function crear_usuario($data)
        {
            $link = $this->bd_conexion();$response = $mail = array(); $username  = $mail['username'] =$data['username']; 
            $correoElectronico = $mail['mail'] = $data['correoElectronico'];
            $password = $mail['password'] = $data['password2'];$newPwd = password_hash($password, PASSWORD_DEFAULT);
            if(isset($data['tipoUsuario1'])){ $tipoUsr = $data['tipoUsuario1'];}else{ $tipoUsr = $data['tipoUsuario'];}
            //&nombreUsuario=luis&paternoUsuario=oviedo&maternoUsuario=salgado&password1=asd&password2=asd&tipoUsr=administrador
            $nombreUsuario =$data['nombreUsuario']; $paternoUsuario =$data['paternoUsuario']; $maternoUsuario=$data['maternoUsuario'];
            $sql = "INSERT INTO usuario (id_usuario, clave, password, email, nombre, ap_paterno, ap_materno, rol) "
                 . "VALUES (NULL, '$username', '$newPwd', '$correoElectronico', '$nombreUsuario', '$paternoUsuario', '$maternoUsuario', '$tipoUsr')";                
             
            if ($resultado = mysqli_query($link, $sql)){
                                          
                    $response['error'] = 0;
                    $response['mensaje'] = " Cuenta creada correctamente!";
                    $mail['type'] = 0;
                    $response['infoCita'] = $username." se envi&oacute; un email a: ".$correoElectronico." con tus credenciales de acceso.";
                    /*if(sendMail($mail)){
                        $response['infoCita'] = $username." se envi&oacute; un email al correo: ".$correoElectronico." con tus credenciales de acceso.";
                    }else{
                        $response['infoCita'] = "No se pudo enviar un email al correo: ".$correoElectronico." por favor envianos un correo a: sistemas@esmefis.edu.mx.";
                    }*/
                   
                    //$response['idCita'] = $info['id_cita'];
                    //var_dump($resultado);
                  
                }else{
                    $response['error'] = 1;
                    $response['mensaje'] = " Error al registrar usuario, por favor enviar un correo a sistemas@esmefis.edu.mx ";
                    $response['debug'] = $sql;
                    $response['debugNotice'] = mysqli_error($link);
                }
                
                $this->bd_desConexion($link);
                return $response;             
             
        } // END of method crear_usuario
        
        function sendMail($data)
        {
            switch ($data['type']) {
                case 0:
                // Nuevo usuario se registra
                    $to      = $data['correoElectronico'];$subject = 'Confirmación de cuenta de correo en el sistema UNIF';
                    $message = "Estimado ".$data['correoElectronico'].". \n\n ".
                                          "Le compartimos sus credenciales de acceso al sistema UNIF:\n\n\n ".
                                          "Usuario: ".$data['username']." \n".
                                          "Contraseña: ".$data['password']."  \n "
                                        . "Correo electronico: $to\n ";	
                    $headers = 'From: sistemas@esmefis.edu.mx' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();                    

                break;
                case 1:

                break;            
            }
            
            if(mail($to, $subject, $message, $headers))
            {
                return true;
            }else{
                return false;
            }
            
            
            
            
        } // End of Method sendMail
		
//Obtiene la IP del cliente
public function get_ip() 
{
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
} // END OF METHOD get_ip	
        
	
} // END OF CLASS baseDatos
	
$bd = new baseDatos; 
	

?>