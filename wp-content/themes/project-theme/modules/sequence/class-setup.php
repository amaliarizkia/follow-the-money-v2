<?php 

namespace BD\Sequence;

defined ('ABSPATH') || die("Can't access directly");

class Setup {
    public function __construct() {
        add_action('init',[$this,'register_post_type_sequence']);
        // add_action('acf/init',[$this,'add_sequence_setting']);
    }

    public function register_post_type_sequence() {
        $args = [
			'labels'            => [
				'name'          => __('Sequences', 'followthemoney'),
				'singular_name' => __('Sequence', 'followthemoney'),
				'add_new'       => __('Add New Sequence', 'followthemoney'),
				'add_new_item'  => __('Add New Sequence', 'followthemoney'),
				'edit_item'     => __('Edit Sequence', 'followthemoney'),
			],
			'public'              => false,
			'publicly_queriable'  => true,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'supports'            => ['title'],
			'menu_icon'           => 'dashicons-marker',
		];
        register_post_type('sequence' , $args);
    }


    public function add_sequence_setting()
	{
		acf_add_options_page(
			array(
				'page_title'  => __( 'Sequence', 'followthemoney' ),
				'menu_title'  => __( 'Sequence', 'followthemoney' ),
				'parent_slug' => 'themes.php',
				'menu_slug'   => 'bd-sequence',
				'capability'  => 'manage_options',
			)
		);
    }
}

new Setup();
