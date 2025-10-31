<?php
/**
 * Default page template.
 *
 * @package TekGurus
 */
get_header();
?>
<div class="max-w-4xl mx-auto px-6 py-16 content-area">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            the_content();
        }
    }
    ?>
</div>
<?php
get_footer();
