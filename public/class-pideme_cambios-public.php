<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       jmedrano.dev
 * @since      1.0.0
 *
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/public
 * @author     Joan Medrano <joanmedranofoz@gmail.com>
 */
class Pideme_cambios_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pideme_cambios-public.css', array(), $this->version, 'all' );

        if (current_user_can( 'administrator' ) || current_user_can( 'iw_pidemecambios_client' )):

            wp_enqueue_style( 'pc-uikit.min', plugins_url( '../includes/uikit/css/pc-uikit.min.css', __FILE__ ) );

        endif;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pideme_cambios-public.js', array( 'jquery' ), $this->version, false );
        
        if (current_user_can( 'administrator' ) || current_user_can( 'iw_pidemecambios_client' )):

            wp_enqueue_script( 'pc-uikit.min', plugins_url( '../includes/uikit/js/pc-uikit.min.js', __FILE__ ) );

            wp_enqueue_script( 'pc-uikit-icons.min', plugins_url( '../includes/uikit/js/pc-uikit-icons.min.js', __FILE__ ) );

        endif;

	}

    function pidemecambios_front(){
        if (current_user_can( 'administrator' ) || current_user_can( 'iw_pidemecambios_client' )):
            

            //PRINT CREATED TICKETS


            $query = new WP_Query( array(
                'post_type' => 'changes',
                'posts_per_page' => 100,
                'order' => 'ASC',
                'orderby' => 'date',
            ) );
            if ( $query->have_posts() ) {
        
                while ( $query->have_posts() ) : $query->the_post(); 
                    //$id = the_ID(); 
                    //$class= post_class(); 
                    //$link = the_permalink(); 
                    //$title = the_title();
                    $id = esc_html(get_the_ID());   
                    $identificador = esc_html(get_post_meta( $id, 'Identificador', true));
                    $posicioX = esc_html(get_post_meta( $id, 'xPosition', true));
                    $posicioY = esc_html(get_post_meta( $id, 'yPosition', true));
                    $url = esc_url(get_post_meta( $id, 'page_url', true));
                    $current_url = $_SERVER['REQUEST_URI'];
                    if($url == $current_url){
                    ?>
                <div class='iw_pc_point' id="iw_pc_ticket<?php echo $identificador;?>" pc-uk-toggle="target: #offcanvas-tickets" style="left: <?php echo $posicioX; ?>%; top: <?php echo $posicioY; ?>px;" ><?php echo $identificador?></div>
                
                <?php 
                }
                endwhile;
            }
            
            //END PRINT CREATED TICKETS

            ?>

            
            <?php

            //CREATE NEW TICKET

            function iw_pc_create_custom_post() {
                $user = wp_get_current_user();
                $contenido = sanitize_textarea_field($_POST['contenido']);
                $id = sanitize_key($_POST['identificador']);
                $xPosition = sanitize_text_field($_POST['posicioX']); 
                $yPosition = sanitize_text_field($_POST['posicioY']); 
                $url = $_SERVER['REQUEST_URI'];
                $state = "unresolved";

                $new_post = array(
                    'post_title' => 'Ticket_' . $id,
                    'post_status' => 'publish',
                    'post_content' => $contenido,
                    'post_author' => $user->ID,
                    'post_type' => 'changes'
                );
                
                $post_id = wp_insert_post($new_post);
                add_post_meta( $post_id, 'Identificador', $id);
                add_post_meta( $post_id, 'xPosition', $xPosition );
                add_post_meta( $post_id, 'yPosition', $yPosition );
                add_post_meta( $post_id, 'page_url', $url );
                add_post_meta( $post_id, 'state', $state );
                echo("<meta http-equiv='refresh' content='1'>");

                
            }

            //END CREATE NEW TICKET


            //OFF CANVAS

            ?>
            <style>
            
                div:not(.iw_pc_zindex){
                    z-index: auto!important;
                }



                .iw-pc-modal-buttons{
                    color: #666!important;
                    font-family: 'Arial';
                    font-size: 14px!important;
                }
                .pc-uk-active .iw-pc-modal-buttons{
                    background-color: #212121!important;
                    color: #fff!important;
                }

                .iw-pc-title{
                    font-family: 'Arial';
                    text-transform: none;
                }

                .iw-pc-title::before{
                    content: none!important;
                }

                #offcanvas-tickets{
                    z-index: 2147489999!important;
                }

            </style>
            <div id="offcanvas-tickets" pc-uk-offcanvas="flip: true; overlay: true" class="iw_pc_zindex">
                <div class="pc-uk-offcanvas-bar" style="background-color:#fff;">

                    <a href="/wp-admin/edit.php?post_type=changes" pc-uk-icon="cog" style="color: #000; position: absolute; top: 15px;"></a>
                    <button class="pc-uk-offcanvas-close" type="button" pc-uk-close style="margin-top: -5px; color: #000;"></button>

                    <h2 class="iw-pc-title"style="color:#000; font-family: 'Arial';     margin-top: 10px;">Tickets</h2>
                    <div pc-uk-filter="target: .js-filter">

                        <ul class="pc-uk-subnav pc-uk-subnav-pill">
                            <li class="pc-uk-active" pc-uk-filter-control="[data-state='unresolved']" ><a class="iw-pc-modal-buttons">Unresolved</a></li>
                            <li pc-uk-filter-control="[data-state='resolved']"><a class="iw-pc-modal-buttons">Resolved</a></li>


                            <!--<li pc-uk-filter-control><a href="#">All</a></li>-->
                        </ul>
                    <?php

                    function iw_pc_update_post_ticket(){
                        $status = sanitize_text_field($_POST['ticket_status']);
                        $post_id = sanitize_text_field($_POST['ticket_real_id']);

                        if ($status == 'unresolved'){
                            update_post_meta( $post_id, 'state', 'resolved' );
                        }
                        else{
                            update_post_meta( $post_id, 'state', 'unresolved' );
                        }
                        echo("<meta http-equiv='refresh' content='1'>");
                    }


                    if ( $query->have_posts() ) {



                        ?>
                        <ul class="js-filter" pc-uk-grid>
                        <?php

                        while ( $query->have_posts() ) : $query->the_post(); 
                            //$id = the_ID(); 
                            //$class= post_class(); 
                            //$link = the_permalink(); 
                            //$title = the_title();
                            $id = esc_html(get_the_ID());   
                            $identificador = esc_html(get_post_meta( $id, 'Identificador', true));
                            $posicioX = esc_html(get_post_meta( $id, 'xPosition', true));
                            $posicioY = esc_html(get_post_meta( $id, 'yPosition', true));
                            $url = esc_url(get_post_meta( $id, 'page_url', true));
                            $state = esc_html(get_post_meta( $id, 'state', true));

                            if ($state == "unresolved"){
                                $ticket_label = '<button class="pc-uk-label pc-uk-label-danger iw_pc_state_button" style="position: absolute;right:5px; top: 10px; background-color: #ff704d; color: #fff; border: none;" type="submit" name="ticket-refresh">Unresolved</button>';
                            }

                            else{
                                $ticket_label = '<button class="pc-uk-label pc-uk-label-success iw_pc_state_button" style="position: absolute;right:5px; top: 10px; background-color: #79ff4d; color: #fff; border: none;" type="submit" name="ticket-refresh">Resolved</button>';
                            }
                            ?>
                        <li data-state="<?php echo $state;?>" class="pc-uk-width-1-1"style="margin:0px!important;">
                            <div id="ticket_<?php echo $identificador;?>"class="pc-uk-card pc-uk-card-default pc-uk-card-small pc-uk-card-hover" style="padding-left: 5px; padding-top: 5px; margin-bottom: 20px!important;">
                            <form method="post" action="" id="ticket_form">
                                <input name="ticket_real_id" value="<?php echo $id;?>" style="display:none;">
                                <input name="ticket_status" value="<?php echo $state;?>" style="display:none;">
                                <?php echo $ticket_label;?>
                            </form>
                            <a href="<?php echo $url?>#iw_pc_ticket<?php echo $identificador;?>"><div class='iw_pc_point' style="left: 10px; width:30px!important; height:30px!important; font-size: 18px!important;"><?php echo $identificador?></div></a>


                            <div style="padding-left: 3px; padding-right: 3px; margin-top: 40px!important; font-family: 'Arial';"><?php the_content();?></div>

                            <div><a href="<?php echo $url?>#iw_pc_ticket<?php echo $identificador;?>" style="color:#666; padding-left: 3px; font-size: 10px; font-family: 'Arial';"><span pc-uk-icon="link" style="width: 15px;"></span> <?php echo $url;?></a></div>
                            
                            </div>

                        </li>

                        <?php endwhile;                   
                        if(isset($_POST['ticket-refresh'])){ // button name
                            iw_pc_update_post_ticket();
                        }
                    }
                    
                    ?>
                    </ul>
                    </div>

                </div>
            </div>

            <?php

            //END OFF CANVAS

            //CALL CREATE NEW TICKET
                if(isset($_POST['go'])){ // button name
                    iw_pc_create_custom_post();
                }
            
            //CALL CREATE NEW TICKET
            ?>
            <?php
            //BUTTONS
            ?>
            <div class="iw_pc_float iw_pc_zindex">
                <a pc-uk-toggle="target: #offcanvas-tickets" class="iw_pc_button_1"><span pc-uk-icon="icon: comments" style="margin-top: 11px!important;"></span></a>
                <a onclick="iwpcenableClick()" class="iw_pc_button_2"><span pc-uk-icon="icon: plus" style="margin-top: 11px!important;"></a>
            </div>
            <?php
            //END BUTTONS
            ?>

            <?php
            //MODAL FORM
            ?>

            <div id="iw-pc-modal-container" pc-uk-modal class="iw_pc_zindex" style="z-index: 9999999999;">
                <div id="iw-pc-modal" class="pc-uk-modal-dialog pc-uk-modal-body">
                    <button class="pc-uk-modal-close-default" type="button" pc-uk-close></button>
                    <h2 class="iw-pc-title" style="font-family: 'Arial';">New Ticket</h2>
                    <form method="post" action="" id="iw_pc_form">
                        <div class="pc-uk-margin">
                            <textarea class="pc-uk-textarea" rows="5" placeholder='Add comment' name="contenido" style="font-family: 'Arial';"></textarea>
                        </div>
                        <button class="pc-uk-button pc-uk-button-default" type="submit" name="go" style="font-family: 'Arial';">Save</button>
                    </form>
                </div>
            </div>


            <?php
            //END MODAL FORM
            ?>


            <?php
            //STYLES
            ?>
            <style>

            .iw_pc_point {
                position: absolute;
                width:35px;
                height:35px;
                background-color:#ff9100cf;
                border-radius:50px;
                text-align: center;
                font-family: 'Arial';
                font-size: 20px;
                line-height: 1.7;
                color: #000;
                z-index: 1000;
            }


            .iw_pc_float{
                position:fixed;
                width:60px;
                height:110px;
                bottom:40px;
                right:40px;
                background-color: #2a2c30;
                color:#FFF;
                border-radius:5px;
                text-align:center;
                box-shadow: 2px 2px 3px #999;
                z-index: 214748899;

            }

            .iw_pc_button_1{
                position:fixed;
                width:40px;
                height:40px;
                bottom:100px;
                right:50px;
                background-color: #d86720;
                color:#FFF;
                border-radius:50px;
                cursor: pointer;

            }


            .iw_pc_button_2{
                position:fixed;
                width:40px;
                height:40px;
                bottom:50px;
                right:50px;
                background-color: #d86720;
                color:#FFF;
                border-radius:50px;
                cursor: pointer;

            }

            .iw_pc_state_button{
                font-family: 'Arial';
                font-size: 14px;
                font-weight: 700;
            }

            
            </style>
            <?php
            //END STYLES
            ?>
            
            <script>

            var buttonTicket;
            var buttonTicketContainer;
            var valor_id = 0;
            var container = document.querySelector("body");
            var modalContainer;
            var modal;
            var form;

            <?php
        if ( $query->have_posts() ) { 
            $id_calc = get_the_ID();
            $identificador_calc = get_post_meta( $id, 'Identificador', true);

        ?>
            valor_id = <?php echo $identificador_calc;?>+1;
        <?php
                }

        ?>

            function iwpcenableClick() {
            setTimeout(
            function(){
                container.addEventListener("click", iwpcmyClick);
                container.style.cursor = "crosshair";
            }, 100);
            
            function iwpcmyClick(event){
                buttonTicketContainer = "<div class='iw_pc_point' id='iw_pc_ticket"+valor_id+"' pc-uk-toggle='target: #iw-pc-modal-container'>"+valor_id+"</div>";
                container.innerHTML += buttonTicketContainer;
                buttonTicket = document.querySelector("#iw_pc_ticket"+valor_id);
                
                var xPosition = event.clientX - container.getBoundingClientRect().left - (buttonTicket.clientWidth / 2);
                var yPosition = event.clientY - container.getBoundingClientRect().top - (buttonTicket.clientHeight / 2 - 30);

                xPosition_new = xPosition / container.clientWidth * 100;
                console.log(xPosition_new);
                


                buttonTicket.style.left = xPosition_new + "%";
                buttonTicket.style.top = yPosition + "px";

                modalContainer = document.getElementById("iw-pc-modal-container");
                modal = document.getElementById("iw-pc-modal");
                modal_form = document.getElementById("iw_pc_form");

                
                identificador = "<input name='identificador' value='"+valor_id+"' style='display:none;'>";
                posicioX = "<input name='posicioX' value='"+xPosition_new+"' style='display:none;'>";
                posicioY = "<input name='posicioY' value='"+yPosition+"' style='display:none;'>";
                modal_form.innerHTML +=  identificador;
                modal_form.innerHTML +=  posicioX;
                modal_form.innerHTML +=  posicioY;

                pcUIkit.modal('#iw-pc-modal-container').show();
                

                

                valor_id++;
                container.style.cursor = "default";
                container.removeEventListener("click", iwpcmyClick);

                console.log(xPosition);
                console.log(yPosition);
                }	
            }
            


            </script>

            <?php
        endif;
        

    }
    

}

