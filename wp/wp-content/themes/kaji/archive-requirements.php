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
		<!-- #main -->
		<div id="main">
			
			<section class="headline">
				<h1>募集要項一覧</h1>
				<div class="inner clearfix">

					<p class="search-type-tit">職種で選ぶ</p>
					<ul class="clearfix">
						<?php wp_list_categories('orderby=id&show_count=0&use_desc_for_title=0&taxonomy=base&hierarchical=0&title_li='); ?>
						<!--
						<li><a href=""><span>新卒採用</span></a></li>
						<li><a href=""><span>中途採用</span></a></li>
						<li><a href=""><span>パート・アルバイト</span></a></li>
						-->
					</ul>
					<p class="search-type-tit">雇用形態で選ぶ</p>
					<ul class="clearfix">
						<?php wp_list_categories('orderby=id&show_count=0&use_desc_for_title=0&taxonomy=employment&hierarchical=0&title_li='); ?>
						<!--
						<li><a href=""><span>○○○</span></a></li>
						<li><a href=""><span>○○○</span></a></li>
						<li><a href=""><span>○○○</span></a></li>
						-->
					</ul>
					<p class="search-type-tit">場所で選ぶ</p>
					<ul class="clearfix">
						<?php wp_list_categories('orderby=id&show_count=0&use_desc_for_title=0&taxonomy=place&hierarchical=0&title_li='); ?>
						<!--
						<li><a href=""><span>○○○</span></a></li>
						<li><a href=""><span>○○○</span></a></li>
						<li><a href=""><span>○○○</span></a></li>
						-->
					</ul>

				</div>
			</section>


			<section>
				<h2>募集要項</h2>
			</section>


	
				 <?php
					  $args = array(
					  'paged' => $paged,
					  'posts_per_page' => 10,/* 1ページに表示する件数を指定 */
					  'post_type' => 'requirements'
					  ); ?>
				 <?php query_posts( $args ); ?>
										 
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); 
					//画像アップロードディレクトリ
					$upload_dir = wp_upload_dir();
					$upload_dir_url = $upload_dir['url'];
					//仕事内容
					$works_txt = post_custom('works_txt');
					$works_img = post_custom('works_img');
					$works_img_url = $upload_dir_url.'/'.$works_img;
					$works_img_url_thumb = wp_get_attachment_image_src(get_attachment_id($works_img_url),'type-point' );
					//募集要項
					$list_address = post_custom('list_address'); //住所（勤務地）
					$list_employment = post_custom('list_employment'); //雇用形態
					$list_salary = post_custom('list_salary'); //給与（手当含む）
					$list_time = post_custom('list_time'); // 勤務時間（勤務体系）
					$list_holiday = post_custom('list_holiday'); // 休日・休暇
					/* ループ開始 */ ?>

				<section class="index">	
					<div class="ttl-block">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title('') ?></a>
							<span>
							<?php
								$terms = get_the_terms( $post->ID, 'place' ); //場所から選ぶ
								echo $terms[0]->name;
								//子カテゴリの場合
								//$terms = get_the_terms( $post->ID, 'base'); 
//								if ( $terms && ! is_wp_error( $terms ) ) {
//									foreach ( $terms as $term ) {
//										$term_parent = $term->parent;
//										if($term_parent==5){
//											echo $term->name;
//										}
//									}
//								}
							?></span>
						</h2>
					</div>
					<div class="section-inner">
						<?php if($works_img_url_thumb[0]){  ?>
						<p class="img"><img src="<?php echo $works_img_url_thumb[0]; ?>" alt="<?php the_title(); ?>" /></p>
						<?php }else{  ?>
						<p class="img"><a href="<?php the_permalink(); ?>"><img src="<?php bloginfo('url'); ?>/imgs/requirements-single/no_img.png" /></a></p>
						<?php } ?>
						<div class="detail">
							<dl>
								<dt>給与</dt>
								<dd><?php echo wpautop($list_salary); ?></dd>
							</dl>
							<dl>
								<dt>勤務時間</dt>
								<dd><?php echo wpautop($list_time); ?></dd>
							</dl>
							<!--
							<dl>
								<dt>休日・休暇</dt>
								<dd><?php echo wpautop($list_holiday); ?></dd>
							</dl>
							-->
							<dl>
								<dt>勤務地</dt>
								<dd><?php echo wpautop($list_address); ?></dd>
							</dl>
							<dl>
								<dt>仕事内容</dt>
								<dd><?php echo wpautop($works_txt); ?></dd>
							</dl>
						</div>
					</div>
					<div class="btn-area">
							<a href="<?php the_permalink(); ?>">詳細</a>
					</div>
				</section>
					<?php endwhile; ?>
				<?php else : ?>
				<?php endif; ?>
				
				<?php if (function_exists('responsive_pagination')) {
					responsive_pagination($additional_loop->max_num_pages);
				} ?>
				
				<?php wp_reset_query(); ?>
	
			
			<?php get_template_part( 'content', 'main-contact' ); ?>
			
		</div>
		<!-- /#main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>