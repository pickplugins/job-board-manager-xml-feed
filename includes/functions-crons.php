<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 



add_shortcode('job_bm_xml_feed_scraps_source','job_bm_xml_feed_scraps_source');

add_action('job_bm_xml_feed_scraps_source','job_bm_xml_feed_scraps_source');

function job_bm_xml_feed_scraps_source(){
    $posts_data =  array();
    $meta_query =  array();

    //echo '<pre>'.var_export('Scraped source: ', true).'</pre>';

    $gmt_offset = get_option('gmt_offset');
    $datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $query_args = array(
        'post_type' => 'xml_source',
        'post_status' => 'publish',
        //'meta_query' => $meta_query,
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
    );


    $query_args = apply_filters('job_bm_scrap_source_query_args',$query_args);



    $wp_query = new WP_Query($query_args);


    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
            $post_id = get_the_id();
            $post_title = get_the_title();

            //echo '<pre>'.var_export('Checking source: '.$post_title, true).'<br>';

            $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));

            //error_log('job_bm_reset_scraps_source');

            $scrap_shortcode = get_post_meta($post_id, 'scrap_shortcode', true);
            $last_check_date = get_post_meta($post_id, 'last_check_date', true);
            $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);

            $interval = get_post_meta($post_id, 'interval', true);

            $interval = !empty($interval) ? $interval : '5 minute';

            $last_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_date));
            $next_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_date . ' + '.$interval));


            if(strtotime($next_check_datetime) < strtotime($current_datetime) ){

                error_log(date('Y-m-d H:i:s').' job_bm_scraps_source');
                error_log($post_title.' job_bm_scraps_source');

                //do_shortcode($scrap_shortcode);
                update_post_meta($post_id, 'last_check_date', $current_datetime);
                update_post_meta($post_id, 'next_check_datetime', $next_check_datetime);

            }

        endwhile;

    else:

        //echo '<pre>'.var_export('None to scrap', true).'</pre>';
        error_log(date('Y-m-d H:i:s').'None to scrap');
    endif;

    //echo '<pre>'.var_export('End of query', true).'</pre>';

    //error_log(date('Y-m-d H:i:s').' job_bm_scraps_source');

}









