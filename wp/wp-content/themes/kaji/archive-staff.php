<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div class="inner">
		<?php
			$args = array(
				'paged' => $paged,
				'posts_per_page' => 6,/* 1ページに表示する件数を指定 */
				'post_type' => 'staff'
			);
			query_posts($args);
		?>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post();
				$text01 = post_custom('text01');
				$text02 = post_custom('text02');
				$text03 = post_custom('text03');
				$text04 = post_custom('text04');
				$text05 = post_custom('text05');
				$img01 = wp_get_attachment_image_src(post_custom('img01'),'type-staff' );
			?>
			<article>
				<div class="staff-plofile">
					<p class="img"><img src="<?php echo $img01[0]; ?>" alt="<?php the_title('') ?>"></p>
					<ul>
						<li class="staff-name"><?php the_title('') ?></li>
						<li><span>職種</span><?php echo $text01; ?></li>
						<li><span>注力分野</span><?php echo $text02; ?></li>
						<li><span>趣味・特技</span><?php echo $text03; ?></li>
					</ul>
				</div>
				<div class="staff-qa">
					<dl>
						<dt>仕事のやりがいはどういったところで感じられますか？</dt>
						<dd><?php echo $text04; ?></dd>
						<dt>仕事をするうえで大切にしていることは何ですか？</dt>
						<dd><?php echo $text05; ?></dd>
					</dl>
				</div>
			</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p>表示するご質問がありません。</p>
		<?php endif; ?>

	</div><!-- /.inner -->


	<?php get_template_part( 'content', 'main-contact' ); ?>
<?php get_footer(); ?>