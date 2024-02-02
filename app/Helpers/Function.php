<?php 
function __replace($valor){
	return str_replace(",", ".", $valor);
}

function __insMaster($email){
	$masters = explode(";", getenv("MASTERMAIL"));

	if(in_array($email, $masters)){
		return true;
	}
	return false;
}