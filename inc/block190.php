<div class="block190" style="padding-top: 22px">
	<div <?/*class="qBlock"*/?> style="margin-bottom: 13px;">
	<?
		for($i=0; $i<=count($GLOBALS['FOOT_BAN'])-1; $i++)
		{
			
			if(!$GLOBALS['FOOT_BAN'][$i]['showed'])
			{
				$GLOBALS['FOOT_BAN'][$i]['showed'] = true;
				?>
				<a href="<?=$GLOBALS['FOOT_BAN'][$i]['PROPERTY_HREF_VALUE']?>"><img style="border: 1px solid #C1C1C1" src="<?=CFile::GetPath($GLOBALS['FOOT_BAN'][$i]['PROPERTY_PICT_VALUE']);?>" alt="" /></a>
				<?
				break;
			};
			
		}
		?>
	</div>	
	<a href="#" class="bt181" id="filter1"><img src="/img/bt192a.png" alt="" /></a>
	<a href="#" class="bt181" id="filter2" ><img src="/img/bt192b.png" alt="" /></a><br />	
</div>