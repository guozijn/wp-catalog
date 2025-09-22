<?php
/**
 * Single product template.
 *
 * @package Catalog
 */

global $post;

get_header();
?>

<main class="product-single">
    <div class="container product-single__wrapper">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'product-single__card' ); ?>>
            <header class="product-single__header">
                <h1 class="product-single__title"><?php the_title(); ?></h1>
                <?php if ( $subtitle = get_post_meta( get_the_ID(), 'catalog_product_subtitle', true ) ) : ?>
                    <p class="product-single__subtitle"><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <figure class="product-single__media">
                    <?php the_post_thumbnail( 'large', [ 'loading' => 'lazy' ] ); ?>
                </figure>
            <?php endif; ?>

            <div class="product-single__content">
                <?php the_content(); ?>

                <?php
                $specs = catalog_get_specs_list( get_post_meta( get_the_ID(), 'catalog_product_specs', true ) );
                if ( $specs ) :
                    ?>
                    <section class="product-single__specs">
                        <h2><?php esc_html_e( 'Key Features', 'catalog' ); ?></h2>
                        <ul>
                            <?php foreach ( $specs as $spec ) : ?>
                                <li><?php echo esc_html( $spec ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                    <?php
                endif;
                ?>
            </div>
        </article>
    </div>
</main>

<?php
get_footer();
