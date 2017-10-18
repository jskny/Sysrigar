<!DOCTYPE html>
<html lang='ja'>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes"/>
	<meta name="theme-color" content="#DDFFDD">

	<title><?php if (!is_front_page()) {
		wp_title( ' | ', true, 'right' );
	}
	bloginfo( 'name' ); ?></title>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
	<link rel='stylesheet' href="<?php echo get_stylesheet_uri(); ?>">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">

	<?php if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_head(); ?>


	<script>
var mesg = "<?php bloginfo( 'description' ); ?>";
var i = 0;
var key = 0;
function TypeWirterDescription()
{
	if (mesg.length >= i) {
		document.getElementById("description").innerHTML = mesg.substring(0, i);
		key = setTimeout("TypeWirterDescription()", 100);
		i = i + 1;
	}
	else {
		i = 0;
		TypeWirterDescriptionEnd();
		clearTimeout(key);
	}
}
function TypeWirterDescriptionEnd()
{
	document.getElementById("description").innerHTML = mesg + ((i%2 == 0) ? "☆" : "★");
	setTimeout("TypeWirterDescriptionEnd()", 470);
	i++;
}


window.onload = function() {
	TypeWirterDescription();
}
	</script>
</head>
<body <?php body_class(); ?>>
	<div id="container">
		<!-- header -->
		<div id="header" class="clearfix">
			<h1 id="logo"><a href="<?php echo home_url('/'); ?>"><span><?php bloginfo("name"); ?></span></a></h1>
			<p><span id="description"><?php bloginfo("description"); ?></span>　</p>
			<?php wp_nav_menu( array(
				"theme_location" => "primary",
				"show_home" => true,
				"container_class" => "menu") ); ?>
		</div>
		<!-- header -->
<!-- /header.php -->