<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 



add_shortcode('job_bm_xml_feed_scraps_source','job_bm_xml_feed_scraps_source');

add_action('job_bm_xml_feed_scraps_source','job_bm_xml_feed_scraps_source');

function job_bm_xml_feed_scraps_source(){

    $job_bm_xml_feed_enable = get_option('job_bm_xml_feed_enable');


    //if($job_bm_xml_feed_enable != 'yes') return;

    $query_args = array(
        'post_type' => 'xml_source',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
    );


    $query_args = apply_filters('job_bm_xml_source_query_args',$query_args);



    $wp_query = new WP_Query($query_args);


    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
            $post_id = get_the_id();

            do_action('job_bm_xml_feed_scraps_source_loop', $post_id);


        endwhile;

    else:
        //error_log(date('Y-m-d H:i:s').'None to scrap');

    endif;


}


add_action('job_bm_xml_feed_scraps_source_loop', 'job_bm_xml_feed_scraps_source_loop');
function job_bm_xml_feed_scraps_source_loop($post_id){
    $post_title = get_the_title($post_id);

    $gmt_offset = get_option('gmt_offset');
    $datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));

    $last_check_date = get_post_meta($post_id, 'last_check_date', true);
    $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);
    $interval = get_post_meta($post_id, 'interval', true);
    $interval = !empty($interval) ? $interval : '30 minute';

    echo '<pre>'.var_export('interval: '.$interval, true).'<br>';




    if(strtotime($next_check_datetime) < strtotime($current_datetime) ){

        //error_log($current_datetime.' job_bm_scraps_source');
        error_log($post_title.' job_bm_scraps_source');


        do_action('job_bm_xml_feed_scraps_xml_source', $post_id);

        $next_check_datetime = date('Y-m-d H:i:s', strtotime($current_datetime . ' + '.$interval));

        echo '<pre>'.var_export('current_datetime: '.$current_datetime, true).'<br>';
        echo '<pre>'.var_export('next_check_datetime: '.$next_check_datetime, true).'<br>';


        //do_shortcode($scrap_shortcode);
        update_post_meta($post_id, 'last_check_date', $current_datetime);
        update_post_meta($post_id, 'next_check_datetime', $next_check_datetime);

    }

}


add_action('job_bm_xml_feed_scraps_xml_source', 'job_bm_xml_feed_scraps_xml_source');
function job_bm_xml_feed_scraps_xml_source($post_id){

    $xml_url = get_post_meta($post_id, 'xml_url', true);
    $interval = get_post_meta($post_id, 'interval', true);
    $last_check_date = get_post_meta($post_id, 'last_check_date', true);
    $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);


    //error_log(date('Y-m-d H:i:s').' job_bm_xml_feed_scraps_xml_source');

    $response  = wp_remote_get($xml_url, array('timeout'     => 2));
    if (  !is_wp_error( $response ) ){
        $tree_path = get_post_meta($post_id, 'tree_path', true);
        $field_index = get_post_meta($post_id, 'field_index', true);
        $class_job_bm_import = new class_job_bm_import();


        $response_data = wp_remote_retrieve_body($response);
        $xml = simplexml_load_string($response_data);
        $xml_json = json_encode($xml);
        $xml_arr = json_decode($xml_json, true);

        $index_list = explode('/', $tree_path);
        $index_list = array_filter($index_list);
        $index_list_count = count($index_list);

        $i = 0;
        if(!empty($index_list))
        foreach ($index_list as $index){

            if($i <=$index_list_count){
                $xml_arr = $xml_arr[$index];
            }

            $i++;
        }

        if(!empty($xml_arr) && is_array($xml_arr))
            foreach ($xml_arr as $item_index => $item){

                $job_data = create_job_item($item_index, $item, $post_id);
                $class_job_bm_import->insert_job_data($job_data);

            }

    }




}






