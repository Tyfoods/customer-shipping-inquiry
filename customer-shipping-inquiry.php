<?php
/*
Plugin Name: Customer Shipping Inquiry
Plugin URI: https://www.propdanceculture.com
Description: Often times we do not set up shipping for every possible country. This discourages customers from countries who we have not set up shipping for. This plugin prompts these customers to send their address information and email so that we can set up their shipping and therefore achieve a higher conversion rate.
Author: TyFoods
Author URI: https://www.propdanceculture.com/profile/?tyfooodsgmail-com-2/
Version: 1.0.0
*/

//Exit if accessed directly
if(!defined('ABSPATH')){
	exit;
}

//Check if WooCommerce exists, if yes, load style sheet that is dependent on WooCommerce select2.css style sheet
if ( ! class_exists( 'WooCommerce' ) ) {
	add_action( 'admin_notices', 'csi_woocommerce_inactive_notice' );
}

//not registering CCS because style will not be enqued elsewhere
//add_action('wp_enqueue_scripts',
//wp_enqueue_style('csi-main-form-css'); //load css AFER WooCommerce Loads

require_once (dirname(__FILE__).'/'.'csi-core-functions/csi-core-functions.php'); //require main plugin functionality

//loads scripts
function csi_load_styles(){
  wp_enqueue_style( 'csi-main-form-css', plugin_dir_url( __DIR__).'customer-shipping-inquiry/assets/css/csi-main-form-css.css', plugin_dir_url( __DIR__).'woocommerce/assets/css/select2.css');
//The above code is NOT working correctly because CSI CSS loads earlier than woocommerce still


  /*There is a difference between SITE PATH & SERVER PATH, you need to specifically request URL in web asset cases*/
}
//Change text from default to next text


add_filter('woocommerce_no_shipping_available_html', 'csi_main'); //When there is no shipping available HTML Form is created
add_action( 'wp_enqueue_scripts', 'csi_load_styles'); //LOADS CSS