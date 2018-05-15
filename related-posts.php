<?php

    $post_id = get_the_ID();
    $post_type = get_post_type($post_id);
    $post_taxonomies = get_object_taxonomies((object) array( 'post_type' => $post_type ));

    foreach ($post_taxonomies as $post_tax) {
        $terms = get_the_terms($post_id, $post_tax, 'term_id');
        $terms_id = wp_list_pluck($terms, 'term_id');
        $tax_array[] = array (
            'taxonomy' => $post_tax,
            'field'    => 'term_id',
            'terms'    => $terms_id,
        );
    }

    $tax_array['relation'] = 'OR';

    $args = array(
        'post_type' => $post_type,
        'post__not_in' => array($post_id),
        'posts_per_page' => 3,
        'tax_query' => $tax_array
        'orderby' => 'rand',
    );

    $related = new WP_Query($args);

    if($related->have_posts()) :
        while ($related->have_posts()) : $related->the_post();
            the_title(); // post template
        endwhile; wp_reset_postdata();
    endif;

?>
