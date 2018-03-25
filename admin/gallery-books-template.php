<?php
/*
 * Template Name: Book Gallery Template
 * Description: A Page Template that displays all of the books on a single page for the end user.
 */
get_header(); ?>
<div id="primary">
	<div id="content" role="main" style="margin-left:20%; margin-right: 20%; width:60%; text-align: left;>
		
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
	</div>
</div>


<?php
wp_reset_query();
get_footer(); ?>