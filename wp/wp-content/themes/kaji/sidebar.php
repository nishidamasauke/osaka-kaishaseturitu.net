<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<?php if(is_singular('seminar')||is_post_type_archive('seminar')||is_tax('seminarbase')): ?>
<div id="sub">
	<div class="seminar-list">
		<h2 class="h2_temp">新着セミナー情報</h2>
		<ul>
			<?php
				$args = array(
				'numberposts'     => 5,
				'orderby'         => 'post_date',
				'post_type'       => 'seminar',
				//'paged' => $paged
			); ?>
			<?php
			$postslist = get_posts( $args );
			foreach ($postslist as $post) : setup_postdata($post);
			$id = $post->ID;
			?>
			<li><a href="<?php echo get_permalink($id); ?>" ><time><?php echo get_the_date('Y年m月d日'); ?></time><?php if(mb_strlen($post->post_title)>20) { $title= mb_substr($post->post_title,0,20) ; echo $title. … ;} else {echo $post->post_title;}?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="seminar-cat">
		<h2 class="h2_temp">新着セミナー情報</h2>
		<ul>
			<?php wp_list_categories(array('title_li'=>'','taxonomy' => 'seminarbase', 'show_count'=>1)); ?>
		</ul>
	</div>
</div><!-- /#sub -->

<?php elseif(is_singular('voice')||is_post_type_archive('voice')||is_tax('voicebase')): ?>

<div id="sub">
	<div class="seminar-list">
		<h2 class="h2_temp">新着お客様の声</h2>
		<ul>
			<?php
				$args = array(
				'numberposts'     => 5,
				'orderby'         => 'post_date',
				'post_type'       => 'voice',
				//'paged' => $paged
			); ?>
			<?php
			$postslist = get_posts( $args );
			foreach ($postslist as $post) : setup_postdata($post);
			$id = $post->ID;
			?>
			<li><a href="<?php echo get_permalink($id); ?>" ><time><?php echo get_the_date('Y年m月d日'); ?></time><?php if(mb_strlen($post->post_title)>20) { $title= mb_substr($post->post_title,0,20) ; echo $title. … ;} else {echo $post->post_title;}?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="seminar-cat">
		<h2 class="h2_temp">お客様の声カテゴリ</h2>
		<ul>
			<?php wp_list_categories(array('title_li'=>'','taxonomy' => 'voicebase', 'show_count'=>1)); ?>
		</ul>
	</div>
</div><!-- /#sub -->

<?php elseif(is_singular('column')||is_post_type_archive('column')||is_tax('columnbase')): ?>

<div id="sub">
	<div class="seminar-list">
		<h2 class="h2_temp">新着お役立ちコラム</h2>
		<ul>
			<?php
				$args = array(
				'numberposts'     => 5,
				'orderby'         => 'post_date',
				'post_type'       => 'column',
				//'paged' => $paged
			); ?>
			<?php
			$postslist = get_posts( $args );
			foreach ($postslist as $post) : setup_postdata($post);
			$id = $post->ID;
			?>
			<li><a href="<?php echo get_permalink($id); ?>" ><time><?php echo get_the_date('Y年m月d日'); ?></time><?php if(mb_strlen($post->post_title)>20) { $title= mb_substr($post->post_title,0,20) ; echo $title. … ;} else {echo $post->post_title;}?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="seminar-cat">
		<h2 class="h2_temp">お役立ちコラムのカテゴリ</h2>
		<ul>
			<?php wp_list_categories(array('title_li'=>'','taxonomy' => 'columnbase', 'show_count'=>1)); ?>
		</ul>
	</div>
</div><!-- /#sub -->

<?php else: ?>

<div id="sub">
	<div class="seminar-list">
		<h2 class="h2_temp">新着情報</h2>
		<ul>
			<?php
				$args = array(
				'numberposts'     => 5,
				'orderby'         => 'post_date',
				//'paged' => $paged
			); ?>
			<?php
			$postslist = get_posts( $args );
			foreach ($postslist as $post) : setup_postdata($post);
			$id = $post->ID;
			?>
			<li><a href="<?php echo get_permalink($id); ?>" ><time><?php echo get_the_date('Y年m月d日'); ?></time><?php if(mb_strlen($post->post_title)>20) { $title= mb_substr($post->post_title,0,20) ; echo $title. … ;} else {echo $post->post_title;}?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="seminar-cat">
		<h2 class="h2_temp">新着情報のカテゴリ</h2>
		<ul>
			<?php wp_list_categories(array('title_li'=>'', 'show_count'=>1)); ?>
		</ul>
	</div>
</div><!-- /#sub -->
<?php endif; ?>


