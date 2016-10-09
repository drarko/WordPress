<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />


<?php if(!themify_check('setting-shortcode_css')){ ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/themify/css/shortcodes.css" type="text/css" media="screen" />
<?php } ?>

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php themify_body_start(); // hook ?>
<div id="pagewrap" class="hfeed site">

	<div id="body">
	<?php themify_layout_before(); //hook ?>