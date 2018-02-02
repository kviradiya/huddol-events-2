<?php
/**
 * Template Name: TCN: Read
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */
 
?>
<?php
// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );
?>

<?php get_header(); ?>

<?php
    $user = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
    if(is_network_partner($user))
    {
        include(locate_template('template-parts/tcn_network_partner.php'));
    }
    else
    {
        include(locate_template('template-parts/tcn_author.php'));
    }   
?>



<?php get_footer(); ?>