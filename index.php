<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="divEspera"><br><br><br><br><br><br><center><h1>Finalizando Operação...</h1></center></div>
<!--<form name="formulario" id="form" action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this);">-->
	<form name="formulario" id="form" action="https://sandbox.pagseguro.uol.com.br/checkout/v2/payment.html" method="post">
		Referencia:<input type="text" name="reference" value="" id="reference"><br />
		Valor:<input type="text"        name="valor" value="" id="valor" /><br>
		Descrição:<input type="text"    name="descricao" value="" id="descricao" /><br>
		Seu Nome:<input type="text"     name="nome" value="" id="nome" /><br>
		E-mail:<input type="text"       name="email" value="" id="email" /><br>
		
		<!--
		DDD<input type="text"           name="ddd" maxlength="2" value="" id="ddd"> &nbsp; 
		Telefone:<input type="text"     name="telefone" value="" id="telefone" /><br>
		Rua:<input type="text"          name="rua"  value="" id="rua"/><br />
		Número:<input type="text"       name="numero" value=""  id="numero"/><br />
		Bairro:<input type="text"       name="bairro" value=""  id="bairro"/><br />
		Cidade:<input type="text"       name="cidade" value=""  id="cidade"/><br />
		Estado:<input type="text"       name="estado"  value="" id="estado"/><br />
		CEP:<input type="text"          ame="cep" value=""  id="cep"/><br />
		-->
		<input type="hidden" id="cod"   name="code" value="" />
		<button type="button" id="enviar">Enviar</button>
	</form>
	<script type="text/javascript" src="jquery.min.js"></script>

	<script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
	
	
	<!--
	<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
	-->	

	<script>
		$("#enviar").on("click", function(){

			var dados = $("#form").serialize();

			//alert(dados);



			var reference  = $("#reference").val();
			var valor      = $("#valor").val();
			var descricao  = $("#descricao").val();
			var nome       = $("#nome").val();
			var email      = $("#email").val();
			var ddd        = $("#ddd").val();
			var telefone   = $("#telefone").val();
			var rua        = $("#rua").val();
			var numero     = $("#numero").val();
			var bairro     = $("#bairro").val();
			var cidade     = $("#cidade").val();
			var estado     = $("#estado").val();
			var cep        = $("#cep").val();


			$.ajax({
				url:"checkout_teste.php",
				type:"post",
				dataType:"text",
				data:dados, /*reference:reference, valor:valor, descricao:descricao, nome:nome, email:email, ddd:ddd, telefone:telefone, rua:rua, numero:numero, bairro:bairro, cidade:cidade, estado:estado, cep:cep},*/		
				beforeSend:function(){
					$("#enviar").text("Carregando, por favor aguarde...");					
				},
				success:function(dados){
					$("#enviar").text("Enviar");
					$("#cod").val(dados);
					//alert(dados);
					PagSeguroLightbox({
						code:dados
						},{
						success:function(transactionCode){
							$.ajax({
								url:"getStatusByCode.php",
								type:"post",
								dataType:"text",
								data:{transaction_code:transactionCode,reference:reference},
								beforeSend:function(){
									$(".divEspera").css("display", "block");
								},
								success:function(dt){
									$(".divEspera").css("display", "none");
									//alert(dt);
								},
								error:function(xhr, err){
									console.log("Deu Erro");
								}
							});
						},
						abort:function(){
							console.log("Erro linha 97");
						}
					});
				},
				error:function(xhr, err){
					console.log("xhr:"+xhr.toString()+", error:"+err);
				}
			});
		});


			
		
	</script>
</body>
</html>