<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
	<div class="inner">
		<!-- #main -->
		<div id="main">
	
				 <?php
					  $args = array(
					  'paged' => $paged,
					  'posts_per_page' => 10,/* 1ページに表示する件数を指定 */
					  ); ?>
				 <?php query_posts( $args ); ?>
										 
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
					<article>
						<?php if (has_post_thumbnail()) { ?>
						<p class="img"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-seminar'); ?></a></p>

						<?php } ?>
						<div class="text">
							<p><time><?php echo get_the_date('Y年m月d日'); ?></time><span class="cat"><?php $cats = get_the_category(); echo $cats[0]->cat_name;?></span></p>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title('') ?></a></h2>
							<p><?php echo kotoriexcerpt(100); ?></p>
							<p class="btn"><a href="<?php the_permalink(); ?>">詳しくはこちら</a></p>
						</div>
					</article>
					<?php endwhile; ?>
				<?php else : ?>
				<?php endif; ?>

				<?php if (function_exists('responsive_pagination')) {
					responsive_pagination($additional_loop->max_num_pages);
				} ?>
				<?php wp_reset_query(); ?>
		</div><!-- /#main -->
		<?php get_sidebar(); ?>
	</div><!-- /.inner -->

	<?php get_template_part( 'content', 'main-contact' ); ?>
<?php get_footer(); ?>
