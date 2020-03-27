<?php
if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('job_bm_metabox_xml_source_content_general','job_bm_metabox_xml_source_content_general');


function job_bm_metabox_xml_source_content_general($job_id){
    $settings_tabs_field = new settings_tabs_field();

    $xml_url = get_post_meta($job_id, 'xml_url', true);
    $interval = get_post_meta($job_id, 'interval', true);

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('XML source info','job-board-manager'); ?></div>
        <p class="section-description"></p>


        <?php

        $args = array(
            'id'		=> 'xml_url',
            //'parent'		=> '',
            'title'		=> __('XML URL','job-board-manager'),
            'details'	=> __('Write xml source URL.','job-board-manager'),
            'type'		=> 'text',
            'value'		=> $xml_url,
            'default'		=> '',
            'placeholder'		=> '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'interval',
            //'parent'		=> '',
            'title'		=> __('Check interval','job-board-manager'),
            'details'	=> __('Interval','job-board-manager'),
            'type'		=> 'select',
            'value'		=> $interval,
            'default'		=> '6 hour',
            'args'		=> array('daily'=>'Daily', 'weekly'=>'Weekly','30minutes'=>'30 minutes','45minutes'=>'45 minutes','1hour'=>'1 hour','6hours'=>'6 hours', '12hours'=>'12 hours'   ),

        );

        $settings_tabs_field->generate_field($args);


        $last_check_date = get_post_meta($job_id, 'last_check_date', true);
        $next_check_datetime = get_post_meta($job_id, 'next_check_datetime', true);


        $gmt_offset = get_option('gmt_offset');
        $datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


        $args = array(
            'id'		=> 'last_check_date',
            //'parent'		=> '',
            'title'		=> __('Last check date','job-board-manager'),
            'details'	=> __('Choose last check date. <code>2019-11-19 13:29:45</code>','job-board-manager'),
            'type'		=> 'datepicker',
            'value'		=> $last_check_date,
            'default'		=> $datetime,
            'placeholder'		=> '2019-11-19 13:29:45',
            'format'		=> 'yy-mm-dd',

        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'		=> 'next_check_datetime',
            //'parent'		=> '',
            'title'		=> __('Next check date','job-board-manager'),
            'details'	=> __('Choose next check date. <code>2019-11-19 13:29:45</code>','job-board-manager'),
            'type'		=> 'datepicker',
            'value'		=> $next_check_datetime,
            'default'		=> $datetime,
            'placeholder'		=> '2019-11-19 13:29:45',
            'format'		=> 'yy-mm-dd',

        );

        $settings_tabs_field->generate_field($args);


        ?>

    </div>


    <?php


}











add_action('job_bm_meta_box_save_xml_source','job_bm_meta_box_save_xml_source');

function job_bm_meta_box_save_xml_source($job_id){

    $xml_url = isset($_POST['xml_url']) ? sanitize_text_field($_POST['xml_url']) : '';
    update_post_meta($job_id, 'xml_url', $xml_url);

    $interval = isset($_POST['interval']) ? sanitize_text_field($_POST['interval']) : '';
    update_post_meta($job_id, 'interval', $interval);

    $last_check_date = isset($_POST['last_check_date']) ? sanitize_text_field($_POST['last_check_date']) : '';
    update_post_meta($job_id, 'last_check_date', $last_check_date);

    $next_check_datetime = isset($_POST['next_check_datetime']) ? sanitize_text_field($_POST['next_check_datetime']) : '';
    update_post_meta($job_id, 'next_check_datetime', $next_check_datetime);


    //$job_bm_application_methods = isset($_POST['job_bm_application_methods']) ? stripslashes_deep($_POST['job_bm_application_methods']) : '';
    //update_post_meta($job_id, 'job_bm_application_methods', $job_bm_application_methods);


}

