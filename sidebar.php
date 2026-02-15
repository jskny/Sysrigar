<!-- sidebar.php -->
<aside id="sidebar">
	<?php if (is_active_sidebar('sidebar-1')): ?>
		<?php dynamic_sidebar('sidebar-1'); ?>
	<?php
else: ?>
		
		<section class="widget widget_categories">
			<h2 class="widget-title">カテゴリー</h2>
			<ul>
				<?php wp_list_categories('title_li='); ?>
			</ul>
		</section>

		<section class="widget widget_recent_entries">
			<h2 class="widget-title">最近の投稿</h2>
			<ul>
				<?php $args = array(
		'type' => 'postbypost',
		'limit' => 5,
	);
	wp_get_archives($args); ?>
			</ul>
		</section>

		<section class="widget widget_archive">
			<h2 class="widget-title">アーカイブ</h2>
			<ul>
			<?php wp_get_archives(); ?>
			</ul>
		</section>

		<section class="widget widget_meta">
			<h2 class="widget-title">メタ情報</h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</section>

	<?php
endif; ?>
</aside>
<!-- /sidebar -->
<!-- /sidebar.php -->