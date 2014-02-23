<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=100" >
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?$APPLICATION->ShowTitle();?></title>
	<?$APPLICATION->ShowHead();?>
	<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
	<?/*<script type="text/javascript" src="/js/jquery-1.7.js"></script>*/?>
	<script type="text/javascript" src="/js/jquery.disable.text.select.js"></script>
	<script type="text/javascript" src="/js/jquery.corner.js"></script>
	<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="/js/jScrollPane.js"></script>
	<?/*<script type="text/javascript" src="/js/jquery.lightbox-0.5.js"></script>*/?>
	<script type="text/javascript" src="/js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<?/*<link rel="stylesheet" type="text/css" media="screen" href="/css/jquery.lightbox-0.5.css" />*/?>
	<link rel="stylesheet" type="text/css" media="all" href="/css/jScrollPane.css" />
	<link rel="shortcut icon" href="/favicon.gif">
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="/css/ie.css" />
	<![endif]-->
	<!--[if IE 6]>
	<script type="text/javascript" src="/js/minmax.js"></script>
	<link rel="stylesheet" type="text/css" href="/ie6.css" />
	<![endif]-->
	<!--[if IE 7]>
	<script type="text/javascript" src="/js/minmax.js"></script>
	<link rel="stylesheet" type="text/css" href="/ie7.css" />
	<![endif]-->
	<?require($_SERVER['DOCUMENT_ROOT'].'/inc/head_include.php');?>
</head>
<body>
<?require($_SERVER['DOCUMENT_ROOT'].'/inc/for_basket_open.php');?>
<?$APPLICATION->ShowPanel();?>
<div id="container988">
	<div id="container980">
		<div class="top">
			<? if($GLOBALS["APPLICATION"] -> GetCurPage(true) != "/index.php") { ?><a href="http://<?=$_SERVER['HTTP_HOST']?>"><? } ?>
				<img src="/img/logo.jpg" alt="" class="logo" />
			<? if($GLOBALS["APPLICATION"] -> GetCurPage(true) != "/index.php") { ?></a><? } ?>
			<?$APPLICATION->IncludeComponent("bitrix:search.form", "top-search", array(
	"PAGE" => "#SITE_DIR#search/index.php",
	"USE_SUGGEST" => "Y"
	),
	false
);?>
			<div class="block330">
				<div class="block163">
					<? $APPLICATION -> IncludeFile("/inc/timetable.html"); ?>
				</div>
				<div class="block167">
					<?require($_SERVER['DOCUMENT_ROOT'].'/inc/top_contact_area.php');?>
				</div>
			</div>
		</div>
		<div class="menu">
			<?$APPLICATION->IncludeComponent("bitrix:menu", "top-menu", array(
				"ROOT_MENU_TYPE" => "top",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => "",
				"MAX_LEVEL" => "1",
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "Y",
				"DELAY" => "Y",
				"ALLOW_MULTI_SELECT" => "N",
				),
				false
			);?>
			<? require($_SERVER['DOCUMENT_ROOT'].'/inc/auth_area.php');?>
		</div>
		<div class="block730b">
			<!--<h1><? $APPLICATION -> ShowTitle(false); ?></h1>--><br/>
		
