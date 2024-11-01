<?php
/**
 * Articles: Front page template
 */

// Template variables
$show_excerpt = sfywp_show_articles_excerpt();

// Header
get_header( 'supportifywp' ); ?>

<?php do_action( 'sfywp_before_main_content' ); ?>

<div class="sfywp">

    <div <?php sfywp_the_container_classes('sfywp-container'); ?>>

        <div class="sfywp-main">

            <div class="sfywp-articles-categories">

                <div class="sfywp-articles-categories__header">
                    <?php sfywp_the_title( '<h1 class="entry-title sfywp-articles-categories__title">', '</h1>' ); ?>
                </div>

                <div class="sfywp-articles-categories__content">

                    <?php $categories = sfywp_get_articles_front_page_categories(); ?>

                    <?php if ( is_array( $categories ) && sizeof( $categories ) > 0 ) : ?>

                        <div class="sfywp-articles-categories-container">

                            <?php foreach ( $categories as $category ) : ?>

                                <?php if ( ! isset( $category->term_id ) || ! isset( $category->name ) || empty( $category->count ) )
                                    continue; ?>

                                <div id="sfywp-articles-category-<?php echo $category->term_id; ?>" class="sfywp-articles-category">
                                    <h2 class="sfywp-articles-category__title">
                                        <a href="<?php echo get_term_link( $category->name, SFYWP_ARTICLES_CATEGORY_TAX ); ?>" title="<?php echo $category->name; ?>">
                                            <?php echo $category->name; ?>
                                        </a>
                                        <span class="sfywp-articles-category__count">
                                            <?php printf( esc_html( _n( '%d article', '%d articles', $category->count, 'supportifywp'  ) ), $category->count ); ?>
                                        </span>
                                    </h2>

                                    <?php $category_posts = sfywp_get_articles_front_page_category_posts( $category->term_id ); ?>

                                    <?php if ( $category_posts->have_posts() ) : ?>

                                        <div class="sfywp-articles-list">
                                            <?php while ( $category_posts->have_posts() ) : $category_posts->the_post(); // Start the Loop ?>

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

                                        <?php wp_reset_postdata(); ?>

                                    <?php endif; ?>

                                    <?php sfywp_the_articles_category_more( $category ); ?>
                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else : ?>

                        <p>No categories found.</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <?php sfywp_the_sidebar(); ?>

    </div>

</div>

<?php do_action( 'sfywp_after_main_content' ); ?>

<?php get_footer( 'supportifywp' );
