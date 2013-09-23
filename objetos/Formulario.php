<?php

	class Formulario {
		
		protected $_arrDatos;
		protected $_adjunto;
		
		//		public function  __construct($arrDatos, $adjunto){
		public function  __construct($arrDatos, $adjunto=''){
			if(istruearray($arrDatos)){
				$this->_arrDatos 	= $arrDatos;
				
				if($adjunto['size'] > 0){
					$this->_adjunto = moveRenameFileForm($adjunto,'mail');
				}
				
			}
		}
		
		public function getFileAdjunto(){
			return $this->_adjunto;
		}
		
		
		
		public function	getDatos(){
			return $this->_arrDatos;
		}
		
		public function enviarMail($de, $para, $titulo, $filePlantilla){
			$arrDatos = $this->getDatos();// print_r($arrDatos);
			$mail = new PHPMailer(true);
			
			/*if($arrDatos['email'] != ''){
				$mail->AddReplyTo($arrDatos['email'], $arrDatos['nombre']);
			}*/
			
			$mail->AddAddress($para);
			
			//$mail->SetFrom($de, CFG_SITE);
			$mail->SetFrom($de, 'Aviso Landing');

			$mail->Subject = utf8_decode($titulo);
			
			//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		

			if(file_exists(CFG_DIR_PLANTILLA.$filePlantilla)){
				$plantilla = (file_get_contents(CFG_DIR_PLANTILLA.$filePlantilla));

				if($filePlantilla == 'enviosDatos.html'){

					$arrPlantilla 	= extraer_insertar_datos($plantilla,'BUCLE_LISTA','DATOS_BUCLE');
					$plantilla		= $arrPlantilla[0];
					$pList			= $arrPlantilla[1];
					$tmplistado = '';
					//print_r($arrDatos);
					if(istruearray($arrDatos)){
						foreach($arrDatos as $index => $dato){
							if( $dato!=''){
								$tmpList = $pList;
								$tmpList = str_replace('##nombre##',$index,$tmpList);
								$tmpList = str_replace('##dato##',$dato,$tmpList);
								
								$tmplistado .= $tmpList;
							}
						}
						$plantilla = str_replace('##DATOS_BUCLE##',$tmplistado,$plantilla);
					}
				}else{

					if(istruearray($arrDatos)){
						foreach($arrDatos as $index => $dato){
							$plantilla = str_replace('##'.$index.'##',$dato,$plantilla);
						}
					}
				}		
				
				$plantilla = limpiarPlantilla('##','##',$plantilla);
				
			}
			
			$mail->MsgHTML($plantilla);

			if($this->getFileAdjunto() !=''){
				$mail->AddAttachment(CFG_PATH_SITE.CFG_DIR_MAIL.$this->getFileAdjunto());      // attachment
			}
			if($mail->Send()){
				return true;
			}else{
				return false;
			}
			
		}
		
	}
	
?>