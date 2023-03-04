<?php
class ControllerExtensionPaymentPayHalal extends Controller {
  private $error = array();

  public function index() {
    $this->load->language('extension/payment/payhalal');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('payment_payhalal', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
    }

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');

    $data['text_live_mode'] = $this->language->get('text_live_mode');
    $data['text_test_mode'] = $this->language->get('text_test_mode');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_completed_text'] = $this->language->get('entry_order_status_completed_text');
		$data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
		$data['entry_order_status_failed_text'] = $this->language->get('entry_order_status_failed_text');

		//
		$data['payment_payhalal_app_key'] = $this->language->get('payment_payhalal_app_key');
		$data['payment_payhalal_app_secret'] = $this->language->get('payment_payhalal_app_secret');
		$data['payment_payhalal_app_key_testing'] = $this->language->get('payment_payhalal_app_key_testing');
    $data['payment_payhalal_app_secret_testing'] = $this->language->get('payment_payhalal_app_secret_testing');
    $data['entry_email'] = $this->language->get('entry_email');

    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');


    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['tab_general'] = $this->language->get('tab_general');


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/payment/payhalal', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/payment/payhalal', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);

		//
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_payhalal_completed_status_id'])) {
			$data['payment_payhalal_completed_status_id'] = $this->request->post['payment_payhalal_completed_status_id'];
		} else {
			$data['payment_payhalal_completed_status_id'] = $this->config->get('payment_payhalal_completed_status_id');
		}

		if (isset($this->request->post['payment_payhalal_failed_status_id'])) {
			$data['payment_payhalal_failed_status_id'] = $this->request->post['payment_payhalal_failed_status_id'];
		} else {
			$data['payment_payhalal_failed_status_id'] = $this->config->get('payment_payhalal_failed_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


    if (isset($this->request->post['payment_payhalal_status'])) {
      $data['payment_payhalal_status'] = $this->request->post['payment_payhalal_status'];
    } else {
      $data['payment_payhalal_status'] = $this->config->get('payment_payhalal_status');
    }



    if (isset($this->request->post['payment_payhalal_sort_order'])) {
      $data['payment_payhalal_sort_order'] = $this->request->post['payment_payhalal_sort_order'];
    } else {
      $data['payment_payhalal_sort_order'] = $this->config->get('payment_payhalal_sort_order');
    }

		if (isset($this->request->post['payment_payhalal_app_key'])) {
			$data['payment_payhalal_app_key'] = $this->request->post['payment_payhalal_app_key'];
		} else {
			$data['payment_payhalal_app_key'] = $this->config->get('payment_payhalal_app_key');
		}

		if (isset($this->request->post['payment_payhalal_app_secret'])) {
			$data['payment_payhalal_app_secret'] = $this->request->post['payment_payhalal_app_secret'];
		} else {
			$data['payment_payhalal_app_secret'] = $this->config->get('payment_payhalal_app_secret');
		}
	  
	    if (isset($this->request->post['payment_payhalal_app_key_testing'])) {
	      $data['payment_payhalal_app_key_testing'] = $this->request->post['payment_payhalal_app_key_testing'];
	    } else {
	      $data['payment_payhalal_app_key_testing'] = $this->config->get('payment_payhalal_app_key_testing');
	    }

	    if (isset($this->request->post['payment_payhalal_app_secret_testing'])) {
	      $data['payment_payhalal_app_secret_testing'] = $this->request->post['payment_payhalal_app_secret_testing'];
	    } else {
	      $data['payment_payhalal_app_secret_testing'] = $this->config->get('payment_payhalal_app_secret_testing');
	    }

		if (isset($this->request->post['payment_payhalal_gw_status'])) {
			$data['payment_payhalal_gw_status'] = $this->request->post['payment_payhalal_gw_status'];
		} else {
			$data['payment_payhalal_gw_status'] = $this->config->get('payment_payhalal_gw_status');
		}


    $data['user_token'] = $this->session->data['user_token'];

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/payment/payhalal', $data));
  }

  private function validate() {



    return !$this->error;
  }
}
