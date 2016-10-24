<?php

class ModelPaymentMoedadigital extends Model
{

    public function getMethod($address, $total) {
        
        $method_data = array(
            'code' => 'moedadigital',
            'title' => $this->config->get('moedadigital_checkout_name') ? $this->config->get('moedadigital_checkout_name') : "Crédito (Moeda Digital)",
            'terms' => '',
            'sort_order' => $this->config->get('moedadigital_sort_order')
        );        

        return $method_data;
    }

    public function addPayment($data) {
    	$this->db->query("INSERT INTO `" . DB_PREFIX . "moedadigital_order` SET 
    	 `nsu` = '" . $this->db->escape($data['NSU']) . "',
    	 `order_id` = '" . (int)$data['order_id'] . "',
    	 `customer_id` = '" . (int)$data['customer_id'] . "',
    	 `cpf` = '" . $this->db->escape($data['cpf']) . "',
    	 `date_added` = NOW(),
    	 `date_modified` = NOW(),
    	 `retorno` = '" . $this->db->escape($data['retorno']) . "'");
    }
}
