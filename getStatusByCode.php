<?php

require_once "Banco.class.php";
require_once "PagSeguroAllFunctions.php";

$transaction_code = $_POST['transaction_code'];
$reference        = $_POST['reference'];

$p = new PagSeguro();

$status = $p->getStatusByCode($transaction_code);

$b = new Banco();
$sql = "
	INSERT INTO 
		tabela_status
		(
			status, 
			referencia, 
			cod_transacao
		) 
	VALUES 
		(
			'".$status."', 
			'".$reference."', 
			'".$transaction_code."'
		)";
$b->query($sql);

echo $status;