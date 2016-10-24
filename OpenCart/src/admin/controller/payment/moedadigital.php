<?php

class ControllerPaymentMoedadigital extends Controller
{

    private $error = array();

    public function index()
    {
        $language = $this->load->language('payment/moedadigital');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('moedadigital', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }
        
        // passa array de linguagens para array data
        foreach ($language as $key => $value) {
            $data[$key] = $value;
        }

        if($this->request->post['moedadigital_status_cancelado_id']){
            $data['moedadigital_status_cancelado_id'] = (int)$this->request->post['moedadigital_status_cancelado_id'];
        }else{
            $data['moedadigital_status_cancelado_id'] = $this->config->get('moedadigital_status_cancelado_id');
        }


        if($this->request->post['moedadigital_lojaapp']){
            $data['moedadigital_lojaapp'] = (int)$this->request->post['moedadigital_lojaapp'];
        }else{
            $data['moedadigital_lojaapp'] = $this->config->get('moedadigital_lojaapp');
        }

        if($this->request->post['moedadigital_status_aprovado_id']){
            $data['moedadigital_status_aprovado_id'] = (int)$this->request->post['moedadigital_status_aprovado_id'];
        }else{
            $data['moedadigital_status_aprovado_id'] = $this->config->get('moedadigital_status_aprovado_id');
        }

        if($this->request->post['moedadigital_status_negado_id']){
            $data['moedadigital_status_negado_id'] = (int)$this->request->post['moedadigital_status_negado_id'];
        }else{
            $data['moedadigital_status_negado_id'] = $this->config->get('moedadigital_status_negado_id');
        }

        if($this->request->post['moedadigital_status_pendente_id']){
            $data['moedadigital_status_pendente_id'] = (int)$this->request->post['moedadigital_status_pendente_id'];
        }else{
            $data['moedadigital_status_pendente_id'] = $this->config->get('moedadigital_status_pendente_id');
        }

        if($this->request->post['moedadigital_status_invalido_id']){
            $data['moedadigital_status_invalido_id'] = (int)$this->request->post['moedadigital_status_invalido_id'];
        }else{
            $data['moedadigital_status_invalido_id'] = $this->config->get('moedadigital_status_invalido_id');
        }

        if($this->request->post['moedadigital_status_chargeback_id']){
            $data['moedadigital_status_chargeback_id'] = (int)$this->request->post['moedadigital_status_chargeback_id'];
        }else{
            $data['moedadigital_status_chargeback_id'] = $this->config->get('moedadigital_status_chargeback_id');
        }

        if($this->request->post['moedadigital_token']){
            $data['moedadigital_token'] = $this->request->post['moedadigital_token'];
        }else{
            $data['moedadigital_token'] = $this->config->get('moedadigital_token');
        }

        if($this->request->post['moedadigital_num_aplicacao']){
            $data['moedadigital_num_aplicacao'] = $this->request->post['moedadigital_num_aplicacao'];
        }else{
            $data['moedadigital_num_aplicacao'] = $this->config->get('moedadigital_num_aplicacao');
        }
        
        if($this->request->post['moedadigital_status_analise_id']){
            $data['moedadigital_status_analise_id'] = $this->request->post['moedadigital_status_analise_id'];
        }else{
            $data['moedadigital_status_analise_id'] = $this->config->get('moedadigital_status_analise_id');
        }

        if($this->request->post['moedadigital_status_estorno_id']){
            $data['moedadigital_status_estorno_id'] = $this->request->post['moedadigital_status_estorno_id'];
        }else{
            $data['moedadigital_status_estorno_id'] = $this->config->get('moedadigital_status_estorno_id');
        }

        if($this->request->post['moedadigital_status_agendado_id']){
            $data['moedadigital_status_agendado_id'] = $this->request->post['moedadigital_status_agendado_id'];
        }else{
            $data['moedadigital_status_agendado_id'] = $this->config->get('moedadigital_status_agendado_id');
        }

        if($this->request->post['moedadigital_status']){
            $data['moedadigital_status'] = $this->request->post['moedadigital_status'];
        }else{
            $data['moedadigital_status'] = $this->config->get('moedadigital_status');
        }

        if($this->request->post['moedadigital_checkout_name']){
            $data['moedadigital_checkout_name'] = $this->request->post['moedadigital_checkout_name'];
        }else{
            $data['moedadigital_checkout_name'] = $this->config->get('moedadigital_checkout_name');
        }


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/moedadigital', 'token=' . $this->session->data['token'], true)
        );
        
        $data['action'] = $this->url->link('payment/moedadigital', 'token=' . $this->session->data['token'], true);
        
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);
        
        if (isset($this->request->post['moedadigital_email'])) {
            $data['moedadigital_email'] = $this->request->post['moedadigital_email'];
        } else {
            $data['moedadigital_email'] = $this->config->get('moedadigital_email');
        }
                
        $this->load->model('localisation/order_status');
        
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
                
        if (isset($this->request->post['moedadigital_sort_order'])) {
            $data['moedadigital_sort_order'] = $this->request->post['moedadigital_sort_order'];
        } else {
            $data['moedadigital_sort_order'] = $this->config->get('moedadigital_sort_order');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('payment/moedadigital.tpl', $data));
    }

    private function validate()
    {
        if (! $this->user->hasPermission('modify', 'payment/moedadigital')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (! $this->request->post['moedadigital_email']) {
            $this->error['email'] = $this->language->get('error_email');
        }
        
        return ! $this->error;
    }

    public function install()
    {
        $this->load->model('payment/moedadigital');
        $this->model_payment_moedadigital->install();
    }
}