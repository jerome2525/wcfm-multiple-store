<?php
	global $WCFM, $wp_query;
	$current_user = wp_get_current_user();
	$current_login = $current_user->user_login;
	$current_user_id = $current_user->ID;
?>

<div class="collapse wcfm-collapse" id="wcfm_build_listing">
	
	<div class="wcfm-page-headig">
		<span class="wcfmfa fa-store"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Multi Store Settings', 'wcfm-custom-menus' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
		<?php $WCFM->template->get_template( 'dashboard/wcfm-view-icon-box.php' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		<?php if( get_user_meta( get_current_user_id(), 'wcfmmp_store_name', true ) ) { ?>
			<div class="wcfm_current_store_title wcfm_welcomebox_header"><div class="wcfm_welcomebox_user_details rgt"><h3>Welcome to <?php echo get_user_meta( get_current_user_id(), 'wcfmmp_store_name', true ); ?> Store</h3><?php martfury_child_get_vendor_name_log(); ?></div></div>
		<?php } ?>
		<?php do_action( 'before_wcfm_build' ); ?>
		
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('Multi Store Settings', 'wcfm-custom-menus' ); ?></h2>
			<div class="wcfm-clearfix"></div>
	  </div>
	  <div class="wcfm-clearfix"></div><br />
		

		<div class="wcfm-container">
			<div id="wcfm_build_listing_expander" class="wcfm-content">
				<div class="loader"></div>
				<div id="result"></div>
				<!---- Add Content Here ----->
				<form method="get" action="" id="ms-switch-settings-form" class="ms-switch-settings-form">

                    <table class="form-table" summary="Swith Store Settings">
                        <tr valign="top">
                        <?php if( get_user_meta( $current_user_id, '_ms_user_parent_id', true ) ) { ?>
	                        <th scope="row">Store</th>
	                        <td>
	                        	<select name="ms_store_switch" class="input-text">
		                            <?php
		    							if( get_user_meta( $current_user_id, '_ms_user_parent_id', true ) ) {
			    							if( $current_user_id !== get_user_meta( $current_user_id, '_ms_user_parent_id', true ) ) {
			    								$meta_val = get_user_meta( $current_user_id, '_ms_user_parent_id', true );
			    							}
			    							else {
			    								$meta_val = $current_user_id;
			    							}

											$store_users = get_users( array( 'role__in' => array( 'wcfm_vendor' ), 'meta_key' => '_ms_user_parent_id', 'meta_value' => $meta_val, 'meta_compare' => '=' ) );
											foreach ( $store_users as $user ) {
												$user_id = $user->ID;
												$user_login = $user->user_login;
												$store_name = get_user_meta( $user_id, 'wcfmmp_store_name', true );
			    								$option_selected = '';
			    								$main_label = '';
			    								if( $current_login == $user_login ) {
			    									$option_selected = 'selected';
			    								}

			    								if( $user_id == get_user_meta( $current_user_id, '_ms_user_parent_id', true ) ) {
			    									$main_label = '(Main)';
				    							}

												echo '<option value="' . $user_login . '" ' . $option_selected . '>' . esc_html( $main_label .' '.$store_name ) . '</option>';
											}
										}
									?>
	                        	</select>
	                        </td>
	                    <?php } ?>    
                        </tr>
                    </table>  
                    <?php if( get_user_meta( $current_user_id, '_ms_user_parent_id', true ) ) { ?>
                    	<p class="submit"><input type="submit" name="submit" class="button button-primary" value="Switch Store" id="switch-store-button"></p>
                    <?php } ?>	
                </form>

				<form method="post" action="<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php" id="ms-settings-form" class="ms-settings-form">

                    <table class="form-table" summary="Add Store Settings">
                    	<input type="hidden" name="controller" value="wcfm-ms" />
                    	<input type="hidden" name="action" value="wcfm_ajax_controller" />
                        <tr valign="top">
	                        <th scope="row">Store Name</th>
	                        <td class="wcfm-text-field">
	                        	<span class="invalid-span invalid-text"></span>
	                        	<input type="text" name="ms_store_name" value="" required/>
	                        </td>
                        </tr>

                        <tr valign="top">
	                        <th scope="row">Store Email</th>
	                        <td class="wcfm-email-field">
	                        	<span class="invalid-span invalid-email"></span>
	                        	<input type="text" name="ms_store_email" value="" class="email-format" required/>
	                        </td>
                        </tr>
                    </table>  
                    <p class="submit"><input type="submit" name="submit" class="button button-primary" value="Add Store" id="add-store-button"></p>
                </form>
			
				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div>
		</div>
	
		<div class="wcfm-clearfix"></div>
		<?php
			do_action( 'after_wcfm_build' );
		?>
	</div>
</div>