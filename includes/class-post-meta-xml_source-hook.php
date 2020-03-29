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


add_action('job_bm_metabox_xml_source_content_fields','job_bm_metabox_xml_source_content_fields');


function job_bm_metabox_xml_source_content_fields($job_id){
    $settings_tabs_field = new settings_tabs_field();

    $job_bm_total_vacancies = get_post_meta($job_id, 'job_bm_total_vacancies', true);

    ?>
    <div class="section">
    <div class="section-title"><?php echo __('XML source info','job-board-manager'); ?></div>
    <p class="section-description"></p>

        <div class="setting-field">

            <ul class="fields-selector">
                <li>
                    <label>Job title</label>
                    <input name="field_index[post_title]" value="" placeholder="total_vacancies">
                </li>
                <li>
                    <label>Job content</label>
                    <input name="field_index[post_content]" value="" placeholder="post_content">
                </li>

                <li>
                    <label>Total vacancies</label>
                    <input name="field_index[job_bm_total_vacancies]" value="" placeholder="total_vacancies">
                </li>
                <li>
                    <label>Job type</label>
                    <input name="field_index[job_bm_job_type]" value="" placeholder="job_type">
                </li>
                <li>
                    <label>Job level</label>
                    <input name="field_index[job_bm_job_level]" value="" placeholder="job_level">
                </li>
                <li>
                    <label>Years of experience</label>
                    <input name="field_index[job_bm_years_experience]" value="" placeholder="years_experience">
                </li>

                <li>
                    <label>Salary type</label>
                    <input name="field_index[job_bm_salary_type]" value="" placeholder="salary_type">
                </li>
                <li>
                    <label>Fixed salary</label>
                    <input name="field_index[job_bm_salary_fixed]" value="" placeholder="salary_fixed">
                </li>
                <li>
                    <label>Minimum salary</label>
                    <input name="field_index[job_bm_salary_min]" value="" placeholder="salary_min">
                </li>
                <li>
                    <label>Maximum salary</label>
                    <input name="field_index[job_bm_salary_max]" value="" placeholder="salary_max">
                </li>

                <li>
                    <label>Salary duration</label>
                    <input name="field_index[job_bm_salary_duration]" value="" placeholder="salary_duration">
                </li>
                <li>
                    <label>Salary currency</label>
                    <input name="field_index[job_bm_salary_currency]" value="" placeholder="salary_currency">
                </li>

                <li>
                    <label>Contact email</label>
                    <input name="field_index[job_bm_contact_email]" value="" placeholder="contact_email">
                </li>


                <li>
                    <label>Company name</label>
                    <input name="field_index[job_bm_company_name]" value="" placeholder="company_name">
                </li>
                <li>
                    <label>Address</label>
                    <input name="field_index[job_bm_address]" value="" placeholder="address">
                </li>

                <li>
                    <label>Company website</label>
                    <input name="field_index[job_bm_company_website]" value="" placeholder="company_website">
                </li>
                <li>
                    <label>Job link</label>
                    <input name="field_index[job_bm_job_link]" value="" placeholder="job_link">
                </li>
                <li>
                    <label>Company logo</label>
                    <input name="field_index[job_bm_company_logo]" value="" placeholder="company_logo">
                </li>
                <li>
                    <label>Job status</label>
                    <input name="field_index[job_bm_job_status]" value="" placeholder="job_status">
                </li>

                <li>
                    <label>Expiry date</label>
                    <input name="field_index[job_bm_expire_date]" value="" placeholder="expire_date">
                </li>


            </ul>

            <style type="text/css">
                .fields-selector{}

                .fields-selector li{
                    display: block;
                    /* border-bottom: 1px solid #ddd; */
                    padding: 10px 0;
                }

                .fields-selector label{
                    width: 206px;
                    display: inline-block;
                }
                .fields-selector input{
                    padding: 3px 10px;
                }


            </style>
        </div>
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

