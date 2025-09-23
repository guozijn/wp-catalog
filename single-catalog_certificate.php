<?php
/**
 * Single certificate template.
 *
 * @package Catalog
 */

global $post;

get_header();
?>

<main class="certificate-single">
    <div class="container certificate-single__wrapper">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'certificate-single__card' ); ?>>
            <header class="certificate-single__header">
                <h1 class="certificate-single__title"><?php the_title(); ?></h1>
            </header>

            <div class="certificate-single__content">
                <?php the_content(); ?>
            </div>
        </article>
    </div>
</main>

<?php
get_footer();
