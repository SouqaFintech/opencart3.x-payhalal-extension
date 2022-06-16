<?php
class ControllerExtensionPaymentPayHalal extends Controller {

  public function index() {
    $this->language->load('extension/payment/payhalal');
    $this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data["amount"] = number_format($order_info['total'],2,".","");

		if ($this->config->get('payment_payhalal_gw_status') == "LIVE") {
			  $data['action'] = 'https://api.payhalal.my/pay';
		}
		else {
			$data['action'] = 'https://api-testing.payhalal.my/pay';
		}

    $data['button_confirm'] = $this->language->get('button_confirm');

		$data["app_id"] = $this->config->get('payment_payhalal_app_key');
		$data["secret_key"] = $this->config->get('payment_payhalal_app_secret');

		$data["currency"] = $order_info['currency_code'];

		$data["order_id"] = $order_info['order_id'];

		$data["product_description"]  = "Order ID ".$order_info['order_id'];

		$data["customer_name"] 		= $order_info['payment_firstname']." ".$order_info['payment_lastname'];
		$data["customer_email"] 		= $order_info['email'];
		$data["customer_phone"] 		= $order_info['telephone'];

		$data["language"] 			= "en";

		$data["hash"] =  hash('sha256',$data["secret_key"].$data["amount"].$data["currency"].$data["product_description"].$data["order_id"].$data["customer_name"].$data["customer_email"].$data["customer_phone"]);

    return $this->load->view('extension/payment/payhalal', $data);
  }



	// Redirect to Payment Page
  public function status() {

    if (isset($this->session->data['order_id'])) {
      $order_id = $this->session->data['order_id'];
    } else {
      $order_id = 0;
    }

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info["order_status_id"] == $this->config->get('payment_payhalal_completed_status_id')) {
			  $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		}
		else {
			  $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
		}


  }


	// Server Callback
  public function callback() {

    $postData =  json_encode($_POST);

		$order_id = $_POST["order_id"];

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);


		$verify_hash =  hash('sha256',$this->config->get('payment_payhalal_app_secret').number_format($order_info['total'],2,".","").$order_info["currency_code"]."Order ID ".$order_info['order_id'].$order_info['order_id'].$order_info['payment_firstname']." ".$order_info['payment_lastname'].$order_info['email'].$_POST["transaction_id"].
									  $_POST["status"]);


		$status = $_POST['status'];

		if ($verify_hash != $_POST["hash"]) {
			$status = "ERROR"; 	$transaction_message = "Hash not valid";
		}

		$transaction_id = $_POST["transaction_id"];


    $this->log->write("Webhook received: $postData");


    if ($order_info) {

      if(isset($status) && $status == 'SUCCESS'){
        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_payhalal_completed_status_id'), "UID: $transaction_id.  Processor message: SUCCESS", true);
      }
      else {
        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_payhalal_failed_status_id'), "UID: $transaction_id. Fail reason: ".$status, true);
      }
    }
  }

  private function _language($lang_id) {
    $lang = substr($lang_id, 0, 2);
    $lang = strtolower($lang);
    return $lang;
  }
}
