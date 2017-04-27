<?php
	header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
	header("Content-Type: text/html; charset=UTF-8",true);
	date_default_timezone_set('America/Sao_Paulo');


	require_once("PagSeguroAllFunctions.php");
	require_once('Banco.class.php');
	$b = new Banco();

	//$f = fopen('log_notificacoes.txt', "a+");
	//fwrite($f, "Está acessando a página.");
	//fclose($f);

	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		



		$PagSeguro = new PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);

		$f = fopen('log_notificacoes.txt', "a+");	
		fwrite($f, "Não está dando erro ate aqui.\n");
		//$resp = print_r($response);
		fwrite($f, "Referencia:" . $response->reference);
		fclose($f);	

		if($response->status==3 || $response->status==4){
        	//PAGAMENTO CONFIRMADO
			//ATUALIZAR O STATUS NO BANCO DE DADOS
			$sql = "
				UPDATE 
					tabela_status 
				SET 
					status = '" . $response->status . "' 
				WHERE 
					reference = '" . $response->reference . "'";
			$b->query($sql);
		}else{
			//PAGAMENTO PENDENTE
			//$f = fopen('log_notificacoes.txt', "a+");	
			//fwrite($f, $PagSeguro->getStatusText($PagSeguro->status));
			//fclose($f);	
			//echo $PagSeguro->getStatusText($PagSeguro->status);
		}
	}
?>