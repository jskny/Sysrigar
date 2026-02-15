<?php
/**
 * Sysrigar 関数と定義
 */

// コンテンツ幅の設定
if (!isset($content_width)) {
	$content_width = 1200;
}

/**
 * テーマのセットアップとWordPress機能のサポート登録
 */
function sysrigar_setup()
{
	// RSSフィードリンクを<head>に出力
	add_theme_support('automatic-feed-links');

	// タイトルタグをWordPressに管理させる
	add_theme_support('title-tag');

	// アイキャッチ画像（サムネイル）の有効化
	add_theme_support('post-thumbnails');

	// HTML5マークアップのサポート
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	));

	// ブロックスタイルのサポート
	add_theme_support('wp-block-styles');

	// 全幅・幅広配置の画像のサポート
	add_theme_support('align-wide');

	// レスポンシブ埋め込みのサポート
	add_theme_support('responsive-embeds');

	// エディタスタイル（管理画面の見た目）のサポート
	add_theme_support('editor-styles');

	// ナビゲーションメニューの登録
	register_nav_menus(array(
		'primary' => 'ヘッダーナビゲーション',
	));
}
add_action('after_setup_theme', 'sysrigar_setup');

/**
 * ウィジェットエリア（サイドバー）の登録
 */
function sysrigar_widgets_init()
{
	register_sidebar(array(
		'name' => 'サイドバー',
		'id' => 'sidebar-1',
		'description' => 'ここにウィジェットを追加してください。',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
}
add_action('widgets_init', 'sysrigar_widgets_init');

/**
 * スタイルとスクリプトの読み込み
 */
function sysrigar_scripts()
{
	// スタイルシートの読み込み
	wp_enqueue_style('sysrigar-style', get_stylesheet_uri(), array(), '2.0.0');

	// Highlight.js（コードハイライト用）
	wp_enqueue_style('highlight-js', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css', array(), '9.12.0');
	wp_enqueue_script('highlight-js', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js', array(), '9.12.0', true);
	wp_add_inline_script('highlight-js', 'hljs.initHighlightingOnLoad();');
}
add_action('wp_enqueue_scripts', 'sysrigar_scripts');


/**
 * Schema.org 対応パンくずリスト
 */
function sysrigar_breadcrumb()
{
	if (is_home() || is_front_page() || is_admin()) {
		return;
	}

	echo '<nav aria-label="Breadcrumb" class="breadcrumb">';
	echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

	// ホーム
	echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
	echo '<a itemprop="item" href="' . home_url() . '"><span itemprop="name">ホーム</span></a>';
	echo '<meta itemprop="position" content="1" />';
	echo '</li>';

	$position = 2;

	if (is_category() || is_single()) {
		$cats = get_the_category();
		if (!empty($cats)) {
			// 最初のカテゴリーを取得
			$cat = $cats[0];
			// 親カテゴリーがあれば取得してループ
			if ($cat->parent != 0) {
				$ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
				foreach ($ancestors as $ancestor) {
					echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
					echo '<a itemprop="item" href="' . get_category_link($ancestor) . '"><span itemprop="name">' . get_cat_name($ancestor) . '</span></a>';
					echo '<meta itemprop="position" content="' . $position++ . '" />';
					echo '</li>';
				}
			}

			if (is_category()) {
				echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				echo '<span itemprop="name" aria-current="page">' . $cat->cat_name . '</span>';
				echo '<meta itemprop="position" content="' . $position++ . '" />';
				echo '</li>';
			}
			else {
				// 投稿ページの場合、カテゴリーへのリンクを表示
				echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				echo '<a itemprop="item" href="' . get_category_link($cat->term_id) . '"><span itemprop="name">' . $cat->cat_name . '</span></a>';
				echo '<meta itemprop="position" content="' . $position++ . '" />';
				echo '</li>';
			}
		}
	}
	elseif (is_page()) {
		global $post;
		if ($post->post_parent) {
			$ancestors = array_reverse(get_post_ancestors($post->ID));
			foreach ($ancestors as $ancestor) {
				echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				echo '<a itemprop="item" href="' . get_permalink($ancestor) . '"><span itemprop="name">' . get_the_title($ancestor) . '</span></a>';
				echo '<meta itemprop="position" content="' . $position++ . '" />';
				echo '</li>';
			}
		}
	}

	if (is_single() || is_page()) {
		echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<span itemprop="name" aria-current="page">' . get_the_title() . '</span>';
		echo '<meta itemprop="position" content="' . $position . '" />';
		echo '</li>';
	}
	elseif (is_search()) {
		echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<span itemprop="name" aria-current="page">検索結果: ' . get_search_query() . '</span>';
		echo '<meta itemprop="position" content="' . $position . '" />';
		echo '</li>';
	}

	echo '</ol>';
	echo '</nav>';
}


// --- 従来の機能とセキュリティ対策（維持） ---

// メニューの「ホーム」という表記を Home に変更するフィルター
function sysrigar_page_menu_args($args)
{
	$args['show_home'] = 'Home';
	return $args;
}
add_filter('wp_page_menu_args', 'sysrigar_page_menu_args');

// head内の不要なタグを削除（セキュリティと軽量化のため）
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');

// 絵文字スクリプトの無効化（軽量化）
function disable_emoji()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'disable_emoji');

// 管理画面へのアクセス制限
define('LOGIN_PAGE', 'admin-page-access.php');
add_action('login_init', 'admin_login_init');
function admin_login_init()
{
	// 定数が定義されているか確認
	if(!defined('SYSRIGAR_LOGIN_KEY') || password_verify(SYSRIGAR_LOGIN_KEY_TEXT, SYSRIGAR_LOGIN_KEY) === false) {
		header('Location:' . site_url() . '/404.php');
		exit;
	}
}

add_filter('site_url', 'admin_login_site_url', 10, 4);
function admin_login_site_url($url, $path, $orig_scheme, $blog_id)
{
	if (($path == 'wp-login.php' || preg_match('/wp-login\.php\?action=\w+/', $path)) && (is_user_logged_in() || strpos($_SERVER['REQUEST_URI'], LOGIN_PAGE) !== false)) {
		$url = str_replace('wp-login.php', LOGIN_PAGE, $url);
	}
	return $url;
}
add_filter('wp_redirect', 'admin_login_wp_redirect', 10, 2);
function admin_login_wp_redirect($location, $status)
{
	if (is_user_logged_in() && strpos($_SERVER['REQUEST_URI'], LOGIN_PAGE) !== false) {
		$location = str_replace('wp-login.php', LOGIN_PAGE, $location);
	}
	return $location;
}

// 「続きを読む」リンクのスクロール削除
function remove_more_link_scroll($link)
{
	$link = preg_replace('|#more-[0-9]+|', '', $link);
	return $link;
}
add_filter('the_content_more_link', 'remove_more_link_scroll');
