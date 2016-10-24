<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<script src="/catalog/view/javascript/moedadigital/jquery.mask.min.js"></script>
<div class="moedadigital">
	<div class="abas">
		<div class="aba-credito">Cartão de crédito</div>
	</div>
	<div class="conteudo-aba-credito">
		<form id="moedadigital-frm" action="<?php echo $action; ?>" method="POST">
			<div class="bandeiras campo">
				<?php foreach ($bandeiras as $bandeira) { ?>
					<div class="bandeira">
						<label class="bandeira-img" for="input-<?php echo strtolower($bandeira->Nome); ?>">
							<img src="<?php echo $bandeira->Imagem; ?>" alt="">
						</label>
						<input id="input-<?php echo strtolower($bandeira->Nome); ?>" value="<?php echo $bandeira->Nome; ?>" name="bandeira" type="radio" data-parcela="<?php echo $bandeira->Parcelado; ?>">
					</div>
				<?php } ?>
				<div class="error"></div>
			</div>
			<div class="linha">
				<div class="col-6">
					<div class="campo">
						<label for="input-card">Número do cartão</label>
						<input id="input-card" type="text" name="card" >
						<div class="error"></div>
					</div>
					<div class="linha">
						<div class="col-12">
							<div class="linha">
								<div class="col-6 campo">
									<label for="input-mes">Data de validade</label>
									<div class="linha">
										<div class="col-6 pr4">
											<input id="input-mes" type="text" name="mes" placeholder="mês">
										</div>
										<div class="col-6 pl4">
											<input id="input-ano" type="text" name="ano" placeholder="ano">
										</div>
									</div>
									<div class="error"></div>
								</div>
								<div class="col-6 campo">
										<label for="input-ccv">Código de segurança</label>
										<input id="input-ccv" type="text" name="ccv" placeholder="ccv">
										<div class="error"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="campo">
						<div class="clearfix"></div>
						<div class="parcelas">
							<label for="parcelas">Número de parcelas</label>
							<select name="parcelas" id="parcelas"></select>
							<div class="error"></div>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="campo">
						<div class="parcelas">
							<label for="parcelas">Nome do titular do cartão</label>
							<input type="text" name="nome" placeholder="Extremamente como impresso no cartão">
							<div class="error"></div>
						</div>
					</div>
					<div class="campo">
						<div class="parcelas">
							<label for="input-cpf">CPF ou CNPJ do titular</label>
							<input id="input-cpf" type="text" name="cpf" placeholder="">
							<div class="error"></div>
						</div>
					</div>
					<div class="campo invisible">
						<div class="parcelas">
							<label for="parcelas">CPF ou CNPJ do titular</label>
							<input type="text" >
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="linha valign espacotopo">
				<div class="col-6 tal">
					<p>
						<b class="accent">Importante:</b> O limite disponível no cartão de crédito deve ser superior ao valor total da compra, e não ao valor de cada parcela.
					</p>
				</div>
				<div class="col-6 tar">
					<input type="button" class="pagar" value="<?php echo $text_finalizar; ?>">
				</div>
			</div>
			<div class="clearfix"></div>

		</form>
	</div>
	<script>
		var createOptions = ({Parcela: Parcela, ValorTotal, ValorParcela, Obs}) => {
			if (Parcela > 0) {
				$("#parcelas").append("<option value=\"" + Parcela + "\">" + Parcela + " de R$ " + ValorParcela + "</option>");
			}
		}
		var parcelas = <?php echo $jsonParcelas; ?>;
		parcelas.ConsultaParcelasArrayResult.RetornoParcelas.map(createOptions);
		$('#input-card').mask('9999 9999 9999 9999');
		$('#input-ccv').mask('999');
		$('#input-mes').mask('99');
		$('#input-ano').mask('99');
		$('#input-cpf').mask('999.999.999-99?999').focusout(function (event) {  
			var target, phone, element;  
			target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
			phone = target.value.replace(/\D/g, '');
			element = $(target);  
			element.unmask();  
			if(phone.length > 11) {  
				element.mask("99.999.999/9999-99");  
			} else {  
				element.mask("999.999.999-99?999");  
			}  
		});
		$('.moedadigital').on('click', '.pagar', function(e) {
			$('.pagar').val('carregando...');
			$('.pagar').attr('disabled','disabled');
			e.preventDefault();
			$.ajax({
				 url: "<?php echo $action; ?>"
				,type: "post"
				,data: $('#moedadigital-frm').serialize()
				,success: (res)=>{
					console.log(res);
					$('.error').text('');

					if (res.error !== null && typeof res.error === 'object') {
						$.each(res.error, function(i,e){
							$('[name=' + i + ']').closest('.campo').find('.error').text(e);
						})
					};

					if (res.redirect) {
						window.location = res.redirect;
					}else{
						$('.pagar').val('<?php echo $text_finalizar; ?>');
						$('.pagar').removeAttr('disabled');
					}
				}
			});

			

			return false;
		});
	</script>
	<style>
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.bandeira{
		display: inline-block;
		text-align: center;
		margin-right: 10px;
	}
	.bandeira-img{
		height: 46px;;
		display: block;
		overflow: hidden;
		margin-bottom: 5px;
	}
	.bandeira-img img{
		height: 60px;
	}
	.aba-credito{
		display: inline-block;
		padding: 20px 45px;
		font-size: 25px;
		border: 1px solid #dedede;
		border-bottom: none;
		position: relative;
		top: 1px;
		background-color: #fff;
		color: #F9A825;
	}
	.conteudo-aba-credito{
		padding: 30px;
		border: 1px solid #dedede;
		background-color: #fff;
	}
	.col-6 {
		width: 50%;
		padding-left: 20px;
		padding-right: 20px;
		display: inline-block;
		vertical-align: top;
		float: left;
	}
	.col-12{
		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
		display: inline-block;
		vertical-align: top;
	}
	.error {
		padding-left: 12px;
		font-size: 13px;
		color: #bf0000;
	}
	.linha {
		margin-left: -20px;
		margin-right: -20px;
	}
	.campo{
		margin-top: 20px;
	}
	.campo input[type="text"]{
		display: block;
		width: 100%;
		height: 31px;
		border-radius: 4px;
		border: none;
		background-color: rgb(239, 239, 239);
		padding-left: 12px;
	}
	.campo label {
		display: block;
		margin-bottom: 5px;
		font-size: 16px;
		color: #696969;
	}
	.campo select{
		display: block;
		width: 100%;
		height: 31px;
		padding: 8px 13px;
		font-size: 12px;
		line-height: 1.028571;
		color: #555555;
		border: none;
		background-color: rgb(239, 239, 239);
		background-image: none;
		border-radius: 3px;
	}
	.pr4{
		padding-right: 4px;
	}
	.pl4{
		padding-left: 4px;
	}
	.invisible{
		visibility: hidden !important;
	}
	.tar{
		text-align: right;
	}
	.pagar{
		background-color: #F9A825;
		color: #fff;
		border: none;
		font-weight: bolder;
		font-size: 17px;
		padding: 10px 30px;
		border-radius: 4px;
		box-shadow: inset 0px -2px 4px -2px rgba(0, 0, 0, 0.62);
	}
	.accent{
		color: #f9a927;
	}
	.clearfix:after { 
	   content: "."; 
	   visibility: hidden; 
	   display: block; 
	   height: 0; 
	   clear: both;
	}
	.valign{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		-ms-align-items: center;
		align-items: center;
	}
	.espacotopo{
		margin-top: 40px;
		display: flex;
		align-items: center;
	}
	.campo input[type="text"]:focus, .campo select:focus {
		outline: none;
		box-shadow: 0 0 2px 1px #ababab;
	}
	.moedadigital{
		font-family: 'Roboto', sans-serif;
	}
	</style>
</div>