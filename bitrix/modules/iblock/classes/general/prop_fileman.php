<?
IncludeModuleLangFile(__FILE__);

class CIBlockPropertyFileMan
{
	function GetUserTypeDescription()
	{
		return array(
			"PROPERTY_TYPE"		=>"S",
			"USER_TYPE"		=>"FileMan",
			"GetPropertyFieldHtml"	=>array("CIBlockPropertyFileMan","GetPropertyFieldHtml"),
			"ConvertToDB"		=>array("CIBlockPropertyFileMan","ConvertToDB"),
			"ConvertFromDB"		=>array("CIBlockPropertyFileMan","ConvertFromDB"),
		);
	}


	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		global $APPLICATION;

		if (strLen(trim($strHTMLControlName["FORM_NAME"])) <= 0)
			$strHTMLControlName["FORM_NAME"] = "form_element";
		ob_start();
		$name = preg_replace("/[^a-zA-Z0-9_]/i", "x", htmlspecialchars($strHTMLControlName["VALUE"]));

		if(is_array($value["VALUE"]))
		{
			$value["VALUE"] = $value["VALUE"]["VALUE"];
			$value["DESCRIPTION"] = $value["DESCRIPTION"]["VALUE"];
		}

		if($strHTMLControlName["MODE"]=="FORM_FILL"):?>
			<input type="text" name="<?=htmlspecialchars($strHTMLControlName["VALUE"])?>" id="<?=$name?>" size="<?=$arProperty["COL_COUNT"]?>" value="<?=htmlspecialcharsEx($value["VALUE"])?>">
			<input type="button" value="<?=GetMessage("IBLOCK_PROP_FILEMAN_VIEW")?>" OnClick="BC<?=$name?>();">
			<?
			CAdminFileDialog::ShowScript
			(
				Array(
					"event" => "BC".$name,
					"arResultDest" => array(
						"ELEMENT_ID" => $name,
					),
					"arPath" => array("SITE" => SITE_ID, "PATH" =>"/"),
					"select" => 'F',// F - file only, D - folder only
					"operation" => 'O',// O - open, S - save
					"showUploadTab" => true,
					"showAddToMenuTab" => false,
					"fileFilter" => '',
					"allowAllFiles" => true,
					"SaveConfig" => true,
				)
			);
		else:?>
			<input type="text" name="<?=htmlspecialchars($strHTMLControlName["VALUE"])?>" id="<?=$name?>" size="<?=$arProperty["COL_COUNT"]?>" value="<?=htmlspecialcharsEx($value["VALUE"])?>">
		<?endif;

		if($arProperty["WITH_DESCRIPTION"]=="Y")
			echo ' <span title="'.GetMessage("IBLOCK_PROP_FILEMAN_DESCRIPTION_TITLE").'">'.GetMessage("IBLOCK_PROP_FILEMAN_DESCRIPTION_LABEL").':<input name="'.htmlspecialcharsEx($strHTMLControlName["DESCRIPTION"]).'" value="'.htmlspecialcharsEx($value["DESCRIPTION"]).'" size="18" type="text"></span>';
			echo "<br>";
		$return = ob_get_contents();
		ob_end_clean();
		return  $return;
	}

	function ConvertToDB($arProperty, $value)
	{
		$result = array();
		$return = array();
		if(is_array($value["VALUE"]))
		{
			$result["VALUE"] = $value["VALUE"]["VALUE"];
			$result["DESCRIPTION"] = $value["DESCRIPTION"]["VALUE"];
		}
		else
		{
			$result["VALUE"] = $value["VALUE"];
			$result["DESCRIPTION"] = $value["DESCRIPTION"];
		}
		$return["VALUE"] = trim($result["VALUE"]);
		$return["DESCRIPTION"] = trim($result["DESCRIPTION"]);
		return $return;
	}

	function ConvertFromDB($arProperty, $value)
	{
		$return = array();
		if (strLen(trim($value["VALUE"])) > 0)
			$return["VALUE"] = $value["VALUE"];
		if (strLen(trim($value["DESCRIPTION"])) > 0)
			$return["DESCRIPTION"] = $value["DESCRIPTION"];
		return $return;
	}
}
?>
