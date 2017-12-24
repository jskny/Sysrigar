<?php
// メインカラムの幅を指定する変数。下記は 600px を指定（記述推奨）
if ( ! isset( $content_width ) ) $content_width = 600;

// <head>内に RSSフィードのリンクを表示するコード
add_theme_support( 'automatic-feed-links' );

// ダイナミックサイドバーを定義するコード（CHAPTER 11）
register_sidebar( array(
	'name'			=> 'サイドバーウィジット-1',
	'id'			=> 'sidebar-1',
	'description'	=> 'サイドバーのウィジットエリアです。デフォルトのサイドバーと丸ごと入れ替えたいときに使ってください。',
	'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
	'after_widget'	=> '</div>',
) );


// カスタムメニュー機能を有効にするコード（CHAPTER 12）
add_theme_support( 'menus' );
// メニューの「ホーム」という表記を Home に変更
function sysrigar_page_menu_args( $args ) {
	$args['show_home'] = 'Home';
	return $args;
}
add_filter('wp_page_menu_args', 'sysrigar_page_menu_args');

// カスタムメニューの「場所」を設定するコード
register_nav_menu( 'primary', 'ヘッダーのナビゲーション' );


// 不必要な meta 情報を排除
// generatorを非表示にする
remove_action('wp_head', 'wp_generator');
// EditURIを非表示にする
remove_action('wp_head', 'rsd_link');
// wlwmanifestを非表示にする
remove_action('wp_head', 'wlwmanifest_link');
// http://wpcj.net/1977
foreach ( array( 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head' ) as $action ) {
	remove_action( $action, 'the_generator' );
}
// http://on-ze.com/archives/5127
remove_action('wp_head','rest_output_link_wp_head');
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');


// 絵文字スクリプトを削除
function disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'disable_emoji' );


// dnsプリフェッチのコードを除去
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}


// パンくずリスト
// http://wind-mill.co.jp/iwashiblog/2014/08/pankuzu-breadcrumb/
function breadcrumb() {
	global $post;
	$str ='';
	if(!is_home()&&!is_admin()){
		$str.= '<div id="breadcrumb"><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;">';
		$str.= '<a href="'. home_url() .'" itemprop="url"><span itemprop="title">Home</span></a> &gt;&#160;</div>';
		if(is_category()) {
			$cat = get_queried_object();
			if($cat -> parent != 0){
				$ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
				foreach($ancestors as $ancestor){
$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($ancestor) .'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor) .'</span></a> &gt;&#160;</div>';
				}
			}
$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($cat -> term_id). '" itemprop="url"><span itemprop="title">'. $cat-> cat_name . '</span></a> &gt;&#160;</div>';
		} elseif(is_page()){
			if($post -> post_parent != 0 ){
				$ancestors = array_reverse(get_post_ancestors( $post->ID ));
				foreach($ancestors as $ancestor){
					$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_permalink($ancestor).'" itemprop="url"><span itemprop="title">'. get_the_title($ancestor) .'</span></a> &gt;&#160;</div>';
				}
			}
		} elseif(is_single()){
			$categories = get_the_category($post->ID);
			$cat = $categories[0];
			if($cat -> parent != 0){
				$ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
				foreach($ancestors as $ancestor){
					$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($ancestor).'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor). '</span></a>→</div>';
				}
			}
			$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($cat -> term_id). '" itemprop="url"><span itemprop="title">'. $cat-> cat_name . '</span></a> &gt;&#160;</div>';
		} else{
			$str.='<div>'. wp_title('', false) .'</div>';
		}
		$str.='</div>';
	}
	echo $str;
}


// ウクライナ等からのログインページへの攻撃が続いているので、先人様を参考にしてセキュリティ対策を施す。
// wordpress フォルダ直下に admin-page-access.php を置き、そこ経由でのみアクセスを認める。
// 2017/12/24
// https://gray-code.com/blog/wordpress/change-the-url-of-admin-page/
define('LOGIN_PAGE', 'admin-page-access.php');
add_action('login_init', 'admin_login_init');
function admin_login_init()
{
	if(!defined('SYSRIGAR_LOGIN_KEY') || password_verify(SYSRIGAR_LOGIN_KEY_TEXT, SYSRIGAR_LOGIN_KEY) === false) {
		header('Location:' . site_url() . '/404.php');
		exit;
	}
}

add_filter('site_url', 'admin_login_site_url', 10, 4);
function admin_login_site_url( $url, $path, $orig_scheme, $blog_id)
{
	if(($path == 'wp-login.php' || preg_match( '/wp-login\.php\?action=\w+/', $path) ) && (is_user_logged_in() || strpos( $_SERVER['REQUEST_URI'], LOGIN_PAGE) !== false)) {
		$url = str_replace( 'wp-login.php', LOGIN_PAGE, $url);
	}
	return $url;
}
add_filter('wp_redirect', 'admin_login_wp_redirect', 10, 2);
function admin_login_wp_redirect( $location, $status) {
	if(is_user_logged_in() && strpos( $_SERVER['REQUEST_URI'], LOGIN_PAGE) !== false) {
		$location = str_replace( 'wp-login.php', LOGIN_PAGE, $location);
	}
	return $location;
}


?>
