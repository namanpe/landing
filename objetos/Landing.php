<?php 

	/**
	* 
	*/
	class Landing{

		private $nombreTabla = '';
		private $arrColumnasTabla = array();
		private $enviarDatosA = array();
		private $enviarASuscriptor = false;
		private $_plantillaEnvioSuscriptor = '';
		private $_subjectEnvioSuscriptor = '';
		private $direccionPosEnvio = '';

		public function __construct($nombre){
			$this->nombreTabla = $nombre;
			$arrDatosTabla = DataBase::show_colums($this->nombreTabla); 
			$this->arrColumnasTabla = $arrDatosTabla[0];
			
		}

		public function enviarDatosA($email){
			$this->enviarDatosA[] = $email;
		}

		public function seEnviaASuscriptor($bool = false){
			$this->enviarASuscriptor = $bool;
		}

		public function plantillaEnvioSuscriptor($plantilla){
			$this->_plantillaEnvioSuscriptor = $plantilla;
		}

		public function subjectEnvioSuscriptor($subject){
			$this->_subjectEnvioSuscriptor = $subject;
		}

		public function direccionPosEnvio($direccion){
			$this->direccionPosEnvio = $direccion;
		}

		public function run(){

			$error = array();

			if(isset($_POST)){
				if(istruearray($_POST)){

					$tabla = new Tabla($this->nombreTabla);

					foreach ($_POST as $key => $value) {
						if(in_array($key, $this->arrColumnasTabla)){
							$datosEnviar[$key] = $value;
							$tabla->set($key,$value);
						}
					}

					$tabla->set('fecha', CFG_AHORA);

					if(validarEmail($datosEnviar['email'])){
						if($tabla->save()){
							
							if(istruearray($this->enviarDatosA)){

								foreach ($this->enviarDatosA as $k => $emailA) {
									if(validarEmail($emailA)){
										$enviar = new Formulario($datosEnviar);
										//$enviar->Mailer = 'smtp';
										$enviar->enviarMail('noreply@namanweb.com.ar',$emailA,$this->_subjectEnvioSuscriptor,'enviosDatos.html');
									}
								}
							}
							
							if($this->enviarASuscriptor){
								
								$enviarMail = new Formulario($datosEnviar);
								$enviarMail->enviarMail('noreply@namanweb.com.ar',$datosEnviar['email'],$this->_subjectEnvioSuscriptor,$this->_plantillaEnvioSuscriptor);
								
							}

							//redirección si todo sale bien
							if(stripos($this->direccionPosEnvio, 'http://') !== false){
								header('Location: '.$this->direccionPosEnvio);
							}else{
								header('Location: '.CFG_URL_SITE.CFG_DIR_PLANTILLA.$this->direccionPosEnvio);
							}
							exit;
						}else{
							$error['tipo']	= 'noguardo';
							return $error;
						}
					}else{
						$error['tipo']	= 'email';
						return $error;
					}


				}
			}
		}
	}


 ?>