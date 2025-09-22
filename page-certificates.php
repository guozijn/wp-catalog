<?php
/**
 * Template Name: Catalog Certifications
 * Description: Lists catalog compliance and accreditation entries.
 *
 * @package Catalog
 */

get_header();
?>

<main>
    <section class="certificates-banner">
        <div class="container certificates-banner__inner">
            <div class="certificates-banner__content">
                <h1 class="section-title">Certifications</h1>
                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        if ( get_the_content() ) {
                            echo '<div class="page-content">' . wp_kses_post( wpautop( get_the_content() ) ) . '</div>';
                        }
                    }
                    rewind_posts();
                }
                ?>
            </div>
        </div>
    </section>

    <section class="section certificates" style="padding-top:0;">
        <div class="container">
            <?php
            $certificate_query = new WP_Query([
                'post_type'      => 'catalog_certificate',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order date',
                'order'          => 'ASC',
            ]);

            if ( $certificate_query->have_posts() ) :
                ?>
                <div class="certificate-carousel" data-carousel data-autoplay>
                    <div class="certificate-track">
                        <?php
                        while ( $certificate_query->have_posts() ) :
                            $certificate_query->the_post();
                            $thumb = catalog_get_product_image_html( get_the_ID(), 'medium' );
                            ?>
                    <article class="certificate-slide">
                        <a class="certificate-slide__link" href="<?php the_permalink(); ?>">
                        <figure class="certificate-slide__media">
                            <?php
                            if ( $thumb ) {
                                echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            } elseif ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] );
                            } else {
                                echo '<span class="certificate-slide__placeholder" aria-hidden="true">📄</span>';
                            }
                            ?>
                        </figure>
                        <h3 class="certificate-slide__title"><?php the_title(); ?></h3>
                        </a>
                    </article>
                            <?php
                        endwhile;
                        ?>
                    </div>
                    <button class="carousel-nav carousel-prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'catalog' ); ?>">‹</button>
                    <button class="carousel-nav carousel-next" type="button" aria-label="<?php esc_attr_e( 'Next', 'catalog' ); ?>">›</button>
                </div>
                <?php
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'No certifications published yet. Add certificate entries to showcase your credentials.', 'catalog' ) . '</p>';
            endif;
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
