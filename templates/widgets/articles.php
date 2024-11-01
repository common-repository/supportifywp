<?php
/**
 * Articles: Default widget template
 */

if ( ! isset( $articles ) )
    return;
?>
<div class="sfywp-widget">

    <?php if ( $articles->have_posts() ) : ?>

        <ul <?php sfywp_the_article_widget_list_classes('sfywp-widget-list'); ?>>

            <?php while ( $articles->have_posts() ) : $articles->the_post(); // Start the Loop ?>

                <li <?php sfywp_the_article_widget_list_item_classes('sfywp-widget-list__item'); ?>>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                </li>

            <?php endwhile; // End of the loop. ?>

        </ul>

        <?php wp_reset_postdata(); ?>

    <?php else : ?>

        <?php _e('No articles found.', 'supportifywp' ); ?>

    <?php endif; ?>

</div>