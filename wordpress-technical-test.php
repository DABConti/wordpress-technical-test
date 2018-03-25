<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www2.gov.bc.ca/
 * @since             1.0.0
 * @package           WordPress_Technical_Test
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Technical Test
 * Plugin URI:        https://www2.gov.bc.ca/
 * Description:       The WordPress technical test for the Province of British Columbia - GCPE.
 * Version:           1.0.0
 * Author:            Province of British Columbia
 * Author URI:        https://www2.gov.bc.ca/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordpress-technical-test
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-technical-test-activator.php
 */
function activate_wordpress_technical_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test-activator.php';
	WordPress_Technical_Test_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-technical-test-deactivator.php
 */
function deactivate_wordpress_technical_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test-deactivator.php';
	WordPress_Technical_Test_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordpress_technical_test' );
register_deactivation_hook( __FILE__, 'deactivate_wordpress_technical_test' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordpress_technical_test() {

	$plugin = new WordPress_Technical_Test();
	$plugin->run();

}
run_wordpress_technical_test();

add_action( 'init', 'create_books' );

function create_books() {
    register_post_type( 'books',
        array(
            'labels' => array(
                'name' => 'Books',
                'singular_name' => 'Book',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Book',
                'edit' => 'Edit',
                'edit_item' => 'Edit Book',
                'new_item' => 'New Book',
                'view' => 'View',
                'view_item' => 'View Book',
                'search_items' => 'Search Books',
                'not_found' => 'No Books found',
                'not_found_in_trash' => 'No Books found in Trash',
                'parent' => 'Parent Book'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

function book_genre_init() {
	// create a new taxonomy
	register_taxonomy(
		'book-genre',
		'books',
		array(
			'label' => __( 'Book Genre' ),
			'rewrite' => array( 'slug' => 'book-genre' )
		)
	);

	//prepopulate taxonomies

	wp_insert_term(
			'Technical Documentation',
			'book-genre'
		);

	wp_insert_term(
			'Coding Standards',
			'book-genre'
		);

	wp_insert_term(
			'Easy Reading',
			'book-genre'
		);

}
add_action( 'init', 'book_genre_init' );

add_action ('admin_init', 'my_admin');

function my_admin() {
	add_meta_box( 'book_meta_box',
        'Book Details',
        'display_book_author_meta_box',
        'books', 
        'normal', 
        'high'
    );
}

function display_book_author_meta_box( $books ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
    $author_name = esc_html( get_post_meta( $books->ID, 'author_name', true ) );;
    $release_year = intval( get_post_meta( $books->ID, 'release_year', true ) );;
    $publisher = esc_html( get_post_meta( $books->ID, 'publisher', true ) );;

    ?>
    <table>
        <tr>
            <td style="width: 100%">Author Name</td>
            <td><input type="text" size="80" name="book_author_name" value="<?php echo $author_name; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Release Year</td>
            <td><input type="number" size="8" min="0" max="2100" name="book_release_year" value="<?php echo $release_year; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Publisher</td>
            <td><input type="text" size="80" name="book_publisher" value="<?php echo $publisher; ?>" /></td>
        </tr>
    </table>
    <?php
}

add_action ('save_post', 'add_book_fields', 10, 2);

function add_book_fields ($book_id, $book){
	//check post type for books
	if ( $book->post_type == 'books') {
		// Store Data in post meta table if it is present in post data
		if ( isset( $_POST['book_author_name'] ) && $_POST['book_author_name'] != '' ) {
            update_post_meta( $book_id, 'author_name', $_POST['book_author_name'] );
        }
        if ( isset( $_POST['book_release_year'] ) && $_POST['book_release_year'] != '' ) {
            update_post_meta( $book_id, 'release_year', $_POST['book_release_year'] );
        }
        if ( isset( $_POST['book_publisher'] ) && $_POST['book_publisher'] != '' ) {
            update_post_meta( $book_id, 'publisher', $_POST['book_publisher'] );
        }
	}
}



?>