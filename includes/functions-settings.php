<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_filter('job_bm_settings_tabs','job_bm_settings_tabs_indeed_jobs');
function job_bm_settings_tabs_indeed_jobs($job_bm_settings_tab){

    $job_bm_settings_tab[] = array(
        'id' => 'indeed_jobs',
        'title' => sprintf(__('%s Indeed','job-board-manager-company-profile'),'<i class="fas fa-filter"></i>'),
        'priority' => 10,
        'active' => false,
    );

    return $job_bm_settings_tab;

}




add_action('job_bm_settings_tabs_content_indeed_jobs', 'job_bm_settings_tabs_content_indeed_jobs', 20);

if(!function_exists('job_bm_settings_tabs_content_indeed_jobs')) {
    function job_bm_settings_tabs_content_indeed_jobs($tab){

        $settings_tabs_field = new settings_tabs_field();

        $job_bm_xml_feed_publisher_id = get_option('job_bm_xml_feed_publisher_id');
        $job_bm_xml_feed_archive_top_max_limit = get_option('job_bm_xml_feed_archive_top_max_limit');

        $job_bm_xml_feed_archive_top = get_option('job_bm_xml_feed_archive_top');
        $job_bm_xml_feed_archive_top_display_type = get_option('job_bm_xml_feed_archive_top_display_type');

        $job_bm_xml_feed_archive_top_prefix_text = get_option('job_bm_xml_feed_archive_top_prefix_text');
        $job_bm_xml_feed_company_logo = get_option('job_bm_xml_feed_company_logo');

        ?>
        <div class="section">
            <div class="section-title"><?php echo __('Indeed Settings', 'job-board-manager-company-profile'); ?></div>
            <p class="description section-description"><?php echo __('Choose options for indeed jobs.', 'job-board-manager-company-profile'); ?></p>

            <?php


            $args = array(
                'id'		=> 'job_bm_xml_feed_publisher_id',
                //'parent'		=> '',
                'title'		=> __('Indeed publisher id','job-board-manager-company-profile'),
                'details'	=> __('Write your indeed publisher id','job-board-manager-company-profile'),
                'type'		=> 'text',
                'value'		=> $job_bm_xml_feed_publisher_id,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'job_bm_xml_feed_company_logo',
                //'parent'		=> '',
                'title'		=> __('Company logo indeed jobs','job-board-manager'),
                'details'	=> __('Upload company logo for indeed jobs in normal view','job-board-manager'),
                'type'		=> 'media_url',
                'value'		=> $job_bm_xml_feed_company_logo,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            ?>
        </div>

        <div class="section">
            <div class="section-title"><?php echo __('Indeed on archive top', 'job-board-manager-company-profile'); ?></div>
            <p class="description section-description"><?php echo __('Choose options for indeed jobs on archive top.', 'job-board-manager-company-profile'); ?></p>

            <?php

            $args = array(
                'id'		=> 'job_bm_xml_feed_archive_top',
                //'parent'		=> '',
                'title'		=> __('Indeed jobs on archive top','job-board-manager-company-profile'),
                'details'	=> __('Display indeed jobs on archive top','job-board-manager-company-profile'),
                'type'		=> 'select',
                'value'		=> $job_bm_xml_feed_archive_top,
                'default'		=> 'no',
                'args'		=> array('no'=>'No', 'yes' => 'Yes'),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'job_bm_xml_feed_archive_top_max_limit',
                //'parent'		=> '',
                'title'		=> __('Display max limit','job-board-manager-company-profile'),
                'details'	=> __('Display max number of jobs from indeed.','job-board-manager-company-profile'),
                'type'		=> 'text',
                'value'		=> $job_bm_xml_feed_archive_top_max_limit,
                'default'		=> '3',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'job_bm_xml_feed_archive_top_prefix_text',
                //'parent'		=> '',
                'title'		=> __('Title prefix text','job-board-manager-company-profile'),
                'details'	=> __('Display text before the job title','job-board-manager-company-profile'),
                'type'		=> 'text',
                'value'		=> $job_bm_xml_feed_archive_top_prefix_text,
                'default'		=> '[Indeed Jobs]',
            );

            $settings_tabs_field->generate_field($args);




            $args = array(
                'id'		=> 'job_bm_xml_feed_archive_top_display_type',
                //'parent'		=> '',
                'title'		=> __('Indeed jobs display type','job-board-manager-company-profile'),
                'details'	=> __('Display indeed jobs as list or normal','job-board-manager-company-profile'),
                'type'		=> 'select',
                'value'		=> $job_bm_xml_feed_archive_top_display_type,
                'default'		=> 'no',
                'args'		=> array('normal'=>'Normal', 'list' => 'List'),
            );

            $settings_tabs_field->generate_field($args);








            ?>
        </div>
        <?php


    }
}









add_action('job_bm_settings_save', 'job_bm_settings_save_indeed_jobs', 20);

if(!function_exists('job_bm_settings_save_indeed_jobs')) {
    function job_bm_settings_save_indeed_jobs($tab){


        $job_bm_xml_feed_publisher_id = isset($_POST['job_bm_xml_feed_publisher_id']) ? sanitize_text_field($_POST['job_bm_xml_feed_publisher_id']) : '';
        update_option('job_bm_xml_feed_publisher_id', $job_bm_xml_feed_publisher_id);

        $job_bm_xml_feed_company_logo = isset($_POST['job_bm_xml_feed_company_logo']) ? sanitize_text_field($_POST['job_bm_xml_feed_company_logo']) : '';
        update_option('job_bm_xml_feed_company_logo', $job_bm_xml_feed_company_logo);

        $job_bm_xml_feed_archive_top_max_limit = isset($_POST['job_bm_xml_feed_archive_top_max_limit']) ? sanitize_text_field($_POST['job_bm_xml_feed_archive_top_max_limit']) : '';
        update_option('job_bm_xml_feed_archive_top_max_limit', $job_bm_xml_feed_archive_top_max_limit);

        $job_bm_xml_feed_archive_top = isset($_POST['job_bm_xml_feed_archive_top']) ? sanitize_text_field($_POST['job_bm_xml_feed_archive_top']) : '';
        update_option('job_bm_xml_feed_archive_top', $job_bm_xml_feed_archive_top);


        $job_bm_xml_feed_archive_top_display_type = isset($_POST['job_bm_xml_feed_archive_top_display_type']) ? sanitize_text_field($_POST['job_bm_xml_feed_archive_top_display_type']) : '';
        update_option('job_bm_xml_feed_archive_top_display_type', $job_bm_xml_feed_archive_top_display_type);

        $job_bm_xml_feed_archive_top_prefix_text = isset($_POST['job_bm_xml_feed_archive_top_prefix_text']) ? sanitize_text_field($_POST['job_bm_xml_feed_archive_top_prefix_text']) : '';
        update_option('job_bm_xml_feed_archive_top_prefix_text', $job_bm_xml_feed_archive_top_prefix_text);




    }
}

