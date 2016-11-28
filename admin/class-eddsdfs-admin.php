<?php
class EDDSDFSPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Construct
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_eddsdfs_page' ) );
        add_action( 'admin_init', array( $this, 'eddsdfs_page_init' ) );
    }

    /**
     * Add settings page
     */
    public function add_eddsdfs_page()
    {
        // Add submenu page will be under "New Trends" page
        add_submenu_page(
            'nt-admin-page',
             __('Settings EAN, Delivery Date, Short Description and Free Shipping','nt-EDDSDFS'),
            __('EAN, Delivery Date, Short Description and Free Shipping','nt-EDDSDFS'),
            'manage_woocommerce',
            'eddsdfs',
            array( $this, 'nt_create_admin_eddsdfs_page', )
        );

        // REMOVE THE SUBMENU CREATED BY add_menu_page
            //global $submenu;
            //unset( $submenu['nt-admin-page'][] );
    }

    /**
     * Options page callback
     */
    public function nt_create_admin_eddsdfs_page()
    {
        // Set class property
        $this->options = get_option( 'nt_eddsdfs' );
        ?>
<script>
jQuery(document).ready(function(){

    if(document.getElementById('delivery_date_support').checked) {
    jQuery(".section_delivery_date, .del_date").show(1000);
} else {
    jQuery(".section_delivery_date, .del_date").hide(1000);
}

jQuery('#delivery_date_support').click(function() {
    jQuery(".section_delivery_date, .del_date").toggle(1000);
});

/*
if(document.getElementById('check_nt_note_2').checked) {
    jQuery(".nt_note_2").show(1000);
} else {
    jQuery(".nt_note_2").hide(1000);
}

jQuery('#check_nt_note_2').click(function() {
    jQuery(".nt_note_2").toggle(1000);
});
*/

})
</script>

        <div class="wrap">
            <?php echo '<a target="_blank" title="' .  __('WebStudio New Trends','nt-PWOVAT') . '" href="http://webstudionovetrendy.eu"><img alt="' .  __('WebStudio New Trends','nt-PWOVAT') . '" title="' .  __('WebStudio New Trends','nt-PWOVAT') . '" class="ntlogo" src=" '. plugin_dir_url( __FILE__ ) .'images/logo.png" /><br /></a><hr />';?>
            <h2><?php _e('EAN, Delivery Date, Short Description and Free Shipping - Plugin settings', 'nt-EDDSDFS')?></h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'nt_eddsdfs_option_group' );
                do_settings_sections( 'eddsdfs' );
                submit_button();
            ?>
            </form>
        </div>

        <?php  }

    /**
     * Register and add settings
     */
    public function eddsdfs_page_init()
    {
        register_setting(
            'nt_eddsdfs_option_group', // Group settings
            'nt_eddsdfs', // Name of setting
            array( $this, 'sanitize' ) // Sanitize
        );
        add_settings_section(
            'setting_section_nt_eddsdfs', // ID
             __('Settings','nt-EDDSDFS'), // Title
            array( $this, 'print_section_info' ), // Callback
            'eddsdfs' // Page
        );
        add_settings_field(
            'ean_support', // Enable support for EAN ?
            __('Enable support for EAN ?','nt-EDDSDFS'),
            array( $this, 'ean_support_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'title_description_support', // Enable support for Title Short Descriptions ?
            __('Enable support for Title Short Descriptions ?','nt-EDDSDFS'),
            array( $this, 'title_description_support_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'title_description_add_string', // Add string " ..." after Title Descriptions ?
            __('Add string " ..." after Title Descriptions ?','nt-EDDSDFS'),
            array( $this, 'title_description_add_string_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'free_shipping_product_details', // Show Free Shipping on product details?
            __('Show Free Shipping on product details?','nt-EDDSDFS'),
            array( $this, 'free_shipping_product_details_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'free_shipping_archive_page', // Show Free Shipping on archive pages?
            __('Show Free Shipping on archive pages?','nt-EDDSDFS'),
            array( $this, 'free_shipping_archive_page_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'badge_free_shipping', // Show Free Shipping Badge?
            __('Show Free Shipping Badge ?','nt-EDDSDFS'),
            array( $this, 'badge_free_shipping_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs'
        );
        add_settings_field(
            'delivery_date_support', // Enable support for Delivery Date ?
            __('Enable support for Delivery Date ?','nt-EDDSDFS'),
            array( $this, 'delivery_date_support_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs',
            array( 'class' => 'nt-title' )
        );
        add_settings_field(
            'delivery_date_product_details', // Show Delivery Date on product details?
            __('Show Delivery Date on product details?','nt-EDDSDFS'),
            array( $this, 'delivery_date_product_details_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs',
            array( 'class' => 'del_date nt-title' )
        );
        add_settings_field(
            'delivery_date_archive_page', // Show Delivery Date on archive pages?
            __('Show Delivery Date on archive pages?','nt-EDDSDFS'),
            array( $this, 'delivery_date_archive_page_callback'),
            'eddsdfs',
            'setting_section_nt_eddsdfs',
            array( 'class' => 'del_date nt-title' )
        );

        /** Delivery Date description */
        add_settings_section(
            'setting_section_delivery_date_eddsdfs', // ID
             '<h2 class="del_date nt-title">' . __('Settings Delivery Date','nt-EDDSDFS').'<h2>', // Title
            array( $this, 'print_section_delivery_date' ), // Callback
            'eddsdfs' // Page
        );
        add_settings_field(
            'delivery_date_0',
            __('Delivery Date 0 day:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_0_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_3',
            __('Delivery Date 1 - 3 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_3_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_7',
            __('Delivery Date 4 - 7 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_7_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_14',
            __('Delivery Date 8 - 14 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_14_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_21',
            __('Delivery Date 15 - 21 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_21_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_28',
            __('Delivery Date 22 - 28 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_28_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_35',
            __('Delivery Date 29 - 35 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_35_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_42',
            __('Delivery Date 36 - 42 days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_42_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );
        add_settings_field(
            'delivery_date_56',
            __('Delivery Date 43 - infinite days:','nt-EDDSDFS'), // Title
            array( $this, 'delivery_date_56_callback' ), // Callback
            'eddsdfs', // Page
            'setting_section_delivery_date_eddsdfs', // Section
            array( 'class' => 'section_delivery_date' )
        );

        /**************************************/
        add_settings_section(
            'setting_section_css_eddsdfs', // ID
             __('Settings frontend CSS','nt-EDDSDFS'), // Title
            array( $this, 'print_section_css' ), // Callback
            'eddsdfs' // Page
        );
        add_settings_field(
            'css_title_descriptions', // CSS Title Descriptions
            __('CSS rules for Title Descriptions','nt-EDDSDFS'),
            array( $this, 'css_title_descriptions_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );
        add_settings_field(
            'css_delivery_date', // CSS Delivery Date
            __('CSS rules for Delivery Date','nt-EDDSDFS'),
            array( $this, 'css_delivery_date_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );
        add_settings_field(
            'css_free__shipping', // CSS Free--shipping
            __('CSS rules for Free Shipping (archive page)','nt-EDDSDFS'),
            array( $this, 'css_free__shipping_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );
        add_settings_field(
            'css_free_shipping_catalog', // CSS Free-shipping-catalog
            __('CSS rules for Free Shipping (catalog)','nt-EDDSDFS'),
            array( $this, 'css_free_shipping_catalog_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );
        add_settings_field(
            'css_badge_free_shipping', // CSS Badge-shipping
            __('CSS rules for Badge Free Shipping','nt-EDDSDFS'),
            array( $this, 'css_badge_free_shipping_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );
        add_settings_field(
            'css_wrap_badge_free_shipping', // CSS Wrap-badge-shipping
            __('CSS rules for Wrap Badge Free Shipping','nt-EDDSDFS'),
            array( $this, 'css_wrap_badge_free_shipping_callback'),
            'eddsdfs',
            'setting_section_css_eddsdfs',
            array( 'class' => 'css_rules' )
        );

        /*************************************/
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['delivery_date_support'] ) ) $new_input['delivery_date_support'] = (int)( $input['delivery_date_support'] );
        if( isset( $input['ean_support'] ) ) $new_input['ean_support'] = (int)( $input['ean_support'] );
        if( isset( $input['title_description_support'] ) ) $new_input['title_description_support'] = (int)( $input['title_description_support'] );
        if( isset( $input['title_description_add_string'] ) ) $new_input['title_description_add_string'] = (int)( $input['title_description_add_string'] );
        if( isset( $input['delivery_date_product_details'] ) ) $new_input['delivery_date_product_details'] = (int)( $input['delivery_date_product_details'] );
        if( isset( $input['delivery_date_archive_page'] ) ) $new_input['delivery_date_archive_page'] = (int)( $input['delivery_date_archive_page'] );
        if( isset( $input['free_shipping_product_details'] ) ) $new_input['free_shipping_product_details'] = (int)( $input['free_shipping_product_details'] );
        if( isset( $input['free_shipping_archive_page'] ) ) $new_input['free_shipping_archive_page'] = (int)( $input['free_shipping_archive_page'] );
        if( isset( $input['badge_free_shipping'] ) ) $new_input['badge_free_shipping'] = (int)( $input['badge_free_shipping'] );
        if( isset( $input['delivery_date_0'] ) ) $new_input['delivery_date_0'] = sanitize_text_field( $input['delivery_date_0'] );
        if( isset( $input['delivery_date_3'] ) ) $new_input['delivery_date_3'] = sanitize_text_field( $input['delivery_date_3'] );
        if( isset( $input['delivery_date_7'] ) ) $new_input['delivery_date_7'] = sanitize_text_field( $input['delivery_date_7'] );
        if( isset( $input['delivery_date_14'] ) ) $new_input['delivery_date_14'] = sanitize_text_field( $input['delivery_date_14'] );
        if( isset( $input['delivery_date_21'] ) ) $new_input['delivery_date_21'] = sanitize_text_field( $input['delivery_date_21'] );
        if( isset( $input['delivery_date_28'] ) ) $new_input['delivery_date_28'] = sanitize_text_field( $input['delivery_date_28'] );
        if( isset( $input['delivery_date_35'] ) ) $new_input['delivery_date_35'] = sanitize_text_field( $input['delivery_date_35'] );
        if( isset( $input['delivery_date_42'] ) ) $new_input['delivery_date_42'] = sanitize_text_field( $input['delivery_date_42'] );
        if( isset( $input['delivery_date_56'] ) ) $new_input['delivery_date_56'] = sanitize_text_field( $input['delivery_date_56'] );
        if( isset( $input['css_title_descriptions'] ) ) $new_input['css_title_descriptions'] = sanitize_text_field( $input['css_title_descriptions'] );
        if( isset( $input['css_delivery_date'] ) ) $new_input['css_delivery_date'] = sanitize_text_field( $input['css_delivery_date'] );
        if( isset( $input['css_free__shipping'] ) ) $new_input['css_free__shipping'] = sanitize_text_field( $input['css_free__shipping'] );
        if( isset( $input['css_free_shipping_catalog'] ) ) $new_input['css_free_shipping_catalog'] = sanitize_text_field( $input['css_free_shipping_catalog'] );
        if( isset( $input['css_badge_free_shipping'] ) ) $new_input['css_badge_free_shipping'] = sanitize_text_field( $input['css_badge_free_shipping'] );
        if( isset( $input['css_wrap_badge_free_shipping'] ) )
        $new_input['css_wrap_badge_free_shipping'] = sanitize_text_field( $input['css_wrap_badge_free_shipping'] );


        return $new_input;
    }

    /** Print Plugin Settings Section*/
    public function print_section_info()
    {_e('Settings plugin functions:','nt-EDDSDFS');}

    /** Callback */
    public function delivery_date_support_callback(){
        ?><input id="delivery_date_support" name="nt_eddsdfs[delivery_date_support]" type="checkbox" value="1" <?php checked( isset( $this->options['delivery_date_support'] ) );?> />
        <?php }
    public function ean_support_callback(){
    ?><input name="nt_eddsdfs[ean_support]" type="checkbox" value="1" <?php checked( isset( $this->options['ean_support'] ) );?> />
    <?php }
    public function title_description_support_callback(){
    ?><input name="nt_eddsdfs[title_description_support]" type="checkbox" value="1" <?php checked( isset( $this->options['title_description_support'] ) );?> />
    <?php }
    public function title_description_add_string_callback(){
    ?><input name="nt_eddsdfs[title_description_add_string]" type="checkbox" value="1" <?php checked( isset( $this->options['title_description_add_string'] ) );?> />
    <?php }
    public function delivery_date_product_details_callback(){
        ?><input name="nt_eddsdfs[delivery_date_product_details]" type="checkbox" value="1" <?php checked( isset( $this->options['delivery_date_product_details'] ) );?> />
        <?php }
    public function delivery_date_archive_page_callback(){
    ?><input name="nt_eddsdfs[delivery_date_archive_page]" type="checkbox" value="1" <?php checked( isset( $this->options['delivery_date_archive_page'] ) );?> />
    <?php }
    public function free_shipping_product_details_callback(){
        ?><input name="nt_eddsdfs[free_shipping_product_details]" type="checkbox" value="1" <?php checked( isset( $this->options['free_shipping_product_details'] ) );?> />
        <?php }
    public function free_shipping_archive_page_callback(){
    ?><input name="nt_eddsdfs[free_shipping_archive_page]" type="checkbox" value="1" <?php checked( isset( $this->options['free_shipping_archive_page'] ) );?> />
    <?php }
    public function badge_free_shipping_callback(){
    ?><input name="nt_eddsdfs[badge_free_shipping]" type="checkbox" value="1" <?php checked( isset( $this->options['badge_free_shipping'] ) );?> />
    <?php }

    /** Print Delivery Date Section*/
    public function print_section_delivery_date()
    {echo '<span class="del_date nt-title">' . __('Settings Delivery Date title:','nt-EDDSDFS') . '</span>';}


    public function delivery_date_0_callback(){
        printf('<input type="text" id="delivery_date_0" name="nt_eddsdfs[delivery_date_0]" value="%s" />',
            !empty( $this->options['delivery_date_0'] ) ? esc_attr( $this->options['delivery_date_0']) : __('In Stock', 'nt-EDDSDFS') );
        echo '<em>' . __('Default: ', 'nt-EDDSDFS') . __('In Stock', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_3_callback(){
        printf('<input type="text" id="delivery_date_3" name="nt_eddsdfs[delivery_date_3]" value="%s" />',
            !empty( $this->options['delivery_date_3'] ) ? esc_attr( $this->options['delivery_date_3']) : __('Within three days', 'nt-EDDSDFS') );
        echo'<em> ' . __('Default: ', 'nt-EDDSDFS') . __('Within three days', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_7_callback(){
        printf('<input type="text" id="delivery_date_7" name="nt_eddsdfs[delivery_date_7]" value="%s" />',
            !empty( $this->options['delivery_date_7'] ) ? esc_attr( $this->options['delivery_date_7']) : __('Within a week', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('Within a week', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_14_callback(){
        printf('<input type="text" id="delivery_date_14" name="nt_eddsdfs[delivery_date_14]" value="%s" />',
            !empty( $this->options['delivery_date_14'] ) ? esc_attr( $this->options['delivery_date_14']) : __('1 - 2 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('1 - 2 weeks', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_21_callback(){
        printf('<input type="text" id="delivery_date_21" name="nt_eddsdfs[delivery_date_21]" value="%s" />',
            !empty( $this->options['delivery_date_21'] ) ? esc_attr( $this->options['delivery_date_21']) : __('2 - 3 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('2 - 3 weeks', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_28_callback(){
        printf('<input type="text" id="delivery_date_28" name="nt_eddsdfs[delivery_date_28]" value="%s" />',
            !empty( $this->options['delivery_date_28'] ) ? esc_attr( $this->options['delivery_date_28']) : __('2 - 4 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('2 - 4 weeks', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_35_callback(){
        printf('<input type="text" id="delivery_date_35" name="nt_eddsdfs[delivery_date_35]" value="%s" />',
            !empty( $this->options['delivery_date_35'] ) ? esc_attr( $this->options['delivery_date_35']) : __('3 - 5 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('3 - 5 weeks', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_42_callback(){
        printf('<input type="text" id="delivery_date_42" name="nt_eddsdfs[delivery_date_42]" value="%s" />',
            !empty( $this->options['delivery_date_42'] ) ? esc_attr( $this->options['delivery_date_42']) : __('4 - 6 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('4 - 6 weeks', 'nt-EDDSDFS') . '</em>';
    }
    public function delivery_date_56_callback(){
        printf('<input type="text" id="delivery_date_56" name="nt_eddsdfs[delivery_date_56]" value="%s" />',
            !empty( $this->options['delivery_date_56'] ) ? esc_attr( $this->options['delivery_date_56']) : __('4 - 7 weeks', 'nt-EDDSDFS') );
        echo '<em> ' . __('Default: ', 'nt-EDDSDFS') . __('4 - 7 weeks', 'nt-EDDSDFS') . '</em>';
    }

    /** Print the CSS Section */
    public function print_section_css()
    {_e('Settings frontend CSS:','nt-EDDSDFS');}

    /** Callback CSS settings option */
    public function css_title_descriptions_callback(){
         $default = 'font-weight:300;font-size:0.7em;';
        printf('<textarea name="nt_eddsdfs[css_title_descriptions]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_title_descriptions'] ) ? $default : $this->options['css_title_descriptions']);
            echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }
    public function css_delivery_date_callback(){
        $default = 'font-weight:600;padding:5px;font-size:20px;background-color:#404040;color:#FFF;line-height:2.1em;';
       printf('<textarea name="nt_eddsdfs[css_delivery_date]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_delivery_date'] ) ? $default : $this->options['css_delivery_date']);
        echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }
    public function css_free__shipping_callback(){
         $default = 'font-weight:600;padding:5px;font-size:20px;background-color:#EF3F32;color:#FFF;line-height:2.1em;';
        printf('<textarea name="nt_eddsdfs[css_free__shipping]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_free__shipping'] ) ? $default : $this->options['css_free__shipping']);
            echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }
    public function css_free_shipping_catalog_callback(){
         $default = 'font-weight:100;padding:5px;background-color:#EF3F32;color:#FFF;font-size: 14px';
        printf('<textarea name="nt_eddsdfs[css_free_shipping_catalog]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_free_shipping_catalog'] ) ? $default : $this->options['css_free_shipping_catalog']);
            echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }
    public function css_badge_free_shipping_callback(){
         $default = 'font-weight:300;padding:5px;font-size:15px;background-color:#EF3F32;color:#FFF;line-height:2.1em;';
        printf('<textarea name="nt_eddsdfs[css_badge_free_shipping]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_badge_free_shipping'] ) ? $default : $this->options['css_badge_free_shipping']);
            echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }
    public function css_wrap_badge_free_shipping_callback(){
         $default = 'position:absolute;top:188px;left:0;z-index:5;width:100%;';
        printf('<textarea name="nt_eddsdfs[css_wrap_badge_free_shipping]" type="textarea" cols="80" rows="2">%s</textarea>',
            empty( $this->options['css_wrap_badge_free_shipping'] ) ? $default : $this->options['css_wrap_badge_free_shipping']);
            echo '<br /><em>' . __('Default: ', 'nt-EDDSDFS') . $default . '</em>';
        }

}
?>
