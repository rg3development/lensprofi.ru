<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>

<?if(!empty($arParams["FILTER"])):?>
<div class="bx-interface-filter">
<form name="filter_<?=$arParams["GRID_ID"]?>" action="" method="GET">
<?
foreach($arResult["GET_VARS"] as $var=>$value):
	if(is_array($value)):
		foreach($value as $k=>$v):
			if(is_array($v))
				continue;
?>
<input type="hidden" name="<?=htmlspecialchars($var)?>[<?=htmlspecialchars($k)?>]" value="<?=htmlspecialchars($v)?>">
<?
		endforeach;
	else:
?>
<input type="hidden" name="<?=htmlspecialchars($var)?>" value="<?=htmlspecialchars($value)?>">
<?
	endif;
endforeach;
?>
<table cellspacing="0" class="bx-interface-filter">
	<tr class="bx-filter-header" id="flt_header_<?=$arParams["GRID_ID"]?>" oncontextmenu="return bxGrid_<?=$arParams["GRID_ID"]?>.filterMenu">
		<td>
<?if(!empty($arResult["FILTER"])):?>
			<div class="bx-filter-btn bx-filter-on" title="<?echo GetMessage("interface_grid_used")?>"></div>
<?else:?>
			<div class="bx-filter-btn bx-filter-off" title="<?echo GetMessage("interface_grid_not_used")?>"></div>
<?endif?>
			<div class="bx-filter-text"><?echo GetMessage("interface_grid_search")?></div>
			<div class="bx-filter-sep"></div>
			<a href="javascript:void(0)" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.SwitchFilterRows(true)" class="bx-filter-btn bx-filter-show" title="<?echo GetMessage("interface_grid_show_all")?>"></a>
			<a href="javascript:void(0)" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.SwitchFilterRows(false)" class="bx-filter-btn bx-filter-hide" title="<?echo GetMessage("interface_grid_hide_all")?>"></a>
			<div class="bx-filter-sep"></div>
			<a href="javascript:void(0)" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.menu.ShowMenu(this, bxGrid_<?=$arParams["GRID_ID"]?>.filterMenu);" class="bx-filter-btn bx-filter-menu" title="<?echo GetMessage("interface_grid_additional")?>"></a>
			<div class="empty" style="width:50px; float:left;"></div>
<?if($arResult["OPTIONS"]["filter_shown"] <> "N"):?>
			<a href="javascript:void(0)" id="a_minmax_<?=$arParams["GRID_ID"]?>" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.SwitchFilter(this)" class="bx-filter-btn bx-filter-min" title="<?echo GetMessage("interface_grid_to_head")?>"></a>
<?else:?>
			<a href="javascript:void(0)" id="a_minmax_<?=$arParams["GRID_ID"]?>" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.SwitchFilter(this)" class="bx-filter-btn bx-filter-max" title="<?echo GetMessage("interface_grid_from_head")?>"></a>
<?endif?>
		</td>
	</tr>
	<tr class="bx-filter-content" id="flt_content_<?=$arParams["GRID_ID"]?>"<?if($arResult["OPTIONS"]["filter_shown"] == "N"):?> style="display:none"<?endif?>>
		<td>
			<table cellspacing="0" class="bx-filter-rows">
<?
foreach($arParams["FILTER"] as $field):
	$bShow = $arResult["FILTER_ROWS"][$field["id"]];
?>
				<tr id="flt_row_<?=$arParams["GRID_ID"]?>_<?=$field["id"]?>"<?if($field["valign"] <> '') echo ' valign="'.$field["valign"].'"';?><?if(!$bShow) echo ' style="display:none"'?>>
					<td><?=$field["name"]?>:</td>
					<td>
<?
	//default attributes
	if(!is_array($field["params"]))
		$field["params"] = array();
	if($field["type"] == '' || $field["type"] == 'text')
	{
		if($field["params"]["size"] == '')
			$field["params"]["size"] = "30";
	}
	elseif($field["type"] == 'date')
	{
		if($field["params"]["size"] == '')
			$field["params"]["size"] = "10";
	}
	elseif($field["type"] == 'number')
	{
		if($field["params"]["size"] == '')
			$field["params"]["size"] = "8";
	}
	
	$params = '';
	foreach($field["params"] as $p=>$v)
		$params .= ' '.$p.'="'.$v.'"';

	$value = $arResult["FILTER"][$field["id"]];

	switch($field["type"]):
		case 'custom':
			echo $field["value"];
			break;
		case 'checkbox':
?>
<input type="hidden" name="<?=$field["id"]?>" value="N">
<input type="checkbox" name="<?=$field["id"]?>" value="Y"<?=($value == "Y"? ' checked':'')?><?=$params?>>
<?
			break;
		case 'list':
			$bMulti = isset($field["params"]["multiple"]);
?>
<select name="<?=$field["id"].($bMulti? '[]':'')?>"<?=$params?>>
<?
			if(is_array($field["items"])):
				if(!is_array($value))
					$value = array($value);
				$bSel = false;
				if($bMulti):
?>
	<option value=""<?=($value[0] == ''? ' selected':'')?>><?echo GetMessage("interface_grid_no_no_no")?></option>
<?
				endif;
				foreach($field["items"] as $k=>$v):
?>
	<option value="<?=htmlspecialchars($k)?>"<?if(in_array($k, $value) && (!$bSel || $bMulti)) {$bSel = true; echo ' selected';}?>><?=htmlspecialchars($v)?></option>
<?
				endforeach;
?>
</select>
<?
			endif;
			break;
		case 'date':
?>
				<select name="<?=$field["id"]."_datesel"?>" onchange="bxGrid_<?=$arParams["GRID_ID"]?>.OnDateChange(this)">
<?
			foreach($arResult["DATE_FILTER"] as $k=>$v):
?>
					<option value="<?=$k?>"<?if($arResult["FILTER"][$field["id"]."_datesel"] == $k) echo ' selected="selected"'?>><?=$v?></option>
<?
			endforeach;
?>
				</select>
				<span class="bx-filter-days" style="display:none"><input type="text" name="<?=$field["id"]."_days"?>" value="<?=htmlspecialchars($arResult["FILTER"][$field["id"]."_days"])?>"  class="filter-date-days" size="5" /> <?echo GetMessage("interface_filter_days")?></span>
				<span class="bx-filter-from" style="display:none"><input type="text" name="<?=$field["id"]."_from"?>" value="<?=htmlspecialchars($arResult["FILTER"][$field["id"]."_from"])?>" class="filter-date-interval"<?=$params?> /><?
$APPLICATION->IncludeComponent(
	"bitrix:main.calendar",
	"",
	array(
		"SHOW_INPUT"=>"N",
		"INPUT_NAME"=>$field["id"]."_from",
		"INPUT_VALUE"=>$arResult["FILTER"][$field["id"]."_from"],
		"FORM_NAME"=>"filter_".$arParams["GRID_ID"],
	),
	$component,
	array("HIDE_ICONS"=>true)
);?></span><span class="bx-filter-hellip" style="display:none">&hellip;</span><span class="bx-filter-to" style="display:none"><input type="text" name="<?=$field["id"]."_to"?>" value="<?=htmlspecialchars($arResult["FILTER"][$field["id"]."_to"])?>" class="filter-date-interval"<?=$params?> /><?
$APPLICATION->IncludeComponent(
	"bitrix:main.calendar",
	"",
	array(
		"SHOW_INPUT"=>"N",
		"INPUT_NAME"=>$field["id"]."_to",
		"INPUT_VALUE"=>$arResult["FILTER"][$field["id"]."_to"],
		"FORM_NAME"=>"filter_".$arParams["GRID_ID"],
	),
	$component,
	array("HIDE_ICONS"=>true)
);?></span>
<script type="text/javascript">
BX.ready(function(){bxGrid_<?=$arParams["GRID_ID"]?>.OnDateChange(document.forms['filter_<?=$arParams["GRID_ID"]?>'].<?=$field["id"]?>_datesel)});
</script>
<?
			break;
		case 'quick':
?>
<input type="text" name="<?=$field["id"]?>" value="<?=htmlspecialchars($value)?>"<?=$params?>>
<?
			if(is_array($field["items"])):
?>
<select name="<?=$field["id"]?>_list">
<?foreach($field["items"] as $key=>$item):?>
	<option value="<?=htmlspecialchars($key)?>"<?=($arResult["FILTER"][$field["id"]."_list"] == $key? ' selected':'')?>><?=htmlspecialchars($item)?></option>
<?endforeach?>
</select>
<?
			endif;
			break;
		case 'number':
?>
<input type="text" name="<?=$field["id"]?>_from" value="<?=htmlspecialchars($arResult["FILTER"][$field["id"]."_from"])?>"<?=$params?>> ... 
<input type="text" name="<?=$field["id"]?>_to" value="<?=htmlspecialchars($arResult["FILTER"][$field["id"]."_to"])?>"<?=$params?>>
<?
			break;
		default:
?>
<input type="text" name="<?=$field["id"]?>" value="<?=htmlspecialchars($value)?>"<?=$params?>>
<?
			break;
	endswitch;
?>
					</td>
					<td class="bx-filter-minus"><a href="javascript:void(0)" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.SwitchFilterRow('<?=CUtil::addslashes($field["id"])?>')" class="bx-filter-minus" title="<?echo GetMessage("interface_grid_hide")?>"></a></td>
				</tr>
<?endforeach?>
			</table>
			<div class="bx-filter-buttons">
				<input type="submit" name="filter" value="<?echo GetMessage("interface_grid_find")?>" title="<?echo GetMessage("interface_grid_find_title")?>">
				<input type="button" name="" value="<?echo GetMessage("interface_grid_flt_cancel")?>" title="<?echo GetMessage("interface_grid_flt_cancel_title")?>" onclick="bxGrid_<?=$arParams["GRID_ID"]?>.ClearFilter(this.form)">
				<input type="hidden" name="clear_filter" value="">
			</div>
		</td>
	</tr>
</table>

</form>
</div>
<?endif;?>
