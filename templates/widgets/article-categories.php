<?php
/**
 * Articles: Categories widget template
 */

if ( ! isset( $article_categories ) )
    return;
?>
<div class="sfywp-widget">

    <?php if ( is_array( $article_categories ) && sizeof( $article_categories ) > 0 ) : ?>

        <ul <?php sfywp_the_article_widget_list_classes('sfywp-widget-list'); ?>>

            <?php foreach ( $article_categories as $article_category ) : ?>

                <li <?php sfywp_the_article_widget_list_item_classes('sfywp-widget-list__item', $article_category->term_id ); ?>>
                    <a href="<?php echo get_term_link( $article_category->name, SFYWP_ARTICLES_CATEGORY_TAX ); ?>" title="<?php echo $article_category->name; ?>">
                        <?php echo $article_category->name; ?>
                    </a>
                </li>

            <?php endforeach; ?>

        </ul>

    <?php else : ?>

        <?php _e('No categories found.', 'supportifywp' ); ?>

    <?php endif; ?>

</div>
