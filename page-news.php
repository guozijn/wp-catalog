<?php
/**
 * Template Name: Catalog Newsroom
 * Description: Displays all published posts for the newsroom feed.
 *
 * @package Catalog
 */

get_header();
?>

<main>
    <section class="section" style="padding-bottom:1.5rem;">
        <div class="container">
            <h1 class="section-title" style="margin-top:0.5rem;">News</h1>
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

    <section class="section news" style="padding-top:0;">
        <div class="container">
            <?php
            $news_query = new WP_Query([
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            ]);

            if ( $news_query->have_posts() ) :
                echo '<div class="news-list">';
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
                            <p class="news-item__summary"><?php echo esc_html( $news_summary ); ?></p>
                            <?php
                        endif;
                        ?>
                    </article>
                    <?php
                endwhile;
                echo '</div>';

                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'No news articles published yet. Create posts to populate this list.', 'catalog' ) . '</p>';
            endif;
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
