<?php 
	function istruearray($arr){
		if(count($arr)>0 && is_array($arr)){
			return true;
		}else return false;
	}

	function limpiarPlantilla($search1,$search2,$text){
		preg_match('/{{NO_LIMPIAR}}(.*){{\\/NO_LIMPIAR}}/U', $text, $m);
		$text = str_replace($m[0],'{{TMP_NO_LIMPIAR}}',$text);
		
		$text = preg_replace('/'.$search1.'(.*)'.$search2.'/smU','',$text);
		$text = str_replace('{{TMP_NO_LIMPIAR}}',$m[1],$text);
		return $text;
	}	
	
	function comillaSql($str){
		return str_replace("'","''",$str);
	}
	
	function sqlS($str){
		$str = str_replace('SELECT ','XX',$str);
		$str = str_replace('UPDATE ','XX',$str);
		$str = str_replace('INSERT ','XX',$str);
		$str = str_replace('DELETE ','XX',$str);
		
		return comillaSql( addslashes($str));
	}

	function extraer_insertar_datos($plantilla,$buscar,$reemplazar){
		$plantilla = ($plantilla);
		preg_match('/##'.$buscar.'##(.*)##\\/'.$buscar.'##/smU', $plantilla, $m);
		
		if($reemplazar == 'XBORRARX')
			$plantilla = str_replace($m[0],'',$plantilla);
		else
			$plantilla = str_replace($m[0],'##'.$reemplazar.'##',$plantilla);
			
		$subplantilla = $m[1];
		return array($plantilla,$subplantilla);
	}

	function fetch_array($result){
		$query=array();
		$i=0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$query[$i]=$row;
			$i++;
		}
		
		return $query;
	}

	function validarEmail($email){
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return true;
		}else{
			return false;
		}	

	}
 ?>