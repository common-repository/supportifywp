<?php
/**
 * Articles: Archive template
 */

// Template variables
$show_excerpt = sfywp_show_articles_excerpt();

// Header
get_header( 'supportifywp' ); ?>

<?php do_action( 'sfywp_before_main_content' ); ?>

<div class="sfywp">

    <div <?php sfywp_the_container_classes('sfywp-container'); ?>>

        <div class="sfywp-main">

            <div class="sfywp-articles-archive">

                <div class="sfywp-articles-archive__header">
                    <?php
                    sfywp_the_archive_title( '<h1 class="entry-title sfywp-articles-archive__title">', '</h1>' );
                    sfywp_the_archive_description( '<div class="sfywp-articles-archive__description">', '</div>' );
                    ?>
                </div>

                <div class="sfywp-articles-archive__content">

                    <?php if ( have_posts() ) : ?>

                        <div class="sfywp-articles-list">

                        <?php while ( have_posts() ) : the_post(); // Start the Loop ?>

                            <article id="sfywp-article-<?php the_ID(); ?>" <?php sfywp_the_article_classes('sfywp-article'); ?>>
                                <h3 class="sfywp-article__title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <?php if ( $show_excerpt ) : ?>
                                    <?php sfywp_the_excerpt( '<p class="sfywp-article__excerpt">', '</p>' ); ?>
                                <?php endif; ?>
                            </article>

                        <?php endwhile; // End of the loop. ?>

                        </div>

                    <?php else : ?>

                        <p>No articles found.</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <?php sfywp_the_sidebar(); ?>

    </div>

</div>

<?php do_action( 'sfywp_after_main_content' ); ?>

<?php get_footer( 'supportifywp' );
