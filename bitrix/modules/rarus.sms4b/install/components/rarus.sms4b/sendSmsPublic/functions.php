<?//some fuctions definitions
function ShowStructureSection(&$arStructure, &$usersInStructure, $bUpper = false)
{
	if (count($arStructure) <= 0)
	{
		echo 'bx_ec_no_structure_data';
		return;
	}

	while(list($key, $department) = each($arStructure)):?>
	<?
		$bExit = false;
		if(list($key, $subdepartment) = each($arStructure))
		{
			prev($arStructure);
			$list = false;
			if ($subdepartment["DEPTH_LEVEL"] <= $department["DEPTH_LEVEL"])
			{
				$list = true;
			}
		}
	?>
		<div class="vcsd-user-section<?= $bUpper ? ' vcsd-user-section-upper' : ''?>" onclick="BxecCS_SwitchSection(document.getElementById('dep_<?=$department["ID"]?>_arrow'), '<?=$department["ID"]?>', arguments[0] || window.event);" title="<?= GetMessage("EC_OPEN_CLOSE_SECT")?>">
		<table>
		<tr>
			<td><div style="width: <?= (($department["DEPTH_LEVEL"] - 1) * 15)?>px"></div></td>
			<?if ($list):?>
				<td class="vcsd-list-cell"><div id="dep_<?=$department["ID"]?>_arrow"></div></td>
			<?else:?>
				<td class="vcsd-arrow-cell"><div id="dep_<?=$department["ID"]?>_arrow" class="vcsd-arrow-right"></div></td>
			<?endif;?>
			<td><input type="checkbox" value = "<?=$department["ID"]?>" id="dep_<?=$department["ID"]?>" onclick="BxecCS_CheckGroup(this);" title='<?= GetMessage("EC_SELECT_SECTION", array('#SEC_TITLE#' => $department["NAME"]))?>' /></td>
			<td class="vcsd-contact-section"><?=$department["NAME"]?></td>
		</tr>
		</table>
		</div>
		<div style = "display: none;" id="<?=$department["ID"]?>" class="vcsd-user-contact-block">
		<?
			if($subdepartment["DEPTH_LEVEL"] > $department["DEPTH_LEVEL"])
			{
				ShowStructureSection($arStructure, $arUsersInStructure);
			}
			if($subdepartment["DEPTH_LEVEL"] < $department["DEPTH_LEVEL"])
			{
				$bExit = true;
			}
		?>
		</div>
		<?if($bExit)return;
	endwhile;
}
?>
