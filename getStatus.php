<?php
header("access-control-allow-origin: https://pagseguro.uol.com.br");
/*
header("Content-Type: text/html; charset=UTF-8",true);
date_default_timezone_set('America/Sao_Paulo');
*/


require_once("PagSeguroAllFunctions.php");
require_once("Banco.class.php");

if(isset($_GET['reference']) && ($_GET['reference'] != ""))
{

	$PagSeguro = new PagSeguro();
	$P = $PagSeguro::getStatusByReference($_GET['reference']);
	$status = $PagSeguro::getStatusText($P->status);

	$B = new Banco();

	$sql = "INSERT INTO tabela_status(status) VALUES('" . $status . "')";

	$B->query($sql);
}


//}else{
   // echo "Parâmetro \"reference\" não informado!";
//}

?>