<?php

class PagSeguro{
	private static $email         = "mzaha@hotmail.com"; //SEU EMAIL USADO NO CADASTRO DO PAGSEGURO";
	private static $token_sandbox = "C8065E6BA99C4011A91CB2E6B31FF750";
	private static $token_oficial = "C8065E6BA99C4011A91CB2E6B31FF750";
	private static $url_retorno   = "http://www.wma-stand.com/pagseguro_teste/notificacao.php";
	
	//URL OFICIAL
	//COMENTE AS 4 LINHAS ABAIXO E DESCOMENTE AS URLS DA SANDBOX PARA REALIZAR TESTES

	/*
	private $url              = "https://ws.pagseguro.uol.com.br/v2/checkout/";
	private $url_redirect     = "https://pagseguro.uol.com.br/v2/checkout/payment.html?code=";
	private $url_notificacao  = 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications/';
	private $url_transactions = 'https://ws.pagseguro.uol.com.br/v2/transactions/';
	*/

	//URL SANDBOX
	//DESCOMENTAR PARA REALIZAR TESTES
	
	
	private static $url              = "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/";
	private static $url_redirect     = "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=";
	private static $url_notificacao  = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/';
	private static $url_transactions = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/';
	
	
	
	private static $email_token = "";//NÃO MODIFICAR

	/*private $statusCode = array(0=>"Pendente",
								1=>"Aguardando pagamento",
								2=>"Em análise",
								3=>"Pago",
								4=>"Disponível",
								5=>"Em disputa",
								6=>"Devolvida",
								7=>"Cancelada");
	*/
	private static $statusCode = array(
									0 => "analisando",
									1 => "analisando",
									2 => "analisando",
									3 => "pago",
									4 => "pago",
									5 => "analisando",
									6 => "cancelado",
									7 => "cancelado"
								);
		
	public function __construct(){
		self::$email_token = "?email=".self::$email."&token=". self::$token_sandbox;//$this->token_oficial;
		self::$url .= self::$email_token;
	}
		
	private static function generateUrl($dados,$retorno){
		//Configurações
		$data['email']    = self::$email;
		$data['token']    = self::$token_sandbox;//$this->token_oficial;
		$data['currency'] = 'BRL';


		//Itens
		$data['itemId1']          = '1';
		$data['itemDescription1'] = $dados['descricao'];
		$data['itemAmount1']      = $dados['valor'];
		$data['itemQuantity1']    = '1';
		$data['itemWeight1']      = '0';
		
		//Dados do pedido
		$data['reference']        = $dados['reference'];		
			
		//Dados do comprador
		
		//Tratar telefone
		//$telefone = implode("",explode("-",substr($dados['telefone'],5,strlen($dados['telefone']))));
		//$ddd = substr($dados['telefone'],1,2);
		
		//Tratar CEP
		//$cep = implode("",explode("-",$dados['cep']));
		//$cep = implode("",explode(".",$cep));
		
		$data['senderName']                = $dados['nome'];
		$data['senderAreaCode']            = $dados['ddd'];
		$data['senderPhone']               = $dados['telefone'];
		$data['senderEmail']               = $dados['email'];
		$data['shippingType']              = '3';
		$data['shippingAddressStreet']     = $dados['rua'];
		$data['shippingAddressNumber']     = $dados['numero'];
		$data['shippingAddressComplement'] = $dados['complemento'];
		$data['shippingAddressDistrict']   = $dados['bairro'];
		$data['shippingAddressPostalCode'] = $dados['cep'];
		$data['shippingAddressCity']       = $dados['cidade'];
		$data['shippingAddressState']      = strtoupper($dados['estado']);
		$data['shippingAddressCountry']    = 'BRA';
		$data['redirectURL']               = $retorno;
		$data['notificationURL']           = "http://www.wma-stand.com/pagseguro_teste/notificacao.php";


		return http_build_query($data);
	}
	
	public static function executeCheckout($dados,$retorno){
		
		if($dados['codigo_pagseguro']!="" && $dados['codigo_pagseguro']!=null){
			//header('Location: '.$this->url_redirect.$dados['codigo_pagseguro']);
		}
		
		$dados = self::generateUrl($dados,$retorno);

		//return json_encode(array("cod" => $dados));
		//exit;
		

		//return "https://pagseguro.uol.com.br/v2/checkout/payment.html?code=" . $xml->code;
		//return "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" . $xml->code;

		

		//return $xml->code;


		$curl = curl_init(self::$url);

		
		/*
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);
		$xml = curl_exec($curl);
		*/

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);
		
		//executando o curl
		$xml = curl_exec($curl);
		

		if($xml == 'Unauthorized'){
			//Insira seu código de prevenção a erros
			echo "Erro: Dados invalidos - Unauthorized";
			exit;//Mantenha essa linha
		}
		
		curl_close($curl);

		$xml_obj = simplexml_load_string($xml);

		if(count($xml_obj->error) > 0){
			//Insira seu código de tratamento de erro, talvez seja útil enviar os códigos de erros.
			//echo json_encode(array("error" => "houve um erro na requisição ao pagseguro"));
			exit;
		}




		//header('Location: '.$this->url_redirect.$xml_obj->code);

		return $xml_obj->code;
	}
	
	//RECEBE UMA NOTIFICAÇÃO DO PAGSEGURO
	//RETORNA UM OBJETO CONTENDO OS DADOS DO PAGAMENTO
	public static function executeNotification($POST){
		$url = self::$$url_notificacao.$POST['notificationCode'].self::$email_token;
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		$transaction = curl_exec($curl);
		if($transaction == 'Unauthorized'){
			//TRANSAÇÃO NÃO AUTORIZADA
			
		    exit;
		}
		curl_close($curl);
		$transaction_obj = simplexml_load_string($transaction);
		return $transaction_obj;		
	}
	
	//Obtém o status de um pagamento com base no código do PagSeguro
	//Se o pagamento existir, retorna um código de 1 a 7
	//Se o pagamento não exitir, retorna NULL
	public static function getStatusByCode($code){
		$url = self::$url_transactions.$code.self::$email_token;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		$transaction = curl_exec($curl);
		if($transaction == 'Unauthorized') {
			//Insira seu código avisando que o sistema está com problemas
			//sugiro enviar um e-mail avisando para alguém fazer a manutenção
			exit;//Mantenha essa linha para evitar que o código prossiga
		}
		$transaction_obj = simplexml_load_string($transaction);
		
		if(count($transaction_obj -> error) > 0) {
		   //Insira seu código avisando que o sistema está com problemas
		   var_dump($transaction_obj);
		}		

		if(isset($transaction_obj->status)){
			return $transaction_obj->status;
		}else{
			return NULL;
		}
	}
	
	//Obtém o status de um pagamento com base na referência
	//Se o pagamento existir, retorna um código de 1 a 7
	//Se o pagamento não exitir, retorna NULL
	public static function getStatusByReference($reference){
		$url = self::$url_transactions.self::$email_token."&reference=".$reference;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
		$transaction = curl_exec($curl);
		if($transaction == 'Unauthorized') {
			//Insira seu código avisando que o sistema está com problemas
			exit;//Mantenha essa linha para evitar que o código prossiga
		}

		$transaction_obj = simplexml_load_string($transaction);
		if(count($transaction_obj->error) > 0) {
		   //Insira seu código avisando que o sistema está com problemas
		   var_dump($transaction_obj);
		}
		//print_r($transaction_obj);
		if(isset($transaction_obj->transactions->transaction->status))
			return $transaction_obj->transactions->transaction->status;
		else
			return NULL;
	}
	
	public static function getStatusText($code){
		if($code>=1 && $code<=7):
			return self::$statusCode[$code];
		else:
			return $code; //self::$statusCode[0];
		endif;
	}
	
}
?>