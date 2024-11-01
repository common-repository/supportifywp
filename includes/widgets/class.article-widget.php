<?php
/**
 * Adds SFYWP_Article_Widget widget.
 */
class SFYWP_Article_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'sfywp_article_widget', // Base ID
            esc_html__( 'SupportifyWP: Articles', 'supportifywp' ), // Name
            array( 'description' => esc_html__( 'Listing articles.', 'supportifywp' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        if ( ! empty( $args['before_widget'] ) )
            echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $args = array();

        if ( ! empty( $instance['items'] ) )
            $args['posts_per_page'] = $instance['items'];

        if ( ! empty( $instance['category'] ) )
            $args['sfywp_article_category'] = $instance['category'];

        if ( ! empty( $instance['orderby'] ) )
            $args['sfywp_orderby'] = $instance['orderby'];

        if ( ! empty( $instance['order'] ) )
            $args['sfywp_order'] = $instance['order'];

        $articles = sfywp_get_articles( $args );

        include SFYWP_TEMPLATES_DIR . '/widgets/articles.php';

        if ( ! empty( $args['after_widget'] ) )
            echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'New title', 'supportifywp' );
        $items = ( ! empty( $instance['items'] ) ) ? $instance['items'] : 5;
        $category = ( ! empty( $instance['category'] ) ) ? $instance['category'] : '';
        $categories = sfywp_widget_get_article_category_options();
        $orderby = ( ! empty( $instance['orderby'] ) ) ? $instance['orderby'] : sfywp_get_articles_orderby_default();
        $orderby_options = sfywp_get_articles_orderby_options();
        $order = ( ! empty( $instance['order'] ) ) ? $instance['order'] : sfywp_get_order_default();
        $order_options = sfywp_get_order_options();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'supportifywp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>"><?php esc_attr_e( 'Amount of items:', 'supportifywp' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>" type="number" value="<?php echo esc_attr( $items ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'supportifywp' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat">
                <?php foreach ( $categories as $key => $label ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $category, $key ); ?>><?php echo $label; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order by:', 'supportifywp' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
                <?php foreach ( $orderby_options as $key => $label ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $orderby, $key ); ?>><?php echo $label; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order:', 'supportifywp' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat">
                <?php foreach ( $order_options as $key => $label ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $order, $key ); ?>><?php echo $label; ?></option>
                <?php } ?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['items'] = ( ! empty( $new_instance['items'] ) && is_numeric( $new_instance['items'] ) ) ? strip_tags( $new_instance['items'] ) : 5;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
        $instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';

        return $instance;
    }

} // class SFYWP_Article_Widget
