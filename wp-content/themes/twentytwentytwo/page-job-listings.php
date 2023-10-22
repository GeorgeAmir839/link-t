<?php
/*
Template Name: Job Vacancies
*/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <?php
                $args = array(
                    'post_type' => 'vacancies', // Change to your custom post type name
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );

                $vacancies = new WP_Query($args);

                if ($vacancies->have_posts()) :
                    echo '<ul>';
                    while ($vacancies->have_posts()) : $vacancies->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    endwhile;
                    echo '</ul>';
                else :
                    echo '<p>No job vacancies found.</p>';
                endif;

                wp_reset_postdata();
                ?>
            </div>
        </article>
    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>