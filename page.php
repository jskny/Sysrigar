<?php get_header(); ?>
<!-- page.php -->
<main id="main">
<?php
if (have_posts()): ?>
		<?php while (have_posts()):
		the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</header>

				<div class="entry-content">
					<?php the_content(); ?>
					<?php
		$args = array(
			'before' => '<div class="page-link">',
			'after' => '</div>',
			'link_before' => '<span>',
			'link_after' => '</span>',
		);
		wp_link_pages($args);
?>
				</div>

				<footer class="entry-footer">
					<?php if (function_exists('sysrigar_breadcrumb')):
			sysrigar_breadcrumb(); ?>
				<?php endif; ?>
				</footer>
			</article>
		<?php
	endwhile; ?>
	<?php
else: ?>
			<article class="post page">
				<h2>ページが見つかりません</h2>
				<p>申し訳ありませんが、お探しのページは見つかりませんでした。</p>
				<?php if (function_exists('sysrigar_breadcrumb')):
		sysrigar_breadcrumb(); ?>
				<?php endif; ?>
			</article>
	<?php
endif; ?>
</main>
<!-- /main -->
<!-- /page.php -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>