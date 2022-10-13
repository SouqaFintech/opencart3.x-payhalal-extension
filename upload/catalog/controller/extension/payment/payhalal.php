<?php
class ControllerExtensionPaymentPayHalal extends Controller {

  public function index() {

    header('Set-Cookie: ' . $this->config->get('session_name') . '=' . $this->session->getId() . '; SameSite=None; Secure' . '; HttpOnly');

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

		$data["product_description"]  = "Opencart Order ID ".$order_info['order_id'];

		$data["customer_name"] 		= $order_info['payment_firstname']." ".$order_info['payment_lastname'];
		$data["customer_email"] 		= $order_info['email'];
		$data["customer_phone"] 		= $order_info['telephone'];

		$data["language"] 			= "en";

		$data["hash"] =  hash('sha256',$data["secret_key"].$data["amount"].$data["currency"].$data["product_description"].$data["order_id"].$data["customer_name"].$data["customer_email"].$data["customer_phone"]);

    return $this->load->view('extension/payment/payhalal', $data);
  }



	// Redirect to Payment Page
  public function status() {
	  
	header('Set-Cookie: ' . $this->config->get('session_name') . '=' . $this->session->getId() . '; SameSite=None; Secure' . '; HttpOnly');
	
	if ($this->config->get('payment_payhalal_gw_status') == "LIVE") {
		$key = $this->config->get('payment_payhalal_app_key');
		$secret = $this->config->get('payment_payhalal_app_secret');
	}
	else {
		$key = $this->config->get('payment_payhalal_app_key_testing');
		$secret = $this->config->get('payment_payhalal_app_secret_testing');
	}
	  
  	$post_array = $_POST;

  	$this->language->load('extension/payment/payhalal');
    $this->load->model('checkout/order');
  	$order_info = $this->model_checkout_order->getOrder($post_array['order_id']);
    $amount = number_format($order_info['total'],2);
    $currency = $order_info['currency_code'];
    $product_description = "Opencart Order ID ".$order_info['order_id'];
    $order_id = $order_info['order_id'];
    $customer_name = $order_info['firstname']." ".$order_info['lastname'];
    $customer_email = $order_info['email'];
    $customer_phone = $order_info['telephone'];
    $hash = hash('sha256', $secret.$amount.$currency.$product_description.$order_id. $customer_name . $customer_email . $customer_phone . $post_array['status']);

    if ($hash == $post_array['hash'] && $post_array['amount'] == $amount) {
    		// echo 1;
    		$order_status = $this->config->get('config_order_status_id');

    		foreach( $post_array As $r => $o )
        {
            $data .= $r . "=" . $o . "<br>";
        }

    		 if ( $post_array['status'] == "SUCCESS" )  {

                $order_status_id = $this->config->get('payment_payhalal_completed_status_id');
                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $data);
                $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));

         } else {
                $order_status_id = $this->config->get('payment_payhalal_failed_status_id');
                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $data);
                $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
         }
    }
    else
    {
    	  $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
    }

  }


	// Server Callback
  public function callback() {
    $data = "";
    $post_array = $_POST;

  	$this->language->load('extension/payment/payhalal');
    $this->load->model('checkout/order');
  	$order_info = $this->model_checkout_order->getOrder($post_array['order_id']);

  	$secret = $this->config->get('payment_payhalal_app_secret');
    $amount = number_format($order_info['total'],2);
    $currency = $order_info['currency_code'];
    $product_description = "Opencart Order ID ".$order_info['order_id'];
    $order_id = $order_info['order_id'];
    $customer_name = $order_info['firstname']." ".$order_info['lastname'];
    $customer_email = $order_info['email'];
    $customer_phone = $order_info['telephone'];
    $hash = hash('sha256', $secret.$amount.$currency.$product_description.$order_id. $customer_name . $customer_email . $customer_phone . $post_array['status']);

    

    if ($hash == $post_array['hash'] && $post_array['amount'] == $amount) {
    		// echo 1;
    	$order_status = $this->config->get('config_order_status_id');

        

    	foreach( $post_array As $r => $o )
        {
            $data .= $r . "=" . $o . "<br>";
        }

    		 if ( $post_array['status'] == "SUCCESS" )  {

                $order_status_id = $this->config->get('payment_payhalal_completed_status_id');
                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $data);

         } else {
                $order_status_id = $this->config->get('payment_payhalal_failed_status_id');
                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $data);
         }
    }
  }

  private function _language($lang_id) {
    $lang = substr($lang_id, 0, 2);
    $lang = strtolower($lang);
    return $lang;
  }

  public function ph_sha256($data, $secret)
  {
      $hash = hash('sha256', $secret . $data["amount"] . $data["currency"] . $data["product_description"] . $data["order_id"] . $data["customer_name"] . $data["customer_email"] . $data["customer_phone"] . $data["status"]);
      return $hash;
  }
}
