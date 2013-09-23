Pasos para armar una correcta landing:

1)Editar el archivo config.php, en la linea 21 poner el nombre de la tabla que se va usar o crear.

2)Editar el archivo main.php, dentro puedes crear la tabla (de no existir) que definiste anteriormente en config.php.
	
	Cuando se crea la tabla lo hace con las columnas "id" y "fecha".
		$init = new Init(CFG_NOMBRETABLA);
	
	Para crear nuevas columnas: 
		$init->addColumn('nombre columna','tipo','despues de'); 
	Donde vale:
	nombre columna: debe ser alfanumérico y sin espacios 
	tipo puede ser: "string", "text", "int", "date". Diferencia entre string y text es que string soporta hasta 250 caracteres el otro mucho más 1024 bytes. Sugiero usar para el campo edad el tipo string.
	despues de: defines depués de qué columna vas agregar la que pasas por primer parámetro, es opcional, si dejás en blanco lo agrega al final.

	Para borra columnas:
		$init->deleteColumn('nombre columna');

	Para mostrar las columnas de la tabla
		$init->showColumns();

3) editamos index.php 
	Seteamos el nombre del archivo que sería el formulario landing. En este formulario se tiene que validar los datos por javascript. Los datos se tiene que enviar por POST ( method="post").
		$contenido = file_get_contents(CFG_DIR_PLANTILLA.'formulario.html');

	Seteamos la tabla que vamos a usar en la landing
		 $landing = new Landing(CFG_NOMBRETABLA);

	Definimos a quienes se van a enviar los datos del formulario, puede ser más de uno. !importante! la plantilla que usa es "enviosDatos.html"
		 $landing->enviarDatosA('luis@powersite.com.ar');
		 $landing->enviarDatosA('namanweb@gmail.com');

	Definimos si le enviamos al suscriptor un mensaje luego de completar el formulario. 
	Para esto es importante que el campo del email del suscriptor sea "email".
	También podemos definir cual es la plantilla que se va enviar al suscriptor, esta puede tener variables. Mirar el ejemplo.
	Se debe definir el subjet del mail.
		$landing->enviarASuscriptor(true); 
  	 	$landing->plantillaEnvioSuscriptor('plantillaEnvioCliente.html');
  	 	$landing->subjectEnvioSuscriptor('Actualización nuevo suscriptor');

  	Luego de que se envío y guardó los datos se puede ejecutar alguna acción de redirección, si decias que vaya a una url simplemente agregas la url(es importante que tenga "http://"), sino como en este ejemplo seteas una plantilla.
  		$landing->direccionPosEnvio('gracias.html');

  	Aclaraciones: 
  		Asumo que existe la columna fecha, por lo que cuando se guarda un nuevo suscriptor setea la fecha en la que lo está haciendo, se podrá ver cuando se descarga la base.
  		Los errores son de dos tipo:
  			email: cuando el suscriptor ingresa un email invádido.
  			noguardo: si sucedió un problema con la base y por algún motivo desconocido no se guardo. Ej: problema temporal del servidor.
  		Si ni hubo errores, se redirecciona a la url o archivo definido previamente.


4)editar descargarBase.php
	Levanta el nombre de la tabla seteada en config y en el defines en el segundo parámetro el nombre del archivo.
		$descargarBase = new DescargarBase(CFG_NOMBRETABLA,'Landing Prueba14');


		
...FIN...
