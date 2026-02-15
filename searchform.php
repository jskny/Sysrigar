<!-- searchform.php -->
<form role="search" method="get" id="search-form" class="search-form" action="<?php echo home_url('/'); ?>" >
	<label>
		<span class="screen-reader-text">検索:</span>
		<input type="search" class="search-field" placeholder="検索 &hellip;" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<input type="submit" class="search-submit" value="検索" />
</form>
<!-- /searchform.php -->