<?php
/**
 * Theme footer markup.
 *
 * @package Catalog
 */

$support_phone = get_theme_mod( 'catalog_contact_support', '+1 (800) 123-4567' );
$sales_email   = get_theme_mod( 'catalog_contact_sales', 'sales@fuseshield.com' );
$hq_address    = get_theme_mod( 'catalog_contact_address', '66 Innovation Avenue, Springfield' );
$copyright     = get_theme_mod( 'catalog_copyright_text', '© ' . date( 'Y' ) . ' FuseShield Technologies Inc. All rights reserved.' );
?>
<footer class="site-footer" id="contact">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-nav">
                <h4><?php esc_html_e( 'QUICK LINKS', 'catalog' ); ?></h4>
                <?php
                if ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'depth'          => 1,
                    ] );
                } else {
                    ?>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/#advantages' ) ); ?>"><?php esc_html_e( 'ADVANTAGES', 'catalog' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/#products' ) ); ?>"><?php esc_html_e( 'PRODUCTS', 'catalog' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/#certifications' ) ); ?>"><?php esc_html_e( 'CERTIFICATIONS', 'catalog' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/#news' ) ); ?>"><?php esc_html_e( 'NEWS', 'catalog' ); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>
            <div class="footer-nav">
                <h4><?php esc_html_e( 'CONTACT', 'catalog' ); ?></h4>
                <ul>
                    <?php if ( $support_phone ) : ?>
                        <li><?php printf( '%s %s', esc_html__( 'Phone:', 'catalog' ), esc_html( $support_phone ) ); ?></li>
                    <?php endif; ?>
                    <?php if ( $sales_email ) : ?>
                        <li><?php printf( '%s <a href="mailto:%s">%s</a>', esc_html__( 'Sales:', 'catalog' ), esc_attr( $sales_email ), esc_html( $sales_email ) ); ?></li>
                    <?php endif; ?>
                    <?php if ( $hq_address ) : ?>
                        <li><?php printf( '%s %s', esc_html__( 'Headquarters:', 'catalog' ), esc_html( $hq_address ) ); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span><?php echo wp_kses_post( $copyright ); ?></span>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
