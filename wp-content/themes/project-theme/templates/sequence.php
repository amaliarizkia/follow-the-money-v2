<?php
/**
 * Template Name: Sequence
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use BD\Sequence\Sequence;

$setting = Sequence::get_first_sequence();

get_header(); ?>

<h1><?php echo $setting->post_title?></h1>


<?php get_footer(); ?>