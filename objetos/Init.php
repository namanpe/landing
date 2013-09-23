<?php 
/**
* maneja la base da datos landing:
* preapra la tabla que se va usar, borra, o modifica 
* en cada return devuelve los datos de la tabla
*/
class Init{

	private $nombreTabla	= '';
	private $columns 		= array();
	private $types 			= array('int', 'string','date','text');

	/**
	 * crea la tabla la tabla de no existir
	 *
	 * @param $tabla string
	 * @return el objeto init
	 */
	public function __construct($nombretabla){

		$query = 'CREATE TABLE IF NOT EXISTS `'.$nombretabla.'` (
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `fecha` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1; ';
		if( DataBase::query($query) ){
			$this->nombreTabla = $nombretabla;
			$DatosTabla = (DataBase::show_colums($this->nombreTabla));
			$this->columns = $DatosTabla[0];
			echo  ' <h1> Se creo la tabla <strong>'.$nombretabla.'</strong> </h1> <br>';
			return $this;
		}else{
			echo 'No se pudo crear la tabla';
		}

	}

	public function deleteColumn($column){
		$DatosTabla = (DataBase::show_colums($this->nombreTabla));
		$ColumnasTabla = $DatosTabla[0];

		if(in_array($column, $ColumnasTabla)){
			$query = 'ALTER TABLE `'.$this->nombreTabla.'`
  						DROP `'.$column.'`;';

  			if(DataBase::query($query)){
  				$DatosTabla = (DataBase::show_colums($this->nombreTabla));
				$this->columns = $DatosTabla[0];
  			}
		}

	}

	public function addColumn($nombre,$type,$despuesDe){
		switch ($type) {
			case 'int':
				$typevar = 'int(10)';
				break;

			case 'string':
				$typevar = 'varchar(256)';
				break;

			case 'date':
				$typevar = 'datetime';
				break;

			case 'text':
				$typevar = 'text';
				break;	

		}

		if(isset($typevar)){

			$tmpSql = 'ALTER TABLE  `'.$this->nombreTabla.'` ';
			$tmpSql .= ' ADD  `'.$nombre.'` '.$typevar.' NOT NULL';

			if(in_array($despuesDe, $this->columns)){
				$tmpSql .= ' AFTER  `'.$despuesDe.'`';
			}
			

			if(DataBase::query($tmpSql)){
				$DatosTabla = (DataBase::show_colums($this->nombreTabla));
				$this->columns = $DatosTabla[0];
				return true;
			}else{
				return false;
			}
		}
	}



	public function showColumns(){
		echo '<h2> Estas son las columnas de la tabla: </h2> <br>';
		if(istruearray($this->columns)){
			foreach ($this->columns as $k => $value) {
				echo $value.'<br>';
			}
		}
	}

	/**
	 * agrga las columnas que se pasa como parametro
	 *
	 * @param $tabla string
	 * @return el objeto init
	 */

	public function addColumns($arrdatos)	{

		if(istruearray($arrdatos)){
			foreach ($arrdatos as $k => $arrDato) {

				if( in_array($k,$this->types) && istruearray($arrDato) ){
					$this->sqlAddColumns($k,$arrDato);
					$DatosTabla = (DataBase::show_colums($this->nombreTabla));
					$this->columns = $DatosTabla[0];
				}
			}
		}

	}

	private function sqlAddColumns($type,$arrDato){
		
		switch ($type) {
			case 'int':
				$typevar = 'int(10)';
				break;

			case 'string':
				$typevar = 'varchar(256)';
				break;

			case 'date':
				$typevar = 'datetime';
				break;

			case 'text':
				$typevar = 'text';
				break;	

		}

		foreach ($arrDato as $k => $dato) {

			if(!in_array($dato, $this->columns)){
				$tmpSql = 'ALTER TABLE  `'.$this->nombreTabla.'` ';
				$tmpSql .= ' ADD  `'.$dato.'` '.$typevar.' NOT NULL';
				$tmpSql .= ' AFTER  `id`';

				DataBase::query($tmpSql);
			}
			
		}

	}

	

}

 ?>