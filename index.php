<?php 

  include('config.php');


  $contenido = file_get_contents(CFG_DIR_PLANTILLA.'formulario.html');


  if($_POST){
    $landing = new Landing(CFG_NOMBRETABLA);

    //los mail con los datos se envian con la plantilla enviosDatos.html
    $landing->enviarDatosA('namanwe@gmail.com');
    $landing->enviarDatosA('naman@hotmail.es');
    $landing->enviarDatosA('luis@powersite.com.ar');

    $landing->seEnviaASuscriptor(true); //es importante que el campo del email del suscriptor sea "email"
    $landing->plantillaEnvioSuscriptor('plantillaEnvioCliente.html');//a esta plantilla le ponés las variables de la tabla: ej mira la plantilla de prueba.
    $landing->subjectEnvioSuscriptor('Actualización nuevo suscriptor');

    $landing->direccionPosEnvio('gracias.html');//si el contenido es una url basta con que empiece con "http://", para identificarlo

    //si en el formulario tienes fecha de nacimiento, podés armarlo con select con campos dividos en 3 partes con este nombre:
    //fNacimiento_anio, fNacimiento_mes, fNacimiento_dia. Y en la tabla lo tenés como fecha_nacimiento. 
    //Lo pasas así: 
    //$_POST['fecha_nacimiento'] = $_POST['fNacimiento_anio'].'-'.$_POST['fNacimiento_mes'].'-'.$_POST['fNacimiento_dia'];

    $error = $landing->run();
  	
  }

  if(istruearray($error)){

    switch ($error['tipo']) {
        case 'email':
          $mensaje = 'Email no v&aacute;lido';
        break;
        case 'noguardo':
          $mensaje = 'No se pudo guardar intente en otro momento';  
        break;  
    }

  }else{
    $mensaje = '';
  }

  $contenido = str_replace('##mensaje##',$mensaje,$contenido);

  echo $contenido;


 ?>