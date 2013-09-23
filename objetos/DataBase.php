<?php

/**
 * DataBase abstract class file.
 *
 * Este el el archivo principal de control de Consultas a base de datos
 * 
 * @author xpromx <hr.barz@gmail.com>
 * @link http://www.hr1.com.ar/
 * @copyright Copyright &copy; 2007 hr
 * @package Estuario del plata
 */

 abstract class DataBase  {

	/**
	 * Conectar con el host y seleccionar la base de datos
	 *
	 * @param Connection $host, $user, $password, $DB
	 * @return retorna el valor de la conexion a la DB
	 */
	
	static public function DBConnect($host,$user,$password,$DB){
		
		$link = mysql_connect($host,$user,$password)
		    or die('Could not connect: ' . mysql_error());
		mysql_select_db($DB) or die('Could not select database');
		return $link;
	}
	
	/**
	 * Manda el query al mysql. Si debug viene en 1, imprime en pantalla.
	 *
	 * @param Connection $query, $debug = 0
	 * @return retorna el valor del query
	 */
	
	static public function query($query,$debug=0){
		//print $query;
		
		$result=mysql_query(($query));

		If ($debug==1)
			echo "<br>$query<br>";

		if (!$result) 
			die(mysql_error());

		return $result;
	}
	
	/**
	 * Devuelve un el result en un array de array organizado por nombre de campo.
	 *
	 * @param Connection $result
	 * @return retorna un array
	 */	
	
	public function fetch ($result){
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	/**
	 * Devuelve la primera línea del result en un array organizado por número de posición de campo.
	 *
	 * @param Connection $result
	 * @return retorna un array
	 */	
	
	public function fetch_row ($result){
		return mysql_fetch_row($result);
	}
	
	/**
	 * Devuelve el total de registros encontrados.
	 *
	 * @param Connection $result
	 * @return retorna un numero integer
	 */	
	
	static public function count_rows ($result){
		return mysql_num_rows($result);
	}
	
	/**
	 * Array organizado especialmente para usar con el DataSource de los combos:.
	 *
	 * @param Connection $result
	 * @return retorna un array para el DataSource
	 */		
	
	static public function fetch_array ($result){
		$array=array();
		$i=0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$array[$i]=$row;
			$i++;
		}
		
		return $array;
	}
	
	/**
	 * Devuelve el identificador generado en la última llamada a INSERT
	 *
	 * @param Connection void
	 * @return retorna un integer
	 */	
	static public function insert_id (){
		return mysql_insert_id();
	}
	
	static public function show_colums($nombre){
		
		$query = "SHOW COLUMNS FROM ".$nombre;
		$filas = DataBase::fetch_array(DataBase::query($query));
		
		$arrCampos = array();
		$arrPK = array();
		$campoAI = '';
		if(count($filas)>0 && is_array($filas)){
			foreach($filas as $fila){
				$arrCampos[] = $fila['Field'];
				if($fila['Key']=='PRI'){
					$arrPK[] = $fila['Field'];
				}
				if($fila['Extra']=='auto_increment'){
					$campoAI = $fila['Field'];
				}
			}
		}
		return array($arrCampos,$arrPK,$campoAI);
	}
	
}
?>