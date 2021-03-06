<?
//<title>CSV (new)</title>
include(GetLangFileName($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/lang/", "/export_setup_templ.php"));

$NUM_CATALOG_LEVELS = IntVal(COption::GetOptionString("catalog", "num_catalog_levels", 3));

$strCSVError = "";

$STEP = intval($STEP);

//********************  ACTIONS  **************************************//
if (($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && $STEP == 1)
{
	if (isset($arOldSetupVars['IBLOCK_ID']))
		$IBLOCK_ID = $arOldSetupVars['IBLOCK_ID'];
}
if ($STEP>1)
{
	$IBLOCK_ID = IntVal($IBLOCK_ID);
/*	$arIBlockres = CIBlock::GetList(Array("sort"=>"asc"), Array("ID"=>$IBLOCK_ID));
	$arIBlockres = new CIBlockResult($arIBlockres);
	if ($IBLOCK_ID<=0 || !($arIBlock = $arIBlockres->GetNext()))
		$strCSVError .= GetMessage("CATI_NO_IBLOCK")."<br>"; */
	$rsIBlocks = CIBlock::GetByID($IBLOCK_ID);
	if ($IBLOCK_ID<=0 || !($arIBlock = $rsIBlocks->Fetch()))
		$strCSVError .= GetMessage("CATI_NO_IBLOCK")."<br>";

	if (strlen($strCSVError)>0)
	{
		$STEP = 1;
	}
}

if (($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && $STEP == 2)
{
	if ($arOldSetupVars['IBLOCK_ID'] == $IBLOCK_ID)
	{
		if (isset($arOldSetupVars['field_needed']))
			$field_needed = $arOldSetupVars['field_needed'];
		if (isset($arOldSetupVars['field_num']))
			$field_num = $arOldSetupVars['field_num'];
		if (isset($arOldSetupVars['field_code']))
			$field_code = $arOldSetupVars['field_code'];
	}
	if (isset($arOldSetupVars['fields_type']))
		$fields_type = $arOldSetupVars['fields_type'];
	if (isset($arOldSetupVars['delimiter_r']))
		$delimiter_r = $arOldSetupVars['delimiter_r'];
	if (isset($arOldSetupVars['delimiter_r_char']))
		$delimiter_r_char = $arOldSetupVars['delimiter_r_char'];
	if ($arOldSetupVars['delimiter_other_r'])
		$delimiter_other_r = $arOldSetupVars['delimiter_other_r'];
	if (isset($arOldSetupVars['SETUP_FILE_NAME']))
		$SETUP_FILE_NAME = $arOldSetupVars['SETUP_FILE_NAME'];
	if (isset($arOldSetupVars['SETUP_PROFILE_NAME']))
		$SETUP_PROFILE_NAME = $arOldSetupVars['SETUP_PROFILE_NAME'];
	if (isset($arOldSetupVars['first_names_r']))
		$first_names_r = $arOldSetupVars['first_names_r'];
	if (isset($arOldSetupVars['first_line_names']))
		$first_line_names = $arOldSetupVars['first_line_names'];
}

if ($STEP>2)
{
	if ($fields_type!="F" && $fields_type!="R")
		$strCSVError .= GetMessage("CATI_NO_FORMAT")."<br>";

	$delimiter_r_char = "";
	switch ($delimiter_r)
	{
		case "TAB":
			$delimiter_r_char = "\t";
			break;
		case "ZPT":
			$delimiter_r_char = ",";
			break;
		case "SPS":
			$delimiter_r_char = " ";
			break;
		case "OTR":
			$delimiter_r_char = substr($delimiter_other_r, 0, 1);
			break;
		case "TZP":
			$delimiter_r_char = ";";
			break;
	}

	if (strlen($delimiter_r_char)!=1)
		$strCSVError .= GetMessage("CATI_NO_DELIMITER")."<br>";

	if (strlen($SETUP_FILE_NAME)<=0)
		$strCSVError .= GetMessage("CATI_NO_SAVE_FILE")."<br>";

	if (strlen($strCSVError)<=0)
	{
		$SETUP_FILE_NAME = Rel2Abs("/", $SETUP_FILE_NAME);
		if (strtolower(substr($SETUP_FILE_NAME, strlen($SETUP_FILE_NAME)-4)) != ".csv")
			$SETUP_FILE_NAME .= ".csv";

		CheckDirPath($_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME);

		if (!($fp = fopen($_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME, "wb")))
			$strCSVError .= GetMessage("CATI_CANNOT_CREATE_FILE")."<br>";
		@fclose($fp);
	}

	$bFieldsPres = False;
	if (is_array($field_needed))
	{
		//for ($i = 0; $i < count($field_needed); $i++)
		for ($i = 0, $intCountFieldsNeeded = count($field_needed); $i < $intCountFieldsNeeded; $i++)
		{
			if ($field_needed[$i]=="Y")
			{
				$bFieldsPres = True;
				break;
			}
		}
	}
	if (!$bFieldsPres)
		$strCSVError .= GetMessage("CATI_NO_FIELDS")."<br>";

	/*
	// We can't link more than 30 tables
	$tableLinksCount = 10;
	for ($i = 0; $i < count($field_code); $i++)
	{
		if (substr($field_code[$i], 0, strlen("CR_PRICE_"))=="CR_PRICE_" && $field_needed[$i]=="Y")
		{
			$tableLinksCount++;
		}
		elseif (substr($field_code[$i], 0, strlen("IP_PROP"))=="IP_PROP" && $field_needed[$i]=="Y")
		{
			$tableLinksCount+=2;
		}
	}
	if ($tableLinksCount>30)
		$strCSVError .= GetMessage("CATI_TOO_MANY_TABLES")."<br>";
	*/

	if (($ACTION=="EXPORT_SETUP" || $ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && strlen($SETUP_PROFILE_NAME)<=0)
		$strCSVError .= GetMessage("CET_ERROR_NO_NAME")."<br>";

	if (strlen($strCSVError)>0)
	{
		$STEP = 2;
	}
}
//********************  END ACTIONS  **********************************//

echo ShowError($strCSVError);
?>

<form method="POST" action="<?echo $sDocPath?>?lang=<?echo LANGUAGE_ID; ?>" ENCTYPE="multipart/form-data" name="dataload">
<?=bitrix_sessid_post()?>
<?
if ($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY')
{
	?><input type="hidden" name="PROFILE_ID" value="<? echo intval($PROFILE_ID); ?>"><?
}
?>
<?if ($STEP < 3):?>
<table border="0" cellspacing="1" cellpadding="0" width="99%">
	<tr>
		<td align="left"><font class="tableheadtext" style="font-size: 15px;">
		<b><?echo GetMessage("CET_STEP1");?> <?echo $STEP;?> <?echo GetMessage("CET_STEP2");?> 2</b>
		</font></td>
		<td align="right"><font class="tableheadtext">
			<?if ($STEP>1):?>
			<input type="submit" class="button" name="backButton" value="&lt;&lt; <?echo GetMessage("CATI_BACK") ?>">
			<?endif?>
			<input type="submit" class="button" value="<?echo ($STEP==2)?(($ACTION=="EXPORT")?GetMessage("CATI_NEXT_STEP_F"):GetMessage("CET_SAVE")):GetMessage("CATI_NEXT_STEP")." &gt;&gt;" ?>" name="submit_btn">
		</font></td>
	</tr>
</table>
<?endif;?>

<table border="0" cellspacing="1" cellpadding="3" width="100%"
	class="edittable">
<?
//*****************************************************************//
if ($STEP==1):
//*****************************************************************//
?>
	<tr>
		<td valign="middle" colspan="2" align="center" nowrap
			class="tablehead"><font class="tableheadtext"><b><?echo GetMessage("CATI_DATA_EXPORT") ?></b></font>
		</td>
	</tr>
	<tr>
		<td align="right" nowrap class="tablebody"><font class="tablefieldtext"><?echo GetMessage("CATI_INFOBLOCK") ?></font>
		</td>
		<td align="left" nowrap class="tablebody"><font class="tablebodytext">
		<!-- 			<select name="IBLOCK_ID" class="typeselect">
				<?
/*				$iblocks = CIBlock::GetList(array("SORT"=>"ASC"));
				while ($ar = $iblocks->GetNext())
				{
					?><option value="<?echo $ar['ID'] ?>" <?if (IntVal($ar['ID'])==$IBLOCK_ID) echo "selected";?>><?echo $ar['NAME']?></option><?
				} */
				?>
			</select>  -->
			<? echo GetIBlockDropDownListEx($IBLOCK_ID, 'IBLOCK_TYPE_ID', 'IBLOCK_ID',array('CHECK_PERMISSIONS' => 'Y','MIN_PERMISSION' => 'R')); ?>
			</font></td>
	</tr>
<?
//*****************************************************************//
elseif($STEP==2):
//*****************************************************************//
?>
	<tr>
		<td valign="middle" colspan="2" align="center" nowrap class="tablehead">
			<font class="tableheadtext"> <b><?echo GetMessage("CATI_FORMAT_PROPS") ?></b></font> <input type="hidden" name="fields_type" value="R">
		</td>
	</tr>

	<tr>
		<td valign="middle" colspan="2" align="center" nowrap class="tablebody">
		<table border="0" cellspacing="1" cellpadding="0" class="tableborder" width="95%">
			<tr valign="top">
				<td>
				<table id="table_r" border="0" cellspacing="0" cellpadding="3" width="100%">
					<tr>
						<td valign="middle" colspan="2" align="center" nowrap class="tablehead"><font class="tableheadtext">
								<?echo GetMessage("CATI_DELIMITERS") ?>
							</font></td>
					</tr>
					<tr>
						<td valign="top" width="50%" align="right" class="tablebody">
							<font class="tablefieldtext"><?echo GetMessage("CATI_DELIMITER_TYPE") ?></font>
						</td>
						<td valign="top" width="50%" align="left" nowrap class="tablebody">
						<font class="tablebodytext">
							<input type="radio" name="delimiter_r" value="TZP" <?if ($delimiter_r=="TZP" || strlen($delimiter_r)<=0) echo "checked"?>><?echo GetMessage("CATI_TZP") ?><br>
						<input type="radio" name="delimiter_r" value="ZPT"
							<?if ($delimiter_r=="ZPT") echo "checked"?>><?echo GetMessage("CATI_ZPT") ?><br>
						<input type="radio" name="delimiter_r" value="TAB"
							<?if ($delimiter_r=="TAB") echo "checked"?>><?echo GetMessage("CATI_TAB") ?><br>
						<input type="radio" name="delimiter_r" value="SPS"
							<?if ($delimiter_r=="SPS") echo "checked"?>><?echo GetMessage("CATI_SPS") ?><br>
						<input type="radio" name="delimiter_r" value="OTR"
							<?if ($delimiter_r=="OTR") echo "checked"?>><?echo GetMessage("CATI_OTR") ?>
							<input type="text" class="typeinput" name="delimiter_other_r"
							size="3" value="<?echo htmlspecialchars($delimiter_other_r) ?>">
						</font> <input type="hidden" name="first_names_r" value="N"></td>
					</tr>
					<tr>
						<td valign="top" align="right" width="50%" class="tablebody">
							<font class="tablefieldtext"><?echo GetMessage("CATI_FIRST_LINE_NAMES") ?></font>
						</td>
						<td valign="top" align="left" width="50%" class="tablebody">
							<font class="text"> <input type="checkbox" name="first_line_names" value="Y"
							<?if ($first_line_names=="Y" || (!isset($first_line_names) && strlen($strCSVError)<=0)) echo "checked"?>>
						</font></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<br>
		</td>
	</tr>

	<tr>
		<td valign="middle" colspan="2" align="center" nowrap
			class="tablehead"><font class="tableheadtext"> <b><?echo GetMessage("CATI_FIELDS") ?></b>
		</font></td>
	</tr>

	<tr>
				<?
				$arAvailFields = array();

				$strVal = COption::GetOptionString("catalog", "allowed_product_fields", $defCatalogAvailProdFields.",".$defCatalogAvailPriceFields);
				$arVal = explode(",", $strVal);
				$arCatalogAvailProdFields_tmp = array_merge($arCatalogAvailProdFields, $arCatalogAvailPriceFields);
				$intCountVal = count($arVal);
				$intCountTmp = count($arCatalogAvailProdFields_tmp);
				//for ($i = 0; $i < count($arVal); $i++)
				for ($i = 0; $i < $intCountVal; $i++)
				{
					//for ($j = 0; $j < count($arCatalogAvailProdFields_tmp); $j++)
					for ($j = 0; $j < $intCountTmp; $j++)
					{
						if ($arVal[$i]==$arCatalogAvailProdFields_tmp[$j]["value"])
						{
							$arAvailFields[] = array("value"=>$arCatalogAvailProdFields_tmp[$j]["value"], "name"=>$arCatalogAvailProdFields_tmp[$j]["name"]);
							break;
						}
					}
				}

				$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
				while ($prop_fields = $properties->GetNext())
				{
					$arAvailFields[] = array("value"=>"IP_PROP".$prop_fields["ID"], "name"=>GetMessage("CATI_FI_PROPS")." \"".$prop_fields["NAME"]."\"");
				}

				for ($k = 0; $k < $NUM_CATALOG_LEVELS; $k++)
				{
					$strVal = COption::GetOptionString("catalog", "allowed_group_fields", $defCatalogAvailGroupFields);
					$arVal = explode(",", $strVal);
					$intCountVal = count($arVal);
					$intCountFields = count($arCatalogAvailGroupFields);
					//for ($i = 0; $i < count($arVal); $i++)
					for ($i = 0; $i < $intCountVal; $i++)
					{
						//for ($j = 0; $j < count($arCatalogAvailGroupFields); $j++)
						for ($j = 0; $j < $intCountFields; $j++)
						{
							if ($arVal[$i]==$arCatalogAvailGroupFields[$j]["value"])
							{
								$arAvailFields[] = array("value"=>$arCatalogAvailGroupFields[$j]["value"].$k, "name"=>GetMessage("CATI_FI_GROUP_LEV")." ".($k+1).": ".$arCatalogAvailGroupFields[$j]["name"]);
								break;
							}
						}
					}
				}

				$arAvailFields[] = array("value"=>"CV_QUANTITY_FROM", "name"=>GetMessage("EST_QUANTITY_FROM"));
				$arAvailFields[] = array("value"=>"CV_QUANTITY_TO", "name"=>GetMessage("EST_QUANTITY_TO"));

				$strVal = COption::GetOptionString("catalog", "allowed_price_fields", $defCatalogAvailValueFields);
				$arVal = explode(",", $strVal);
				$intCount = count($arVal);
				$intCountFields = count($arCatalogAvailValueFields);
				$db_prgr = CCatalogGroup::GetList(array("NAME" => "ASC"), Array());
				while ($prgr = $db_prgr->GetNext())
				{
					//for ($i = 0; $i < count($arVal); $i++)
					for ($i = 0; $i < $intCount; $i++)
					{
						//for ($j = 0; $j < count($arCatalogAvailValueFields); $j++)
						for ($j = 0; $j < $intCountFields; $j++)
						{
							if ($arVal[$i]==$arCatalogAvailValueFields[$j]["value"])
							{
								$arAvailFields[] = array("value"=>$arCatalogAvailValueFields[$j]["value"]."_".$prgr["ID"], "name"=>str_replace("#TYPE#", $prgr["NAME"], GetMessage("EST_PRICE_TYPE")).": ".$arCatalogAvailValueFields[$j]["name"]);
								break;
							}
						}
					}
				}

				/*
				$strVal = COption::GetOptionString("catalog", "allowed_currencies", $defCatalogAvailCurrencies);
				$arVal = explode(",", $strVal);
				$lcur = CCurrency::GetList(($by1="sort"), ($order1="asc"));
				$arCurList = array();
				while ($lcur_res = $lcur->Fetch())
				{
					if (in_array($lcur_res["CURRENCY"], $arVal))
					{
						$arCurList[] = $lcur_res["CURRENCY"];
					}
				}

				$db_prgr = CCatalogGroup::GetList(($by1="NAME"), ($order1="ASC"), Array());
				while ($prgr = $db_prgr->Fetch())
				{
					for ($i = 0; $i < count($arCurList); $i++)
					{
						$arAvailFields[] = array("value"=>"CR_PRICE_".$prgr["ID"]."_".$arCurList[$i], "name"=>GetMessage("CATI_FI_PRICE_TYPE")." \"".$prgr["NAME"]."\" - ".$arCurList[$i]);
					}
				}
				*/
					//for ($i = 0; $i < count($arAvailFields); $i++)
				$intCountAvailFields = count($arAvailFields);
				$intCountChecked = 0;
				$arCheckID = array();
				for ($i = 0; $i < $intCountAvailFields; $i++)
				{
					if ($field_needed[$i]=="Y" || (!isset($field_needed) && strlen($strCSVError)<=0))
					{
						$arCheckID[] = $i;
						$intCountChecked++;
					}
				}
		?><td valign="middle" colspan="2" align="left" nowrap class="tablebody">
		<table width="100%" border="0" cellspacing="2" cellpadding="3">
			<tr>
				<td valign="top" align="left" class="tablehead" style="white-space: nowrap;"><font class="tableheadtext">
						<input type="checkbox" name="field_needed_all" id="field_needed_all" value="Y" onclick="checkAll(this,<? echo $intCountAvailFields; ?>);"<? echo ($intCountChecked == $intCountAvailFields ? ' checked' : ''); ?>>&nbsp;
						<?echo GetMessage("CATI_FIELDS_NEEDED") ?>
						</font></td>
				<td valign="top" class="tablehead"><font class="tableheadtext">
						<?echo GetMessage("CATI_FIELDS_NAMES") ?>
						</font></td>
				<td valign="top" align="center" class="tablehead"><font class="tableheadtext">
						<?echo GetMessage("CATI_FIELDS_SORTING") ?>
						</font></td>
			</tr><?
				for ($i = 0; $i < $intCountAvailFields; $i++)
				{
					?>
					<tr>
				<td valign="top" align="left" class="tablebody"><font class="tablefieldtext"> <input
					type="checkbox" name="field_needed[<?echo $i ?>]" id="field_needed_<? echo $i; ?>"
					<?if (in_array($i,$arCheckID)) echo "checked"; ?>
					value="Y" onclick="checkOne(this,<? echo $intCountAvailFields; ?>);"> </font></td>
				<td valign="top" class="tablebody"><font class="tablefieldtext">
								<?if ($i<2) echo "<b>";?>
								<?echo htmlspecialchars($arAvailFields[$i]["name"]); ?>
								<?if ($i<2) echo "</b>";?>
							</font></td>
				<td valign="top" align="center" class="tablebody">
							<?if ($i<2) echo "<b>";?>
							<input type="text" class="typeinput"
					name="field_num[<?echo $i ?>]"
					value="<?echo (is_array($field_num)?$field_num[$i]:(10*($i+1))) ?>"
					size="2"> <input type="hidden" name="field_code[<?echo $i ?>]"
					value="<?echo htmlspecialchars($arAvailFields[$i]["value"]) ?>"> <!--<font class="tablebodytext"><?echo GetMessage("CATI_FIELD") ?></font>-->
							<?if ($i<2) echo "</b>";?>
						</td>
			</tr>
					<?
				}
				?>
			</table>
			<input type="hidden" name="count_checked" id="count_checked" value="<? echo $intCountChecked; ?>">
			<script type="text/javascript">
			function checkAll(obj,cnt)
			{
				var boolCheck = obj.checked;
				for (i = 0; i < cnt; i++)
				{
					BX('field_needed_'+i).checked = boolCheck;
				}
				BX('count_checked').value = (boolCheck ? cnt : 0);
			}
			function checkOne(obj,cnt)
			{
				var boolCheck = obj.checked;
				var intCurrent = parseInt(BX('count_checked').value);
				intCurrent += (boolCheck ? 1 : -1);
				BX('field_needed_all').checked = (intCurrent < cnt ? false : true);
				BX('count_checked').value = intCurrent;
			}
			</script>
		<br>
		<br>
		</td>
	</tr>

	<tr>
		<td valign="middle" colspan="2" align="center" nowrap
			class="tablehead"><font class="tableheadtext"> <b><?echo GetMessage("CATI_DATA_FILE_NAME") ?></b>
		</font></td>
	</tr>
	<tr>
		<td valign="middle" align="right" nowrap class="tablebody"><font
			class="tablefieldtext">
				<?echo GetMessage("CATI_DATA_FILE_NAME1") ?>
			</font></td>
		<td valign="top" align="left" nowrap class="tablebody"><font
			class="tablebodytext"> <input type="text" class="typeinput"
			name="SETUP_FILE_NAME" size="40"
			value="<?echo htmlspecialchars(strlen($SETUP_FILE_NAME)>0 ? $SETUP_FILE_NAME: (COption::GetOptionString("catalog", "export_default_path", "/upload/"))."export_file_".mt_rand(0, 999999).".csv");?>"><br>
		<small><?echo GetMessage("CATI_DATA_FILE_NAME1_DESC") ?></small> </font>
		</td>
	</tr>

	<?if ($ACTION=="EXPORT_SETUP" || $ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY'):?>
		<tr>
		<td valign="middle" colspan="2" align="center" nowrap
			class="tablehead"><font class="tableheadtext"> <b><?echo GetMessage("CATI_SAVE_SCHEME") ?></b>
		</font></td>
	</tr>
	<tr>
		<td valign="middle" align="right" nowrap class="tablebody"><font
			class="tablefieldtext">
					<?echo GetMessage("CATI_SSCHEME_NAME") ?>
				</font></td>
		<td valign="top" align="left" nowrap class="tablebody"><input
			type="text" class="typeinput" name="SETUP_PROFILE_NAME" size="40"
			value="<?echo htmlspecialchars($SETUP_PROFILE_NAME)?>"></td>
	</tr>
	<?endif;?>

<?
//*****************************************************************//
elseif($STEP==3):
//*****************************************************************//
	$FINITE = True;
//*****************************************************************//
endif;
//*****************************************************************//
?>
</table>

<?if ($STEP < 3):?>
	<table border="0" cellspacing="1" cellpadding="0" width="99%">
	<tr>
		<td align="right" nowrap colspan="2">
			<input type="hidden" name="STEP" value="<?echo intval($STEP)+1;?>">
			<input type="hidden" name="lang" value="<?echo LANGUAGE_ID ?>">
			<input type="hidden" name="ACT_FILE" value="<?echo htmlspecialchars($_REQUEST["ACT_FILE"]) ?>">
			<input type="hidden" name="ACTION" value="<?echo htmlspecialchars($ACTION) ?>">
		<?if ($STEP>1):?>
			<input type="hidden" name="IBLOCK_ID" value="<?echo intval($IBLOCK_ID) ?>">
			<input type="hidden" name="SETUP_FIELDS_LIST" value="IBLOCK_ID,SETUP_FILE_NAME,fields_type,delimiter_r,delimiter_other_r,first_names_r,first_line_names,field_needed,field_num,field_code">
		<?endif;?>
				<font class="tableheadtext">
				<?if ($STEP>1):?>
				<input type="submit" class="button" name="backButton" value="&lt;&lt; <?echo GetMessage("CATI_BACK") ?>">
				<?endif?>
				<input type="submit" class="button" value="<?echo ($STEP==2)?(($ACTION=="EXPORT")?GetMessage("CATI_NEXT_STEP_F"):GetMessage("CET_SAVE")):GetMessage("CATI_NEXT_STEP")." &gt;&gt;" ?>" name="submit_btn">
			</font></td>
	</tr>
</table>
<?endif;?>
</form>