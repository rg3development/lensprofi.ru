<?
IncludeModuleLangFile(__FILE__);

class CAllSaleDiscount
{
	function PrepareCurrency4Where($val, $key, $operation, $negative, $field, &$arField, &$arFilter)
	{
		$val = DoubleVal($val);

		$baseSiteCurrency = "";
		if (isset($arFilter["LID"]) && strlen($arFilter["LID"]) > 0)
			$baseSiteCurrency = CSaleLang::GetLangCurrency($arFilter["LID"]);
		elseif (isset($arFilter["CURRENCY"]) && strlen($arFilter["CURRENCY"]) > 0)
			$baseSiteCurrency = $arFilter["CURRENCY"];

		if (strlen($baseSiteCurrency) <= 0)
			return False;

		$strSqlSearch = "";

		$dbCurrency = CCurrency::GetList(($by = "sort"), ($order = "asc"));
		while ($arCurrency = $dbCurrency->Fetch())
		{
			$val1 = roundEx(CCurrencyRates::ConvertCurrency($val, $baseSiteCurrency, $arCurrency["CURRENCY"]), SALE_VALUE_PRECISION);
			if (strlen($strSqlSearch) > 0)
				$strSqlSearch .= " OR ";

			$strSqlSearch .= "(D.CURRENCY = '".$arCurrency["CURRENCY"]."' AND ";
			if ($negative == "Y")
				$strSqlSearch .= "NOT";
			$strSqlSearch .= "(".$field." ".$operation." ".$val1." OR ".$field." IS NULL OR ".$field." = 0)";
			$strSqlSearch .= ")";
		}

		return "(".$strSqlSearch.")";
	}

	function GetByID($ID)
	{
		global $DB;

		$ID = IntVal($ID);
/*		$strSql =
			"SELECT * ".
			"FROM b_sale_discount ".
			"WHERE ID = ".$ID."";
		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		if ($res = $db_res->Fetch())
		{
			return $res;
		}
		return False; */
		if (0 < $ID)
		{
			$rsDiscounts = CSaleDiscount::GetList(array(),array('ID' => $ID),false,false,array());
			if ($arDiscount = $rsDiscounts->Fetch())
			{
				return $arDiscount;
			}
		}
		return false;
	}

	function CheckFields($ACTION, &$arFields)
	{
		global $DB;
		global $APPLICATION;

		if ((is_set($arFields, "ACTIVE") || $ACTION=="ADD") && $arFields["ACTIVE"]!="Y")
			$arFields["ACTIVE"] = "N";
		if ((is_set($arFields, "DISCOUNT_TYPE") || $ACTION=="ADD") && $arFields["DISCOUNT_TYPE"]!="P")
			$arFields["DISCOUNT_TYPE"] = "V";

		if ((is_set($arFields, "SORT") || $ACTION=="ADD") && IntVal($arFields["SORT"])<=0)
			$arFields["SORT"] = 100;

		if ((is_set($arFields, "LID") || $ACTION=="ADD") && strlen($arFields["LID"])<=0)
			return false;
		if ((is_set($arFields, "CURRENCY") || $ACTION=="ADD") && strlen($arFields["CURRENCY"])<=0)
			return false;

		if (is_set($arFields, "CURRENCY"))
		{
			if (!($arCurrency = CCurrency::GetByID($arFields["CURRENCY"])))
			{
				$APPLICATION->ThrowException(str_replace("#ID#", $arFields["CURRENCY"], GetMessage("SKGD_NO_CURRENCY")), "ERROR_NO_CURRENCY");
				return false;
			}
		}

		if (is_set($arFields, "LID"))
		{
			$dbSite = CSite::GetByID($arFields["LID"]);
			if (!$dbSite->Fetch())
			{
				$APPLICATION->ThrowException(str_replace("#ID#", $arFields["LID"], GetMessage("SKGD_NO_SITE")), "ERROR_NO_SITE");
				return false;
			}
		}

		if (is_set($arFields, "DISCOUNT_VALUE"))
		{
			$arFields["DISCOUNT_VALUE"] = str_replace(",", ".", $arFields["DISCOUNT_VALUE"]);
			$arFields["DISCOUNT_VALUE"] = DoubleVal($arFields["DISCOUNT_VALUE"]);
		}
		if ((is_set($arFields, "DISCOUNT_VALUE") || $ACTION=="ADD") && DoubleVal($arFields["DISCOUNT_VALUE"])<=0)
		{
			$APPLICATION->ThrowException(GetMessage("SKGD_EMPTY_DVAL"), "ERROR_NO_DISCOUNT_VALUE");
			return false;
		}

		if (is_set($arFields, "PRICE_FROM"))
		{
			$arFields["PRICE_FROM"] = str_replace(",", ".", $arFields["PRICE_FROM"]);
			$arFields["PRICE_FROM"] = DoubleVal($arFields["PRICE_FROM"]);
		}

		if (is_set($arFields, "PRICE_TO"))
		{
			$arFields["PRICE_TO"] = str_replace(",", ".", $arFields["PRICE_TO"]);
			$arFields["PRICE_TO"] = DoubleVal($arFields["PRICE_TO"]);
		}

		/*
		if ($ACTION=="ADD"
			&& (!is_set($arFields, "PRICE_FROM") && DoubleVal($arFields["PRICE_TO"])<=0
			|| !is_set($arFields, "PRICE_TO") && DoubleVal($arFields["PRICE_FROM"])<=0
			|| DoubleVal($arFields["PRICE_TO"])<=0 && DoubleVal($arFields["PRICE_FROM"])<=0))
		{
			$GLOBALS["APPLICATION"]->ThrowException(GetMessage("SKGD_WRONG_DBOUND"), "ERROR_BAD_BORDER");
			return false;
		}
		*/

		if ((is_set($arFields, "ACTIVE_FROM") || $strAction=="ADD") && (!$DB->IsDate($arFields["ACTIVE_FROM"], false, LANG, "FULL")))
			$arFields["ACTIVE_FROM"] = false;
		if ((is_set($arFields, "ACTIVE_TO") || $strAction=="ADD") && (!$DB->IsDate($arFields["ACTIVE_TO"], false, LANG, "FULL")))
			$arFields["ACTIVE_TO"] = false;

		return True;
	}

	function Update($ID, $arFields)
	{
		global $DB;

		$ID = IntVal($ID);
		if (!CSaleDiscount::CheckFields("UPDATE", $arFields))
			return false;

		$strUpdate = $DB->PrepareUpdate("b_sale_discount", $arFields);
		$strSql = "UPDATE b_sale_discount SET ".$strUpdate." WHERE ID = ".$ID."";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		if (array_key_exists('USER_GROUPS',$arFields) && is_array($arFields['USER_GROUPS']))
		{
			$DB->Query("DELETE FROM b_sale_discount_group WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$arValid = array();
			foreach ($arFields['USER_GROUPS'] as &$value)
			{
				$value = intval($value);
				if (0 < $value)
					$arValid[] = $value;
			}
			$arFields['USER_GROUPS'] = array_unique($arValid);
			if (!empty($arFields['USER_GROUPS']))
			{
				foreach ($arFields['USER_GROUPS'] as &$value)
				{
					$strSql =
						"INSERT INTO b_sale_discount_group(DISCOUNT_ID, GROUP_ID) ".
						"VALUES(".$ID.", ".$value.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		return $ID;
	}

	function Delete($ID)
	{
		global $DB;
		$ID = IntVal($ID);

		$DB->Query("DELETE FROM b_sale_discount_group WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);
		return $DB->Query("DELETE FROM b_sale_discount WHERE ID = ".$ID."", true);
	}

}
?>