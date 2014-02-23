		</div>
		<?// bann block?>
		<div class="block190" style="padding-top:35px;">
				<? // DISCOUNT BY FRIENDS?>
				<?if(isset($GLOBALS['FRIENDS_DISCOUNT']) and $GLOBALS['FRIENDS_DISCOUNT']>0):?>
					<div class="dsBlock">Количество скидок по акции «Приведи друга»:<span><?=$GLOBALS['FRIENDS_DISCOUNT']?></span></div>
				<?endif;?>
				<? // ----------?>
				
				<?
					for($i=0; $i<=count($GLOBALS['FOOT_BAN'])-1; $i++)
					{
						
						if(!$GLOBALS['FOOT_BAN'][$i]['showed'])
						{
							$GLOBALS['FOOT_BAN'][$i]['showed'] = true;
							?>
							<div <?/*class="qBlock"*/?> style="margin-bottom: 13px"><noindex><a href="<?=$GLOBALS['FOOT_BAN'][$i]['PROPERTY_HREF_VALUE']?>" rel="nofollow" style="display:block"><img style='border: 1px solid #C1C1C1' src="<?=CFile::GetPath($GLOBALS['FOOT_BAN'][$i]['PROPERTY_PICT_VALUE']);?>" alt="" /></a></noindex></div>
							<?
							break;
						};
						
					}
					?>
					
				<a href="#" class="bt181" id="filter1"><img src="/img/bt192a.png" alt="" /></a>
				<a href="#" class="bt181" id="filter2" ><img src="/img/bt192b.png" alt="" /></a><br />
		
				<?if(isset($GLOBALS['LEMS']) and isset($GLOBALS['LEMS'][0])) { ?>
					<div class="extra188a">
						<?
						// echo '<pre>';
						// print_R($GLOBALS['LEMS']);
						// echo '</pre>';
						?>
<!--noindex-->
						<h4>С этим товаром покупают</h4>
						<ul>
						<?$x=0;?>
						<?foreach($GLOBALS['LEMS'] as $arElement):?>
							<?
							$el = CIBlockElement::GetByID($arElement['ID']);
							$arr_el = $el->GetNext();
							$pict = CFile::GetPath($arr_el['PREVIEW_PICTURE']);
							$arr_pr = GetCatalogProductPriceList($arr_el['ID']);
							$actual_pr = array();
							foreach($arr_pr as $one_pr)
							{
								if($one_pr['QUANTITY_FROM']==1 and $one_pr['CATALOG_GROUP_NAME']=='Розничная цена')
								{
									$actual_pr = $one_pr;
									break;
								};
							};
							// echo '<pre>';
							// print_R($actual_pr);
							// echo '</pre>';
							?>
							<form id="prop_form<?=$x?>" name="prop_form<?=$x?>" method="post">
								<input type="hidden" name="prod_id" value="<?=$arElement['ID']?>" />
								<input type="hidden" name="count" value="1" />
							</form>
							<li><h5><a href="javascript:ajax_buy_with_add(<?=$x?>);"><?=$arElement["NAME"]?></a></h5><span class="price2"><?=FormatCurrency($actual_pr['PRICE'], $actual_pr['CURRENCY']);?></span>
							<div style="text-align: center"><a style="margin: 0 auto 0" href="javascript:ajax_buy_with_add(<?=$x?>);"><script>document.write('<img src="<?=$pict?>" alt="" />');</script></a><br /><center><a style="color: #D72A18; font: bold 12px Trebuchet Ms;text-decoration:underline" href="javascript:ajax_buy_with_add(<?=$x?>);">Добавить в корзину</center></a></div></li>
							<?$x++;?>
						<?endforeach;?>
						</ul>
<!--/noindex-->
					</div>
				<? } ?>
		</div>
		<? // ------------------?>
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