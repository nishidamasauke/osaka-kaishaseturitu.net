<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
<main class="necessary">
	<section class="sub_top_bg">
		<h1 class="subpage_ttl">新着情報</h1>
	</section>

	<section class="pd112">
		<div class="container">
			
			<article>
				<h2 class="sec_ttl"><?php wp_title(''); ?></h2>
				<?php while ( have_posts() ) : the_post(); ?>
				<div class="data">
					  <p><?php the_date('Y年m月d日'); ?></p>
				</div>

				<?php the_content(); ?>
				<?php endwhile; ?>
			</article>
			</div>
		</section>

			<ul id="pager" class="clearfix">
				<li class="stay"><a href="<?php bloginfo('url'); ?>/news/">一覧へ戻る</a></li>
			</ul><!-- END .pagination -->

		</main>
<?php get_footer(); ?>