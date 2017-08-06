<?php

/*
* Plugin Name: EAN, Delivery Date, Short Description and Free Shipping
* Plugin URI: http://webstudionovetrendy.eu/
* Description: Add and show few custom fields to woocommerce products. Add new: EAN field, Delivery Date field, Title Short Description field and Free Shipping badge
* Version: 170806
* Text Domain: nt-EDDSDFS
* Domain Path: /languages/
* Author: Webstudio Nove Trendy
* Author URI: http://webstudionovetrendy.eu/
* License: GPL2
* WC requires at least: 2.6
* WC tested up to: 3.0
* GitHub Plugin URI: https://github.com/novetrendy/EAN-DD-FS
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/**
* Frontend CSS
*/
function nt_add_EDDSDFS_css(){
        wp_enqueue_style( 'eddsdfs', plugins_url('assets/css/eddsdfs.css', __FILE__), false, '1.0.0', 'all');
    }
    add_action('wp_enqueue_scripts', "nt_add_EDDSDFS_css");

/**
 * Backend CSS
*/
 function nt_admin_EDDSDFS_style() {
    wp_enqueue_style('eddsdfs-admin', plugins_url('assets/css/eddsdfs-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'nt_admin_EDDSDFS_style');
add_action('login_enqueue_scripts', 'nt_admin_EDDSDFS_style');

/**
 * Localization
 */
 add_action('plugins_loaded', 'ntplugin_localization_init');
 function ntplugin_localization_init() {
 load_plugin_textdomain( 'nt-EDDSDFS', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
}

/* **************************************************************************************** */

/**
 * Add page to menu
 */



add_action( 'admin_menu', 'nt_eddsdfs_admin_menu' );
function nt_eddsdfs_admin_menu() {
     if ( empty ( $GLOBALS['admin_page_hooks']['nt-admin-page'] ) ){
    add_menu_page( __('New Trends','nt-EDDSDFS'), __('New Trends','nt-EDDSDFS'), 'manage_options', 'nt-admin-page', 'nt_EDDSDFS_page', 'dashicons-admin-tools', 3  );
}
}

function nt_EDDSDFS_page() {
    echo '<h1>' . __( 'Mainpage for setting plugins from New Trends', 'nt-EDDSDFS' ) . '</h1>';
    echo '<a target="_blank" title="' .  __('WebStudio New Trends','nt-EDDSDFS') . '" href="http://webstudionovetrendy.eu"><img alt="' .  __('WebStudio New Trends','nt-EDDSDFS') . '" title="' .  __('WebStudio New Trends','nt-EDDSDFS') . '" class="ntlogo" src=" '. plugin_dir_url( __FILE__ ) .'admin/images/logo.png" /><br /></a><hr />';
    do_action('nt_after_main_content_admin_page_loop_action');
 }

add_action('nt_after_main_content_admin_page_loop_action', 'eddsdfs_print_details');

function eddsdfs_print_details(){
  echo  '<br /><a href="' . admin_url(). 'admin.php?page=eddsdfs">' . __('EAN, Delivery Date, Short Description and Free Shipping', 'nt-EDDSDFS') . '</a><br /><p>'. __( 'Plugin EAN, Delivery Date, Short Description and Free Shipping add and show few custom fields to woocommerce products. Add new: EAN field, Delivery Date field, Title Short Description field and Free Shipping badge ', 'nt-EDDSDFS' ) .'</p><br /><hr />';
}

/**
 * Load class
 */
  require_once plugin_dir_path( __FILE__ ) . 'admin/class-eddsdfs-admin.php';


if( is_admin() )

    $eddsdfs_settings_page = new EDDSDFSPage();

/* ************************************************************************************************************* */

$dd_options = get_option('nt_eddsdfs');

/** Add Delivery Date and EAN fields to product */
// Display Fields - EAN, Delivery Date

global $post, $product;

function nt_check_product_type() {
global $product;
if( $product->is_type( 'simple' ) ){
  $nt_product_type = '1';// a simple product

} elseif( $product->is_type( 'variable' ) ){
  $nt_product_type = '2';// a variable product
}
echo $nt_product_type;
}



if (isset ($dd_options['ean_support']) == 1 && $nt_check_product_type = '3'){
            add_action( 'woocommerce_product_options_general_product_data', 'nt_add_ean_fields' );
        }


if (isset ($dd_options['delivery_date_support'] ) == 1 ) {
            add_action( 'woocommerce_product_options_general_product_data', 'nt_add_delivery_date_fields' );
        }


/** Add EAN fields to product */
function nt_add_ean_fields() {

global $woocommerce, $post;

echo '<div class="options_group show_if_simple">';
// Text Field
woocommerce_wp_text_input(
    array(
        'id' => 'ean',
        'label' => __( 'EAN', 'nt-EDDSDFS' ),
        'placeholder' => __('Enter the EAN', 'nt-EDDSDFS'),
        'desc_tip' => 'true',
        'description' => __( 'Enter the product EAN.', 'nt-EDDSDFS' )
        )
    );
    echo '</div>';
}


/** Add Delivery Date fields to product */
function nt_add_delivery_date_fields() {

global $woocommerce, $post;

echo '<div class="options_group show_if_simple">';
// Text Field
woocommerce_wp_text_input(
    array(
        'id' => 'delivery_date',
        'label' => __( 'Delivery Date', 'nt-EDDSDFS'),
        'placeholder' => __('Enter the Delivery Date', 'nt-EDDSDFS'),
        'desc_tip' => 'true',
        'description' => __( 'Doba doručení = 0:Skladem<br />
Doba=1 až 3: Do tří dnů<br />
Doba=4 až 7: Do týdne<br />
Doba=8 až 14: 1 - 2 týdny<br />
Doba=15 až 21: 2 - 3 týdny<br />
Doba=22 až 28: 2 - 4 týdny<br />
Doba=29 až 35: 3 - 5 týdnů<br />
Doba=36 až 42: 4 - 6 týdnů<br />
Doba=43 a více: Na telefonický dotaz', 'nt-EDDSDFS')
        )
    );
    echo '</div>';
}

if (isset ($dd_options['delivery_date_support']) == 1 || isset ($dd_options['ean_support']) == 1)
{

        // Save Fields - EAN, Delivery Date
        add_action( 'save_post', 'ntfields_save',1 ,2 );

function ntfields_save($post_id, $post) {
    // delivery_date
    if ( get_post_meta($post->ID, 'delivery_date', FALSE ) ) {
        update_post_meta($post->ID, 'delivery_date', $_POST['delivery_date']);
    }
    else { add_post_meta($post->ID, 'delivery_date', $_POST['delivery_date']);
    }
    if ( $_POST['delivery_date'] == '' ) {
        delete_post_meta($post->ID, 'delivery_date');
    }
    // ean
    if ( get_post_meta($post->ID, 'ean', FALSE ) ) {
        update_post_meta($post->ID, 'ean', $_POST['ean']);
    }
    else { add_post_meta($post->ID, 'ean', $_POST['ean']);
    }
    if ( $_POST['ean'] == '' ) {
        delete_post_meta($post->ID, 'ean');
    }
   }
   }

/** Show Delivery Date in product details */
        if (isset ($dd_options['delivery_date_product_details']) == 1){
            add_action( 'woocommerce_single_product_summary', 'delivery_details', 9 );
        }


// Optional: To show on archive pages
    if (isset ($dd_options['delivery_date_archive_page']) == 1){
            add_action( 'woocommerce_after_shop_loop_item_title', 'delivery_details' );
    }


function delivery_details() {
    global $product;{
        $nt_delivery_date = get_post_meta( $product->id, 'delivery_date', true );
        
        if ($nt_delivery_date == 0){$delivery_date_days_0 = $dd_options['delivery_date_0'];
            if (empty ($delivery_date_days_0)){$nt_delivery_date = __('In Stock', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_0;}

         elseif ($nt_delivery_date <= 3){$delivery_date_days_3 = $dd_options['delivery_date_3'];
            if (empty ($delivery_date_days_3)){$nt_delivery_date = __('Within three days', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_3;}

         elseif ($nt_delivery_date <= 7){$delivery_date_days_7 = $dd_options['delivery_date_7'];
            if (empty ($delivery_date_days_7)){$nt_delivery_date = __('Within a week', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_7;}

         elseif ($nt_delivery_date <= 14){$delivery_date_days_14 = $dd_options['delivery_date_14'];
            if (empty ($delivery_date_days_14)){$nt_delivery_date = __('1 - 2 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_14;}

         elseif ($nt_delivery_date <= 21){$delivery_date_days_21 = $dd_options['delivery_date_21'];
            if (empty ($delivery_date_days_21)){$nt_delivery_date = __('2 - 3 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_21;}

         elseif ($nt_delivery_date <= 28){$delivery_date_days_28 = $dd_options['delivery_date_28'];
            if (empty ($delivery_date_days_28)){$nt_delivery_date = __('2 - 4 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_28;}

         elseif ($nt_delivery_date <= 35){$delivery_date_days_35 = $dd_options['delivery_date_35'];
            if (empty ($delivery_date_days_35)){$nt_delivery_date = __('3 - 5 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_35;}

         elseif ($nt_delivery_date <= 42){$delivery_date_days_42 = $dd_options['delivery_date_42'];
            if (empty ($delivery_date_days_42)){$nt_delivery_date = __('4 - 6 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_42;}

         elseif ($nt_delivery_date >= 43){$delivery_date_days_56 = $dd_options['delivery_date_56'];
            if (empty ($delivery_date_days_56)){$nt_delivery_date = __('4 - 7 weeks', 'nt-EDDSDFS');}
            else $nt_delivery_date = $delivery_date_days_56;}

        if ( $product->is_type( 'simple' ) ){
        echo '<div class="woocommerce_dd show_if_simple"><span style="' . get_option('nt_eddsdfs')['css_delivery_date'] . '">' . $nt_delivery_date . '</span><br /></div>';
        }
}
}

/** FREE SHIPPING */
/** Show Free Shipping in product details */
    if (isset ($dd_options['free_shipping_product_details']) == 1) {
            add_action( 'woocommerce_single_product_summary', 'nt_free_shipping', 10 );
        }
// Optional: To show on archive pages
    if (isset ($dd_options['free_shipping_archive_page']) == 1) {
            add_action( 'woocommerce_after_shop_loop_item_title', 'nt_free_shipping', 20 );
 }

 function nt_free_shipping() {
/** FREE SHIPPING */
        global $product;
        $free_shipping_settings = get_option( 'woocommerce_free_shipping_settings' );
        $free_shipping_min_amount = $free_shipping_settings['min_amount'];
        $product_actual_price = $product->get_price();
         if ($free_shipping_min_amount <= $product_actual_price)
         {
                if (is_product()) {
                echo '<span style="'.get_option('nt_eddsdfs')['css_free__shipping'].'">' . __('FREE SHIPPING !!!', 'nt-EDDSDFS') . '</span><br />';
                }
                else
                echo '<br /><span class="test" style="'.get_option('nt_eddsdfs')['css_free_shipping_catalog'].'">' . __('FREE SHIPPING !!!', 'nt-EDDSDFS') . '</span><br />';
             }
         if ($product_actual_price == 0)
         {
         if (is_product()) {
         echo '<div style="' .get_option('nt_eddsdfs')['css_free__shipping']. '">' . __('FOR THE CURRENT PRICE PLEASE CALL', 'nt-EDDSDFS') . ' </div>';
         }
         else
         echo '<div style="'.get_option('nt_eddsdfs')['css_free_shipping_catalog'].'">' . __('FOR THE CURRENT PRICE PLEASE CALL', 'nt-EDDSDFS') . ' </div>';
         }
         echo '<div style="height:15px;"></div>';
    }


/* Add a badge FREE DELIVERY to archive product */
    if (isset ($dd_options['badge_free_shipping']) == 1) {
    add_action( 'woocommerce_before_shop_loop_item_title', 'badge_free_delivery', 10 );
        function badge_free_delivery (){
            global $product;
            $free_shipping_settings = get_option( 'woocommerce_free_shipping_settings' );
            $free_shipping_min_amount = $free_shipping_settings['min_amount'];
            $product_actual_price = $product->get_price();
                if ($free_shipping_min_amount <= $product_actual_price){
                if (empty(get_option('nt_eddsdfs')['css_wrap_badge_free_shipping'])){$wrap='position:absolute;top:188px;left:0;z-index:5;width:100%;';}
                else $wrap = get_option('nt_eddsdfs')['css_wrap_badge_free_shipping'];

                    echo '<span style="'.$wrap.'"><span style="'.get_option('nt_eddsdfs')['css_badge_free_shipping'].'">' . __('FREE SHIPPING !!!', 'nt-EDDSDFS') . '</span></span>';
                                                                       }
                                        }
                                                            }

/* Add short title description */
    if (isset ($dd_options['title_description_support']) == 1) {
        add_action('add_meta_boxes', 'product_title_desc_box');
        add_action('save_post', 'product_title_desc_save', 1, 2);

       function product_title_desc_box(){
        add_meta_box('product_title_desc_box', __('Short Title Description', 'nt-EDDSDFS'), 'product_title_desc_form', 'product','side', 'core');
        wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' );}

       function product_title_desc_form($post){
        wp_nonce_field( basename( __FILE__ ), 'product_title_description_nonce' );
        $title_description = get_post_meta($post->ID, 'title-description', true);
        ?>
        <p><label for="title-description"><?php echo __('Enter a short description that appears above the title product in the catalog. <br /> A maximum of 180 characters.', 'nt-EDDSDFS' )?></label><br /><br /><textarea maxlength="180" rows="6" cols="25" name="title-description" id="title-description1" value="<?php echo $title_description ; ?>"><?php echo $title_description ; ?></textarea></p> <?php
                                              }

        function product_title_desc_save($post_id, $post ){
         if(!isset( $_POST['title-description']) || !wp_verify_nonce($_POST['product_title_description_nonce'], basename(__FILE__)))
         { return $post_id; }
             $post_type = get_post_type_object( $post->post_type );
            /* Check if the current user has permission to edit the post. */
            if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
                return $post_id;
                $description = $_POST['title-description'] ;
                update_post_meta( $post_id, 'title-description', $description);
                                                        }

            add_action( 'woocommerce_before_shop_loop_item_title', 'add_short_description_before_item_title', 9 );

function add_short_description_before_item_title()
{
        global $product;
        if ( get_option('nt_eddsdfs')['title_description_add_string'] == 1) {
            $add_string = ' ...'; }
            else {
            $add_string = ''; }
        $before_title_add_description = substr(get_post_meta( $product->id, 'title-description', true ), 0, 220);
        if (!empty ($before_title_add_description)){
       echo '<span style="'.get_option('nt_eddsdfs')['css_title_descriptions'].'">' . $before_title_add_description . $add_string . '<br /></span>';
        }
}
}




}
else  {
 // If WooCommerce not active, deactivate plugin
 if ( is_admin() ) {
          add_action( 'admin_init', 'my_plugin_deactivate' );
          add_action( 'admin_notices', 'my_plugin_admin_notice' );
          function my_plugin_deactivate() {
              deactivate_plugins( plugin_basename( __FILE__ ) );
          }
          function my_plugin_admin_notice() {
               echo '<div class="updated"><p>' . __('<strong>EAN, Delivery Date, Short Description and Free Shipping</strong> requires active Woocommerce plugin. Because Woocommerce plugin is not installed or active <strong>plugin has been deactivated</ strong>.', '') . '</p></div>';
               if ( isset( $_GET['activate'] ) )
                    unset( $_GET['activate'] );
          }
        }
    }
?>
