<?php
/**
 * Articles: Single template
 */

get_header( 'supportifywp' ); ?>

<?php do_action( 'sfywp_before_main_content' ); ?>

<div class="sfywp">

    <div <?php sfywp_the_container_classes('sfywp-container'); ?>>

        <div class="sfywp-main">

        <?php while ( have_posts() ) : the_post(); // Start the Loop ?>

            <article id="sfywp-article-<?php the_ID(); ?>" <?php sfywp_the_article_classes('sfywp-article'); ?>>

                <div class="sfywp-article__header">
                    <?php sfywp_the_title( '<h1 class="entry-title sfywp-article__title">', '</h1>' ); ?>
                </div>

                <div class="sfywp-article__content">
                    <?php the_content(); ?>
                </div>

                <div class="sfywp-article__footer">
                    <?php sfywp_the_meta(); ?>
                </div>

            </article>

        <?php endwhile; // End of the loop. ?>

        </div>

        <?php sfywp_the_sidebar(); ?>

    </div>

</div>

<?php do_action( 'sfywp_after_main_content' ); ?>

<?php get_footer( 'supportifywp' );