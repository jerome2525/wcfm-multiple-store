<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Products Custom Menus EP Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmcsm/controllers
 * @version   1.0.0
 */

class WCFM_MS_Controller {
	
	public function __construct() {
		
        global $WCFM, $WCFMu;
		
		$this->processing();

	}

    public function error_notification( $status, $label ) {
        
        echo '<div class="ep-message ' . $status . '" tabindex="-1"><span class="wc-icon wcicon-status-' . $status . '">' . $label . '</span></div>';
        
    }

    function get_custom_email_html( $order, $heading = false, $mailer ) {

        $template = 'emails/store-new-account.php';

        return wc_get_template_html( $template, array(
            'order'         => $order,
            'email_heading' => $heading,
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $mailer
        ) );

    }

    public function sub_store_send_email( $recipient, $store_name, $store_email ) {
        
        // load the mailer class
        $mailer = WC()->mailer();

        //format the email
        $subject = __("Sub-store $store_name has been created with an email of $store_email ", 'MII');
        $content = $this->get_custom_email_html( $order, $subject, $mailer );
        $headers = "Content-Type: text/html\r\n";

        //send the email through wordpress
        $mailer->send( $recipient, $subject, $content, $headers );
    
    }

    public function sub_store_vendor_send_email( $recipient, $store_name ) {
        
        // load the mailer class
        $mailer = WC()->mailer();

        //format the email
        $subject = __("$store_name has been successfully created using this email ", 'MII');
        $content = $this->get_custom_email_html( $order, $subject, $mailer );
        $headers = "Content-Type: text/html\r\n";

        //send the email through wordpress
        $mailer->send( $recipient, $subject, $content, $headers );
    
    }

    public function insert_store() {
        
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;
        $email = $current_user->user_email;
        $first = $current_user->user_firstname;
        $last = $current_user->user_lastname;
        $display_name = $current_user->display_name;
        $user_id = $current_user->ID;
        
        if( $_POST['ms_store_name'] && $_POST['ms_store_email'] && $username ) {
            
            if( empty( get_user_meta( $user_id, '_ms_user_parent_id', true ) ) ) {
                update_user_meta( $user_id, '_ms_user_parent_id', $user_id );
            }
            $data = array(
                'user_login'    => $username . '_' . $_POST['ms_store_name'], 
                'user_pass'     => 'JeromeJerome2015$', 
                'user_email'    => $_POST['ms_store_email'],
                'role'          => 'wcfm_vendor',
                'first_name'    => $first,  
                'last_name'     => $last,
                'display_name'  => $display_name
            );
              
            $new_user_id = wp_insert_user( $data );
              
            if ( ! is_wp_error( $new_user_id ) ) {
                update_user_meta( $new_user_id, '_ms_user_parent_id', get_user_meta( $user_id, '_ms_user_parent_id', true ) );
                update_user_meta( $new_user_id, 'store_name', $_POST['ms_store_name'] );
                update_user_meta( $new_user_id, 'wcfmmp_store_name', $_POST['ms_store_name'] );
                update_user_meta( $new_user_id, '_wcfm_email_verified_for', $_POST['ms_store_email'] );
                update_user_meta( $new_user_id, '_wcfm_email_verified', 1 );

                $this->error_notification( 'completed', '<span class="wcfm-capital">' . $_POST['ms_store_name'].'</span> has been Added! to Main store <span class="wcfm-capital">' . get_user_meta( get_user_meta( $user_id, '_ms_user_parent_id', true ), 'wcfmmp_store_name', true ) . '</span>' );
                
                if( get_user_meta( $new_user_id, '_ms_user_parent_id', true ) ) {
                    $user_info = get_userdata( get_user_meta( $new_user_id, '_ms_user_parent_id', true ) );
                    if( $user_info->user_email ) {
                        $this->sub_store_send_email( $user_info->user_email, $_POST['ms_store_name'], $_POST['ms_store_email'] );
                    }

                    $this->sub_store_vendor_send_email( $_POST['ms_store_email'], $_POST['ms_store_name'] );
                }
                 
            }
            else {
                $this->error_notification( 'failed', $new_user_id->get_error_message() );
            }

        }

    }

	public function processing() {
		
        global $WCFM, $WCFMu, $wpdb, $_POST;
			
            if( isset( $_POST['ms_store_name'] ) ) {
               
               $this->insert_store();
			
            }

	  	die;

	}
}