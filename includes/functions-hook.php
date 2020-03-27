<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 



add_filter('job_bm_job_archive_loop_no_post_text', 'job_bm_job_archive_loop_no_post_text_indeed_jobs', 99);

function job_bm_job_archive_loop_no_post_text_indeed_jobs($text){

    $job_bm_xml_feed_publisher_id = get_option('job_bm_xml_feed_publisher_id');
    $job_bm_company_logo = get_option('job_bm_company_logo');
    $job_bm_xml_feed_display_type = get_option('job_bm_xml_feed_archive_top_display_type','normal');
    $job_bm_xml_feed_prefix_text = get_option('job_bm_xml_feed_archive_top_prefix_text');



    $query_keyword = isset($_GET['keywords']) ? $_GET['keywords'] : '';
    $query_locations = isset($_GET['locations']) ? $_GET['locations'] : 'austin tx';


    $api_url = 'http://api.indeed.com/ads/apisearch?publisher='.$job_bm_xml_feed_publisher_id.'&q='.urlencode($query_keyword).'&l=austin%2C+tx&start=&limit=3&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2';
    $api_url = file_get_contents($api_url);

    if(empty($api_url)) return;

    $xml = simplexml_load_string($api_url);
    $xml_results = $xml->results;
    //$xml_result = $xml_results->result;

    $xml_results_count = count($xml_results->result);

    if($xml_results_count > 0){
        return '';
    }else{
        return $text;
    }





}



add_action('job_bm_job_archive_loop_top', 'job_bm_job_archive_loop_top_indeed_jobs', 99, 2);

function job_bm_job_archive_loop_top_indeed_jobs($wp_query, $atts){

    $job_bm_xml_feed_archive_top = get_option('job_bm_xml_feed_archive_top', 'yes');

    if($job_bm_xml_feed_archive_top != 'yes') return;

    $job_bm_xml_feed_publisher_id = get_option('job_bm_xml_feed_publisher_id');
    $job_bm_company_logo = get_option('job_bm_company_logo');
    $job_bm_xml_feed_display_type = get_option('job_bm_xml_feed_archive_top_display_type','normal');
    $job_bm_xml_feed_prefix_text = get_option('job_bm_xml_feed_archive_top_prefix_text');
    $job_bm_xml_feed_archive_top_max_limit = get_option('job_bm_xml_feed_archive_top_max_limit', 3);
    $job_bm_xml_feed_company_logo = get_option('job_bm_xml_feed_company_logo', $job_bm_company_logo);




    $query_args = isset($atts['query_args']) ? $atts['query_args'] : '';
    $query_keyword = isset($query_args['s']) ? $query_args['s'] : '';
    $query_keyword = isset($query_args['s']) ? $query_args['s'] : '';
    $query_locations = isset($_GET['locations']) ? $_GET['locations'] : 'austin tx';

    if ( get_query_var('paged') ) {$paged = get_query_var('paged');}
    elseif ( get_query_var('page') ) {$paged = get_query_var('page');}
    else {$paged = 1;}

    //echo '<pre>'.var_export($paged, true).'</pre>';

    $api_url = 'http://api.indeed.com/ads/apisearch?publisher='.$job_bm_xml_feed_publisher_id.'&q='.urlencode($query_keyword).'&l=austin%2C+tx&start='.($paged*$job_bm_xml_feed_archive_top_max_limit).'&limit='.$job_bm_xml_feed_archive_top_max_limit.'&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2';
    $api_url = file_get_contents($api_url);


    if(empty($api_url)) return;

    $xml = simplexml_load_string($api_url);
    $xml_results = $xml->results;
    //$xml_result = $xml_results->result;

    $xml_results_count = count($xml_results->result);



    foreach ($xml_results->result as $jobData){

        $jobtitle = isset($jobData->jobtitle[0]) ? (string) $jobData->jobtitle : '';
        $company = isset($jobData->company[0]) ? (string) $jobData->company : '';
        $city = isset($jobData->city) ? (string) $jobData->city : '';
        $state = isset($jobData->state) ? (string) $jobData->state : '';
        $country = isset($jobData->country) ? (string) $jobData->country : '';

        $source = isset($jobData->source) ? (string) $jobData->source : '';
        $url = isset($jobData->url) ? (string) $jobData->url : '';
        $jobkey = isset($jobData->jobkey) ? (string) $jobData->jobkey : '';

        $latitude = isset($jobData->latitude) ? (string) $jobData->latitude : '';
        $longitude = isset($jobData->longitude) ? (string) $jobData->longitude : '';

        $formattedRelativeTime = isset($jobData->formattedRelativeTime) ? (string) $jobData->formattedRelativeTime : '';
        $formattedLocation = isset($jobData->formattedLocation) ? (string) $jobData->formattedLocation : '';


        //echo '<pre>'.var_export($jobData, true).'</pre>';

        do_action('job_bm_job_archive_loop_indeed_top', $jobData);

        if($job_bm_xml_feed_display_type == 'list'){

            ?>
            <div class="indeed-job"><?php echo $job_bm_xml_feed_prefix_text; ?> <a href="<?php echo $url; ?>"><?php echo $jobtitle; ?></a> </div>
            <?php

        }elseif ($job_bm_xml_feed_display_type == 'normal'){
            ?>
            <div class="single indeed-job">
                <div class="company_logo">
                    <img src="<?php echo $job_bm_xml_feed_company_logo; ?>">
                </div>
                <div class="title">
                    <a href="<?php echo $url; ?>"><?php echo $job_bm_xml_feed_prefix_text; ?> <?php echo $jobtitle; ?></a>
                </div>
                <div class="company-name"><a href="#"><?php echo $company; ?></a></div>
                <div class="clear"></div>

                <?php
                $meta_items = array();

                ob_start();

                if(!empty($formattedLocation)): ?>
                    <span class="job-location meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo $formattedLocation; ?></span>
                <?php endif;

                $meta_items['location'] = ob_get_clean();


                ob_start();

                ?>
                <span class="job-post-date meta-item"><i class="far fa-calendar-alt"></i> <?php echo $formattedRelativeTime?></span>
                <?php

                $meta_items['date'] = ob_get_clean();


                $meta_items = apply_filters('job_bm_job_archive_loop_meta_indeed', $meta_items);

                ?>


                <div class="job-meta">
                    <?php

                    if(!empty($meta_items)):
                        foreach ($meta_items as $item):

                            echo $item;

                        endforeach;
                    endif;
                    ?>
                </div>

            </div>
            <?php

        }




    }


    ?>
    <style type="text/css">
        .indeed-job{
            padding: 5px 10px;
        }
    </style>
    <?php



}
