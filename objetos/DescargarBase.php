<?php 

	/**
	* 
	*/
	class DescargarBase {
		private $nombreTabla = '';
		private $arrColumnas = array();
		private $nombreArchivo = '';


		function __construct($nombre, $nombreArchivo='Descargar Base'){

			$arrDatosTabla = DataBase::show_colums($nombre);
			$this->arrColumnas = $arrDatosTabla[0];
			$this->nombreTabla = $nombre;
			$this->nombreArchivo = $nombreArchivo;
		}

		function run(){

			$filaCampos = '';
			$cantidad = count($this->arrColumnas);
			if(istruearray($this->arrColumnas)){
				foreach ($this->arrColumnas as $key => $columna) {
					$filaCampos .= '"'.$columna.'"';
					if( $cantidad-1 > $key ) $filaCampos .= ';';
					//if( $cantidad-1 == $key ) $filaCampos .= '\n';
				}
			}


			header("Content-Type: application/force-download;"); 
			header('Content-Disposition: attachment; filename="'.' '.$this->nombreArchivo.' - '.date('Y-m-d_H-i').'.csv"');
			//echo "ID;Nombre;Apellido;Email;Telefono;Celular;Domicilio;Localidad;Provincia;Empresa;Cargo;Rubro;Cuit;SituacionIVA;FormaPago;Paquete;Fecha\n";
echo $filaCampos;
echo "
";
			//echo '\n';


			$arrSql['select'] = '*';
			$arrSql['from'] = $this->nombreTabla;
			$arrSql['order'] = ' id DESC';
			$arrDatos = Tabla::getRow($arrSql);

			if(istruearray($arrDatos)){
				foreach ($arrDatos as $key => $arrDato) {
					if(istruearray($arrDato)){
						$cont = 0;
						$filaDatos = '';
						foreach ($arrDato as $nombre => $value) {
							$filaDatos .= '"'.$value.'"';
							if( count($arrDato)-1 > $cont ) $filaDatos .= ';';
							//if( count($arrDato)-1 == $cont ) $filaDatos .= '\n';
							$cont++;
						}

echo $filaDatos;
echo "
";
						//echo '\n';
					}	
				}
			}
		}
	}

 ?>