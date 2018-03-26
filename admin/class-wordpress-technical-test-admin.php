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

		//Add the action for loading the custom page template from the plugin to the theme
		//	This is used to create a gallary of all the books in a page by selecting a template
		add_action( 'plugins_loaded', array( 'BooksTemplate', 'get_instance' ) );

		//Add the action to create the books custom post types
		add_action( 'init', 'create_books' );

		//Add the action to create the custom book taxonomies
		add_action( 'init', 'book_genre_init' );

		//Add the action to create the custom meta field for a book
		add_action ('admin_init', 'book_meta_field');

		//Add the action for POSTing the custom data in the meta fields
		add_action ('save_post', 'add_book_fields', 10, 2);

		//Add the action that loads the template for a single book.  This overrides 
		//	the default post functionality for an individual books custom post type
		add_filter ('template_include', 'include_book_template_function', 1);

		//Adds the shortcode [books]/[books id="[post id]"] to load a galery 
		//	of all books or a single one
		add_shortcode('books', 'books_shortcode');


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


/**
	Plugin Page Templates - In essence "tricks" wordpress into loading a template from a plugin and not a theme

	Code extract copied with some modifications on 03-14-2018.

	Refrence URL: http://www.wpexplorer.com/wordpress-page-templates-plugin/
	Author: WPExplorer
	Author URI: http://www.wpexplorer.com/
*/
class BooksTemplate {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new BooksTemplate();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);


		// Add your templates to this array.
		// This defines the file where the template is pulled from.
		$this->templates = array(
			'gallery-books-template.php' => 'Books Gallery',
		);

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		// Allows filtering of file path
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );

		$file =  $filepath . get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}

/**
 *	Creates the Books Custom Post type
 *
 */
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

/**
 *	Creates the custom taxonomy, book-genre for the Books Custom Post type
 *	It also prepopulates it with 3 initial genres
 *
 */
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

/**
 *	Creates the box for the meta fields for the author, release year and publisher
 *
 */
function book_meta_field() {
	add_meta_box( 'book_meta_box',
        'Book Details',
        'display_book_author_meta_box',
        'books', 
        'normal', 
        'high'
    );
}


/**
 *	Populates the meta box with the proper fields and configures these correctly.
 *
 *	Here we also limit the release year to a min of year 0 and a max of year 2100 
 *		and also ensure that it is a numeric value
 */
function display_book_author_meta_box( $books ) {
    // Retrieve current values for these meta fields
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

/**
 *	Here we post any data filled into these fields to the back end database
 *
 *	@param $book_id int
 *  @param $book object
 *
 */
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

/**
 *	Handler for adding the single-book-template template to override the default post functionality
 *		for displaying a single book.  We need this to display all the custom meta fields as well as
 *		any taxonomies
 *
 *	@param $template_path
 */
function include_book_template_function ($template_path) {
	if (get_post_type() == 'books') {
		//if the query for an existing single post
		if ( is_single() ) {
			$template_path = plugin_dir_path( __FILE__) . '/partials/single-book-template.php';
		}

	}

	return $template_path;
}

/**
 *	Handler adding a [books] shortcode that displays either a galary of all books or a singular one.
 *
 *	This could probibly be refactored as it copies most of its code from gallery-books-template, but
 *		At the time I could not figure out how to import a new template correctly using get_template_part
 *
 *	@param $atts input attribute that allows a user to use the shortcode like [books id="X"] where X
 *		is a post id of a books custom post type.  This will cause the shortcode to display that one 
 *		book
 */
function books_shortcode($atts) {
	//turn on output buffering
	$a = shortcode_atts( array(
        'id' => null,
    ), $atts );
	ob_start();
	
	//Gets the template
	?>
	<?php
	// The Query
    $args = array(  'post_type' => 'books');
    $loop = new WP_Query( $args );

    ?>

    <?php while ( $loop->have_posts() ) : $loop->the_post();?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php
					$page_id = get_the_ID();
					if(!is_null($a['id']) and $page_id != intval($a['id']))
					{
						continue;
					}

					$title = get_the_title( $page_id );
					$page_link = get_permalink ($page_id);

					echo sprintf('<h1 class="entry-title"> <a href= "%s"> %s </a> </h1>', $page_link, $title)
				?>
				
			</header><!-- .entry-header -->

			<?php 
	    	//Load Meta fields
	    	$author_name = get_post_meta(get_the_ID(),'author_name',true);
	    	$year  =  get_post_meta(get_the_ID(),'release_year',true);
	    	$publisher  =  get_post_meta(get_the_ID(),'publisher',true);
	    	$post_thumb = get_the_post_thumbnail( $page_id, 'thumbnail' );
	    	?>

	    	<h3 style="padding: 0px;">by <i> <?php echo $author_name?> </i></h3>

	    	<div class="thumbnail" style="float: left; padding: 0px 10px 0px 10px;">
		    	<?php
		    		if ( has_post_thumbnail() ) {
					    echo $post_thumb;
					} 
				?>
			</div>
			
			<!-- Display Book Description Contents -->
			<div class="entry-content">
			      <?php the_content(); ?>  
			</div>

			<h6 style="padding: 0px;">Published <?php echo $publisher . "	" . $year?> </i></h6>
			
			<ul><?php echo get_the_term_list( $page_id, 'book-genre', '<li class="book_genre">', ', ', '</li>' ) ?></ul>

			<hr>

		</article>
	<?php endwhile; ?>
	<?php
	wp_reset_query();
	return ob_get_clean();
}