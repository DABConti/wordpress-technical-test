<?php
/**
 *	Template Name: Single Book Post Template
 *
 *	Custom template for the display of the custom book page.
 * 
 *	This is how a site user can view a single book page including all details about the book
 *
 * @link       https://www2.gov.bc.ca/
 * @since      1.0.0
 *
 * @package    WordPress_Technical_Test
 * @subpackage WordPress_Technical_Test/admin/partials
 */

get_header(); ?>
<div id="primary">
	<div id="content" role="main" style="margin-left:20%; width:80%; text-align: left;>
		
		<?php
		// The Query
	    $args = array(  'post_type' => 'books');
	    $loop = new WP_Query( $args );

	    ?>

	    <?php while ( have_posts() ) : the_post();?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					
				</header><!-- .entry-header -->

				<?php 
		    	//Load Meta fields
		    	$page_id = get_the_ID();
		    	$author_name = get_post_meta(get_the_ID(),'author_name',true);
		    	$year  =  get_post_meta(get_the_ID(),'release_year',true);
		    	$publisher  =  get_post_meta(get_the_ID(),'publisher',true);
		    	$post_thumb = get_the_post_thumbnail( $page_id, 'post-thumbnail' );
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

			</article>
		<?php endwhile; ?>
	</div>
</div>


<?php 
get_sidebar();
wp_reset_query();
get_footer(); ?>