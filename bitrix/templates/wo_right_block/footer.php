		</div>
		<div style="clear:both"></div>
		<? require($_SERVER['DOCUMENT_ROOT'].'/inc/footer_banners.php')?>
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

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter15622222 = new Ya.Metrika({id:15622222, enableAll: true, webvisor:true});
        } catch(e) {}
    });
    
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/15622222" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
<!--
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30548788-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->
</html>