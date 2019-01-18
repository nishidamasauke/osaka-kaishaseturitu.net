<?php
/**
 * The template for displaying Post Format pages
 *
 * Used to display archive-type pages for posts with a post format.
 * If you'd like to further customize these Post Format views, you may create a
 * new template file for each specific one.
 *
 * @todo http://core.trac.wordpress.org/ticket/23257: Add plural versions of Post Format strings
 * and remove plurals below.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
		<div class="inner">
		<!-- #main -->
		<div id="main">
	
				
			<?php query_posts($query_string ."& posts_per_page=10"); ?>
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<article>
						<?php if (has_post_thumbnail()) { ?>
						<p class="img"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-voice'); ?></a></p>
						<?php } ?>
						<div class="text">
						<p><time><?php echo get_the_date('Y年m月d日'); ?></time><span class="cat"><?php echo get_the_term_list($post->ID, 'voicebase'); ?></span></p>
						<!--<p class="user-name">株式会社◯◯◯　◯◯様</p>-->
						<h2><a href="<?php the_permalink(); ?>"><?php the_title('') ?></a></h2>
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