<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 



function job_add_xml_source_column( $columns ) {
    return array_merge( $columns,
        array(
            'xml_source' => __( 'XML source', 'xml_source' ),

        )

    );
}
add_filter( 'manage_job_posts_columns' , 'job_add_xml_source_column' );


function job_posts_shortcode_display( $column, $post_id ) {


    if ($column == 'xml_source'){

        $xml_source_id = get_post_meta($post_id, 'xml_source_id', true);

        if(!empty($xml_source_id)){
            ?>
            <a href="<?php echo get_edit_post_link($xml_source_id); ?>"><?php echo get_the_title($xml_source_id); ?></a>
            <?php
        }



    }

}

add_action( 'manage_job_posts_custom_column' , 'job_posts_shortcode_display', 10, 2 );

















add_filter('the_content', 'job_bm_job_content_import_link', 99);

function job_bm_job_content_import_link($content){

    if(is_singular('xml_source')) {

        $post_id = get_the_ID();
        $xml_url = get_post_meta($post_id, 'xml_url', true);

        job_bm_xml_feed_scraps_source_loop($post_id);


        $response  = wp_remote_get($xml_url, array('timeout'     => 2));

        if (  is_wp_error( $response ) ){

            echo '<pre>'.var_export('There is a error.', true).'</pre>';
            //return;
        }else{

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

                    ?>
                    <pre><?php echo var_export($job_data, true); ?></pre>

                    <?php
                }

            ?>
            <pre><?php //echo var_export($xml_arr, true); ?></pre>
            <textarea style="width: 100%"><?php //echo var_export($xml_arr['results'], true); ?></textarea>

            <?php
        }







    }

    return $content;
}



function create_job_item($item_index, $item, $post_id){

    $field_index = get_post_meta($post_id, 'field_index', true);

    $job_data = array();

    foreach ($field_index as $field_key=>$index){

        if(!empty($index)){

            if($field_key == 'post_title' || $field_key == 'post_content'){
                $job_data[$field_key] = isset($item[$index]) ? $item[$index] : '';
            }else{
                $job_data['meta_fields'][$field_key] = isset($item[$index]) ? $item[$index] : '';
            }



        }

    }

    $job_data['meta_fields']['xml_source_id'] = $post_id;

    return $job_data;
}









function displayNode($node, $offset) {



    if (is_object($node)) {
        $node = get_object_vars($node);
        foreach ($node as $key => $value) {
            echo  $key;
            echo  ',';
            displayNode($value, $offset + 1);

        }
    } elseif (is_array($node)) {
        foreach ($node as $key => $value) {
            if (is_object($value)){
                displayNode($value, $offset + 1);
            }
            else{
                echo $key;
                echo  ',';
            }


        }
    }




}


function get_new_str($v, $v_new, $str) {

    $str_new = str_replace($v, $v_new, $str);

    return $str_new;

}