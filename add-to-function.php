// add this to function.php in theme


//  nevisande


function display_related_portfolio_posts() {
    // ابتدا بررسی می‌کنیم که پست جاری از نوع 'us_portfolio' باشد
    if ( ! is_singular('us_portfolio') ) {
        return '';
    }

    $current_portfolio_id = get_the_ID();

    // کوئری برای یافتن تمام نوشته‌هایی که در فیلد related_portfolio
    // مقدارشان حاوی آی‌دی پورتفولیوی جاری است
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'related_portfolio',
                'value'   => '"' . $current_portfolio_id . '"',
                'compare' => 'LIKE'
            )
        )
    );

    $query = new WP_Query($args);
    $output = '';

    if ( $query->have_posts() ) {
        $output .= '<ul class="related-portfolio-posts">';
        while ( $query->have_posts() ) {
            $query->the_post();
            
            // تصویر شاخص نوشته (اگر دارد)
            $thumbnail = get_the_post_thumbnail( get_the_ID(), 'medium' );
            
            // 10 کلمه از توضیحات (خلاصه) نوشته
            // اگر نگذارید get_the_excerpt() برمی‌گرداند, ولی ممکن است طولانی باشد.
            // با wp_trim_words آن را کوتاه می‌کنیم.
            $excerpt_10words = wp_trim_words( get_the_excerpt(), 10, '...' );
            
            // ساخت خروجی
            $output .= '<li>';
            
            // اگر تصویر دارد
            if ( $thumbnail ) {
                $output .= '<a href="' . get_permalink() . '">' . $thumbnail . '</a>';
            }
            
            // عنوان نوشته
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            // نمایش 10 کلمه از متن/خلاصه
            if ( ! empty( $excerpt_10words ) ) {
                $output .= '<p>' . esc_html( $excerpt_10words ) . '</p>';
            }
            
            $output .= '</li>';
        }
        $output .= '</ul>';
        wp_reset_postdata();
    } else {
        $output .= '<p>هیچ نوشته‌ای مرتبط یافت نشد.</p>';
    }

    return $output;
}
add_shortcode( 'related_portfolio_posts', 'display_related_portfolio_posts' );








//portfoli in posts


function display_portfolio_in_post() {
    if ( ! is_singular('post') ) {
        return '';
    }
    
    $portfolio_ids = get_field('related_portfolio'); 
    if ( empty($portfolio_ids) ) {
        return '';
    }


    if ( ! is_array($portfolio_ids) ) {
        $portfolio_ids = array($portfolio_ids);
    }

    $output = '<div class="related-portfolio-multi">';

    foreach ( $portfolio_ids as $portfolio_id ) {
        $thumbnail  = get_the_post_thumbnail($portfolio_id, 'medium');
        $title      = get_the_title($portfolio_id);
        $permalink  = get_permalink($portfolio_id);

        $output .= '<div class="single-portfolio-item">';
        if ($thumbnail) {
            $output .= '<a href="' . esc_url($permalink) . '">' . $thumbnail .'</a>' ;
        }
        $output .= '<p><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></p>';
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('portfolio_in_post', 'display_portfolio_in_post');





