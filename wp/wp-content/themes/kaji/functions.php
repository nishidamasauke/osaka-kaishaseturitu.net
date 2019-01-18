<?php


/*----------------------------------------------------------------------------------------------------- 
画像のURLからattachemnt_idを取得する
----------------------------------------------------------------------------------------------------*/ 
function get_attachment_id($url)
{
  global $wpdb;
  $sql = "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s";
  preg_match('/([^\/]+?)(-e\d+)?(-\d+x\d+)?(\.\w+)?$/', $url, $matches);
  $post_name = $matches[1];
  return (int)$wpdb->get_var($wpdb->prepare($sql, $post_name));
}


/*-----------------------------------------------------------------------------------------------------
URL自動更新機能
----------------------------------------------------------------------------------------------------*/

//URL自動更新機能
add_action ( 'url_update_trigger', 'url_update' );
function url_update(){
	//$now = current_time('Y-m-d H:i:s'); // ローカル時刻
	 
	$new_posts = array();
	// 更新予定が設定されている記事があれば、そのIDと日付の配列を作成
	$args = array(
	    'post_type' => 'requirements',
		'orderby' => 'date',
	    'posts_per_page' => -1
	);
	 
	$posts = get_posts($args); // ②
	$i=0;
	foreach($posts as $post) {
		$i++;
		$rag = '-'. $i.' minute';
		$post_date = date('Y-m-d H:i:s');
		//$post_date = current_time('Y-m-d H:i:s');
		$post_date = date('Y-m-d H:i:s',strtotime($post_date.$rag));
        $new_posts[$post->ID] = $post_date;
	}
	if ($new_posts) {
		wp_set_current_user(webmaster); // ③
		foreach($new_posts as $id => $date) {
			wp_update_post(array('ID' => $id, 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date($date) ) );
		}
	}
}

// イベントの時間追加
add_filter('cron_schedules', 'my_interval' );
function my_interval($schedules) {
    date_default_timezone_set( 'Asia/Tokyo' );
    $dt = new DateTime('now');
	//$dt = current_time('Y-m-d H:i:s'); // ローカル時刻
    //$dt_2 = new DateTime('14day');
    $dt_2 = new DateTime('midnight first day of next month');
    $d = $dt_2->diff($dt, true);
    $dt_array = get_object_vars($d);
    $day = $dt_array["d"] * 24 * 60 * 60;
    $hour = $dt_array["h"] * 60 * 60;
    $minutes = $dt_array["i"] * 60;
    $second = $dt_array["s"];
    $difftime = $day + $hour + $minutes + $second;
    $schedules['Nextmonth'] = array(
        'interval' => $difftime,
        'display' => 'Nextmonth'
    );
    return $schedules;
}

// cron登録処理
function my_activation() {
	if ( !wp_next_scheduled( 'url_update_trigger' ) ) {
 	 wp_schedule_event( time(), 'Nextmonth', 'url_update_trigger' );
	}
}
add_action('wp', 'my_activation');

// イベント排除
register_deactivation_hook(__FILE__, 'my_deactivation');
function my_deactivation() {
    wp_clear_scheduled_hook('url_update_trigger');
}


/*-----------------------------------------------------------------------------------------------------
プラグインなどの更新時、FTP接続を求められないようにする
----------------------------------------------------------------------------------------------------*/
function set_fs_method($args) {
	return 'direct';
}
add_filter('filesystem_method','set_fs_method');
/*-----------------------------------------------------------------------------------------------------
カスタム分類のラベルをwp_titleから削除
----------------------------------------------------------------------------------------------------*/
add_filter( 'wp_title', 'fix_wp_title', 10, 3 );
function fix_wp_title($title, $sep, $seplocation){
	global $wp_query;
	if ( is_tax() ) {
		$term = $wp_query->get_queried_object();
		$term = $term->name;
		$title =$term;
		$t_sep = '%WP_TITILE_SEP%'; // Temporary separator, for accurate flipping, if necessary
	
		$prefix = '';
		if ( !empty($title) )
			$prefix = " $sep ";
		if ( 'right' == $seplocation ) {
			$title_array = explode( $t_sep, $title );
			$title_array = array_reverse( $title_array );
			$title = implode( " $sep ", $title_array ) . $prefix;
		} else {
			$title_array = explode( $t_sep, $title );
			$title = $prefix . implode( " $sep ", $title_array );
		}
	}
	return $title;
}
/*-----------------------------------------------------------------------------------------------------
 レスポンシブなページネーションを作成する

//出力
//<?php if (function_exists('responsive_pagination')) {
//responsive_pagination($additional_loop->max_num_pages);
//} ?> 

//※<head>内に追加
//<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
----------------------------------------------------------------------------------------------------*/
function responsive_pagination($pages = '', $range = 4){
  $showitems = ($range * 2)+1;
 
  global $paged;
  if(empty($paged)) $paged = 1;
 
  //ページ情報の取得
  if($pages == '') {
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    if(!$pages){
      $pages = 1;
    }
  }
 
  if(1 != $pages) {
    echo '<ul class="paginations" role="menubar" aria-label="Pagination">';
    //先頭へ
    echo '<li class="first"><a href="'.get_pagenum_link(1).'"><span>First</span></a></li>';
    //1つ戻る
    echo '<li class="previous"><a href="'.get_pagenum_link($paged - 1).'"><span>Previous</span></a></li>';
    //番号つきページ送りボタン
    for ($i=1; $i <= $pages; $i++)     {
      if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))       {
        echo ($paged == $i)? '<li class="current"><a>'.$i.'</a></li>':'<li><a href="'.get_pagenum_link($i).'" class="inactive" >'.$i.'</a></li>';
      }
    }
    //1つ進む
	if($paged + 1 <= $pages ){
    echo '<li class="next"><a href="'.get_pagenum_link($paged + 1).'"><span>Next</span></a></li>';
	}
    //最後尾へ
    echo '<li class="last"><a href="'.get_pagenum_link($pages).'"><span>Last</span></a></li>';
    echo '</ul>';
  }
}
/*-----------------------------------------------------------------------------------------------------
 記事・ページの自動整形を無効化
----------------------------------------------------------------------------------------------------*/
add_action('init', function() {
    remove_filter('the_excerpt', 'wpautop');
    remove_filter('the_content', 'wpautop');
remove_filter( 'the_excerpt ', 'wptexturize' );
remove_filter( 'the_content', 'wptexturize' );
});

add_filter('tiny_mce_before_init', function($init) {
global $allowedposttags;
$init['valid_elements']          = '*[*]';
    $init['extended_valid_elements'] = '*[*]';
    $init['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
    $init['indent']                  = true;
    $init['wpautop']                 = false;
    $init['force_p_newlines']        = false;
    return $init;
});
/*-----------------------------------------------------------------------------------------------------
 ヘッダーから絵文字等余計な要素を削除
----------------------------------------------------------------------------------------------------*/
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');

/*-----------------------------------------------------------------------------------------------------
ブログURLのパスをショートコードに登録　→　[site_url]
----------------------------------------------------------------------------------------------------*/
function shortcode_templateurl() {
    return get_bloginfo('url');
}
add_shortcode('site_url', 'shortcode_templateurl');




/*-----------------------------------------------------------------------------------------------------
ウェジェット追加
----------------------------------------------------------------------------------------------------*/
if (function_exists('register_sidebar')) {
 
register_sidebar(array(
 'name' => 'カレンダー',
 'id' => 'calendar',
 'description' => 'ウィジェットの説明',
 'before_widget' => '',
 'after_widget' => '',
 'before_title' => '',
 'after_title' => ''
 ));
 
}
/*-----------------------------------------------------------------------------------------------------
記事抜粋
----------------------------------------------------------------------------------------------------*/
function kotoriexcerpt($length) {
global $post;
$content = mb_substr(strip_tags($post->post_excerpt),0,$length);
 
if(!$content){
$content =  $post->post_content;
$content =  strip_shortcodes($content);
$content =  strip_tags($content);
$content =  str_replace("&nbsp;","",$content); 
$content =  html_entity_decode($content,ENT_QUOTES,"UTF-8");
$all_content =  mb_strlen($content);
$content =  mb_substr($content,0,$length);
}

if($all_content > $length){
	$content = $content.'...';
}

return $content;
}
	
/*-----------------------------------------------------------------------------------------------------
バーを非表示にする
----------------------------------------------------------------------------------------------------*/
add_filter( 'show_admin_bar', '__return_false' );


/*-----------------------------------------------------------------------------------------------------
サムネイルサポート
----------------------------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_image_size('top-column', 130, 90, true); // TOPのBLOG画像
	add_image_size('blog-seminar', 250, 250, true); // セミナー一覧（250*250)
	add_image_size('blog-voice', 345, 200, true); // お客様の声一覧（345*200)
	add_image_size('blog-column', 345, 200, true); // お役立ちコラム一覧（345*200)
	add_image_size('type-staf', 200, 300, true); // お役立ちコラム一覧（345*200)
}

/*-----------------------------------------------------------------------------------------------------
更新非表示
add_filter( 'pre_site_transient_update_core', '__return_zero' );
----------------------------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------------------------
//editar-style.cssを適用
----------------------------------------------------------------------------------------------------*/
add_editor_style();


/*-----------------------------------------------------------------------------------------------------
//<span>が消えるのを停止（tinymceの機能）
----------------------------------------------------------------------------------------------------*/
add_filter('tiny_mce_before_init', 'tinymce_init');
function tinymce_init( $init ) {
     $init['verify_html'] = false;
     return $init;
}

/*-----------------------------------------------------------------------------------------------------
カテゴリー一覧の（記事数）←を、<a>タグで囲う方法
----------------------------------------------------------------------------------------------------*/
add_filter( 'wp_list_categories', 'my_list_categories', 10, 2 );
function my_list_categories( $output, $args ) {
     $output = preg_replace('/<\/a>\s*\((\d+)\)/',' ($1)</a>',$output);
     return $output;
}

/*-----------------------------------------------------------------------------------------------------
jqueryをオリジナル・バージョンにする。
----------------------------------------------------------------------------------------------------*/
function load_script(){
     if (! is_admin()){
          wp_deregister_script('jquery');
          wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.7.1.min.js', array(), '1.10.0');
     }
}
add_action('init', 'load_script');


/*-----------------------------------------------------------------------------------------------------
管理画面内ヘッダーの情報を表示させない

----------------------------------------------------------------------------------------------------*/
if ( is_user_logged_in()) {
    function mytheme_remove_item( $wp_admin_bar ) {
         $wp_admin_bar->remove_node('comments'); // 管理バーのコメント
         $wp_admin_bar->remove_node('new-content'); // 管理バーの＋新規
         $wp_admin_bar->remove_node('updates'); // アップデート
    }
    add_action( 'admin_bar_menu', 'mytheme_remove_item', 1000 );
}

//自動生成するpタグやbrタグを固定ページだけ取り除く
remove_filter('the_content','wpautop');
add_filter('the_content','custom_content');
function custom_content($content){
if(get_post_type()=='page') 
    return $content; //
else
 return wpautop($content);
}


function my_tiny_mce_before_init( $init_array ) {
    $init_array['valid_elements']          = '*[*]';
    $init_array['extended_valid_elements'] = '*[*]';
    return $init_array;
}
add_filter( 'tiny_mce_before_init' , 'my_tiny_mce_before_init' );