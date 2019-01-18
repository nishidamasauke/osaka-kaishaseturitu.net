<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div class="inner">
		<!-- #main -->
		<div id="main">
			<article>
				<h1 class="h2_temp"><?php wp_title(''); ?></h1>

				<?php while ( have_posts() ) : the_post(); ?>
				<div class="data">
					  <p><?php the_date('Y年m月d日'); ?><?php echo get_the_term_list($post->ID, 'columnbase'); ?></p>
				</div>

				<?php the_content(); ?>
				<?php endwhile; ?>
			</article>

			<ul id="pager" class="clearfix">
				<li class="prev"><?php previous_post_link( '%link', __( '&laquo;前の記事へ')); ?></li>
				<li class="next"><?php next_post_link( '%link', __( '次の記事へ&raquo;')); ?></li>
				<li class="stay"><a href="<?php bloginfo('url'); ?>/blog/">一覧へ戻る</a></li>
			</ul><!-- END .pagination -->
		</div>
		<!-- /#main -->
		<?php get_sidebar(); ?>
	</div>
	<?php get_template_part( 'content', 'main-contact' ); ?>

<?php get_footer(); ?>