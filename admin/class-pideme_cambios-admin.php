<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       jmedrano.dev
 * @since      1.0.0
 *
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/admin
 * @author     Joan Medrano <joanmedranofoz@gmail.com>
 */
class Pideme_cambios_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pideme_cambios_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pideme_cambios_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pideme_cambios-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pideme_cambios_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pideme_cambios_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pideme_cambios-admin.js', array( 'jquery' ), $this->version, false );

	}

    //Create CPT

    function pidemecambios_cpt() {
        $supports = array(
        'title', // post title
        'editor', // post content
        //'author', // post author
        //'thumbnail', // featured images
        //'excerpt', // post excerpt
        'custom-fields', // custom fields
        //'comments', // post comments
        //'revisions', // post revisions
        //'post-formats', // post formats
        );
        $labels = array(
        'name' => _x('Changes', 'plural'),
        'singular_name' => _x('Changes', 'singular'),
        'menu_name' => _x('Changes', 'admin menu'),
        'name_admin_bar' => _x('Changes', 'admin bar'),
        'add_new' => _x('Add New', 'newsadd new'),
        'add_new_item' => __('Add New Changes'),
        'new_item' => __('New Changes'),
        'edit_item' => __('Edit Changes'),
        'view_item' => __('View Changes'),
        'all_items' => __('All Changes'),
        'search_items' => __('Search Changes'),
        'not_found' => __('No Changes found.'),
        );
        $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'Changes'),
        'has_archive' => false,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-welcome-write-blog',


        );
        register_post_type('Changes', $args);
    }

    
    //Create roles

    function pidemecambios_roles() {
        //add_role( 'pidemecambios_client', 'Pideme Cambios Client', array( 'read' => true, 'edit_posts' => true, 'delete_posts' => true,) );
        add_role( 'iw_pidemecambios_client', 'Client', array( 'read' => true, 'edit_posts' => true, 'delete_posts' => true,) );
        
    }

	//Create menu page

    function pidemecambios_menu(){
        
        $page_title = 'Pídeme Cambios';
        $menu_title = 'Pídeme Cambios';
        $capability = 'edit_posts';
        $menu_slug = 'pidemecambios';
        $function = array( $this, 'pidemecambios_action' );
        $icon_url = 'dashicons-feedback';
        $position = 99;
    
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    }

    //Only show menu page specifycs users

    function pidemecambios_hide_cpt(){
        $user = wp_get_current_user();
        if (!current_user_can( 'administrator' )){
            if (!current_user_can( 'iw_pidemecambios_client' )){ 
            remove_menu_page( 'edit.php?post_type=changes' );
            }     
        }
    }

    //Change CPT columns
    
    function iw_pc_change_columns( $columns ) {
        $columns = array(
          'cb'       => '<input type="checkbox" />',
          'title'    => __( 'Title'),
          'url'      => __( 'Url'),
          'state'    => __( 'State'),
          'date'     => __( 'Date'),
        );
        return $columns;
      }

    function iw_pc_manage_changes_columns( $column, $post_id ) {
        
        if ( 'url' === $column ) {
            $url = get_post_meta( $post_id, 'page_url', true );
            $identificador = get_post_meta( $post_id, 'Identificador', true );

            echo '<a href="'. $url .'#iw_pc_ticket'. $identificador .'">'. $url .'</a>';
        }

        if ( 'state' === $column ) {
            $state = get_post_meta( $post_id, 'state', true );
            if ($state === 'unresolved'){
                $state = '<p style="color: #ff704d;">Unresolved</p>';
            }
            if ($state === 'resolved'){
                $state = '<p style="color: #79ff4d;">Resolved</p>';
            }
            echo $state;
        }
    
    }


	//Declarate options
    
    function iw_pc_settings_init(  ) { 
    
        register_setting( 'generalPage', 'iw_pc_settings' );
    
        add_settings_section(
            'iw_pc_generalPage_section', 
            __( '', 'pidemecambios' ), 
            'iw_pc_settings_section_callback', 
            'generalPage'
		);
		
	}
	
	function pidemecambios_action(){
    
    ?>   
    <div class="wrap">
    
    <div class="wrap about-wrap full-width-layout">
    
              <h1>Pídeme Cambios</h1>
              <h3 style="margin-top: -5px; color:#32373c;">by <a href="https://indianwebs.com" target="_blank">IndianWebs</a></h3>
    
              <p class="about-text">Thank you for installing the Pídeme Cambios plugin! We do our best to offer our users the best experience.</p>
    
              <p class="about-text">
                <a href="https://indianwebs.com/plugins" target="_blank">Visit the plugin page</a>
              </p>
    
              <a href="https://indianwebs.com" target="_blank"><div style="
                   background: url(<?php echo plugins_url( 'indianwebs-pideme-cambios/admin/images/logoindianwebsgrande.png' ,_FILE_ );?>) no-repeat;
                   background-size: 130px 130px;
                   margin: 5px 0 0;
                   padding-top: 120px;
                   height: 40px;
                   width: 140px;
                   position: absolute;
                   top: 0;
                   right: 0;">
                   </div></a>
        <hr>
    </div>
    <div class="wrap about-wrap full-width-layout">
            <div class="two-col">
      <div>
          <h2 style="margin: 0px; text-align: left;">How to use</h2>
          <p>
          The objective of the plugin is to create an interaction between the <strong>Client</strong> and the <strong>Webmaster</strong> where the two will be able to contribute ideas and notes on modifications of the page in real time.
          </p>
          <p>
          To be able to interact as a <strong>client</strong>, the only thing we have to do is go to the section of <a href="/wp-admin/users.php">Wordpress Users</a> and the specific user, adding the role of "<strong>Client</strong>".<br>
          This way, when we are in the <strong>front-end</strong>, in a registered way, we can use the plugin.
          </p>
        <div style="
                background: url(<?php echo plugins_url( 'indianwebs-pideme-cambios/admin/images/select_role.png' ,_FILE_ );?>) no-repeat;
                margin: 5px 0 0;
                height: 50px;">
        </div>
        <p>
        When accessing the front-end, we only have to click on the + icon and add a ticket.<br>
        And we can see and manage all the tickets by clicking on the message button.
        </p>
        <div style="
                background: url(<?php echo plugins_url( 'indianwebs-pideme-cambios/admin/images/buttons_front.png' ,_FILE_ );?>) no-repeat;
                margin: 5px 0 0;
                height: 130px;">
        </div>
        <hr>
      </div>
    </div>      </div>
    
    <div class="wrap about-wrap full-width-layout">
            <div class="two-col">
      <div>     
        <div>
          <h3>Support</h3>
          <p>
          If you find an error we request that you inform us so we can fix it in future versions. If you have questions about the plugin, contact us.</p>
          <a style="background-color: #f88f2c;color: #ffffff;text-decoration: none;padding: 10px 30px;border-radius: 30px;margin: 10px 0 0 0;display: inline-block;" target="_blank" href="mailto:info@indianwebs.com">Contact Us</a>
        </div>
      </div>
    </div>      </div>



    </div>
    <?php
    }

}
