<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_xml_feed_shortcodes{
	
    public function __construct(){
		

		add_shortcode( 'job_bm_xml_feed', array( $this, 'job_bm_xml_feed_display' ) );

   		}
		
		

	public function job_bm_xml_feed_display($atts, $content = null ) {

        $atts = shortcode_atts(
            array(
                'api_url' => 'http://api.indeed.com/ads/apisearch?publisher=14526954691009&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2',

                ), $atts);

        $class_job_bm_import = new class_job_bm_import();
        $api_url = isset($atts['api_url']) ? file_get_contents($atts['api_url']) : '';

        if(empty($api_url)) return;

        $xml = simplexml_load_string($api_url);
        $xml_results = $xml->results;
        //$xml_result = $xml_results->result;

        $xml_results_count = count($xml_results->result);

        //echo '<pre>'.var_export($xml_results_count, true).'</pre>';

        //echo '<pre>'.var_export($xml_results->result, true).'</pre>';

        foreach ($xml_results->result as $jobData){

            $jobtitle = isset($jobData->jobtitle[0]) ? (string) $jobData->jobtitle : '';
            $company = isset($jobData->company[0]) ? (string) $jobData->company : '';
            $company = isset($jobData->company[0]) ? (string) $jobData->company : '';
            $city = isset($jobData->city) ? (string) $jobData->city : '';
            $state = isset($jobData->state) ? (string) $jobData->state : '';
            $country = isset($jobData->country) ? (string) $jobData->country : '';

            $source = isset($jobData->source) ? (string) $jobData->source : '';
            $url = isset($jobData->url) ? (string) $jobData->url : '';
            $jobkey = isset($jobData->jobkey) ? (string) $jobData->jobkey : '';

            $latitude = isset($jobData->latitude) ? (string) $jobData->latitude : '';
            $longitude = isset($jobData->longitude) ? (string) $jobData->longitude : '';


            echo '<pre>'.var_export($jobtitle, true).'</pre>';


            $job_data['post_title'] = $jobtitle;
            $job_data['job_bm_is_imported'] = 'yes';
            $job_data['job_bm_import_source_jobid'] = $jobkey;
            $job_data['job_bm_import_source'] = $source;
            $job_data['job_bm_imported_job_url'] = $url;

            //echo '<pre>'.var_export($job_data, true).'</pre>';

            $class_job_bm_import->insert_job_data($job_data);


        }



		}


			
	}
	
	new class_job_bm_xml_feed_shortcodes();