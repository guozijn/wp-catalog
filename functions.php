<?php
/**
 * Catalog theme functions.
 */

define( 'CATALOG_VERSION', '1.1.1' );

add_action( 'after_setup_theme', 'catalog_setup' );
function catalog_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'catalog' ),
        'footer'  => __( 'Footer Quick Links', 'catalog' ),
    ] );
}

add_action( 'wp_enqueue_scripts', 'catalog_assets' );
function catalog_assets() {
    wp_enqueue_style( 'catalog-style', get_stylesheet_uri(), [], CATALOG_VERSION );
    wp_enqueue_script(
        'catalog-main',
        get_template_directory_uri() . '/assets/main.js',
        [],
        CATALOG_VERSION,
        true
    );
}

add_filter( 'body_class', 'catalog_body_class' );
function catalog_body_class( $classes ) {
    $classes[] = 'catalog';
    return $classes;
}

add_action( 'init', 'catalog_register_post_types' );
function catalog_register_post_types() {
    $common_supports = [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ];

    register_post_type( 'catalog_slide', [
        'label'               => __( 'Slides', 'catalog' ),
        'public'              => true,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-images-alt2',
        'supports'            => $common_supports,
        'rewrite'             => false,
    ] );

    register_post_type( 'catalog_stat', [
        'label'             => __( 'Highlights', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-chart-pie',
        'supports'          => [ 'title', 'editor', 'excerpt', 'page-attributes' ],
        'rewrite'           => false,
    ] );

    register_post_type( 'catalog_advantage', [
        'label'             => __( 'Advantages', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-lightbulb',
        'supports'          => [ 'title', 'editor', 'excerpt', 'page-attributes' ],
        'rewrite'           => false,
    ] );

    register_post_type( 'catalog_product', [
        'label'             => __( 'Products', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-products',
        'supports'          => $common_supports,
        'has_archive'       => false,
        'rewrite'           => [
            'slug'       => 'products',
            'with_front' => false,
        ],
    ] );

    register_taxonomy( 'catalog_product_category', [ 'catalog_product' ], [
        'labels'            => [
            'name'          => __( 'Product Categories', 'catalog' ),
            'singular_name' => __( 'Product Category', 'catalog' ),
        ],
        'public'           => true,
        'hierarchical'     => true,
        'show_in_rest'     => true,
        'show_admin_column'=> true,
        'rewrite'          => [
            'slug'       => 'products/categories',
            'with_front' => false,
        ],
    ] );

    register_post_type( 'catalog_certificate', [
        'label'             => __( 'Certificates', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-awards',
        'supports'          => [ 'title', 'editor', 'excerpt', 'page-attributes', 'thumbnail' ],
        'rewrite'           => [
            'slug'       => 'certifications',
            'with_front' => false,
        ],
    ] );

    register_post_type( 'catalog_testimonial', [
        'label'             => __( 'Testimonials', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-format-quote',
        'supports'          => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
        'rewrite'           => false,
    ] );

    register_post_type( 'catalog_cta', [
        'label'             => __( 'Call To Action', 'catalog' ),
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-megaphone',
        'supports'          => [ 'title', 'editor', 'excerpt' ],
        'rewrite'           => false,
    ] );
}

add_action( 'after_switch_theme', 'catalog_flush_rewrite_on_activation' );
function catalog_flush_rewrite_on_activation() {
    catalog_register_post_types();
    flush_rewrite_rules();
    update_option( 'catalog_rewrite_version', '3' );
}

add_action( 'init', 'catalog_maybe_flush_rewrite' );
function catalog_maybe_flush_rewrite() {
    if ( get_option( 'catalog_rewrite_version' ) !== '3' ) {
        catalog_register_post_types();
        flush_rewrite_rules();
        update_option( 'catalog_rewrite_version', '3' );
    }
}

add_action( 'init', 'catalog_register_meta' );
function catalog_register_meta() {
    $string_meta = static function( $type, $key, $args = [] ) {
        register_post_meta( $type, $key, array_merge( [
            'type'              => 'string',
            'single'            => true,
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
        ], $args ) );
    };

    $string_meta( 'catalog_slide', 'catalog_slide_tagline' );
    $string_meta( 'catalog_slide', 'catalog_slide_primary_label' );
    $string_meta( 'catalog_slide', 'catalog_slide_primary_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
    $string_meta( 'catalog_slide', 'catalog_slide_secondary_label' );
    $string_meta( 'catalog_slide', 'catalog_slide_secondary_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
    $string_meta( 'catalog_slide', 'catalog_slide_theme_class' );

    $string_meta( 'catalog_product', 'catalog_product_subtitle' );
    register_post_meta( 'catalog_product', 'catalog_product_specs', [
        'type'              => 'string',
        'single'            => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
    ] );

    $string_meta( 'catalog_cta', 'catalog_cta_button_label' );
    $string_meta( 'catalog_cta', 'catalog_cta_button_link', [ 'sanitize_callback' => 'esc_url_raw' ] );
}

add_action( 'customize_register', 'catalog_customize_register' );
function catalog_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'catalog_branding', [
        'title'       => __( 'Catalog Branding & Contact', 'catalog' ),
        'priority'    => 30,
        'description' => __( 'Update the footer branding, contact information, and copyright notice.', 'catalog' ),
    ] );

    $wp_customize->add_setting( 'catalog_brand_logo_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'catalog_brand_logo_image',
            [
                'label'       => __( 'Header Logo Image', 'catalog' ),
                'section'     => 'catalog_branding',
                'mime_type'   => 'image',
                'description' => __( 'Upload a logo to display in the header. Leave empty to show the fallback brand mark.', 'catalog' ),
            ]
        )
    );

    $wp_customize->add_setting( 'catalog_brand_logo_text', [
        'default'           => 'FG',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_brand_logo_text', [
        'label'       => __( 'Brand Mark (Text Fallback)', 'catalog' ),
        'section'     => 'catalog_branding',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_brand_tagline', [
        'default'           => 'FuseShield | Smart Protection',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_brand_tagline', [
        'label'   => __( 'Brand Tagline', 'catalog' ),
        'section' => 'catalog_branding',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_seo_keywords', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_seo_keywords', [
        'label'       => __( 'SEO Keywords', 'catalog' ),
        'description' => __( 'Optional comma-separated keywords for search engine optimization.', 'catalog' ),
        'section'     => 'catalog_branding',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_contact_support', [
        'default'           => '+1 (800) 123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_contact_support', [
        'label'   => __( 'Support Phone', 'catalog' ),
        'section' => 'catalog_branding',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_contact_sales', [
        'default'           => 'sales@fuseshield.com',
        'sanitize_callback' => 'sanitize_email',
    ] );
    $wp_customize->add_control( 'catalog_contact_sales', [
        'label'   => __( 'Sales Email', 'catalog' ),
        'section' => 'catalog_branding',
        'type'    => 'email',
    ] );

    $wp_customize->add_setting( 'catalog_contact_address', [
        'default'           => '66 Innovation Avenue, Springfield',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_contact_address', [
        'label'   => __( 'Headquarters Address', 'catalog' ),
        'section' => 'catalog_branding',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_copyright_text', [
        'default'           => '© ' . date( 'Y' ) . ' FuseShield Technologies Inc. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'catalog_copyright_text', [
        'label'   => __( 'Footer Copyright Text', 'catalog' ),
        'section' => 'catalog_branding',
        'type'    => 'textarea',
    ] );

    $wp_customize->add_section( 'catalog_homepage_sections', [
        'title'       => __( 'Catalog Homepage Sections', 'catalog' ),
        'priority'    => 35,
        'description' => __( 'Control the titles and descriptions shown on the homepage sections.', 'catalog' ),
    ] );

    $wp_customize->add_setting( 'catalog_advantages_title', [
        'default'           => __( 'WHY CHOOSE US', 'catalog' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'catalog_advantages_title', [
        'label'   => __( 'Advantages Section Title', 'catalog' ),
        'section' => 'catalog_homepage_sections',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'catalog_advantages_subtitle', [
        'default'           => __( 'Build resilient energy protection platforms with configurable, high-reliability catalog technology.', 'catalog' ),
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'catalog_advantages_subtitle', [
        'label'   => __( 'Advantages Section Subtitle', 'catalog' ),
        'section' => 'catalog_homepage_sections',
        'type'    => 'textarea',
    ] );

}

add_action( 'wp_head', 'catalog_output_meta_keywords', 1 );
function catalog_output_meta_keywords() {
    $keywords = trim( (string) get_theme_mod( 'catalog_seo_keywords', '' ) );
    if ( '' === $keywords ) {
        return;
    }

    printf( "\n<meta name=\"description\" content=\"%s\" />\n", esc_attr( $keywords ) );
}

add_action( 'add_meta_boxes', 'catalog_add_meta_boxes' );
function catalog_add_meta_boxes() {
    add_meta_box( 'catalog-slide-settings', __( 'Slide Settings', 'catalog' ), 'catalog_render_slide_meta_box', 'catalog_slide', 'normal', 'high' );
    add_meta_box( 'catalog-product-settings', __( 'Product Details', 'catalog' ), 'catalog_render_product_meta_box', 'catalog_product', 'normal', 'default' );
    add_meta_box( 'catalog-cta-settings', __( 'CTA Button', 'catalog' ), 'catalog_render_cta_meta_box', 'catalog_cta', 'side', 'default' );
}

function catalog_render_slide_meta_box( $post ) {
    wp_nonce_field( 'catalog_slide_meta', 'catalog_slide_meta_nonce' );
    $tagline    = get_post_meta( $post->ID, 'catalog_slide_tagline', true );
    $primaryLbl = get_post_meta( $post->ID, 'catalog_slide_primary_label', true );
    $primaryUrl = get_post_meta( $post->ID, 'catalog_slide_primary_url', true );
    $secondaryLbl = get_post_meta( $post->ID, 'catalog_slide_secondary_label', true );
    $secondaryUrl = get_post_meta( $post->ID, 'catalog_slide_secondary_url', true );
    $themeClass = get_post_meta( $post->ID, 'catalog_slide_theme_class', true );
    ?>
    <p>
        <label for="catalog_slide_tagline"><strong><?php esc_html_e( 'Tagline', 'catalog' ); ?></strong></label><br />
        <input type="text" name="catalog_slide_tagline" id="catalog_slide_tagline" value="<?php echo esc_attr( $tagline ); ?>" class="widefat" />
    </p>
    <p>
        <label for="catalog_slide_primary_label"><strong><?php esc_html_e( 'Primary Button Label', 'catalog' ); ?></strong></label><br />
        <input type="text" name="catalog_slide_primary_label" id="catalog_slide_primary_label" value="<?php echo esc_attr( $primaryLbl ); ?>" class="widefat" />
    </p>
    <p>
        <label for="catalog_slide_primary_url"><strong><?php esc_html_e( 'Primary Button URL', 'catalog' ); ?></strong></label><br />
        <input type="url" name="catalog_slide_primary_url" id="catalog_slide_primary_url" value="<?php echo esc_attr( $primaryUrl ); ?>" class="widefat" placeholder="https://" />
    </p>
    <p>
        <label for="catalog_slide_secondary_label"><strong><?php esc_html_e( 'Secondary Button Label', 'catalog' ); ?></strong></label><br />
        <input type="text" name="catalog_slide_secondary_label" id="catalog_slide_secondary_label" value="<?php echo esc_attr( $secondaryLbl ); ?>" class="widefat" />
    </p>
    <p>
        <label for="catalog_slide_secondary_url"><strong><?php esc_html_e( 'Secondary Button URL', 'catalog' ); ?></strong></label><br />
        <input type="url" name="catalog_slide_secondary_url" id="catalog_slide_secondary_url" value="<?php echo esc_attr( $secondaryUrl ); ?>" class="widefat" placeholder="https://" />
    </p>
    <p>
        <label for="catalog_slide_theme_class"><strong><?php esc_html_e( 'Visual Theme', 'catalog' ); ?></strong></label><br />
        <select name="catalog_slide_theme_class" id="catalog_slide_theme_class" class="widefat">
            <?php
            $themes = [
                'slide--variant-a' => __( 'Warm Gradient', 'catalog' ),
                'slide--variant-b' => __( 'Deep Blue', 'catalog' ),
                'slide--variant-c' => __( 'Teal Accent', 'catalog' ),
            ];
            foreach ( $themes as $value => $label ) {
                printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $value ), esc_html( $label ), selected( $themeClass, $value, false ) );
            }
            ?>
        </select>
    </p>
    <?php
}

function catalog_render_product_meta_box( $post ) {
    wp_nonce_field( 'catalog_product_meta', 'catalog_product_meta_nonce' );
    $subtitle = get_post_meta( $post->ID, 'catalog_product_subtitle', true );
    $specs    = get_post_meta( $post->ID, 'catalog_product_specs', true );
    ?>
    <p>
        <label for="catalog_product_subtitle"><strong><?php esc_html_e( 'Subtitle', 'catalog' ); ?></strong></label><br />
        <input type="text" name="catalog_product_subtitle" id="catalog_product_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="widefat" />
    </p>
    <p>
        <label for="catalog_product_specs"><strong><?php esc_html_e( 'Key Features (one per line)', 'catalog' ); ?></strong></label><br />
        <textarea name="catalog_product_specs" id="catalog_product_specs" rows="6" class="widefat" placeholder="<?php esc_attr_e( 'Voltage range, Rated current, Highlights…', 'catalog' ); ?>"><?php echo esc_textarea( $specs ); ?></textarea>
    </p>
    <?php
}

function catalog_render_cta_meta_box( $post ) {
    wp_nonce_field( 'catalog_cta_meta', 'catalog_cta_meta_nonce' );
    $label = get_post_meta( $post->ID, 'catalog_cta_button_label', true );
    $link  = get_post_meta( $post->ID, 'catalog_cta_button_link', true );
    ?>
    <p>
        <label for="catalog_cta_button_label"><strong><?php esc_html_e( 'Button Label', 'catalog' ); ?></strong></label><br />
        <input type="text" name="catalog_cta_button_label" id="catalog_cta_button_label" value="<?php echo esc_attr( $label ); ?>" class="widefat" />
    </p>
    <p>
        <label for="catalog_cta_button_link"><strong><?php esc_html_e( 'Button URL', 'catalog' ); ?></strong></label><br />
        <input type="url" name="catalog_cta_button_link" id="catalog_cta_button_link" value="<?php echo esc_attr( $link ); ?>" class="widefat" placeholder="https://" />
    </p>
    <?php
}

add_action( 'save_post', 'catalog_save_meta_boxes' );
function catalog_save_meta_boxes( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    $post_type = get_post_type( $post_id );

    if ( 'catalog_slide' === $post_type ) {
        if ( ! isset( $_POST['catalog_slide_meta_nonce'] ) || ! wp_verify_nonce( $_POST['catalog_slide_meta_nonce'], 'catalog_slide_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = [
            'catalog_slide_tagline'         => 'sanitize_text_field',
            'catalog_slide_primary_label'   => 'sanitize_text_field',
            'catalog_slide_primary_url'     => 'esc_url_raw',
            'catalog_slide_secondary_label' => 'sanitize_text_field',
            'catalog_slide_secondary_url'   => 'esc_url_raw',
            'catalog_slide_theme_class'     => 'sanitize_text_field',
        ];

        foreach ( $fields as $field => $callback ) {
            $value = isset( $_POST[ $field ] ) ? call_user_func( $callback, wp_unslash( $_POST[ $field ] ) ) : '';
            if ( 'catalog_slide_theme_class' === $field ) {
                $allowed = [ 'slide--variant-a', 'slide--variant-b', 'slide--variant-c' ];
                if ( ! in_array( $value, $allowed, true ) ) {
                    $value = 'slide--variant-a';
                }
            }
            update_post_meta( $post_id, $field, $value );
        }
    }

    if ( 'catalog_product' === $post_type ) {
        if ( ! isset( $_POST['catalog_product_meta_nonce'] ) || ! wp_verify_nonce( $_POST['catalog_product_meta_nonce'], 'catalog_product_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $subtitle = isset( $_POST['catalog_product_subtitle'] ) ? sanitize_text_field( wp_unslash( $_POST['catalog_product_subtitle'] ) ) : '';
        $specs    = isset( $_POST['catalog_product_specs'] ) ? sanitize_textarea_field( wp_unslash( $_POST['catalog_product_specs'] ) ) : '';

        update_post_meta( $post_id, 'catalog_product_subtitle', $subtitle );
        update_post_meta( $post_id, 'catalog_product_specs', $specs );
    }

    if ( 'catalog_cta' === $post_type ) {
        if ( ! isset( $_POST['catalog_cta_meta_nonce'] ) || ! wp_verify_nonce( $_POST['catalog_cta_meta_nonce'], 'catalog_cta_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $label = isset( $_POST['catalog_cta_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['catalog_cta_button_label'] ) ) : '';
        $link  = isset( $_POST['catalog_cta_button_link'] ) ? esc_url_raw( wp_unslash( $_POST['catalog_cta_button_link'] ) ) : '';

        update_post_meta( $post_id, 'catalog_cta_button_label', $label );
        update_post_meta( $post_id, 'catalog_cta_button_link', $link );
    }
}

function catalog_get_excerpt( WP_Post $post, $words = 24 ) {
    $excerpt = $post->post_excerpt ?: wp_trim_words( wp_strip_all_tags( $post->post_content ), $words, '…' );
    return esc_html( $excerpt );
}

function catalog_get_first_line( WP_Post $post ) {
    $source = $post->post_excerpt ?: $post->post_content;

    if ( '' === trim( $source ) ) {
        return '';
    }

    $normalized = preg_replace( '/<br\s*\/?>/i', "\n", $source );
    $normalized = preg_replace( '/<\/p>/i', "</p>\n", $normalized );

    $plain = wp_strip_all_tags( $normalized );
    $plain = preg_replace( "/\r\n|\r|\n/", "\n", $plain );
    $plain = preg_replace( '/[ \t]+/u', ' ', $plain );
    $plain = trim( $plain );

    if ( '' === $plain ) {
        return '';
    }

    $lines      = preg_split( "/\n+/", $plain );
    $first_line = trim( (string) ( $lines[0] ?? '' ) );

    if ( '' === $first_line ) {
        return '';
    }

    $has_more = false;
    if ( count( $lines ) > 1 ) {
        foreach ( array_slice( $lines, 1 ) as $line ) {
            if ( '' !== trim( $line ) ) {
                $has_more = true;
                break;
            }
        }
    }

    if ( ! $has_more ) {
        $plain_length = function_exists( 'mb_strlen' ) ? mb_strlen( $plain ) : strlen( $plain );
        $first_length = function_exists( 'mb_strlen' ) ? mb_strlen( $first_line ) : strlen( $first_line );
        $has_more     = $plain_length > $first_length;
    }

    if ( $has_more && ! preg_match( '/…$/u', $first_line ) ) {
        $first_line .= '…';
    }

    return $first_line;
}

function catalog_get_specs_list( $spec_string ) {
    $lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $spec_string ) ) );
    return $lines;
}

function catalog_get_product_image_html( $post_id, $size = 'medium_large' ) {
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, [ 'loading' => 'lazy' ] );
    }

    $content = get_post_field( 'post_content', $post_id );
    if ( $content && preg_match( '/<img[^>]+src=["\"](.*?)["\"][^>]*>/i', $content, $matches ) ) {
        $src = esc_url( $matches[1] );
        if ( $src ) {
            return sprintf( '<img src="%1$s" alt="" loading="lazy" />', $src );
        }
    }

    return '';
}

function catalog_get_slide_image_html( $post_id ) {
    $alt_text = wp_strip_all_tags( get_the_title( $post_id ) );

    $featured = get_the_post_thumbnail( $post_id, 'full', [
        'class'   => 'hero-slide__image',
        'loading' => 'lazy',
        'alt'     => $alt_text,
    ] );
    if ( $featured ) {
        return $featured;
    }

    $attachments = get_attached_media( 'image', $post_id );
    if ( $attachments ) {
        $attachment = reset( $attachments );
        $alt        = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
        $alt_attr   = $alt ? wp_strip_all_tags( $alt ) : $alt_text;

        return wp_get_attachment_image( $attachment->ID, 'full', false, [
            'class'   => 'hero-slide__image',
            'loading' => 'lazy',
            'alt'     => $alt_attr,
        ] );
    }

    $content = get_post_field( 'post_content', $post_id );
    if ( $content && preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches ) ) {
        $src = esc_url( $matches[1] );
        if ( $src ) {
            return sprintf(
                '<img src="%1$s" class="hero-slide__image" alt="%2$s" loading="lazy" />',
                $src,
                esc_attr( $alt_text )
            );
        }
    }

    return '';
}

add_filter( 'wp_robots', 'catalog_customize_wp_robots' );
function catalog_customize_wp_robots( array $robots ) {
    unset( $robots['noindex'], $robots['nofollow'] );

    $robots['index']             = true;
    $robots['follow']            = true;
    $robots['max-image-preview'] = 'large';

    return $robots;
}
