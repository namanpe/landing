<?php
	Class Tabla {
		
		private $_lstCampos 	= array();
		private $_arrCampos 	= array();
		private $_lstPK 		= array();
		private $campoAI 		= '';
		private $nombreTabla	= '';
		private $modo 			= 'nuevo';
		private $_debug 		= false;
		
		public function debug($bool){
			$this->_debug = $bool;
		}
		
		public function getCampos(){
			return $this->_arrCampos;
		}
		
		public function __construct($nombre) {
			$arr = DataBase::show_colums($nombre);
			$this->_lstCampos 	= $arr[0];
			$this->_lstPK 		= $arr[1];
			$this->campoAI 		= $arr[2];
			$this->nombreTabla 	= $nombre;
		}
		
		public function set($nombreCampo,$valor){
			$this->_arrCampos[$nombreCampo] = $valor;
		}
	
		public function get($nombreCampo){
			return $this->_arrCampos[$nombreCampo];
		}
		
		public function setModo($modo){
			$this->modo = $modo;
		}
		
		public function getListPK(){
			return $this->_lstPK;
		}
		
		public function getListCampos(){
			return $this->_lstCampos;
		}
		
		public function save($ignore=false){
			if(count($this->_arrCampos)>0){
				
				if($ignore==true) $ignore = 'IGNORE';
				
				switch($this->modo){
				
					case 'actualizar':
						$query = 'UPDATE '.$ignore.' '.$this->nombreTabla.' SET ';
						$i = 0;
						foreach($this->_lstCampos as $campo){
							if($this->campoAI != $campo){
								$query .= $i > 0 ? ', ':'';
								$query .= ' '.$campo." = '".sqlS($this->get($campo))."' ";
								$i++;
							}
						}
						$query .= ' WHERE '; 
						
						foreach($this->_lstPK as $i => $campo){
							$query .= $i > 0 ? ' AND ':'';
							$query .= ' '.$campo." =  '".$this->get($campo)."' ";
						}
					
					break;
					
					case 'nuevo':
						if($ignore==true)
							$ignore = 'IGNORE';
				
						$query = 'INSERT '.$ignore.' INTO '. $this->nombreTabla .' (';
						$i = 0;
						$fields = '';
						$values = '';
						foreach($this->_lstCampos as $campo){
							if($this->campoAI != $campo){
								$fields .= $i > 0 ? ', ':'';
								$fields .= ' '.$campo.' ';
								
								$values .= $i > 0 ? ', ':'';
								$values .= " '".sqlS($this->_arrCampos[$campo])."' ";
								
								$i++;
							}
						}
						$query .= $fields.' ) VALUES ( '.$values.' )';
					
					break;
				}
				
				if($this->_debug == true) echo $query;
				

				if(DataBase::query($query)){
					$campos = $this->_lstCampos;
					if($this->campoAI!='' && $this->get($this->campoAI)==''){
						$this->set($this->campoAI,DataBase::insert_id());
					}
					$this->setModo('actualizar');
					return true;						
				}else{
					return false;				
				}
			}			
		}

		public function getByPK($nombretabla,$pk, $lnk=array()){
		
			$o = new Tabla($nombretabla);
			$sqlPK = '';
			$arrPK = $o->getListPK();
			
			if(count($arrPK)>1 && is_array($pk)){
				foreach($arrPK as $i => $namePK){
					$sqlPK .= $i > 0 ? ' AND ':'';
					$sqlPK .= $namePK.' = "'.$pk[$i].'"';
				}	
			}elseif(count($arrPK)==1 && $pk!=''){
				$sqlPK = $arrPK[0].' = "'.$pk.'"'; 
			}
			
			if($sqlPK!=''){
				$query = 'SELECT * FROM '.$nombretabla.' WHERE '.$sqlPK;
				
				$fila = DataBase::fetch(DataBase::query($query));
				if(count($fila) && is_array($fila)){
					$arrCampos = $o->getListCampos();
					$o->setModo('actualizar');
					foreach($arrCampos as $campo){
						$o->set($campo,$fila[$campo]);
					}
					return $o;
				}				
			}
			return false;
		}
		
		public function delete($nombretabla,$pk,$fisico=false){
			$o = new Tabla($nombretabla);
			$sqlPK = '';
			$arrPK = $o->getListPK();
			
			if(count($arrPK)>1 && is_array($pk)){
				foreach($arrPK as $i => $namePK){
					$sqlPK .= $i > 0 ? ' AND ':'';
					$sqlPK .= $namePK.' = "'.$pk[$i].'"';
				}	
			}elseif(count($arrPK)==1 && $pk!=''){
				$sqlPK = $arrPK[0].' = "'.$pk.'"'; 
			}
			
			if($sqlPK!=''){
				$arrCampos = $o->getListCampos();
				if(in_array('Eliminado',$arrCampos)== false || $fisico == true){
					$query = 'DELETE FROM '.$nombretabla.' WHERE '.$sqlPK;
				}else{
					$query = 'UPDATE  '.$nombretabla.' SET Eliminado = "1"  WHERE '.$sqlPK;
				}
				return DataBase::query($query);
			}
			return false;
		}
		
		
		/*STATIC*/
		
		static public function getRow($arr=''){
			if($arr['select']!='') 	$select = ' SELECT '.$arr['select'];
			else 				   	$select = ' SELECT *';
			
			if($arr['from']!='') 	$from 	= ' FROM '.$arr['from'];

			if($arr['join']!='') 	$join 	= ' '.$arr['join'];
			
			if($arr['where']!='') 	$where 	= ' WHERE '.$arr['where'];

			if($arr['having']!='') 	$having	= ' HAVING '.$arr['having'];

			if($arr['group']!='') 	$group 	= ' GROUP BY '.$arr['group'];

			if($arr['order']!='') 	$order 	= ' ORDER BY '.$arr['order'];
			
			if($arr['limit']!='') 	$limit 	= ' LIMIT '.$arr['limit'];


			$sql = $select. $from . $join . $where . $having . $group . $order . $limit;
			
			if($arr['debug']== true) print $sql;
			
			$query = DataBase::query($sql);
			
			if($arr['count'] == true)	return DataBase::count_rows($query);
			elseif($arr['one']== true)	return DataBase::fetch($query);		
			else						return DataBase::fetch_array($query);
		}
		
		
	}
?>
