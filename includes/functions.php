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
        if (  is_wp_error( $response ) ){

            echo '<pre>'.var_export('There is a error.', true).'</pre>';

            return;
        }

        $xml_str = file_get_contents($xml_url);

        ?>
        <textarea style="width: 100%"><?php echo var_export($xml_str, true); ?></textarea>

        <?php


        $re = '/<\w*:\w*>/';

        preg_match_all($re, $xml_str, $matches);

        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($matches));

        if(!empty($it))
        foreach($it as $v) {

            $v_new = str_replace('<','', $v);
            $v_naked = str_replace('>','', $v_new);
            $v_new = str_replace(':','_', $v_naked);

            $tag_list[] = $v_naked;
            $tag_list_replace[] = $v_new;
        }


        if(!empty($tag_list) || !empty($tag_list_replace)){
            $string = str_replace(
                $tag_list,
                $tag_list_replace,
                $xml_str
            );

            ?>
            <!--        <textarea style="width: 100%">--><?php //echo var_export($tag_list, true); ?><!--</textarea>-->
            <!--        <textarea style="width: 100%">--><?php //echo var_export($tag_list_replace, true); ?><!--</textarea>-->
            <!--        <textarea style="width: 100%">--><?php //echo var_export($string, true); ?><!--</textarea>-->
            <!--        <br>-->
            <?php


            $xml = new SimpleXMLElement($string);

            //echo '<pre>'.var_export($xml, true).'</pre>';


            ob_start();
            displayNode($xml, 0);
            $tags = ob_get_clean();
            //echo '<pre>'.var_export($tags, true).'</pre>';

            ?>
            <!--                <textarea style="width: 100%">--><?php //echo var_export($tags, true); ?><!--</textarea>-->
            <!--        <textarea style="width: 100%">--><?php //echo var_export($tag_list_replace, true); ?><!--</textarea>-->
            <!--        <textarea style="width: 100%">--><?php //echo var_export($string, true); ?><!--</textarea>-->
            <!--        <br>-->
            <?php


            $tags = explode(',', $tags);
            $tags = array_filter($tags);

            $tags_count = array_count_values($tags);


            echo '<pre>'.var_export($tags_count, true).'</pre>';

        }





    }

    return $content;
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