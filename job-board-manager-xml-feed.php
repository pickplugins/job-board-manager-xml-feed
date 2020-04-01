<?php
/*
Plugin Name: Job Board Manager - XML feed
Plugin URI: http://pickplugins.com
Description: Display Indeed job for job board manager.
Version: 1.0.0
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class JobBoardManagerXMLFeed{
	
	public function __construct(){
        define('job_bm_xml_feed_plugin_url', plugins_url('/', __FILE__)  );
        define('job_bm_xml_feed_plugin_dir', plugin_dir_path( __FILE__ ) );
        define('job_bm_xml_feed_plugin_name', 'Job Board Manager - XML feed' );
        define('job_bm_xml_feed_plugin_version', '1.0.0' );

        // Class

        require_once( job_bm_xml_feed_plugin_dir . 'includes/class-post-types.php');
        //require_once( job_bm_xml_feed_plugin_dir . 'includes/class-shortcodes.php');

        require_once( job_bm_xml_feed_plugin_dir . 'includes/class-post-meta-xml_source.php');
        require_once( job_bm_xml_feed_plugin_dir . 'includes/class-post-meta-xml_source-hook.php');
        require_once( job_bm_xml_feed_plugin_dir . 'includes/functions.php');
        require_once( job_bm_xml_feed_plugin_dir . 'includes/functions-crons.php');
        require_once( job_bm_xml_feed_plugin_dir . 'includes/functions-settings.php');

        // Function's
	    //require_once( job_bm_xml_feed_plugin_dir . 'includes/functions-settings.php');
        //require_once( job_bm_xml_feed_plugin_dir . 'includes/functions-hook.php');



        register_activation_hook( __FILE__, array( $this, '_activation' ) );
        register_deactivation_hook(__FILE__, array( $this, '_deactivation' ));

        add_filter( 'cron_schedules', array( $this, 'cron_recurrence_interval' ) );
	}



    function cron_recurrence_interval( $schedules ){


        $schedules['15minute'] = array(
            'interval'  => 900,
            'display'   => __( 'Every 15 Minutes', 'textdomain' )
        );

        $schedules['30minutes'] = array(
            'interval'  => 1800,
            'display'   => __( 'Every 30 Minutes', 'textdomain' )
        );


        $schedules['1hour'] = array(
            'interval'  => 3600,
            'display'   => __( 'Every 2 hours', 'textdomain' )
        );


        $schedules['6hours'] = array(
            'interval'  => 18000,
            'display'   => __( 'Every 5 hours', 'textdomain' )
        );


        $schedules['12hours'] = array(
            'interval'  => 36000,
            'display'   => __( 'Every 12 hours', 'textdomain' )
        );


        $schedules['weekly'] = array(
            'interval'  => 604800,
            'display'   => __( 'Every 7 days', 'textdomain' )
        );

        $schedules['halfmonth'] = array(
            'interval'  => 1209600,
            'display'   => __( 'Every 14 days', 'textdomain' )
        );



        return $schedules;
    }
	

	

		
	public function _uninstall(){
		
		do_action( 'job_bm_xml_feed_uninstall' );
		}

    public function _activation(){


        if (! wp_next_scheduled ( 'job_bm_xml_feed_scraps_source' )){
            wp_schedule_event(time(), '15minute', 'job_bm_xml_feed_scraps_source');
        }



        do_action( 'job_bm_xml_feed_activation' );
    }

	public function _deactivation(){



        do_action( 'job_bm_xml_feed_deactivation' );
		}
		
	public function _front_scripts(){
		

		
		}

	public function _admin_scripts(){


		}
	
	
	
	
	}

new JobBoardManagerXMLFeed();