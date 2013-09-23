<?php
	@setlocale(LC_TIME,'es_AR');

	ini_set('error_reporting', E_ALL & ~E_NOTICE);	

	ini_set('session.auto_start', 1);

	define('CFG_HOST','localhost');
	define('CFG_URL_SITE','');
	define('CFG_PATH_SITE','');
	define('CFG_BASE','');
	define('CFG_USER','');
	define('CFG_PASS','');

	define('CFG_UTF',false);
	
	define('CFG_AHORA',date('Y-m-d H:i:s'));

	define('CFG_DIR_PLANTILLA','template/');

	//!IMPORTANTE!!!
	define('CFG_NOMBRETABLA','prueba18');
	//!!!!!!!!!!!!!
	
	/* CLASES									*/
	/*
	/*******************************************/

	include('objetos/Tabla.php');
	
	include('objetos/DataBase.php');

	include('objetos/Formulario.php');

	include('objetos/class.phpmailer.php');

	include('objetos/Init.php');

	include('objetos/Landing.php');

	include('objetos/DescargarBase.php');


	
	DataBase::DBConnect(CFG_HOST,CFG_USER,CFG_PASS,CFG_BASE);
	

	include('objetos/functions.php');

	//echo PHP_OS;



?>