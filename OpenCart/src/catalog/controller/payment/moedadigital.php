<?php

class ControllerPaymentMoedadigital extends Controller
{
	private $json = array();

	private function getTotal(){
		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;
		
		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
		    'totals' => &$totals,
		    'taxes' => &$taxes,
		    'total' => &$total
		);
		
		$this->load->model('extension/extension');
		
		$sort_order = array();
		
		$results = $this->model_extension_extension->getExtensions('total');
		
		foreach ($results as $key => $value) {
		    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
		    if ($this->config->get($result['code'] . '_status')) {
		        $this->load->model('total/' . $result['code']);
		        
		        // We have to put the totals in an array so that they pass by reference.
		        $this->{'model_total_' . $result['code']}->getTotal($total_data);
		    }
		}

		return $total_data['total'];
	}

	private function valueFormat($value){
		return number_format( $value , 2 , "," , "" );
	}
	private function getValorParcela(){		
		return $this->valueFormat($this->getTotal()/(int)$this->request->post['parcelas']);
	}
	public function index()
	{
		
		$this->load->language('payment/pp_standard');
		
		$data['text_testmode'] = $this->language->get('text_testmode');
		$data['text_finalizar'] = "Finalizar Compra";
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$data['testmode'] = $this->config->get('pp_standard_test');
		
		$data['action'] = $this->url->link('payment/moedadigital/payment', '', 'SSL');
		
		$this->document->addScript("/catalog/view/javascript/moedadigital/jquery.mask.min.js");

		$moedadigital_url = 'http://www.moedadigital.net/Gateway.asmx?WSDL'; 

		$soap = new SoapClient($moedadigital_url);

		$array_meios_pagamento = array( 
		    "Loja" => $this->config->get("moedadigital_token"),
		    "Aplicacao" => $this->config->get("moedadigital_lojaapp"), 
		    "Meios" => "credito"
		);

		$Result = $soap->ConsultaMeiosDePagamento($array_meios_pagamento);
		$data['bandeiras'] = $Result->ConsultaMeiosDePagamentoResult->RetornoMeiosPagamento; 

		// $arrayParcelas = array( 
		//     "Loja" => $this->config->get("moedadigital_token"),
		//     "Aplicacao" => $this->config->get("moedadigital_lojaapp"), 
		//     "Valor" => $this->cart->getTotal()
		// );

		// $Result = $soap->ConsultaParcelas($arrayParcelas);


		$soap = new SoapClient($moedadigital_url);

		$array_meios_pagamento = array(
			"Loja" => $this->config->get("moedadigital_token"),
		    "Aplicacao" => $this->config->get("moedadigital_lojaapp"), 
		    "Valor" => $this->valueFormat($this->getTotal())
		);

		$Result = $soap->ConsultaParcelasArray($array_meios_pagamento);

		$data["jsonParcelas"] = json_encode($Result);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/moedadigital.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/moedadigital.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/moedadigital.tpl', $data);
		}
	}

	public function payment(){
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$order_id = (int)$this->session->data['order_id'];
		if ($order_info && $this->validate()) {

			$xmlRequisicao = 
			'
				<?xml version="1.0" encoding="utf-8"?>
				<clsPedido xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
					<Cliente>
						<DataCadastro></DataCadastro>
						<Nome>' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . '</Nome>
						<Sobrenome>' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8') . '</Sobrenome>
						<RazaoSocial></RazaoSocial>
						<Genero>' . 'M' . '</Genero>
						<CpfCnpj>' . html_entity_decode($this->request->post['cpf'], ENT_QUOTES, 'UTF-8') . '</CpfCnpj>
						<NascAbertura></NascAbertura>
						<Login></Login>
						<Moeda>' . $order_info['currency_code'] . '</Moeda>
						<Idioma>' . 'PT-BR' . '</Idioma>
						<IpCadastro>'. '177.79.25.01' . '</IpCadastro>
						<Score></Score>
						<Notas></Notas>
					</Cliente>
					<Endereco1>
						<Endereco>' . html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . '</Endereco>
						<Numero>' . '123' . '</Numero>
						<Complemento></Complemento>
						<Bairro>' . 'Vila Olimpia' . '</Bairro>
						<Cidade>' . html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8') . '</Cidade>
						<UF>' . html_entity_decode($order_info['payment_zone_code'], ENT_QUOTES, 'UTF-8') . '</UF>
						<CEP>' . html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8') . '</CEP>
						<Pais>' . 'BR' . '</Pais>
						<DDD></DDD>
						<Telefone>' . '(11) 9999-99999' . '</Telefone>
						<Tipo>' . 'Entrega' . '</Tipo>
					</Endereco1>
					<Email1>
						<Email>' . $order_info['email'] . '</Email>
					</Email1>
					<LojaChaveAcesso>' . $this->config->get("moedadigital_token") . '</LojaChaveAcesso>
					<LojaApp>' . $this->config->get("moedadigital_lojaapp") . '</LojaApp>
					<LojaCanal>' . 'WEB' . '</LojaCanal>
					<MeiosdePagamento>' . html_entity_decode($this->request->post['bandeira'], ENT_QUOTES, 'UTF-8') . '</MeiosdePagamento>
					<PedidoNumeroLoja>' . (int)$this->session->data['order_id'] . '</PedidoNumeroLoja>
					<PedidoEmissao>' . date("d/m/Y H:m:s", time())  . '</PedidoEmissao>
					<PedidoVencimento>' . date("d/m/Y H:m:s", strtotime('+30 day', time())) . '</PedidoVencimento>
					<PedidoExpiracao>' . date("d/m/Y H:m:s", strtotime('+30 day', time())) . '</PedidoExpiracao>
					<PedidoRecorrente>' . 'N' . '</PedidoRecorrente>
					<PedidoValor>' . 'R$ ' .  $this->valueFormat($this->getTotal()) . '</PedidoValor>
					<PedidoValorSemJuros>' . 'R$ ' . $this->valueFormat($this->getTotal()) . '</PedidoValorSemJuros>
					<PedidoMulta>' . "0" . '</PedidoMulta>
					<PedidoJuros>' . 'R$ ' . '0,00' . '</PedidoJuros>
					<PedidoItens>' . $this->cart->countProducts() . '</PedidoItens>
					<PedidoParcelas>' . (int)$this->request->post['parcelas'] . '</PedidoParcelas>
					<PedidoValorParcelas>' . 'R$ ' . $this->getValorParcela() . '</PedidoValorParcelas>
					<PedidoFinanciador>' . '1' . '</PedidoFinanciador>
					<PedidoInstrucoes></PedidoInstrucoes>
					<PedidoStatus></PedidoStatus>
					<PedidoProduto></PedidoProduto>
					<PortadorCartao>' . preg_replace("/[^0-9]/", "", $this->request->post['card']) . '</PortadorCartao>
					<PortadorValidade>' . html_entity_decode($this->request->post['mes'], ENT_QUOTES, 'UTF-8') . '/' . html_entity_decode($this->request->post['ano'], ENT_QUOTES, 'UTF-8') . '</PortadorValidade>
					<PortadorCVV>' . html_entity_decode($this->request->post['ccv'], ENT_QUOTES, 'UTF-8') . '</PortadorCVV>
					<PortadorNome>' . html_entity_decode($this->request->post['nome'], ENT_QUOTES, 'UTF-8') . '</PortadorNome>
				</clsPedido> 
			';

			$moedadigital_url = 'http://www.moedadigital.net/Gateway.asmx?WSDL'; 
			$soap = new SoapClient($moedadigital_url);

			$parametros = array ( 'PedidoXML' => $xmlRequisicao);

			$Result = $soap->IniciarPagamento($parametros);

			if ($this->config->get('moedadigital_debug')) {
				$this->log->write("MoedaDigital (result IniciarPagamento) :: " . json_encode($Result));
			}

			if ($Result) {

				$this->load->model('checkout/order');
				$this->load->model('payment/moedadigital');

				$pedido = array(
					'NSU' => $Result->IniciarPagamentoResult->NSU,
					'order_id' => $order_id,
					'customer_id' => $order_info['customer_id'],
					'cpf' => $this->request->post['cpf'],
					'retorno' => json_encode($Result)
				);
				
				$this->model_payment_moedadigital->addPayment($pedido);

				$order_status_id = $this->config->get('config_order_status_id');
				
				switch ($Result->IniciarPagamentoResult->PedidoStatus) {
					case 'APROVADO':
						$order_status_id = $this->config->get('moedadigital_status_aprovado_id');
						break;
					case 'CANCELADO':
						$order_status_id = $this->config->get('moedadigital_status_cancelado_id');
						break;
					case 'NEGADO':
						$order_status_id = $this->config->get('moedadigital_status_negado_id');
						break;
					case 'PENDENTE':
						$order_status_id = $this->config->get('moedadigital_status_pendente_id');
						break;
					case 'CHARGEBACK':
						$order_status_id = $this->config->get('moedadigital_status_chargeback_id');
						break;
					case 'EM ANALISE':
						$order_status_id = $this->config->get('moedadigital_status_analise_id');
						break;
					case 'AGENDADO':
						$order_status_id = $this->config->get('moedadigital_status_estorno_id');
						break;
					case 'ESTORNADO':
						$order_status_id = $this->config->get('moedadigital_status_agendado_id');
						break;
				}

				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

				$data = array('redirect' => $this->url->link('checkout/success', '', true) );

				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($data));
			}

		}else{

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($this->json));

		}
	}

	public function callback()
	{
		$this->log->write("MOEDA CALLBAAAAAACK " . json_encode($_REQUEST));
		exit;
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$request = 'cmd=_notify-validate';
			
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			if (! $this->config->get('pp_standard_test')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			
			$response = curl_exec($curl);
			
			if (! $response) {
				$this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			
			if ($this->config->get('pp_standard_debug')) {
				$this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
				$this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
			}
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				$order_status_id = $this->config->get('config_order_status_id');
				
				switch ($this->request->post['payment_status']) {
					case 'Canceled_Reversal':
						$order_status_id = $this->config->get('pp_standard_canceled_reversal_status_id');
						break;
					case 'Completed':
						$receiver_match = (strtolower($this->request->post['receiver_email']) == strtolower($this->config->get('pp_standard_email')));
						
						$total_paid_match = ((float) $this->request->post['mc_gross'] == $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false));
						
						if ($receiver_match && $total_paid_match) {
							$order_status_id = $this->config->get('pp_standard_completed_status_id');
						}
						
						if (! $receiver_match) {
							$this->log->write('PP_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower($this->request->post['receiver_email']));
						}
						
						if (! $total_paid_match) {
							$this->log->write('PP_STANDARD :: TOTAL PAID MISMATCH! ' . $this->request->post['mc_gross']);
						}
						break;
					case 'Denied':
						$order_status_id = $this->config->get('pp_standard_denied_status_id');
						break;
					case 'Expired':
						$order_status_id = $this->config->get('pp_standard_expired_status_id');
						break;
					case 'Failed':
						$order_status_id = $this->config->get('pp_standard_failed_status_id');
						break;
					case 'Pending':
						$order_status_id = $this->config->get('pp_standard_pending_status_id');
						break;
					case 'Processed':
						$order_status_id = $this->config->get('pp_standard_processed_status_id');
						break;
					case 'Refunded':
						$order_status_id = $this->config->get('pp_standard_refunded_status_id');
						break;
					case 'Reversed':
						$order_status_id = $this->config->get('pp_standard_reversed_status_id');
						break;
					case 'Voided':
						$order_status_id = $this->config->get('pp_standard_voided_status_id');
						break;
				}
				
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
			} else {
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
			}
			
			curl_close($curl);
		}
	}

	private function validate(){

		$needed = "Campo obrigatório";

		if ($this->request->post['mes'] != "") {
			if ((int)$this->request->post['mes'] > 12 || (int)$this->request->post['mes'] < 1 || strlen($this->request->post['mes']) < 2) {
				$this->json['error']['mes'] = "Confira o mês";
			} 
		}else{
			$this->json['error']['mes'] = $needed;
		}

		if ($this->request->post['ano'] != "") {
			if ((int)$this->request->post['ano'] < date('y')) {
				$this->json['error']['ano'] = "Confira o ano";
			} 
		}else{
			$this->json['error']['ano'] = $needed;
		}

		if ($this->request->post['ccv'] != "") {
			if (strlen((int)$this->request->post['ccv']) != 3) {
				$this->json['error']['ccv'] = "Confira o ccv";
			} 
		}else{
			$this->json['error']['ccv'] = $needed;
		}

		if ($this->request->post['parcelas'] != "") {
			if ((int)$this->request->post['parcelas'] < 1 || (int)$this->request->post['parcelas'] > 12) {
				$this->json['error']['parcelas'] = "Confira o parcelas";
			} 
		}else{
			$this->json['error']['parcelas'] = $needed;
		}

		if ($this->request->post['card'] != "") {
			if (!$this->validaCARD($this->request->post['card'])) {
				$this->json['error']['card'] = "Confira o Cartão";
			} 
		}else{
			$this->json['error']['card'] = $needed;
		}

		if ($this->request->post['nome'] != "") {
			if (count(explode(" ", trim($this->request->post['nome']))) == 1) {
				$this->json['error']['nome'] = "Confira o nome";
			} 
		}else{
			$this->json['error']['nome'] = $needed;
		}

		if (!isset($this->request->post['bandeira'])) {
			$this->json['error']['bandeira'] = $needed;
		}

		if ($this->request->post['cpf'] != "") {
			if (strlen($this->request->post['cpf']) > 14) {
				if (!$this->validaCNPJ($this->request->post['cpf'])) {
					$this->json['error']['cpf'] = "CNPJ inválido";
				}
			} else {
				if (!$this->validaCPF($this->request->post['cpf'])) {
					$this->json['error']['cpf'] = "CPF inválido";
				}
			}
		}else{
			$this->json['error']['cpf'] = $needed;
		}


		if (!empty($this->json['error'])) {
			return false;
		}

		return true;
	}

	private function validaCPF($cpf = null) {

	    // Verifica se um número foi informado
		if(empty($cpf)) {
			return false;
		}

	    // Elimina possivel mascara
		$cpf = preg_replace('/[^0-9]/', '', $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

	    // Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
			return false;
		}
	    // Verifica se nenhuma das sequências invalidas abaixo 
	    // foi digitada. Caso afirmativo, retorna falso
		else if ($cpf == '00000000000' || 
			$cpf == '11111111111' || 
			$cpf == '22222222222' || 
			$cpf == '33333333333' || 
			$cpf == '44444444444' || 
			$cpf == '55555555555' || 
			$cpf == '66666666666' || 
			$cpf == '77777777777' || 
			$cpf == '88888888888' || 
			$cpf == '99999999999') {
			return false;

	     // Calcula os digitos verificadores para verificar se o
	     // CPF é válido

		} else {   

			for ($t = 9; $t < 11; $t++) {

				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf{$c} != $d) {
					return false;
				}
			}

			return true;
		}
	}
	private function validaCNPJ($cnpj)	{
		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
		// Valida tamanho
		if (strlen($cnpj) != 14)
			return false;
		// Valida primeiro dígito verificador
		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
		{
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $soma % 11;
		if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
			return false;
		// Valida segundo dígito verificador
		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
		{
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $soma % 11;
		return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
	}
	private function validaCARD($number) {

		// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
		$number=preg_replace('/\D/', '', $number);

		// Set the string length and parity
		$number_length=strlen($number);
		$parity=$number_length % 2;

		// Loop through each digit and do the maths
		$total=0;
		for ($i=0; $i<$number_length; $i++) {
			$digit=$number[$i];
			// Multiply alternate digits by two
			if ($i % 2 == $parity) {
				$digit*=2;
				// If the sum is two digits, add them together (in effect)
				if ($digit > 9) {
					$digit-=9;
				}
			}
			// Total up the digits
			$total+=$digit;
		}

		// If the total mod 10 equals 0, the number is valid
		return ($total % 10 == 0) ? TRUE : FALSE;
	}

}