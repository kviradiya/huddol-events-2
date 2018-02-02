<?php
/**
 * The Template for displaying work archive|index.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>
<?php if(is_author()): ?>
        <?php
    // Add proper body classes
    add_filter( 'body_class', array(G1_Theme(), 'secondary_none_body_class') );
    ?>
<?php else: ?>
    <?php
    // Add proper body classes
    add_filter( 'body_class', array(G1_Theme(), 'secondary_wide_body_class') );
    add_filter( 'body_class', array(G1_Theme(), 'secondary_after_body_class') );
    ?>

<?php endif ?>

<?php get_header(); ?>
    <?php /* JHILL HACK? the theme doesn't let us pick the right page for the author pages so I override it here */ ?>
    <?php if(is_author()): ?>
        <?php
            // Our tricky way to pass variables to a template part
            g1_part_set_data( array( 'collection' => 'one-half' ) );

            get_template_part( 'template-parts/g1_primary_collection', 'one-half' );
        ?>
    <?php else: ?>
        <?php
            // Our tricky way to pass variables to a template part
            g1_part_set_data( array( 'collection' => 'two-third', 'summary-type' => 'cut-off' ) );

            get_template_part( 'template-parts/g1_primary_collection', 'two-third' );
        ?>
        <?php get_sidebar(); ?>
    <?php endif ?>
<?php get_footer(); ?>