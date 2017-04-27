<?php

header("access-control-allow-origin: https://pagseguro.uol.com.br");
header("Content-Type: text/html; charset=UTF-8",true);
date_default_timezone_set('America/Sao_Paulo');


require_once("PagSeguroAllFunctions.php");
$PagSeguro = new PagSeguro();

$dados['reference']        = $_POST['reference'];
$dados['valor']            = $_POST['valor'];
$dados['descricao']        = $_POST['descricao'];
$dados['nome']             = $_POST['nome'];
$dados['email']            = $_POST['email'];
$dados['ddd']              = $_POST['ddd'];
$dados['telefone']         = $_POST['telefone'];
$dados['rua']              = $_POST['rua'];
$dados['numero']           = $_POST['numero'];
$dados['bairro']           = $_POST['bairro']; 
$dados['cidade']           = $_POST['cidade'];
$dados['estado']           = $_POST['estado'];
$dados['cep']              = $_POST['cep'];
$dados['complemento']      = "";
$dados['codigo_pagseguro'] = "";

$code = $PagSeguro->executeCheckout($dados, "http://www.wma-stand.com/pagseguro_teste/notification.php");//"http://www.wma-stand.com/pagseguro_teste/notification.php");
echo $code;
//RECEBER RETORNO
if( isset($_GET['transaction_id']) ){

	$pagamento = $PagSeguro->getStatusByReference($_GET['reference']);
	
	$pagamento->codigo_pagseguro = $_GET['transaction_id'];
	if($pagamento->status==3 || $pagamento->status==4){
		//ATUALIZAR DADOS DA VENDA, COMO DATA DO PAGAMENTO E STATUS DO PAGAMENTO
		
	}else{
		//ATUALIZAR NA BASE DE DADOS
	}
}