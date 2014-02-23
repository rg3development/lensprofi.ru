		<? require($_SERVER['DOCUMENT_ROOT'].'/inc/footer_banners.php');?>
		</div>
	</div>
	<div class="foot">
		<div class="counters"><? $APPLICATION -> IncludeFile("/inc/counters.html"); ?></div>
		<?require($_SERVER['DOCUMENT_ROOT'].'/inc/bottom_contact_area.php');?>
		<p>&copy; <?=date("Y")?> LensProfi</p>
		<? if($GLOBALS["APPLICATION"] -> GetCurPage(true) != "/index.php") { ?><a href="http://<?=$_SERVER['HTTP_HOST']?>"><? } ?>
			<img src="/img/logo_b.gif" alt="" class="logoBottom" />
		<? if($GLOBALS["APPLICATION"] -> GetCurPage(true) != "/index.php") { ?><a href="http://<?=$_SERVER['HTTP_HOST']?>"><? } ?>
	</div>
	<? $APPLICATION -> IncludeFile("/inc/filter.php"); ?>

</body>
<!--
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32461292-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
-->
</html>