<?php get_header(); ?>
<!-- single.php -->
<div id="main">
	<?php if ( have_posts() ) : // WordPress ループ
		while ( have_posts() ) : the_post(); // 繰り返し処理開始 ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-meta">
					<span class="post-date"><?php echo get_the_date(); ?></span>
					<span class="category">カテゴリー：<?php the_category( ', ' ) ?></span>
					<span class="comment-num"><?php comments_popup_link( 'コメント : 0', 'コメント : 1', 'コメント : %' ); ?></span>
				</p>
				<?php the_content();
				$args = array(
					'before'		=> '<div class="page-link">',
					'after'			=> '</div>',
					'link_before'	=> '<span>',
					'link_after'	=> '</span>',
				);
				wp_link_pages( $args ); ?>
				<div class="sns-buttons">
					<a class="twitter" href="https://twitter.com/intent/tweet?url=<?php echo the_permalink(); ?>&text=<?php echo urlencode(get_the_title()); ?>&via=jskny_tw&hashtags=sysrigar" target="_blank">Tweet</a>
					<a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo the_permalink(); ?>" target="_blank">Facebook</a>
					<a class="line" href="https://line.me/R/msg/text/?<?php echo urlencode(mb_convert_encoding(get_the_title(), 'UTF-8', 'auto')); ?>%0D%0A<?php the_permalink(); ?>" target="_blank">LINE</a>
				</div>
				<p class="footer-post-meta">
					<?php the_tags( 'Tag : ', ', ' ); ?>
					<span class="post-author">作成者 : <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
<?php breadcrumb(); ?>
				</p>
			</div>
			<div class="navigation"><!-- ページャー -->
				<?php
				if ( get_previous_post() ) : ?>
					<div class="alignleft"><?php previous_post_link( '%link', '&laquo; %title' ); ?></div>
				<?php endif;

				if ( get_next_post() ) : ?>
					<div class="alignright"><?php next_post_link( '%link', '%title &raquo;' ); ?></div>
				<?php endif; ?>
			</div>
			<?php comments_template(); // コメント欄の表示
		endwhile;
	else : ?>
		<div class="post">
			<h2>記事はありません</h2>
			<p>お探しの記事は見つかりませんでした。</p>
<?php breadcrumb(); ?>
		</div>
	<?php endif; ?>
</div><!-- /main -->
<!-- /single.php -->
<?php get_sidebar();
get_footer(); ?>