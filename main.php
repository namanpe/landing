<?php 
	
	include('config.php');

	//Crea la tabla del nombre que se le pasa por parámetro con los campos "id" y "fecha".
	$init = new Init(CFG_NOMBRETABLA);

	//addColumnn(nombre columna, tipo, despues de);
	//nombre columna: debe ser alfanumérico y sin espacios 
	//tipo puede ser: "string", "text", "int", "date". Diferencia entre string y text es que string soporta hasta 250 caracteres el otro mucho más 1024 bytes. Sugiero usar para el campo edad el tipo string.
	//despues de: es opcional sino se agrega o el nombre no existe, lo agrega al final.
	$init->addColumn('nombre','string','id');
	$init->addColumn('apellido','string','nombre');
	$init->addColumn('email','string','apellido');
	$init->addColumn('edad','string','email');
	$init->addColumn('asunto','text','edad');


	//borra una columna
	//$init->deleteColumn('fechaalta');

	//muestras las columnas de la tabla
	$init->showColumns();

 ?>