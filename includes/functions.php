<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 

add_filter('the_content', 'job_bm_job_content_import_link', 99);

function job_bm_job_content_import_link($content){

    if(is_singular('xml_source')) {

        $post_id = get_the_ID();

        $xml_url = get_post_meta($post_id, 'xml_url', true);
        $interval = get_post_meta($post_id, 'interval', true);
        $last_check_date = get_post_meta($post_id, 'last_check_date', true);
        $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);

        $response  = wp_remote_get($xml_url, array('timeout'     => 2));
        if (  is_wp_error( $response ) ) return;


        $html_obj = simplexml_load_string(file_get_contents($xml_url));
        $channel = isset($html_obj->channel)? $html_obj->channel : array();
        $items = isset($channel->item)? $channel->item : array();

        //echo '<pre>'.var_export($channel->item, true).'</pre>';

        $item_count = 0;
        foreach ($items as $item):

            //if($item_count > 1) return;

            $item_title = isset($item->title) ? (string)$item->title : '';
            $item_link = isset($item->link) ? (string)$item->link : '';
            $item_guid = isset($item->guid) ? (string)$item->guid : '';
            $item_description = isset($item->description) ? $item->description : '';

            $post_id = isset($item['post-id']) ? $item['post-id'] : '';


            echo '<pre>'.var_export($item, true).'</pre>';


        endforeach;

    }

    return $content;
}

