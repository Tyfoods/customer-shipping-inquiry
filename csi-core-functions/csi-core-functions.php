<?php

//Check if WOOCOMMERCE is active, this plugin extends WooCommerce
  function csi_woocommerce_inactive_notice() {
    ?>
    <div id="message" class="error">
    <p><?php printf( '%sCustomer Shipping Inquiry is inactive.%s The %sWooCommerce plugin%s must be active for the Customer Shipping Inquiry to work. Please %sinstall & activate WooCommerce%s', '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
    </div>
    <?php
  }

function csi_shipping_box_text(){
  ?>
  <div id = csi-no-shipping-method-message>
    <?php
    echo 'There are no shipping methods available for your address. Would you please ensure your address is entered correctly? If it has been entered correctly, please send us your address and email with the boxes below';
    ?>
  </div>
  <form id = "csi_main_form" method="post">
      <div>
        <label for="mail">Your E-mail:</label>
        <input type="email" id="mailCSI" name="user_inquiry_mail">
      </div>
      <div>
        <label for="address">Your Address Here:</label>
        <textarea id="addressCSI" name="user_inquiry_address"></textarea>
      </div>
      <div>
        <label for="notes">Additional Notes Here:</label>
        <textarea id="notesCSI" name="user_inquiry_message"></textarea>
      </div>
      <div class="button">
        <button type="submit" name="csi_submit_button" value="csi_submit">Submit your shipping information</button>
    </div>
    </form>
    <?php
  //Store all information in vaiables to be used later using global $_POST.
  // The global $_POST variable allows you to access the data sent with the POST method by name
  $mailCSI = htmlspecialchars($_POST['user_inquiry_mail']); 
  $addressCSI  = htmlspecialchars($_POST['user_inquiry_address']);
  $notesCSI  = htmlspecialchars($_POST['user_inquiry_message']);  // To access the data sent with the GET method, you can use $_GET
  $csi_submit_button = $_POST['submitbutton'];

  //check if submit button was pressed, then check if mail/address is empty/accurate if so, then SEND EMAIL!
  if ($csi_submit_button){
    if (!empty($mailCSI) && !empty($addressCSI)) {
      csi_send_email_to_vendor($mailCSI, $addressCSI, $notesCSI);
    }
    else{
      echo 'Please enter both your email and address!';
    }
  }
}

/*
  if($_POST["user_inquiry_mail"] && $_POST["user_inquiry_address"]) { 
    csi_send_email_to_vendor($mailCSI, $addressCSI, $notesCSI);
  }
  else{
    echo 'Please input your email and address!';
  }
  */

function csi_send_email_to_vendor($mailCSI, $addressCSI, $notesCSI){
  $vendor_email = csi_get_product_id_from_cart();

  $customer_inquiry_message = 'Here is my address: '.$addressCSI.'<br></br>'.$notesCSI;
  $headers = 'From: ' . $mailCSI . "\r\n";
  wp_mail($vendor_email, 'Customer Shipping Inquiry', $customer_inquiry_message, $headers); //sends email to vendor with necessary information

  }

function csi_get_product_id_from_cart(){
  global $WCFM, $woocommerce;
  foreach( WC()->cart->get_cart() as $cart_item ){
    // compatibility with WC +3
      if( version_compare( WC_VERSION, '3.0', '<' ) )
      {
        $product_id = $cart_item['data']->id; // Before version 3.0
        if (class_exists( 'WCFM' ) ) {
            $vendor_email = $WCFM->wcfm_vendor_support->wcfm_get_vendor_email_from_product( $product_id );
            return $vendor_email;
        }
        else
        {
          $vendor_email = get_option( 'admin_email' ); //get email of blog adminstrator
          return $vendor_email;
        }
      } 
      else
      {
        $product_id = $cart_item['data']->get_id(); // For version 3 or more
        if (class_exists( 'WCFM' ) ) {
            $vendor_email = $WCFM->wcfm_vendor_support->wcfm_get_vendor_email_from_product( $product_id );
            return $vendor_email;
        }
        else
        {
          $vendor_email = get_option( 'admin_email' ); //get email of blog adminstrator
          return $vendor_email;
        }
      }
    }
  }

function csi_main(){
  csi_shipping_box_text();
  //csi_send_email_to_vendor();
  //csi_get_product_id_from_cart();
}
