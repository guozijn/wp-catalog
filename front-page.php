<?php
/**
 * Front page template for Catalog theme.
 *
 * @package Catalog
 */

get_header();
?>

<main>
    <?php
    $slide_query = new WP_Query([
        'post_type'      => 'catalog_slide',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ]);
    ?>
    <section class="hero" id="top">
        <div class="container">
            <?php if ( $slide_query->have_posts() ) : ?>
                <div class="hero-slider" role="region" aria-label="Homepage highlights">
                    <div class="slides">
                        <?php
                        $index = 0;
                        while ( $slide_query->have_posts() ) :
                            $slide_query->the_post();
                            $slide_id        = get_the_ID();
                            $slide_url       = get_post_meta( $slide_id, 'catalog_slide_primary_url', true );
                            $theme_class     = get_post_meta( $slide_id, 'catalog_slide_theme_class', true ) ?: 'slide--variant-a';
                            $slide_classes   = implode( ' ', array_map( 'sanitize_html_class', array_filter( [ 'slide', $theme_class, 0 === $index ? 'is-active' : '' ] ) ) );
                            $is_hidden       = 0 === $index ? 'false' : 'true';
                            $image_html      = catalog_get_slide_image_html( $slide_id );
                            if ( ! $image_html ) {
                                continue;
                            }
                            ?>
                            <article class="<?php echo esc_attr( $slide_classes ); ?>" aria-hidden="<?php echo esc_attr( $is_hidden ); ?>">
                                <?php if ( $slide_url ) : ?>
                                    <a class="hero-slide__link" href="<?php echo esc_url( $slide_url ); ?>">
                                        <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <?php endif; ?>
                            </article>
                            <?php
                            $index++;
                        endwhile;
                        ?>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-arrow slider-prev" type="button" aria-label="Previous">‹</button>
                        <div class="slider-nav" role="tablist" aria-label="Slide selector">
                            <?php for ( $i = 0; $i < $index; $i++ ) : ?>
                                <button class="slider-dot<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" data-slide-to="<?php echo esc_attr( $i ); ?>" aria-label="Slide <?php echo esc_attr( $i + 1 ); ?>"></button>
                            <?php endfor; ?>
                        </div>
                        <button class="slider-arrow slider-next" type="button" aria-label="Next">›</button>
                    </div>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="hero-placeholder">
                    <h1><?php esc_html_e( 'Add your first slide', 'catalog' ); ?></h1>
                    <p><?php esc_html_e( 'Create slides under Slides in the dashboard to populate the hero area.', 'catalog' ); ?></p>
                </div>
            <?php endif; ?>

            <?php
            $stat_query = new WP_Query([
                'post_type'      => 'catalog_stat',
                'posts_per_page' => 3,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
            ]);
            if ( $stat_query->have_posts() ) :
                ?>
                <div class="hero-stats">
                    <?php
                    while ( $stat_query->have_posts() ) :
                        $stat_query->the_post();
                        ?>
                        <article class="stat-card">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo esc_html( catalog_get_excerpt( get_post(), 20 ) ); ?></p>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <?php
            endif;
            ?>
        </div>
    </section>

    <section class="section products" id="products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e( 'Product spotlight', 'catalog' ); ?></h2>
                <a class="button button-outline" href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Browse all products', 'catalog' ); ?></a>
            </div>
            <?php
            $product_query = new WP_Query([
                'post_type'      => 'catalog_product',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ( $product_query->have_posts() ) :
                ?>
                <div class="products-grid">
                    <?php
                    while ( $product_query->have_posts() ) :
                        $product_query->the_post();
                        $product_id = get_the_ID();
                        ?>
                        <article class="product-card">
                            <a class="product-card__inner" href="<?php the_permalink(); ?>">
                                <figure class="product-card__media">
                                    <?php
                                    $product_image = catalog_get_product_image_html( $product_id );
                                    if ( $product_image ) {
                                        echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    } else {
                                        echo '<span class="product-card__placeholder" aria-hidden="true">📦</span>';
                                    }
                                    ?>
                                </figure>
                                <div class="product-card__content">
                                    <h3><?php the_title(); ?></h3>
                                </div>
                            </a>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>
                <?php
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'Create product entries to populate this grid.', 'catalog' ) . '</p>';
            endif;
            ?>
        </div>
    </section>

    <section class="section certificates" id="certifications">
        <div class="container">
            <div class="certificates-header">
                <h2 class="section-title"><?php esc_html_e( 'Certifications', 'catalog' ); ?></h2>
                <a class="button button-outline" href="<?php echo esc_url( home_url( '/certifications/' ) ); ?>"><?php esc_html_e( 'View all certifications', 'catalog' ); ?></a>
            </div>
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
                    <button class="carousel-nav carousel-prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'catalog' ); ?>">
                        ‹
                    </button>
                    <button class="carousel-nav carousel-next" type="button" aria-label="<?php esc_attr_e( 'Next', 'catalog' ); ?>">
                        ›
                    </button>
                </div>
                <?php
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'Add certificate records to build trust signals.', 'catalog' ) . '</p>';
            endif;
            ?>
        </div>
    </section>

    <section class="section news" id="news">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e( 'News', 'catalog' ); ?></h2>
                <a class="button button-outline" href="<?php echo esc_url( home_url( '/news/' ) ); ?>"><?php esc_html_e( 'View all news', 'catalog' ); ?></a>
            </div>
            <div class="news-list">
                <?php
                $news_query = new WP_Query([
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                ]);

                if ( $news_query->have_posts() ) :
                    while ( $news_query->have_posts() ) :
                        $news_query->the_post();
                        ?>
                        <article class="news-item">
                            <div class="meta">
                                <span><?php echo esc_html( get_the_date() ); ?></span>
                                <span>·</span>
                                <span><?php echo esc_html( get_the_author() ); ?></span>
                            </div>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php
                            $news_summary = catalog_get_first_line( get_post() );
                            if ( $news_summary ) :
                                ?>
                                <p><?php echo esc_html( $news_summary ); ?></p>
                                <?php
                            endif;
                            ?>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p><?php esc_html_e( 'Publish posts to surface them here.', 'catalog' ); ?></p>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </section>

    <?php
    $advantages_title    = get_theme_mod( 'catalog_advantages_title', __( 'WHY CHOOSE US', 'catalog' ) );
    $advantages_subtitle = get_theme_mod( 'catalog_advantages_subtitle', __( 'Describing your advantages.', 'catalog' ) );
    ?>
    <section class="section" id="advantages">
        <div class="container">
            <?php if ( $advantages_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $advantages_title ); ?></h2>
            <?php endif; ?>
            <?php if ( $advantages_subtitle ) : ?>
                <p class="section-subtitle"><?php echo wp_kses_post( $advantages_subtitle ); ?></p>
            <?php endif; ?>
            <?php
            $adv_query = new WP_Query([
                'post_type'      => 'catalog_advantage',
                'posts_per_page' => 4,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
            ]);
            if ( $adv_query->have_posts() ) :
                ?>
                <div class="advantages-list">
                    <?php
                    while ( $adv_query->have_posts() ) :
                        $adv_query->the_post();
                        ?>
                        <article class="feature-card">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo esc_html( catalog_get_excerpt( get_post(), 30 ) ); ?></p>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>
                <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>

    <?php
    /**
     * Customer voices and CTA sections intentionally hidden per current requirements.
     */
    ?>
</main>

<?php
get_footer();
