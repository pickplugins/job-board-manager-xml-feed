<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_filter('job_bm_settings_tabs','job_bm_settings_tabs_scraps');
function job_bm_settings_tabs_scraps($job_bm_settings_tab){

    $job_bm_settings_tab[] = array(
        'id' => 'scraps',
        'title' => sprintf(__('%s Scraps','job-board-manager-company-profile'),'<i class="fas fa-filter"></i>'),
        'priority' => 10,
        'active' => false,
    );

    return $job_bm_settings_tab;

}




add_action('job_bm_settings_tabs_content_scraps', 'job_bm_settings_tabs_content_scraps', 20);

if(!function_exists('job_bm_settings_tabs_content_scraps')) {
    function job_bm_settings_tabs_content_scraps($tab){

        $settings_tabs_field = new settings_tabs_field();

        $job_bm_xml_feed_enable = get_option('job_bm_xml_feed_enable');


        ?>
        <div class="section">
            <div class="section-title"><?php echo __('XML feed', 'job-board-manager-company-profile'); ?></div>
            <p class="description section-description"><?php echo __('Choose options for XML feed scraps.', 'job-board-manager-company-profile'); ?></p>

            <?php


            $args = array(
                'id'		=> 'job_bm_xml_feed_enable',
                //'parent'		=> '',
                'title'		=> __('Enable XML feed scraps','job-board-manager-company-profile'),
                'details'	=> __('Choose to enable XML feed job scrapping','job-board-manager-company-profile'),
                'type'		=> 'select',
                'value'		=> $job_bm_xml_feed_enable,
                'default'		=> 'yes',
                'args'		=> array('no'=>'No', 'yes' => 'Yes'),
            );

            $settings_tabs_field->generate_field($args);

            ?>
        </div>
        <?php


    }
}









add_action('job_bm_settings_save', 'job_bm_settings_save_scraps', 20);

if(!function_exists('job_bm_settings_save_scraps')) {
    function job_bm_settings_save_scraps($tab){


        $job_bm_xml_feed_publisher_id = isset($_POST['job_bm_xml_feed_publisher_id']) ? sanitize_text_field($_POST['job_bm_xml_feed_publisher_id']) : '';
        update_option('job_bm_xml_feed_publisher_id', $job_bm_xml_feed_publisher_id);






    }
}

