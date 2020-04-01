<?php
if ( ! defined('ABSPATH')) exit;  // if direct access



add_action('job_bm_metabox_xml_source_content_general','job_bm_metabox_xml_source_content_general');


function job_bm_metabox_xml_source_content_general($post_id){
    $settings_tabs_field = new settings_tabs_field();

    $xml_url = get_post_meta($post_id, 'xml_url', true);
    $interval = get_post_meta($post_id, 'interval', true);

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
            'args'		=> array('1 day'=>'Daily', '7 days'=>'Weekly','15 minute'=>'15 minute','30 minute'=>'30 minute','45 minute'=>'45 minute','1 hour'=>'1 hour','3 hours'=>'3 hours','4 hours'=>'4 hours','5 hours'=>'5 hours','6 hours'=>'6 hours','10 hours'=>'10 hours', '12 hours'=>'12 hours'   ),

        );

        $settings_tabs_field->generate_field($args);


        $last_check_date = get_post_meta($post_id, 'last_check_date', true);
        $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);


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




function get_json_keys($node, $offset=0) {

    $json_keys = array();



    if (is_array($node)) {
        foreach ($node as $key => $value) {
            if (is_array($value)){
                $json_keys[$key] = get_json_keys($value, $offset + 1);
            }
            else{
                $json_keys[$key] = $key;
            }
        }
    }

    return $json_keys;



}


function mygenerateTreeMenu($dir_array, $index, $limit = 0){

    $post_id = get_the_id();

    $tree_path = get_post_meta($post_id, 'tree_path', true);


    $key = '';
    if ($limit > 5000) return '';
    $tree = '';

    if(is_array($dir_array))
    foreach ($dir_array as $key => $value){

        if (!is_int($key)){

            $checked = ($tree_path==$index.'/'.$key) ? 'checked' : '';

            $tree .= "<li>";
            $tree .= "<input type='radio' $checked value='$index/$key' name='tree_path'>";

            if(is_array($value)){
                $tree .= "<span class='action-open-close'>";
                $tree .= "<span class='expand'><i class='far fa-plus-square'></i></span>";
                $tree .= "<span class='collapse'><i class='far fa-minus-square'></i></span>";
                $tree .= "</span>";
            }


            $tree .= "<a>$key</a>";
            if(is_array($value)){
                $tree .= '('.count($value).')';
            }


            $tree .= "<ul>";

            $tree .= mygenerateTreeMenu($value, $index.'/'.$key, $limit++);
            $tree .= "</ul></li>\n";
        }
        else{
            //$tree .= mygenerateTreeMenu($value,$limit++);
        }
    }
    return $tree;
}






add_action('job_bm_metabox_xml_source_content_fields','job_bm_metabox_xml_source_content_xml');

function job_bm_metabox_xml_source_content_xml($post_id){
    $settings_tabs_field = new settings_tabs_field();

    $xml_url = get_post_meta($post_id, 'xml_url', true);
    //$field_index = get_post_meta($post_id, 'field_index', true);
    $field_index = get_post_meta($post_id, 'field_index', true);
    $tree_path = get_post_meta($post_id, 'tree_path', true);

    //var_dump($field_index);



    ?>
    <div class="section">
        <div class="section-title"><?php echo __('XML source info','job-board-manager'); ?></div>
        <p class="section-description"></p>

        <div class="setting-field">
            <?php

            $response  = wp_remote_get($xml_url, array('timeout'     => 2));
            if (  is_wp_error( $response ) ){

                //echo '<pre>'.var_export('There is a error.', true).'</pre>';

                //return;
            }

            $xml_string = file_get_contents($xml_url);

            $xml = simplexml_load_string($xml_string);

//            ?>
<!--            <textarea style="width: 100%;">--><?php //echo '<pre>'.var_export($xml, true).'</pre>'; ?><!--</textarea>-->
<!--            --><?php

            $xml_json = json_encode($xml);
            $xml_arr = json_decode($xml_json, true);

            $keys = array_keys(json_decode($xml_json, true));

            ?>
            <script>





                xml_json = <?php echo $xml_json; ?>;

                //console.log(xml_json['results']['result']);


                jQuery(document).ready(function($) {
                    $(document).on('click', '.tree-view input', function(){
                        index = $(this).val();

                        index_list = index.split('/');
                        index_list = index_list.filter(function(e){return e});

                        i = 0;
                        get_new_code(xml_json, i, index_list);

                        //console.log( new_code);


                    })
                })

                function  get_new_code(xml_json, i, index_list) {
                    index_count = index_list.length;

                   // while (i <= index_count){

                    if(i < index_count){

                        if(typeof xml_json[index_list[i]] != 'undefined'){
                            new_code = xml_json[index_list[i]];
                            get_new_code(xml_json[index_list[i]], i+1, index_list);
                        }

                    }

                    if(typeof  new_code =='object'){



                        if(typeof new_code[0] != 'undefined'){

                            keys = Object.keys(new_code[0]);
                            selector = '';

                            keys.forEach(function (item) {
                                console.log(item);
                                selector += '<li>'+item+'</li>';
                            })
                            jQuery('.input-selector ul').html(selector);

                        }else{
                            jQuery('.input-selector ul').html('');
                        }

                        new_code = JSON.stringify(new_code);


                    }

                    jQuery('.code-preview textarea').val(new_code);

                    //return new_code;
                    //}



                }


            </script>
            <?php



            $json_keys = get_json_keys($xml_arr, 0);




            echo "<ul class='tree-view'>\n";
            $tree = mygenerateTreeMenu($json_keys, '', 500);
            echo $tree;
            echo "</ul>\n";


            ?>
            <div class="code-preview">
                <textarea style="height: 400px" id="code"></textarea>

            </div>


            <div class="clear"></div>



            <style type="text/css">
                .tree-view{
                    width: 250px;
                    display: inline-block;
                    float: left;
                }
                .tree-view li{
                    border-left: 1px solid;
                    padding: 5px 3px;
                    margin: 0 0 2px 10px;
                }
                .tree-view li ul{
                    display: none;
                }

                .tree-view .action-open-close{
                    cursor: pointer;
                }


                .tree-view .expand{

                }
                .tree-view .action-open-close.active .expand{
                    display: none;
                }
                .tree-view .action-open-close.active .collapse{
                    display: inline-block;
                }

                .tree-view .collapse{
                    cursor: pointer;
                    display: none;
                }
                .tree-view a{
                    margin-left: 5px;
                }
                .tree-view input[name='tree_path']{
                    margin-left: 5px;
                }

                .code-preview{}

            </style>

            <script>
                jQuery(document).ready(function($) {

                    $(document).on('click', '.tree-view .action-open-close', function(){

                        if($(this).hasClass('active')){
                            $(this).removeClass('active');
                            $(this).parent().children('ul').fadeOut();

                        }else{
                            $(this).addClass('active');
                            $(this).parent().children('ul').fadeIn();
                        }

                    })
                })
            </script>

        </div>
    </div>


    <div class="section">

        <div class="json-template">
            <div class="input-fields">

                <div class="setting-field">

                    <ul class="fields-selector">

                        <li>
                            <label>Job title</label>
                            <input name="field_index[post_title]" value="<?php echo isset($field_index['post_title']) ? $field_index['post_title'] : ''; ?>" placeholder="post_title">
                        </li>
                        <li>
                            <label>Job content</label>
                            <input name="field_index[post_content]" value="<?php echo isset($field_index['post_content']) ? $field_index['post_content'] : ''; ?>" placeholder="post_content">
                        </li>




                        <li>
                            <label>Total vacancies</label>
                            <input name="field_index[job_bm_total_vacancies]" value="<?php echo isset($field_index['job_bm_total_vacancies']) ? $field_index['job_bm_total_vacancies'] : ''; ?>" placeholder="total_vacancies">
                        </li>
                        <li>
                            <label>Job type</label>
                            <input name="field_index[job_bm_job_type]" value="<?php echo isset($field_index['job_bm_job_type']) ? $field_index['job_bm_job_type'] : ''; ?>" placeholder="job_type">
                        </li>
                        <li>
                            <label>Job level</label>
                            <input name="field_index[job_bm_job_level]" value="<?php echo isset($field_index['job_bm_job_level']) ? $field_index['job_bm_job_level'] : ''; ?>" placeholder="job_level">
                        </li>
                        <li>
                            <label>Years of experience</label>
                            <input name="field_index[job_bm_years_experience]" value="<?php echo isset($field_index['job_bm_years_experience']) ? $field_index['job_bm_years_experience'] : ''; ?>" placeholder="years_experience">
                        </li>

                        <li>
                            <label>Salary type</label>
                            <input name="field_index[job_bm_salary_type]" value="<?php echo isset($field_index['job_bm_salary_type']) ? $field_index['job_bm_salary_type'] : ''; ?>" placeholder="salary_type">
                        </li>
                        <li>
                            <label>Fixed salary</label>
                            <input name="field_index[job_bm_salary_fixed]" value="<?php echo isset($field_index['job_bm_salary_fixed']) ? $field_index['job_bm_salary_fixed'] : ''; ?>" placeholder="salary_fixed">
                        </li>
                        <li>
                            <label>Minimum salary</label>
                            <input name="field_index[job_bm_salary_min]" value="<?php echo isset($field_index['job_bm_salary_min']) ? $field_index['job_bm_salary_min'] : ''; ?>" placeholder="salary_min">
                        </li>
                        <li>
                            <label>Maximum salary</label>
                            <input name="field_index[job_bm_salary_max]" value="<?php echo isset($field_index['job_bm_salary_max']) ? $field_index['job_bm_salary_max'] : ''; ?>" placeholder="salary_max">
                        </li>

                        <li>
                            <label>Salary duration</label>
                            <input name="field_index[job_bm_salary_duration]" value="<?php echo isset($field_index['job_bm_salary_duration']) ? $field_index['job_bm_salary_duration'] : ''; ?>" placeholder="salary_duration">
                        </li>
                        <li>
                            <label>Salary currency</label>
                            <input name="field_index[job_bm_salary_currency]" value="<?php echo isset($field_index['job_bm_salary_currency']) ? $field_index['job_bm_salary_currency'] : ''; ?>" placeholder="salary_currency">
                        </li>

                        <li>
                            <label>Contact email</label>
                            <input name="field_index[job_bm_contact_email]" value="<?php echo isset($field_index['job_bm_contact_email']) ? $field_index['job_bm_contact_email'] : ''; ?>" placeholder="contact_email">
                        </li>


                        <li>
                            <label>Company name</label>
                            <input name="field_index[job_bm_company_name]" value="<?php echo isset($field_index['job_bm_company_name']) ? $field_index['job_bm_company_name'] : ''; ?>" placeholder="company_name">
                        </li>

                        <li>
                            <label>Location</label>
                            <input name="field_index[job_bm_location]" value="<?php echo isset($field_index['job_bm_location']) ? $field_index['job_bm_location'] : ''; ?>" placeholder="location">
                        </li>
                        <li>
                            <label>Address</label>
                            <input name="field_index[job_bm_address]" value="<?php echo isset($field_index['job_bm_address']) ? $field_index['job_bm_address'] : ''; ?>" placeholder="address">
                        </li>

                        <li>
                            <label>Company website</label>
                            <input name="field_index[job_bm_company_website]" value="<?php echo isset($field_index['job_bm_company_website']) ? $field_index['job_bm_company_website'] : ''; ?>" placeholder="company_website">
                        </li>
                        <li>
                            <label>Job link</label>
                            <input name="field_index[job_bm_job_link]" value="<?php echo isset($field_index['job_bm_job_link']) ? $field_index['job_bm_job_link'] : ''; ?>" placeholder="job_link">
                        </li>
                        <li>
                            <label>Company logo</label>
                            <input name="field_index[job_bm_company_logo]" value="<?php echo isset($field_index['job_bm_company_logo']) ? $field_index['job_bm_company_logo'] : ''; ?>" placeholder="company_logo">
                        </li>
                        <li>
                            <label>Job status</label>
                            <input name="field_index[job_bm_job_status]" value="<?php echo isset($field_index['job_bm_job_status']) ? $field_index['job_bm_job_status'] : ''; ?>" placeholder="job_status">
                        </li>

                        <li>
                            <label>Expiry date</label>
                            <input name="field_index[job_bm_expire_date]" value="<?php echo isset($field_index['job_bm_expire_date']) ? $field_index['job_bm_expire_date'] : ''; ?>" placeholder="expire_date">
                        </li>

                        <li>
                            <label>is imported</label>
                            <input name="field_index[job_bm_is_imported]" value="<?php echo isset($field_index['job_bm_is_imported']) ? $field_index['job_bm_is_imported'] : 'yes'; ?>" placeholder="yes">
                        </li>
                        <li>
                            <label>Source jobid</label>
                            <input name="field_index[job_bm_import_source_jobid]" value="<?php echo isset($field_index['job_bm_import_source_jobid']) ? $field_index['job_bm_import_source_jobid'] : ''; ?>" placeholder="">
                        </li>
                        <li>
                            <label>Import source</label>
                            <input name="field_index[job_bm_import_source]" value="<?php echo isset($field_index['job_bm_import_source']) ? $field_index['job_bm_import_source'] : ''; ?>" placeholder="">
                        </li>

                    </ul>




                    <style type="text/css">
                        .fields-selector{

                        }

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
            <div class="input-selector">
                <div class="selector-title">Copy element to input fields</div>
                <ul>
                    <li>Choose loop element frist</li>
                </ul>
            </div>


        </div>

        <style type="text/css">
            .json-template{}
            .json-template .input-fields{
                float: left;
                width: 450px;
            }
            .json-template .input-selector{
                margin-left: 450px;
                border-left: 1px solid #ddd;
                padding: 10px;
            }
            .json-template .input-selector .selector-title{
                border-bottom: 1px solid #ddd;
                padding: 10px;
                font-size: 16px;
            }

            .json-template .input-selector ul{

            }

            .json-template .input-selector li{
                font-size: 14px;
                border-bottom: 1px solid #ddd;
                padding: 10px 10px;
                margin: 1px 0;
                background: #1b64bf1c;
            }


        </style>




    </div>





    <?
    }


add_action('job_bm_metabox_xml_source_content_fields','job_bm_metabox_xml_source_content_xml_selector');

function job_bm_metabox_xml_source_content_xml_selector($post_id){

    $settings_tabs_field = new settings_tabs_field();




    ?>


    <?php
}








add_action('job_bm_meta_box_save_xml_source','job_bm_meta_box_save_xml_source');

function job_bm_meta_box_save_xml_source($post_id){

    $xml_url = isset($_POST['xml_url']) ? sanitize_text_field($_POST['xml_url']) : '';
    update_post_meta($post_id, 'xml_url', $xml_url);

    $interval = isset($_POST['interval']) ? sanitize_text_field($_POST['interval']) : '';
    update_post_meta($post_id, 'interval', $interval);

    $last_check_date = isset($_POST['last_check_date']) ? sanitize_text_field($_POST['last_check_date']) : '';
    update_post_meta($post_id, 'last_check_date', $last_check_date);

    $next_check_datetime = isset($_POST['next_check_datetime']) ? sanitize_text_field($_POST['next_check_datetime']) : '';
    update_post_meta($post_id, 'next_check_datetime', $next_check_datetime);

    $field_index = isset($_POST['field_index']) ? stripslashes_deep($_POST['field_index']) : '';
    update_post_meta($post_id, 'field_index', $field_index);

    $tree_path = isset($_POST['tree_path']) ? sanitize_text_field($_POST['tree_path']) : '';
    update_post_meta($post_id, 'tree_path', $tree_path);

    //$job_bm_application_methods = isset($_POST['job_bm_application_methods']) ? stripslashes_deep($_POST['job_bm_application_methods']) : '';
    //update_post_meta($post_id, 'job_bm_application_methods', $job_bm_application_methods);


}

