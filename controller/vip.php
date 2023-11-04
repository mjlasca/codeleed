<?php
include("../../SQL.php");

function getUrlFile($id)  {
	$con = new SQL();
	$datos = array();

	$enlaceUsuario = $con->comprobarDato("zonavip_EnlaceUsuario", "zonavip", " WHERE zonavip_ID='$id'");
	if(!empty($enlaceUsuario)){
		$datos["linkUs"] = $enlaceUsuario;
		$datos["exito"] = true;
	}else{
		$datos["exito"] = false;
	}
	return $datos;
}

