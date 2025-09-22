<?php
/**
 * Default single post template.
 *
 * @package Catalog
 */

get_header();
?>

<main class="news-single">
    <section class="section">
        <div class="container news-single__wrapper">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'news-single__article' ); ?>>
                        <h1 class="news-single__title"><?php the_title(); ?></h1>

                        <div class="news-single__content">
                            <?php
                            the_content();

                            wp_link_pages([
                                'before' => '<nav class="news-single__pagination" aria-label="' . esc_attr__( 'Page', 'catalog' ) . '">',
                                'after'  => '</nav>',
                            ]);
                            ?>
                        </div>
                    </article>
                    <?php
                endwhile;
            endif;
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
