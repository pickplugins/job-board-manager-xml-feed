<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_job_bm_xml_feed_post_meta_xml_source{
	
	public function __construct(){

		//meta box action for "job"
		add_action('add_meta_boxes', array($this, '_post_meta_xml_source'));
		add_action('save_post', array($this, 'meta_boxes_job_save'));



		}


	
	public function _post_meta_xml_source($post_type){

            add_meta_box('metabox-job-data',__('Job data', 'job-board-manager'), array($this, 'meta_box_xml_source'), 'xml_source', 'normal', 'high');

		}






	public function meta_box_xml_source($post) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field('xml_source_nonce_check', 'xml_source_nonce_check_value');
 
        // Use get_post_meta to retrieve an existing value from the database.
       // $job_bm_data = get_post_meta($post -> ID, 'job_bm_data', true);

        $post_id = $post->ID;


        $settings_tabs_field = new settings_tabs_field();

        $job_bm_settings_tab = array();

        $job_bm_settings_tab[] = array(
            'id' => 'general',
            'title' => sprintf(__('%s General','job-board-manager'),'<i class="fas fa-briefcase"></i>'),
            'priority' => 1,
            'active' => true,
        );


        $job_bm_settings_tab[] = array(
            'id' => 'fields',
            'title' => sprintf(__('%s Input fields','job-board-manager'),'<i class="fas fa-briefcase"></i>'),
            'priority' => 2,
            'active' => false,
        );





        $job_bm_settings_tab = apply_filters('job_bm_metabox_xml_source_navs', $job_bm_settings_tab);

        $tabs_sorted = array();
        foreach ($job_bm_settings_tab as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
        array_multisort($tabs_sorted, SORT_ASC, $job_bm_settings_tab);

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script('jquery-ui-accordion');

        wp_enqueue_style( 'jquery-ui');
        wp_enqueue_style( 'font-awesome-5' );
        wp_enqueue_style( 'settings-tabs' );
        wp_enqueue_script( 'settings-tabs' );



		?>


        <div class="settings-tabs vertical">
            <ul class="tab-navs">
                <?php
                foreach ($job_bm_settings_tab as $tab){
                    $id = $tab['id'];
                    $title = $tab['title'];
                    $active = $tab['active'];
                    $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                    $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                    ?>
                    <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>"><?php echo $title; ?></li>
                    <?php
                }
                ?>
            </ul>
            <?php
            foreach ($job_bm_settings_tab as $tab){
                $id = $tab['id'];
                $title = $tab['title'];
                $active = $tab['active'];
                ?>

                <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                    <?php
                    do_action('job_bm_metabox_xml_source_content_'.$id, $post_id);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="clear clearfix"></div>

        <?php






        //do_action('job_bm_metabox_job_data', $post);


   		}




	public function meta_boxes_job_save($post_id){

        /*
         * We need to verify this came from the our screen and with
         * proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['xml_source_nonce_check_value']))
            return $post_id;

        $nonce = $_POST['xml_source_nonce_check_value'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'xml_source_nonce_check'))
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        // Check the user's permissions.
        if ('page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id))
                return $post_id;

        } else {

            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize the user input.
        //$job_bm_am_user_email = stripslashes_deep($_POST['job_bm_am_user_email']);


        // Update the meta field.
        //update_post_meta($post_id, 'job_bm_data', $job_bm_data);

        do_action('job_bm_meta_box_save_xml_source', $post_id);


					
		}
	
	}


new class_job_bm_xml_feed_post_meta_xml_source();