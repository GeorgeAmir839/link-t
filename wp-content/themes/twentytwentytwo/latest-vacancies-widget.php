<?php
class Latest_Vacancies_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'latest_vacancies_widget',
            'Latest Vacancies',
            array('description' => 'Display the latest 5 job vacancies.')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Latest Vacancies' . $args['after_title'];

        $args = array(
            'post_type' => 'vacancies',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'order' => 'DESC',
        );

        $vacancies = new WP_Query($args);

        if ($vacancies->have_posts()) {
            echo '<ul>';
            while ($vacancies->have_posts()) {
                $vacancies->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                
            }
            echo '</ul>';
        } else {
            echo '<p>No job vacancies found.</p>';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }
}

function register_latest_vacancies_widget() {
    register_widget('Latest_Vacancies_Widget');
}

add_action('widgets_init', 'register_latest_vacancies_widget');
