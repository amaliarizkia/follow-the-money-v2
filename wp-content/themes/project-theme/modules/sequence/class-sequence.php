<?php 

namespace BD\Sequence;

defined ('ABSPATH') || die("Can't access directly");

class Sequence {

    public static function get_first_sequence() {
        $args = array(
            'post_type'     => 'sequence',
            'post_per_page' => -1,
        );
        
        $sequences = get_posts($args);
        $first_sequence = false;
        $sequence_data = [];
        foreach($sequences as $sequence) {
            $first_sequence = get_field('is_first_sequence', $sequence->ID) ? true : false;
            if($first_sequence) {
                $sequence_data[] = $sequence;
            }
        }

        return $sequence_data[0];

        
    }
}

new Sequence();