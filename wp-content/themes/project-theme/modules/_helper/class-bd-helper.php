<?php
defined('ABSPATH') or die('Can\'t access directly');

class BD_Helper
{

    public static function cache_flush()
    {
    	// Don't cause a fatal if there is no WpeCommon class
    	if ( ! class_exists( 'WpeCommon' ) ) {
    		return false;
    	}

        WpeCommon::purge_memcached();
        WpeCommon::clear_maxcdn_cache();
        WpeCommon::purge_varnish_cache();    	 

        global $wp_object_cache;
        // Check for valid cache. Sometimes this is broken -- we don't know why! -- and it crashes when we flush.
        // If there's no cache, we don't need to flush anyway.
        if ( $wp_object_cache && is_object( $wp_object_cache ) ) {
            try {
                wp_cache_flush();
            } catch ( Exception $ex ) {
                echo("\tWarning: error flushing WordPress object cache: " . $ex->getMessage() . "\n");
                // but, continue.  Probably not that important anyway.
            }
        }

    }
}
