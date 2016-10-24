<?php

class ModelPaymentMoedadigital extends Model
{

    public function install()
    {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "moedadigital_order` (
			  `moedadigital_order_id` int(11) NOT NULL AUTO_INCREMENT,
              `nsu` varchar(255) NOT NULL,
			  `order_id` int(11) NOT NULL,
              `customer_id` int(11) NOT NULL,
              `cpf` varchar(20) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `retorno` text DEFAULT NULL,
			  PRIMARY KEY (`moedadigital_order_id`)
			) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci
		");
        
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "moedadigital_callback` (
			  `callback_id` int(11) NOT NULL AUTO_INCREMENT,
			  `recebido_json` int(11) NOT NULL,
			  PRIMARY KEY (`callback_id`)
			) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci
		");
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE `" . DB_PREFIX . "moedadigital_order`");
        $this->db->query("DROP TABLE `" . DB_PREFIX . "moedadigital_callback`");
    }


    public function log($data, $title = null)
    {
        if ($this->config->get('pp_express_debug')) {
            $this->log->write('PayPal Express debug (' . $title . '): ' . json_encode($data));
        }
    }
    
    private function curl($endpoint, $additional_opts = array())
    {
        $default_opts = array(
            CURLOPT_PORT => 443,
            CURLOPT_HEADER => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_URL => $endpoint
        );
        
        $ch = curl_init($endpoint);
        
        $opts = $default_opts + $additional_opts;
        
        curl_setopt_array($ch, $opts);
        
        $response = json_decode(curl_exec($ch));
        
        curl_close($ch);
        
        return $response;
    }
}