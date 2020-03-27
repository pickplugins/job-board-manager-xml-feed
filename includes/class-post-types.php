<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_xml_feed_post_types{
	
	public function __construct(){
		
		add_action( 'init', array( $this, '_posttype_xml_source' ), 0 );


    }
	
	public function _posttype_xml_source(){
		if ( post_type_exists( "xml_source" ) )
		return;

		$singular  = __( 'XML source', 'job-board-manager' );
		$plural    = __( 'XML sources', 'job-board-manager' );
	 
	 
		register_post_type( "xml_source",
			apply_filters( "job_bm_post_type_xml_source", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $singular,
					'all_items'             => sprintf( __( 'All %s', 'job-board-manager' ), $plural ),
					'add_new' 				=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
					'edit' 					=> __( 'Edit', 'job-board-manager' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'job-board-manager' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'job-board-manager' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'job-board-manager' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'job-board-manager' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'job-board-manager' ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','editor','custom-fields','author','excerpt'),
				'show_in_nav_menus' 	=> false,
                'show_in_menu' 	=> 'edit.php?post_type=job',
				'menu_icon' => 'dashicons-megaphone',
			) )
		); 
	 
	 
		}



}
	

new class_job_bm_xml_feed_post_types();