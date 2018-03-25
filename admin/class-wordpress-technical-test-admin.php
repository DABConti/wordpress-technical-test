<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www2.gov.bc.ca/
 * @since      1.0.0
 *
 * @package    WordPress_Technical_Test
 * @subpackage WordPress_Technical_Test/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WordPress_Technical_Test
 * @subpackage WordPress_Technical_Test/admin
 * @author     Province of British Columbia <no-reply@gov.bc.ca>
 */
class WordPress_Technical_Test_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		add_action( 'init', 'create_books' );

		add_action( 'init', 'book_genre_init' );

		add_action ('admin_init', 'book_meta_field');

		add_action ('save_post', 'add_book_fields', 10, 2);

	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WordPress_Technical_Test_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WordPress_Technical_Test_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordpress-technical-test-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WordPress_Technical_Test_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WordPress_Technical_Test_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordpress-technical-test-admin.js', array( 'jquery' ), $this->version, false );

	}

}

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

function book_meta_field() {
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