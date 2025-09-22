<?php
/**
 * Template Name: Catalog Products
 * Description: Displays the full catalog product grid.
 *
 * @package Catalog
 */

get_header();
?>

<main>
    <section class="section" style="padding-bottom:2rem;">
        <div class="container">
            <h1 class="section-title" style="margin-top:0.5rem;">Products</h1>
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    if ( get_the_content() ) {
                        echo '<div class="page-content">' . wp_kses_post( wpautop( get_the_content() ) ) . '</div>';
                    }
                }
            }
            ?>
        </div>
    </section>

    <section class="section products" style="padding-top:0;">
        <div class="container">
            <?php
            $search_term = isset( $_GET['product_search'] ) ? sanitize_text_field( wp_unslash( $_GET['product_search'] ) ) : '';
            ?>
            <form class="product-search" role="search" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
                <label class="screen-reader-text" for="product-search-field"><?php esc_html_e( 'Search products', 'catalog' ); ?></label>
                <input
                    id="product-search-field"
                    type="search"
                    name="product_search"
                    value="<?php echo esc_attr( $search_term ); ?>"
                    placeholder="<?php esc_attr_e( 'Search products by name...', 'catalog' ); ?>"
                    autocomplete="off"
                />
                <button type="submit"><?php esc_html_e( 'Search', 'catalog' ); ?></button>
            </form>
            <?php
            $product_query_args = [
                'post_type'      => 'catalog_product',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
            ];

            if ( '' !== $search_term ) {
                $product_query_args['s']              = $search_term;
                $product_query_args['search_columns'] = [ 'post_title' ];
            }

            $product_query = new WP_Query( $product_query_args );

            if ( $product_query->have_posts() ) :
                ?>
                <?php if ( '' !== $search_term ) : ?>
                    <p class="product-search__results-meta">
                        <?php
                        printf(
                            /* translators: %s search term */
                            esc_html__( 'Showing results for "%s".', 'catalog' ),
                            esc_html( $search_term )
                        );
                        ?>
                    </p>
                <?php endif; ?>

                <div class="products-grid">
                    <?php
                    while ( $product_query->have_posts() ) :
                        $product_query->the_post();
                        $product_id = get_the_ID();
                        $subtitle   = get_post_meta( $product_id, 'catalog_product_subtitle', true );
                        $specs      = catalog_get_specs_list( get_post_meta( $product_id, 'catalog_product_specs', true ) );
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
                                    <?php if ( $subtitle ) : ?><p class="product-card__subtitle"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
                                    <?php if ( $specs ) : ?>
                                        <ul>
                                            <?php foreach ( $specs as $spec ) : ?>
                                                <li><?php echo esc_html( $spec ); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>
                <?php
            else :
                if ( '' !== $search_term ) {
                    echo '<p>' . esc_html__( 'No products match your search. Try another name.', 'catalog' ) . '</p>';
                } else {
                    echo '<p>' . esc_html__( 'No products published yet. Add product entries to display them here.', 'catalog' ) . '</p>';
                }
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
