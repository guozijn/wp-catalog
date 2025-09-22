<?php
/**
 * Theme header markup.
 *
 * @package Catalog
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container header-inner">
        <?php
        $brand_logo_id   = (int) get_theme_mod( 'catalog_brand_logo_image', 0 );
        $brand_logo_text = get_theme_mod( 'catalog_brand_logo_text', 'FG' );
        $brand_tagline   = get_theme_mod( 'catalog_brand_tagline', 'FuseShield | Smart Protection' );
        $brand_logo_alt  = get_bloginfo( 'name', 'display' );
        $brand_logo_classes = [ 'brand-logo' ];
        if ( $brand_logo_id ) {
            $brand_logo_classes[] = 'brand-logo--has-image';
        }
        ?>
        <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <span class="<?php echo esc_attr( implode( ' ', $brand_logo_classes ) ); ?>">
                <?php
                if ( $brand_logo_id ) {
                    echo wp_get_attachment_image( $brand_logo_id, 'medium', false, [
                        'class' => 'brand-logo__image',
                        'alt'   => esc_attr( $brand_logo_alt ),
                    ] );
                } else {
                    echo '<span class="brand-logo__text">' . esc_html( $brand_logo_text ) . '</span>';
                }
                ?>
            </span>
            <span class="brand-text"><?php echo esc_html( $brand_tagline ); ?></span>
        </a>
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'catalog' ); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => '',
                    'items_wrap'     => '<ul>%3$s</ul>',
                ]);
                ?>
            </nav>
        <?php else : ?>
            <nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'catalog' ); ?>">
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/#advantages' ) ); ?>"><?php esc_html_e( 'Advantages', 'catalog' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Products', 'catalog' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/certifications/' ) ); ?>"><?php esc_html_e( 'Certifications', 'catalog' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>"><?php esc_html_e( 'News', 'catalog' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'catalog' ); ?></a></li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</header>
