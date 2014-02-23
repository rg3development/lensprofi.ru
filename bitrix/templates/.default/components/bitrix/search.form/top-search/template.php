<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!--noindex-->
<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
	<fieldset>
		<?if($USER->IsAdmin()):?>
		<?
			// echo '<pre>';
			// print_R($arResult);
			// echo '</pre>';
		?>
		<?endif;?>
		<input type="text" id="search_edit" class="w342" name="q" value="" />
		<?/*
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.suggest.input",
			"",
			array(
				"NAME" => "q",
				"VALUE" => $arResult["REQUEST"]["~QUERY"],
				"INPUT_SIZE" => 40,
				"DROPDOWN_SIZE" => 10,
				"FILTER_MD5" => $arResult["FILTER_MD5"],
			),
			$component, array("HIDE_ICONS" => "Y")
		);?>
		*/?>
		<input type="submit" value="Найти" class="w40" name="s" />
		<?if(substr_count($_SERVER['REQUEST_URI'], '/question/')>0 or (isset($_REQUEST['oft_cab']) and $_REQUEST['oft_cab']=='on')):?>
			<input type="checkbox" checked="checked" name="oft_cab" id="cabOnly" /><label for="cabOnly">Поиск в офтальмологическом кабинете</label>
		<?endif;?>
	</fieldset>
</form>
<!--/noindex-->
<style type="text/css">
	.autocomplete-w1 {  position:absolute; top:0px; left:0px; margin:8px 0 0 6px; /* IE6 fix: */ _background:none; _margin:0; }
	.autocomplete { border:1px solid #999; background:#FFF; cursor:default; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  	_margin:0; _overflow-x:hidden; }
	.autocomplete .selected { background:#F0F0F0; }
	.autocomplete div { padding:2px 5px; white-space:nowrap; }
	.autocomplete strong { font-weight:normal; color:#3399FF; }
</style>
<?
$phrases = array();
$query_str = 'SELECT * FROM `b_search_phrase`'; 
$res = $DB->Query($query_str);
while($one = $res->GetNext())
{
	$phrases[] = $one['PHRASE'];
};

//print_R($phrases);
?>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
			//var arr_suggest = new Array(<?=json_encode($phrases)?>);
			//alert(arr_suggest);
			$('#search_edit').autocomplete({
				maxHeight:80,
				lookup: [<?foreach($phrases as $phr){echo '"'.$phr.'",';};?>]
				});
	});
</script>

