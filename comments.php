<!-- comments.php -->
<div id="comment-area">
	<?php if ( have_comments() ) : // コメントがあったら ?>
		<h3 id="comments">コメント</h3>
		<ul class="comments-list">
			<?php wp_list_comments( 'avatar_size=55' ); //コメント一覧を表示 ?>
		</ul>
		<div class="comment-page-link">
			<?php paginate_comments_links(); //コメントが多い場合、ページャーを表示 ?>
		</div>
	<?php endif;
	// ここからコメントフォーム
	$args = array(
		// 返信セクションのタイトルを変更
		'title_reply'	=> 'コメントを残す',
		// 送信ボタンのタイトルを変更
		'label_submit'	=> '送信',
		// "Text or HTML to be displayed after the set of comment fields" を削除
		'comment_notes_after' => '',
		// オリジナルの textarea (コメント本文入力欄) を再定義
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br /><textarea id="comment" name="comment" style="width:95%;height:80px;margin:5px;" aria-required="true"></textarea></p>',

	);
	comment_form( $args );
	?>
</div>
<!-- /comments.php -->