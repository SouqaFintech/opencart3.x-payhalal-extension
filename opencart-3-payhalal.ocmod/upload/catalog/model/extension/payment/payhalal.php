<?php

class ModelExtensionPaymentPayHalal extends Model {

  public function getMethod($address, $total) {

    $this->load->language('extension/payment/payhalal');

    $method_data = array();


      $method_data = array(
        'code'       => 'payhalal',
        'title'      => "Pay with PayHalal",
        'terms'      => '',
        'sort_order' => 1
      );

    return $method_data;
  }
}

?>
