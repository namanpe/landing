<?php 
	include('config.php');

	//el primer parametro es el nombre de la tabla, el segundo el nombre del archivo.
	$descargarBase = new DescargarBase(CFG_NOMBRETABLA,'Landing Prueba14');
	$descargarBase->run();
?>