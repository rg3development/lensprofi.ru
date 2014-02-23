<?
IncludeModuleLangFile(__FILE__);

/***********************************************************************/
/***********  CCatalogDiscount  ****************************************/
/***********************************************************************/
define("CATALOG_DISCOUNT_FILE", "/bitrix/modules/catalog/discount_data.php");
define("CATALOG_DISCOUNT_CPN_FILE", "/bitrix/modules/catalog/discount_cpn_data.php");
define("CATALOG_DISCOUNT_OLD_VERSION", 1);
define("CATALOG_DISCOUNT_NEW_VERSION", 2);

class CAllCatalogDiscount
{
	function CheckFields($ACTION, &$arFields, $ID = 0)
	{
		global $APPLICATION;
		global $DB;

		if ((is_set($arFields, "SITE_ID") || $ACTION=="ADD") && empty($arFields["SITE_ID"]))
		{
			$APPLICATION->ThrowException(GetMessage("KGD_EMPTY_SITE"), "EMPTY_SITE");
			return false;
		}

		if ((is_set($arFields, "CURRENCY") || $ACTION=="ADD") && empty($arFields["CURRENCY"]))
		{
			$APPLICATION->ThrowException(GetMessage("KGD_EMPTY_CURRENCY"), "EMPTY_CURRENCY");
			return false;
		}

		if ((is_set($arFields, "NAME") || $ACTION=="ADD") && empty($arFields["NAME"]))
		{
			$APPLICATION->ThrowException(GetMessage("KGD_EMPTY_NAME"), "EMPTY_NAME");
			return false;
		}

		if ((is_set($arFields, "ACTIVE") || $ACTION=="ADD") && $arFields["ACTIVE"] != "N")
			$arFields["ACTIVE"] = "Y";
		if ((is_set($arFields, "ACTIVE_FROM") || $ACTION=="ADD") && (!$DB->IsDate($arFields["ACTIVE_FROM"], false, LANG, "FULL")))
			$arFields["ACTIVE_FROM"] = false;
		if ((is_set($arFields, "ACTIVE_TO") || $ACTION=="ADD") && (!$DB->IsDate($arFields["ACTIVE_TO"], false, LANG, "FULL")))
			$arFields["ACTIVE_TO"] = false;

		if ((is_set($arFields, "RENEWAL") || $ACTION=="ADD") && $arFields["RENEWAL"] != "Y")
			$arFields["RENEWAL"] = "N";

		if ((is_set($arFields, "MAX_USES") || $ACTION=="ADD") && intval($arFields["MAX_USES"]) <= 0)
			$arFields["MAX_USES"] = 0;
		if ((is_set($arFields, "COUNT_USES") || $ACTION=="ADD") && intval($arFields["COUNT_USES"]) <= 0)
			$arFields["COUNT_USES"] = 0;

		// if ((is_set($arFields, 'COUPON')))
			// $arFields['CATALOG_COUPONS'] = $arFields['COUPON'];

		if ((is_set($arFields, "CATALOG_COUPONS") || $ACTION=="ADD") && !is_array($arFields['CATALOG_COUPONS']) && empty($arFields["CATALOG_COUPONS"]))
			$arFields["CATALOG_COUPONS"] = false;

		if ((is_set($arFields, "SORT") || $ACTION=="ADD") && intval($arFields["SORT"]) <= 0)
			$arFields["SORT"] = 100;

		if (is_set($arFields, "MAX_DISCOUNT") || $ACTION=="ADD")
		{
			$arFields["MAX_DISCOUNT"] = str_replace(",", ".", $arFields["MAX_DISCOUNT"]);
			$arFields["MAX_DISCOUNT"] = doubleval($arFields["MAX_DISCOUNT"]);
		}

		/*if ((is_set($arFields, "VALUE_TYPE") || $ACTION=="ADD") && $arFields["VALUE_TYPE"] != "F")
			$arFields["VALUE_TYPE"] = "P"; */
		if ((is_set($arFields, "VALUE_TYPE") || $ACTION=="ADD") && !in_array($arFields["VALUE_TYPE"],array("F","P","S")))
			$arFields["VALUE_TYPE"] = "P";

		if (is_set($arFields, "VALUE") || $ACTION=="ADD")
		{
			$arFields["VALUE"] = str_replace(",", ".", $arFields["VALUE"]);
			$arFields["VALUE"] = doubleval($arFields["VALUE"]);
		}

		if (is_set($arFields, "MIN_ORDER_SUM") || $ACTION=="ADD")
		{
			$arFields["MIN_ORDER_SUM"] = str_replace(",", ".", $arFields["MIN_ORDER_SUM"]);
			$arFields["MIN_ORDER_SUM"] = doubleval($arFields["MIN_ORDER_SUM"]);
		}

		return true;
	}

	function Add($arFields)
	{
		global $DB;

		$ID = CCatalogDiscount::_Add($arFields);
		$ID = IntVal($ID);
		if ($ID <= 0)
			return False;

		if (is_set($arFields, "PRODUCT_IDS"))
		{
			if (!is_array($arFields["PRODUCT_IDS"]))
				$arFields["PRODUCT_IDS"] = array($arFields["PRODUCT_IDS"]);
			$arValid = array();
			foreach ($arFields["PRODUCT_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['PRODUCT_IDS'] = $arValid;

			if (!empty($arFields['PRODUCT_IDS']))
			{
				foreach ($arFields['PRODUCT_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2product(DISCOUNT_ID, PRODUCT_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

				}
			}
		}

		if (is_set($arFields, "SECTION_IDS"))
		{
			if (!is_array($arFields["SECTION_IDS"]))
				$arFields["SECTION_IDS"] = array($arFields["SECTION_IDS"]);
			$arValid = array();
			foreach ($arFields["SECTION_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['SECTION_IDS'] = $arValid;

			if (!empty($arFields['SECTION_IDS']))
			{
				foreach ($arFields['SECTION_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2section(DISCOUNT_ID, SECTION_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "IBLOCK_IDS"))
		{
			if (!is_array($arFields["IBLOCK_IDS"]))
				$arFields["IBLOCK_IDS"] = array($arFields["IBLOCK_IDS"]);
			$arValid = array();
			foreach ($arFields["IBLOCK_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['IBLOCK_IDS'] = $arValid;

			if (!empty($arFields['IBLOCK_IDS']))
			{
				foreach ($arFields['IBLOCK_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2iblock(DISCOUNT_ID, IBLOCK_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "GROUP_IDS"))
		{
			if (!is_array($arFields["GROUP_IDS"]))
				$arFields["GROUP_IDS"] = array($arFields["GROUP_IDS"]);
			$arValid = array();
			foreach ($arFields["GROUP_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['GROUP_IDS'] = $arValid;

			if (!empty($arFields['GROUP_IDS']))
			{
				foreach ($arFields['GROUP_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2group(DISCOUNT_ID, GROUP_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "CATALOG_GROUP_IDS"))
		{
			if (!is_array($arFields["CATALOG_GROUP_IDS"]))
				$arFields["CATALOG_GROUP_IDS"] = array($arFields["CATALOG_GROUP_IDS"]);
			$arValid = array();
			foreach ($arFields["CATALOG_GROUP_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['CATALOG_GROUP_IDS'] = $arValid;

			if (!empty($arFields['CATALOG_GROUP_IDS']))
			{
				foreach ($arFields['CATALOG_GROUP_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2cat(DISCOUNT_ID, CATALOG_GROUP_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "CATALOG_COUPONS"))
		{
			if (!is_array($arFields["CATALOG_COUPONS"]))
				$arFields["CATALOG_COUPONS"] = array("DISCOUNT_ID" => $ID, "ACTIVE" => "Y", "ONE_TIME" => "Y", "COUPON" => $arFields["CATALOG_COUPONS"], "DATE_APPLY" => false);

			$arKeys = array_keys($arFields["CATALOG_COUPONS"]);
			if (!is_array($arFields["CATALOG_COUPONS"][$arKeys[0]]))
				$arFields["CATALOG_COUPONS"] = array($arFields["CATALOG_COUPONS"]);

			foreach ($arFields["CATALOG_COUPONS"] as &$arOneCoupon)
			{
				if (!empty($arOneCoupon['COUPON']))
				{
					CCatalogDiscountCoupon::Add($arOneCoupon, false);
				}
			}
		}

		CCatalogDiscount::GenerateDataFile($ID);
		CCatalogDiscount::SaveFilterOptions();

		$events = GetModuleEvents("catalog", "OnDiscountAdd");
		while ($arEvent = $events->Fetch())
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function _Update($ID, $arFields)
	{
		global $DB;
		global $stackCacheManager;

		$ID = intval($ID);
		if ($ID <= 0)
			return false;

		if (!CCatalogDiscount::CheckFields("UPDATE", $arFields, $ID))
			return false;

		$stackCacheManager->Clear("catalog_discount");

		$strUpdate = $DB->PrepareUpdate("b_catalog_discount", $arFields);
		if (!empty($strUpdate))
		{
			$strSql = "UPDATE b_catalog_discount SET ".$strUpdate." WHERE ID = ".$ID." ";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $ID;
	}

	function Update($ID, $arFields)
	{
		global $DB;

		$ID = intval($ID);
		if ($ID <= 0)
			return false;

		if (!CCatalogDiscount::_Update($ID, $arFields))
			return false;

		CCatalogDiscount::ClearFile($ID);

		if (is_set($arFields, "PRODUCT_IDS"))
		{
			$DB->Query("DELETE FROM b_catalog_discount2product WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if (!is_array($arFields["PRODUCT_IDS"]))
				$arFields["PRODUCT_IDS"] = array($arFields["PRODUCT_IDS"]);
			$arValid = array();
			foreach ($arFields["PRODUCT_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['PRODUCT_IDS'] = $arValid;

			if (!empty($arFields['PRODUCT_IDS']))
			{
				foreach ($arFields['PRODUCT_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2product(DISCOUNT_ID, PRODUCT_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

				}
			}
		}

		if (is_set($arFields, "SECTION_IDS"))
		{
			$DB->Query("DELETE FROM b_catalog_discount2section WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if (!is_array($arFields["SECTION_IDS"]))
				$arFields["SECTION_IDS"] = array($arFields["SECTION_IDS"]);
			$arValid = array();
			foreach ($arFields["SECTION_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['SECTION_IDS'] = $arValid;

			if (!empty($arFields['SECTION_IDS']))
			{
				foreach ($arFields['SECTION_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2section(DISCOUNT_ID, SECTION_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "IBLOCK_IDS"))
		{
			$DB->Query("DELETE FROM b_catalog_discount2iblock WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if (!is_array($arFields["IBLOCK_IDS"]))
				$arFields["IBLOCK_IDS"] = array($arFields["IBLOCK_IDS"]);
			$arValid = array();
			foreach ($arFields["IBLOCK_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['IBLOCK_IDS'] = $arValid;

			if (!empty($arFields['IBLOCK_IDS']))
			{
				foreach ($arFields['IBLOCK_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2iblock(DISCOUNT_ID, IBLOCK_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "GROUP_IDS"))
		{
			$DB->Query("DELETE FROM b_catalog_discount2group WHERE DISCOUNT_ID = ".$ID." ", false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if (!is_array($arFields["GROUP_IDS"]))
				$arFields["GROUP_IDS"] = array($arFields["GROUP_IDS"]);
			$arValid = array();
			foreach ($arFields["GROUP_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['GROUP_IDS'] = $arValid;

			if (!empty($arFields['GROUP_IDS']))
			{
				foreach ($arFields['GROUP_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2group(DISCOUNT_ID, GROUP_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "CATALOG_GROUP_IDS"))
		{
			$DB->Query("DELETE FROM b_catalog_discount2cat WHERE DISCOUNT_ID = ".$ID." ");

			if (!is_array($arFields["CATALOG_GROUP_IDS"]))
				$arFields["CATALOG_GROUP_IDS"] = array($arFields["CATALOG_GROUP_IDS"]);
			$arValid = array();
			foreach ($arFields["CATALOG_GROUP_IDS"] as &$value)
			{
				$value = intval($value);
				if ($value > 0)
					$arValid[] = $value;
			}
			if (!empty($arValid))
			{
				$arValid = array_unique($arValid);
			}
			$arFields['CATALOG_GROUP_IDS'] = $arValid;

			if (!empty($arFields['CATALOG_GROUP_IDS']))
			{
				foreach ($arFields['CATALOG_GROUP_IDS'] as &$intValue)
				{
					$strSql =
						"INSERT INTO b_catalog_discount2cat(DISCOUNT_ID, CATALOG_GROUP_ID) ".
						"VALUES(".$ID.", ".$intValue.")";
					$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				}
			}
		}

		if (is_set($arFields, "CATALOG_COUPONS"))
		{
			CCatalogDiscountCoupon::DeleteByDiscountID($ID, false);

			if (!is_array($arFields["CATALOG_COUPONS"]))
				$arFields["CATALOG_COUPONS"] = array("ACTIVE" => "Y", "COUPON" => $arFields["CATALOG_COUPONS"], "DATE_APPLY" => false);

			$arKeys = array_keys($arFields["CATALOG_COUPONS"]);
			if (!is_array($arFields["CATALOG_COUPONS"][$arKeys[0]]))
				$arFields["CATALOG_COUPONS"] = array($arFields["CATALOG_COUPONS"]);

			foreach ($arFields["CATALOG_COUPONS"] as &$arOneCoupon)
			{
				if (!empty($arOneCoupon['COUPON']))
				{
					CCatalogDiscountCoupon::Add($arOneCoupon, false);
				}
			}
/*			for ($i = 0, $cnt = count($arFields["CATALOG_COUPONS"]); $i < $cnt; $i++)
			{
				if (!empty($arFields["CATALOG_COUPONS"][$i]["COUPON"]))
					CCatalogDiscountCoupon::Add($arFields["CATALOG_COUPONS"][$i], false);
			} */
		}

		CCatalogDiscount::GenerateDataFile($ID);
		CCatalogDiscount::SaveFilterOptions();

		$events = GetModuleEvents("catalog", "OnDiscountUpdate");
		while ($arEvent = $events->Fetch())
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function Delete($ID)
	{
		global $DB;
		global $stackCacheManager;

		$ID = intval($ID);

		$stackCacheManager->Clear("catalog_discount");

		CCatalogDiscount::ClearFile($ID);

		$DB->Query("DELETE FROM b_catalog_discount2product WHERE DISCOUNT_ID = ".$ID." ");
		$DB->Query("DELETE FROM b_catalog_discount2section WHERE DISCOUNT_ID = ".$ID." ");
		$DB->Query("DELETE FROM b_catalog_discount2iblock WHERE DISCOUNT_ID = ".$ID." ");
		$DB->Query("DELETE FROM b_catalog_discount2group WHERE DISCOUNT_ID = ".$ID." ");
		$DB->Query("DELETE FROM b_catalog_discount2cat WHERE DISCOUNT_ID = ".$ID." ");
		$DB->Query("DELETE FROM b_catalog_discount_coupon WHERE DISCOUNT_ID = ".$ID." ");

		$DB->Query("DELETE FROM b_catalog_discount WHERE ID = ".$ID." ");

		CCatalogDiscount::SaveFilterOptions();

		$events = GetModuleEvents("catalog", "OnDiscountDelete");
		while ($arEvent = $events->Fetch())
			ExecuteModuleEventEx($arEvent, array($ID));

		return true;
	}

	function SetCoupon($coupon)
	{
		$coupon = trim($coupon);
		if (empty($coupon))
			return false;

		if (!isset($_SESSION["CATALOG_USER_COUPONS"]) || !is_array($_SESSION["CATALOG_USER_COUPONS"]))
			$_SESSION["CATALOG_USER_COUPONS"] = array();

		$dbCoupon = CCatalogDiscountCoupon::GetList(
			array(),
			array("COUPON" => $coupon, "ACTIVE" => "Y"),
			false,
			false,
			array("ID", "ONE_TIME")
		);
		if ($arCoupon = $dbCoupon->Fetch())
		{
			if (!in_array($coupon, $_SESSION["CATALOG_USER_COUPONS"]))
				$_SESSION["CATALOG_USER_COUPONS"][] = $coupon;
			/*
			if ($arCoupon["ONE_TIME"] == "Y")
			{
				CCatalogDiscountCoupon::Update(
					$arCoupon["ID"],
					array(
						"ACTIVE" => "N",
						"DATE_APPLY" => Date($GLOBALS["DB"]->DateFormatToPHP(CSite::GetDateFormat("FULL", SITE_ID)))
					)
				);
			}
			*/
			return true;
		}

		return false;
	}

	function GetCoupons()
	{
		if (!isset($_SESSION["CATALOG_USER_COUPONS"]) || !is_array($_SESSION["CATALOG_USER_COUPONS"]))
			$_SESSION["CATALOG_USER_COUPONS"] = array();

		return $_SESSION["CATALOG_USER_COUPONS"];
	}

	function ClearCoupon()
	{
		$_SESSION["CATALOG_USER_COUPONS"] = array();
	}

	function OnCurrencyDelete($Currency)
	{
		global $DB;
		if (empty($Currency)) return false;

		$dbDiscounts = CCatalogDiscount::GetList(array(), array("CURRENCY" => $Currency), false, false, array("ID"));
		while ($arDiscounts = $dbDiscounts->Fetch())
		{
			CCatalogDiscount::Delete($arDiscounts["ID"]);
		}

		return true;
	}

	function OnGroupDelete($GroupID)
	{
		global $DB;
		$GroupID = intval($GroupID);

		return $DB->Query("DELETE FROM b_catalog_discount2group WHERE GROUP_ID = ".$GroupID." ", true);
	}

	function GenerateDataFile($ID)
	{
		$ID = intval($ID);
		if ($ID <= 0)
			return false;

		$strDataFileName = CATALOG_DISCOUNT_FILE;
		$dbCoupons = CCatalogDiscountCoupon::GetList(array(), array("DISCOUNT_ID" => $ID), false, array("nTopCount" => 1), array("ID"));
		if ($dbCoupons->Fetch())
			$strDataFileName = CATALOG_DISCOUNT_CPN_FILE;

		$arDiscountSections = array();
		$arDiscountPriceTypes = array();
		$arDiscountUserGroups = array();
		$arDiscountProducts = array();
		$arDiscountIBlocks = array();

		if (file_exists($_SERVER["DOCUMENT_ROOT"].$strDataFileName) && is_file($_SERVER["DOCUMENT_ROOT"].$strDataFileName))
			include($_SERVER["DOCUMENT_ROOT"].$strDataFileName);

		if (!empty($arDiscountSections))
		{
			foreach ($arDiscountSections as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountSections[$key][$key1]);
					if (empty($arDiscountSections[$key]))
						unset($arDiscountSections[$key]);
				}
			}
		}

		if (!empty($arDiscountPriceTypes))
		{
			foreach ($arDiscountPriceTypes as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountPriceTypes[$key][$key1]);
					if (empty($arDiscountPriceTypes[$key]))
						unset($arDiscountPriceTypes[$key]);
				}
			}
		}

		if (!empty($arDiscountUserGroups))
		{
			foreach ($arDiscountUserGroups as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountUserGroups[$key][$key1]);
					if (empty($arDiscountUserGroups[$key]))
						unset($arDiscountUserGroups[$key]);
				}
			}
		}

		if (!empty($arDiscountProducts))
		{
			foreach ($arDiscountProducts as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountProducts[$key][$key1]);
					if (empty($arDiscountProducts[$key]))
						unset($arDiscountProducts[$key]);
				}
			}
		}

		if (!empty($arDiscountIBlocks))
		{
			foreach ($arDiscountIBlocks as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountIBlocks[$key][$key1]);
					if (empty($arDiscountIBlocks[$key]))
						unset($arDiscountIBlocks[$key]);
				}
			}
		}

		$dbSectionsList = CCatalogDiscount::GetDiscountSectionsList(
			array(),
			array("DISCOUNT_ID" => $ID),
			false,
			false,
			array("ID", "SECTION_ID")
		);
		if ($arSectionsList = $dbSectionsList->Fetch())
		{
			do
			{
				$dbSection = CIBlockSection::GetByID($arSectionsList["SECTION_ID"]);
				if ($arSection = $dbSection->Fetch())
				{
					$dbSectionTree = CIBlockSection::GetList(
						array("LEFT_MARGIN" => "DESC"),
						array(
							"IBLOCK_ID" => $arSection["IBLOCK_ID"],
							"ACTIVE" => "Y",
							"GLOBAL_ACTIVE" => "Y",
							"IBLOCK_ACTIVE" => "Y",
							">=LEFT_BORDER" => $arSection["LEFT_MARGIN"],
							"<=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"]
						)
					);
					while ($arSectionTree = $dbSectionTree->Fetch())
					{
						if (!array_key_exists($arSectionTree["ID"], $arDiscountSections))
							$arDiscountSections[$arSectionTree["ID"]] = array();

						$arDiscountSections[$arSectionTree["ID"]][] = $ID;
					}
				}
			}
			while ($arSectionsList = $dbSectionsList->Fetch());
		}
		else
		{
			if (!array_key_exists(0, $arDiscountSections))
				$arDiscountSections[0] = array();

			$arDiscountSections[0][] = $ID;
		}

		$dbCatsList = CCatalogDiscount::GetDiscountCatsList(
			array(),
			array("DISCOUNT_ID" => $ID),
			false,
			false,
			array("ID", "CATALOG_GROUP_ID")
		);
		if ($arCatsList = $dbCatsList->Fetch())
		{
			do
			{
				if (!array_key_exists($arCatsList["CATALOG_GROUP_ID"], $arDiscountPriceTypes))
					$arDiscountPriceTypes[$arCatsList["CATALOG_GROUP_ID"]] = array();

				$arDiscountPriceTypes[$arCatsList["CATALOG_GROUP_ID"]][] = $ID;
			}
			while ($arCatsList = $dbCatsList->Fetch());
		}
		else
		{
			if (!array_key_exists(0, $arDiscountPriceTypes))
				$arDiscountPriceTypes[0] = array();

			$arDiscountPriceTypes[0][] = $ID;
		}

		$dbGroupsList = CCatalogDiscount::GetDiscountGroupsList(
			array(),
			array("DISCOUNT_ID" => $ID),
			false,
			false,
			array("ID", "GROUP_ID")
		);
		if ($arGroupsList = $dbGroupsList->Fetch())
		{
			do
			{
				if (!array_key_exists($arGroupsList["GROUP_ID"], $arDiscountUserGroups))
					$arDiscountUserGroups[$arGroupsList["GROUP_ID"]] = array();

				$arDiscountUserGroups[$arGroupsList["GROUP_ID"]][] = $ID;
			}
			while ($arGroupsList = $dbGroupsList->Fetch());
		}
		else
		{
			if (!array_key_exists(0, $arDiscountUserGroups))
				$arDiscountUserGroups[0] = array();

			$arDiscountUserGroups[0][] = $ID;
		}

		$dbProductsList = CCatalogDiscount::GetDiscountProductsList(
			array(),
			array("DISCOUNT_ID" => $ID),
			false,
			false,
			array("ID", "PRODUCT_ID")
		);
		if ($arProductsList = $dbProductsList->Fetch())
		{
			do
			{
				if (!array_key_exists($arProductsList["PRODUCT_ID"], $arDiscountProducts))
					$arDiscountProducts[$arProductsList["PRODUCT_ID"]] = array();

				$arDiscountProducts[$arProductsList["PRODUCT_ID"]][] = $ID;
			}
			while ($arProductsList = $dbProductsList->Fetch());
		}
		else
		{
			if (!array_key_exists(0, $arDiscountProducts))
				$arDiscountProducts[0] = array();

			$arDiscountProducts[0][] = $ID;
		}

		$dbIBlocksList = CCatalogDiscount::GetDiscountIBlocksList(
			array(),
			array("DISCOUNT_ID" => $ID),
			false,
			false,
			array("ID", "IBLOCK_ID")
		);
		if ($arIBlocksList = $dbIBlocksList->Fetch())
		{
			do
			{
				if (!array_key_exists($arIBlocksList["IBLOCK_ID"], $arDiscountIBlocks))
					$arDiscountIBlocks[$arIBlocksList["IBLOCK_ID"]] = array();

				$arDiscountIBlocks[$arIBlocksList["IBLOCK_ID"]][] = $ID;
			}
			while ($arIBlocksList = $dbIBlocksList->Fetch());
		}
		else
		{
			if (!array_key_exists(0, $arDiscountIBlocks))
				$arDiscountIBlocks[0] = array();

			$arDiscountIBlocks[0][] = $ID;
		}

		ignore_user_abort(true);
		if ($fp = @fopen($_SERVER["DOCUMENT_ROOT"].$strDataFileName, "wb"))
		{
			if (flock($fp, LOCK_EX))
			{
				fwrite($fp, "<"."?\n");
				fwrite($fp, "\$arDiscountSections=unserialize('".serialize($arDiscountSections)."');");
				fwrite($fp, "if(!is_array(\$arDiscountSections))\$arDiscountSections=array();\n");
				fwrite($fp, "\$arDiscountPriceTypes=unserialize('".serialize($arDiscountPriceTypes)."');");
				fwrite($fp, "if(!is_array(\$arDiscountPriceTypes))\$arDiscountPriceTypes=array();\n");
				fwrite($fp, "\$arDiscountUserGroups=unserialize('".serialize($arDiscountUserGroups)."');");
				fwrite($fp, "if(!is_array(\$arDiscountUserGroups))\$arDiscountUserGroups=array();\n");
				fwrite($fp, "\$arDiscountProducts=unserialize('".serialize($arDiscountProducts)."');");
				fwrite($fp, "if(!is_array(\$arDiscountProducts))\$arDiscountProducts=array();\n");
				fwrite($fp, "\$arDiscountIBlocks=unserialize('".serialize($arDiscountIBlocks)."');");
				fwrite($fp, "if(!is_array(\$arDiscountIBlocks))\$arDiscountIBlocks=array();\n");
				fwrite($fp, "?".">");

				fflush($fp);
				flock($fp, LOCK_UN);
				fclose($fp);
			}
		}
		ignore_user_abort(false);
	}

	function ClearFile($ID, $strDataFileName = false)
	{
		$ID = intval($ID);
		if ($ID <= 0)
			return false;

		if (!$strDataFileName || ($strDataFileName != CATALOG_DISCOUNT_FILE && $strDataFileName != CATALOG_DISCOUNT_CPN_FILE))
		{
			$strDataFileName = CATALOG_DISCOUNT_FILE;
			$dbCoupons = CCatalogDiscountCoupon::GetList(array(), array("DISCOUNT_ID" => $ID), false, array("nTopCount" => 1), array("ID"));
			if ($dbCoupons->Fetch())
				$strDataFileName = CATALOG_DISCOUNT_CPN_FILE;
		}

		$arDiscountSections = array();
		$arDiscountPriceTypes = array();
		$arDiscountUserGroups = array();
		$arDiscountProducts = array();
		$arDiscountIBlocks = array();

		if (file_exists($_SERVER["DOCUMENT_ROOT"].$strDataFileName) && is_file($_SERVER["DOCUMENT_ROOT"].$strDataFileName))
			include($_SERVER["DOCUMENT_ROOT"].$strDataFileName);

		if (!empty($arDiscountSections))
		{
			foreach ($arDiscountSections as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountSections[$key][$key1]);
					if (empty($arDiscountSections[$key]))
						unset($arDiscountSections[$key]);
				}
			}
		}

		if (!empty($arDiscountPriceTypes))
		{
			foreach ($arDiscountPriceTypes as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountPriceTypes[$key][$key1]);
					if (empty($arDiscountPriceTypes[$key]))
						unset($arDiscountPriceTypes[$key]);
				}
			}
		}

		if (!empty($arDiscountUserGroups))
		{
			foreach ($arDiscountUserGroups as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountUserGroups[$key][$key1]);
					if (empty($arDiscountUserGroups[$key]))
						unset($arDiscountUserGroups[$key]);
				}
			}
		}

		if (!empty($arDiscountProducts))
		{
			foreach ($arDiscountProducts as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountProducts[$key][$key1]);
					if (empty($arDiscountProducts[$key]))
						unset($arDiscountProducts[$key]);
				}
			}
		}

		if (!empty($arDiscountIBlocks))
		{
			foreach ($arDiscountIBlocks as $key => $value)
			{
				$key1 = array_search($ID, $value);
				if ($key1 !== false)
				{
					unset($arDiscountIBlocks[$key][$key1]);
					if (empty($arDiscountIBlocks[$key]))
						unset($arDiscountIBlocks[$key]);
				}
			}
		}

		ignore_user_abort(true);
		if ($fp = @fopen($_SERVER["DOCUMENT_ROOT"].$strDataFileName, "wb"))
		{
			if (flock($fp, LOCK_EX))
			{
				fwrite($fp, "<"."?\n");
				fwrite($fp, "\$arDiscountSections=unserialize('".serialize($arDiscountSections)."');");
				fwrite($fp, "if(!is_array(\$arDiscountSections))\$arDiscountSections=array();\n");
				fwrite($fp, "\$arDiscountPriceTypes=unserialize('".serialize($arDiscountPriceTypes)."');");
				fwrite($fp, "if(!is_array(\$arDiscountPriceTypes))\$arDiscountPriceTypes=array();\n");
				fwrite($fp, "\$arDiscountUserGroups=unserialize('".serialize($arDiscountUserGroups)."');");
				fwrite($fp, "if(!is_array(\$arDiscountUserGroups))\$arDiscountUserGroups=array();\n");
				fwrite($fp, "\$arDiscountProducts=unserialize('".serialize($arDiscountProducts)."');");
				fwrite($fp, "if(!is_array(\$arDiscountProducts))\$arDiscountProducts=array();\n");
				fwrite($fp, "\$arDiscountIBlocks=unserialize('".serialize($arDiscountIBlocks)."');");
				fwrite($fp, "if(!is_array(\$arDiscountIBlocks))\$arDiscountIBlocks=array();\n");
				fwrite($fp, "?".">");

				fflush($fp);
				flock($fp, LOCK_UN);
				fclose($fp);
			}
		}
		ignore_user_abort(false);
	}

	function GetDiscountByPrice($productPriceID, $arUserGroups = array(), $renewal = "N", $siteID = false, $arDiscountCoupons = false)
	{
		global $DB;
		global $APPLICATION;

		$events = GetModuleEvents("catalog", "OnGetDiscountByPrice");
		if ($arEvent = $events->Fetch())
			return ExecuteModuleEventEx($arEvent, array($productPriceID, $arUserGroups, $renewal, $siteID, $arDiscountCoupons));

		$productPriceID = intval($productPriceID);
		if ($productPriceID <= 0)
		{
			$APPLICATION->ThrowException(GetMessage("BT_MOD_CATALOG_DISC_ERR_PRICE_ID_ABSENT"), "NO_PRICE_ID");
			return false;
		}

		if (!is_array($arUserGroups) && intval($arUserGroups)."|" == $arUserGroups."|")
			$arUserGroups = array(intval($arUserGroups));

		if (!is_array($arUserGroups))
			$arUserGroups = array();

		if (!in_array(2, $arUserGroups))
			$arUserGroups[] = 2;

		$renewal = (($renewal == "N") ? "N" : "Y");

		if ($siteID === false)
			$siteID = SITE_ID;

		if ($arDiscountCoupons === false)
			$arDiscountCoupons = CCatalogDiscount::GetCoupons();

		$dbPrice = CPrice::GetListEx(
			array(),
			array("ID" => $productPriceID),
			false,
			false,
			array("ID", "PRODUCT_ID", "CATALOG_GROUP_ID", "ELEMENT_IBLOCK_ID")
		);
		if ($arPrice = $dbPrice->Fetch())
		{
			return CCatalogDiscount::GetDiscount($arPrice["PRODUCT_ID"], $arPrice["ELEMENT_IBLOCK_ID"], $arPrice["CATALOG_GROUP_ID"], $arUserGroups, $renewal, $siteID, $arDiscountCoupons);
		}
		else
		{
			$APPLICATION->ThrowException(str_replace("#ID#", $productPriceID, GetMessage("BT_MOD_CATALOG_DISC_ERR_PRICE_ID_NOT_FOUND")), "NO_PRICE");
			return false;
		}
	}

	function GetDiscountByProduct($productID = 0, $arUserGroups = array(), $renewal = "N", $arCatalogGroups = array(), $siteID = false, $arDiscountCoupons = false)
	{
		global $DB;
		global $APPLICATION;

		$events = GetModuleEvents("catalog", "OnGetDiscountByProduct");
		if ($arEvent = $events->Fetch())
			return ExecuteModuleEventEx($arEvent, array($productID, $arUserGroups, $renewal, $arCatalogGroups, $siteID, $arDiscountCoupons));

		$productID = intval($productID);

		if (isset($arCatalogGroups))
		{
			if (is_array($arCatalogGroups))
			{
				array_walk($arCatalogGroups, create_function("&\$item", "\$item=intval(\$item);"));
				$arCatalogGroups = array_unique($arCatalogGroups);
			}
			else
			{
				if (intval($arCatalogGroups)."|" == $arCatalogGroups."|")
					$arCatalogGroups = array(intval($arCatalogGroups));
				else
					$arCatalogGroups = array();
			}
		}
		else
		{
			$arCatalogGroups = array();
		}

		if (!is_array($arUserGroups) && intval($arUserGroups)."|" == $arUserGroups."|")
			$arUserGroups = array(intval($arUserGroups));

		if (!is_array($arUserGroups))
			$arUserGroups = array();

		if (!in_array(2, $arUserGroups))
			$arUserGroups[] = 2;

		$renewal = (($renewal == "N") ? "N" : "Y");

		if ($siteID === false)
			$siteID = SITE_ID;

		if ($arDiscountCoupons === false)
			$arDiscountCoupons = CCatalogDiscount::GetCoupons();

		$dbElement = CIBlockElement::GetList(array(), array("ID"=>$productID), false, false, array("ID","IBLOCK_ID"));
		if (!($arElement = $dbElement->Fetch()))
		{
			$APPLICATION->ThrowException(str_replace("#ID#", $productID, GetMessage("BT_MOD_CATALOG_DISC_ERR_ELEMENT_ID_NOT_FOUND")), "NO_ELEMENT");
			return false;
		}

		return CCatalogDiscount::GetDiscount($productID, $arElement["IBLOCK_ID"], $arCatalogGroups, $arUserGroups, $renewal, $siteID, $arDiscountCoupons);
	}

	function GetDiscount($productID, $iblockID, $arCatalogGroups = array(), $arUserGroups = array(), $renewal = "N", $siteID = false, $arDiscountCoupons = false, $boolSKU = true, $boolGetIDS = false)
	{
		global $DB;
		global $APPLICATION;
		global $stackCacheManager;
		global $CATALOG_DISCOUNT_SECTION_CACHE;
		global $CATALOG_DISCOUNT_TYPES_CACHE;
		global $CATALOG_DISCOUNT_GROUPS_CACHE;
		global $CATALOG_DISCOUNT_PRODUCTS_CACHE;
		global $CATALOG_DISCOUNT_IBLOCKS_CACHE;

		$events = GetModuleEvents("catalog", "OnGetDiscount");
		if ($arEvent = $events->Fetch())
			return ExecuteModuleEventEx($arEvent, array($productID, $iblockID, $arCatalogGroups, $arUserGroups, $renewal, $siteID, $arDiscountCoupons, $boolSKU, $boolGetIDS));

		$boolSKU = (true === $boolSKU ? true : false);
		$boolGetIDS = (true === $boolGetIDS ? true : false);

		$productID = intval($productID);
		if ($productID <= 0)
		{
			$APPLICATION->ThrowException(GetMessage("BT_MOD_CATALOG_DISC_ERR_PRODUCT_ID_ABSENT"), "NO_PRODUCT_ID");
			return false;
		}

		$iblockID = intval($iblockID);
		if ($iblockID <= 0)
		{
			$APPLICATION->ThrowException(GetMessage("BT_MOD_CATALOG_DISC_ERR_IBLOCK_ID_ABSENT"), "NO_IBLOCK_ID");
			return false;
		}

		if (isset($arCatalogGroups))
		{
			if (is_array($arCatalogGroups))
			{
				array_walk($arCatalogGroups, create_function("&\$item", "\$item=intval(\$item);"));
				$arCatalogGroups = array_unique($arCatalogGroups);
			}
			else
			{
				if (intval($arCatalogGroups)."|" == $arCatalogGroups."|")
					$arCatalogGroups = array(intval($arCatalogGroups));
				else
					$arCatalogGroups = array();
			}
		}
		else
		{
			$arCatalogGroups = array();
		}

		if (!is_array($arUserGroups) && intval($arUserGroups)."|" == $arUserGroups."|")
			$arUserGroups = array(intval($arUserGroups));

		if (!is_array($arUserGroups))
			$arUserGroups = array();

		if (!in_array(2, $arUserGroups))
			$arUserGroups[] = 2;

		$renewal = (($renewal == "N") ? "N" : "Y");

		if ($siteID === false)
			$siteID = SITE_ID;

		if ($arDiscountCoupons === false)
			$arDiscountCoupons = CCatalogDiscount::GetCoupons();

		$arSKU = false;
		if ($boolSKU)
		{
			$arSKU = CCatalogSKU::GetProductInfo($productID,$iblockID);
			if (!is_array($arSKU))
			{
				$boolSKU = false;
			}
		}

		$arResult = array();

		$valueProductFilter = COption::GetOptionString("catalog", "do_use_discount_product", "Y");
		$valueCatalogGroupFilter = COption::GetOptionString("catalog", "do_use_discount_cat_group", "Y");
		$valueSectionFilter = COption::GetOptionString("catalog", "do_use_discount_section", "Y");
		$valueGroupFilter = COption::GetOptionString("catalog", "do_use_discount_group", "Y");
		$valueIBlockFilter = COption::GetOptionString("catalog", "do_use_discount_iblock", "Y");

		$cacheTime = CATALOG_CACHE_DEFAULT_TIME;
		if (defined("CATALOG_CACHE_TIME"))
			$cacheTime = intval(CATALOG_CACHE_TIME);

		if ($boolGetIDS)
			$strCacheKey = "I-IDS";
		else
			$strCacheKey = "I";

		if ($valueProductFilter == "Y")
			$strCacheKey .= "_".$productID;
		else
			$strCacheKey .= "_x";

		$arProductSections = array();
		if ($valueSectionFilter == "Y")
		{
			$strCacheKeyGroups = $productID."_".$iblockID;

			$stackLengthGroups = 200;
			if (defined("CATALOG_STACK_ELEMENT_LENGTH"))
				$stackLengthGroups = intval(CATALOG_STACK_ELEMENT_LENGTH);

			$stackCacheManager->SetLength("catalog_element_groups", $stackLengthGroups);
			$stackCacheManager->SetTTL("catalog_element_groups", $cacheTime);
			if ($stackCacheManager->Exist("catalog_element_groups", $strCacheKeyGroups))
			{
				$arProductSections = $stackCacheManager->Get("catalog_element_groups", $strCacheKeyGroups);
			}
			else
			{
				$arProductSections = array();
				$dbElementSections = CIBlockElement::GetElementGroups($productID,true);
				while ($arElementSections = $dbElementSections->Fetch())
				{
					$arProductSections[] = intval($arElementSections["ID"]);
				}
				if (!empty($arProductSections))
				{
					sort($arProductSections);
				}
				$stackCacheManager->Set("catalog_element_groups", $strCacheKeyGroups, $arProductSections);
			}

/*			for ($i = 0, $cnt = count($arProductSections); $i < $cnt; $i++)
				$strCacheKey .= "_".$arProductSections[$i]; */
			if (!empty($arProductSections))
				$strCacheKey .= '_'.implode('-',$arProductSections);
			else
				$strCacheKey .= '_x';
		}
		else
		{
			$strCacheKey .= "_x";
		}

		if ($valueCatalogGroupFilter == "Y")
		{
/*			for ($i = 0, $cnt = count($arCatalogGroups); $i < $cnt; $i++)
				$strCacheKey .= "_".$arCatalogGroups[$i]; */
			if (!empty($arCatalogGroups))
				$strCacheKey .= '_'.implode('-',$arCatalogGroups);
			else
				$strCacheKey .= '_x';
		}
		else
		{
			$strCacheKey .= "_x";
		}

		if ($valueGroupFilter == "Y")
		{
/*			for ($i = 0, $cnt = count($arUserGroups); $i < $cnt; $i++)
				$strCacheKey .= "_".$arUserGroups[$i]; */
			if (!empty($arUserGroups))
				$strCacheKey .= '_'.implode('-',$arUserGroups);
			else
				$strCacheKey .= '_x';
		}
		else
		{
			$strCacheKey .= "_x";
		}

		if ($valueIBlockFilter == 'Y')
		{
			$strCacheKey .= "_".$iblockID;
		}
		else
		{
			$strCacheKey .= "_x";
		}

		$strCacheKey .= "_".$renewal;
		$strCacheKey .= "_".$siteID;
		if (!empty($arDiscountCoupons))
		{
/*			for ($i = 0, $cnt = count($arDiscountCoupons); $i < $cnt; $i++)
				$strCacheKey .= "_".$arDiscountCoupons[$i]; */
			$strCacheKey .= (is_array($arDiscountCoupons) ? '_'.implode('-',$arDiscountCoupons) : '_'.$arDiscountCoupons);
		}
		else
		{
			$strCacheKey .= "_x";
		}

		if ($boolSKU)
		{
			$strCacheKey .= '_'.$arSKU['ID'];
		}
		else
		{
			$strCacheKey .= '_x';
		}

		$stackLength = 100;
		if (defined("CATALOG_STACK_DISCOUNT_LENGTH"))
			$stackLength = intval(CATALOG_STACK_DISCOUNT_LENGTH);

		$stackCacheManager->SetLength("catalog_discount", $stackLength);
		$stackCacheManager->SetTTL("catalog_discount", $cacheTime);
		if ($stackCacheManager->Exist("catalog_discount", $strCacheKey))
		{
			$arResult = $stackCacheManager->Get("catalog_discount", $strCacheKey);
		}
		else
		{
			if (!isset($CATALOG_DISCOUNT_SECTION_CACHE) || !is_array($CATALOG_DISCOUNT_SECTION_CACHE)
				|| !isset($CATALOG_DISCOUNT_TYPES_CACHE) || !is_array($CATALOG_DISCOUNT_TYPES_CACHE)
				|| !isset($CATALOG_DISCOUNT_GROUPS_CACHE) || !is_array($CATALOG_DISCOUNT_GROUPS_CACHE)
				|| !isset($CATALOG_DISCOUNT_PRODUCTS_CACHE) || !is_array($CATALOG_DISCOUNT_PRODUCTS_CACHE)
				|| !isset($CATALOG_DISCOUNT_IBLOCKS_CACHE) || !is_array($CATALOG_DISCOUNT_IBLOCKS_CACHE))
			{
				if (file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE) && is_file($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE))
				{
					$arDiscountSections = array();
					$arDiscountPriceTypes = array();
					$arDiscountUserGroups = array();
					$arDiscountProducts = array();
					$arDiscountIBlocks = array();

					include($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE);

					$CATALOG_DISCOUNT_SECTION_CACHE = $arDiscountSections;
					$CATALOG_DISCOUNT_TYPES_CACHE = $arDiscountPriceTypes;
					$CATALOG_DISCOUNT_GROUPS_CACHE = $arDiscountUserGroups;
					$CATALOG_DISCOUNT_PRODUCTS_CACHE = $arDiscountProducts;
					$CATALOG_DISCOUNT_IBLOCKS_CACHE = $arDiscountIBlocks;
				}
				else
				{
					$CATALOG_DISCOUNT_SECTION_CACHE = array();
					$CATALOG_DISCOUNT_TYPES_CACHE = array();
					$CATALOG_DISCOUNT_GROUPS_CACHE = array();
					$CATALOG_DISCOUNT_PRODUCTS_CACHE = array();
					$CATALOG_DISCOUNT_IBLOCKS_CACHE = array();
				}
				if (!empty($arDiscountCoupons))
				{
					if (file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE) && is_file($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE))
					{
						$arDiscountSections = array();
						$arDiscountPriceTypes = array();
						$arDiscountUserGroups = array();
						$arDiscountProducts = array();
						$arDiscountIBlocks = array();

						include($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE);

						$arDiscountSectionsKeys = array_keys($arDiscountSections);
						for ($i = 0, $cnt = count($arDiscountSectionsKeys); $i < $cnt; $i++)
						{
							//if (array_key_exists($arDiscountSectionsKeys[$i], $CATALOG_DISCOUNT_SECTION_CACHE))
							if (isset($CATALOG_DISCOUNT_SECTION_CACHE[$arDiscountSectionsKeys[$i]]))
								$CATALOG_DISCOUNT_SECTION_CACHE[$arDiscountSectionsKeys[$i]] = array_merge($CATALOG_DISCOUNT_SECTION_CACHE[$arDiscountSectionsKeys[$i]], $arDiscountSections[$arDiscountSectionsKeys[$i]]);
							else
								$CATALOG_DISCOUNT_SECTION_CACHE[$arDiscountSectionsKeys[$i]] = $arDiscountSections[$arDiscountSectionsKeys[$i]];
						}

						$arDiscountPriceTypesKeys = array_keys($arDiscountPriceTypes);
						for ($i = 0, $cnt = count($arDiscountPriceTypesKeys); $i < $cnt; $i++)
						{
							//if (array_key_exists($arDiscountPriceTypesKeys[$i], $CATALOG_DISCOUNT_TYPES_CACHE))
							if (isset($CATALOG_DISCOUNT_TYPES_CACHE[$arDiscountPriceTypesKeys[$i]]))
								$CATALOG_DISCOUNT_TYPES_CACHE[$arDiscountPriceTypesKeys[$i]] = array_merge($CATALOG_DISCOUNT_TYPES_CACHE[$arDiscountPriceTypesKeys[$i]], $arDiscountPriceTypes[$arDiscountPriceTypesKeys[$i]]);
							else
								$CATALOG_DISCOUNT_TYPES_CACHE[$arDiscountPriceTypesKeys[$i]] = $arDiscountPriceTypes[$arDiscountPriceTypesKeys[$i]];
						}

						$arDiscountUserGroupsKeys = array_keys($arDiscountUserGroups);
						for ($i = 0, $cnt = count($arDiscountUserGroupsKeys); $i < $cnt; $i++)
						{
							//if (array_key_exists($arDiscountUserGroupsKeys[$i], $CATALOG_DISCOUNT_GROUPS_CACHE))
							if (isset($CATALOG_DISCOUNT_GROUPS_CACHE[$arDiscountUserGroupsKeys[$i]]))
								$CATALOG_DISCOUNT_GROUPS_CACHE[$arDiscountUserGroupsKeys[$i]] = array_merge($CATALOG_DISCOUNT_GROUPS_CACHE[$arDiscountUserGroupsKeys[$i]], $arDiscountUserGroups[$arDiscountUserGroupsKeys[$i]]);
							else
								$CATALOG_DISCOUNT_GROUPS_CACHE[$arDiscountUserGroupsKeys[$i]] = $arDiscountUserGroups[$arDiscountUserGroupsKeys[$i]];
						}

						$arDiscountProductsKeys = array_keys($arDiscountProducts);
						for ($i = 0, $cnt = count($arDiscountProductsKeys); $i < $cnt; $i++)
						{
							//if (array_key_exists($arDiscountProductsKeys[$i], $CATALOG_DISCOUNT_PRODUCTS_CACHE))
							if (isset($CATALOG_DISCOUNT_PRODUCTS_CACHE[$arDiscountProductsKeys[$i]]))
								$CATALOG_DISCOUNT_PRODUCTS_CACHE[$arDiscountProductsKeys[$i]] = array_merge($CATALOG_DISCOUNT_PRODUCTS_CACHE[$arDiscountProductsKeys[$i]], $arDiscountProducts[$arDiscountProductsKeys[$i]]);
							else
								$CATALOG_DISCOUNT_PRODUCTS_CACHE[$arDiscountProductsKeys[$i]] = $arDiscountProducts[$arDiscountProductsKeys[$i]];
						}

						$arDiscountIBlocksKeys = array_keys($arDiscountIBlocks);
						for ($i = 0, $cnt = count($arDiscountIBlocksKeys); $i < $cnt; $i++)
						{
							//if (array_key_exists($arDiscountIBlocksKeys[$i], $CATALOG_DISCOUNT_IBLOCKS_CACHE))
							if (isset($CATALOG_DISCOUNT_IBLOCKS_CACHE[$arDiscountIBlocksKeys[$i]]))
								$CATALOG_DISCOUNT_IBLOCKS_CACHE[$arDiscountIBlocksKeys[$i]] = array_merge($CATALOG_DISCOUNT_IBLOCKS_CACHE[$arDiscountIBlocksKeys[$i]], $arDiscountIBlocks[$arDiscountIBlocksKeys[$i]]);
							else
								$CATALOG_DISCOUNT_IBLOCKS_CACHE[$arDiscountIBlocksKeys[$i]] = $arDiscountIBlocks[$arDiscountIBlocksKeys[$i]];
						}
					}
				}
			}

			$arDiscountSections = $CATALOG_DISCOUNT_SECTION_CACHE;
			$arDiscountPriceTypes = $CATALOG_DISCOUNT_TYPES_CACHE;
			$arDiscountUserGroups = $CATALOG_DISCOUNT_GROUPS_CACHE;
			$arDiscountProducts = $CATALOG_DISCOUNT_PRODUCTS_CACHE;
			$arDiscountIBlocks = $CATALOG_DISCOUNT_IBLOCKS_CACHE;

			$arDiscountIDsTmp = array();
			if (array_key_exists(0, $arDiscountSections))
				$arDiscountIDsTmp = array_merge($arDiscountIDsTmp, $arDiscountSections[0]);
			foreach ($arProductSections as &$intValue)
			{
				if (array_key_exists($intValue, $arDiscountSections))
					$arDiscountIDsTmp = array_merge($arDiscountIDsTmp, $arDiscountSections[$intValue]);
			}

			$arDiscountIDsTmp1 = array();
			if (array_key_exists(0, $arDiscountPriceTypes))
				$arDiscountIDsTmp1 = array_merge($arDiscountIDsTmp1, $arDiscountPriceTypes[0]);
			foreach ($arCatalogGroups as &$intValue)
			{
				if (array_key_exists($intValue, $arDiscountPriceTypes))
					$arDiscountIDsTmp1 = array_merge($arDiscountIDsTmp1, $arDiscountPriceTypes[$intValue]);
			}

			$arDiscountIDsTmp2 = array();
			if (array_key_exists(0, $arDiscountUserGroups))
				$arDiscountIDsTmp2 = array_merge($arDiscountIDsTmp2, $arDiscountUserGroups[0]);
			foreach ($arUserGroups as &$intValue)
			{
				if (array_key_exists($intValue, $arDiscountUserGroups))
					$arDiscountIDsTmp2 = array_merge($arDiscountIDsTmp2, $arDiscountUserGroups[$intValue]);
			}

			$arDiscountIDsTmp3 = array();
			if (array_key_exists(0, $arDiscountProducts))
				$arDiscountIDsTmp3 = array_merge($arDiscountIDsTmp3, $arDiscountProducts[0]);
			if (array_key_exists($productID, $arDiscountProducts))
				$arDiscountIDsTmp3 = array_merge($arDiscountIDsTmp3, $arDiscountProducts[$productID]);

			$arDiscountIDsTmp4 = array();
			if (array_key_exists(0, $arDiscountIBlocks))
				$arDiscountIDsTmp4 = array_merge($arDiscountIDsTmp4, $arDiscountIBlocks[0]);
			if (array_key_exists($iblockID, $arDiscountIBlocks))
				$arDiscountIDsTmp4 = array_merge($arDiscountIDsTmp4, $arDiscountIBlocks[$iblockID]);

			$arDiscountIDsTmp = array_intersect($arDiscountIDsTmp, $arDiscountIDsTmp1, $arDiscountIDsTmp2, $arDiscountIDsTmp3, $arDiscountIDsTmp4);

/*			$arDiscountIDs = array();
			foreach ($arDiscountIDsTmp as $value)
				$arDiscountIDs[] = $value; */
			$arDiscountIDs = array_values($arDiscountIDsTmp);

			if ($boolSKU)
			{
				$arDiscountIDsParent = CCatalogDiscount::GetDiscount($arSKU['ID'], $arSKU['IBLOCK_ID'], $arCatalogGroups, $arUserGroups, $renewal, $siteID, $arDiscountCoupons, false, true);
				if (!empty($arDiscountIDsParent))
				{
					if (!empty($arDiscountIDs))
						$arDiscountIDs = array_values(array_unique(array_merge($arDiscountIDs,$arDiscountIDsParent)));
					else
						$arDiscountIDs = $arDiscountIDsParent;
				}
			}

			if (!empty($arDiscountIDs))
			{
				if ($boolGetIDS)
				{
					$arResult = $arDiscountIDs;
				}
				else
				{
					$arFilter = array(
						"ID" => $arDiscountIDs,
						"SITE_ID" => $siteID,
						"ACTIVE" => "Y",
						"+<=ACTIVE_FROM" => Date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL"))),
						"+>=ACTIVE_TO" => Date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL"))),
						"RENEWAL" => $renewal
					);

					if (is_array($arDiscountCoupons))
					{
						$arFilter["+COUPON"] = $arDiscountCoupons;
					}

					$dbPriceDiscount = CCatalogDiscount::GetList(
						array(
							"VALUE" => "DESC",
							"SORT" => "ASC",
							"ID" => "DESC"
						),
						array_merge($arFilter, array("VALUE_TYPE" => "P")),
						false,
						array('nTopCount' => 1),
						//array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "MAX_USES", "COUNT_USES", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "MIN_ORDER_SUM", "TIMESTAMP_X", "NOTES", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
						array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "TIMESTAMP_X", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
					);

					if ($arPriceDiscount = $dbPriceDiscount->Fetch())
					{
						if ($arPriceDiscount['COUPON_ACTIVE'] != 'N')
							$arResult[] = $arPriceDiscount;
					}

/*					$dbPriceDiscount = CCatalogDiscount::GetList(
						array(
							"VALUE" => "DESC",
							"SORT" => "ASC",
							"ID" => "DESC"
						),
						array_merge($arFilter, array("VALUE_TYPE" => "F")),
						false,
						array('nTopCount' => 1),
						array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "MAX_USES", "COUNT_USES", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "MIN_ORDER_SUM", "TIMESTAMP_X", "NOTES", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
					);

					if ($arPriceDiscount = $dbPriceDiscount->Fetch())
					{
						if ($arPriceDiscount['COUPON_ACTIVE'] != 'N')
							$arResult[] = $arPriceDiscount;
					} */
					$strDiscountCurrency = '';
					$dbPriceDiscount = CCatalogDiscount::GetList(
						array(
							"CURRENCY" => "ASC",
							"VALUE" => "DESC",
							"SORT" => "ASC",
						),
						array_merge($arFilter, array("VALUE_TYPE" => "F")),
						false,
						false,
						//array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "MAX_USES", "COUNT_USES", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "MIN_ORDER_SUM", "TIMESTAMP_X", "NOTES", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
						array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "TIMESTAMP_X", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
					);
					while ($arPriceDiscount = $dbPriceDiscount->Fetch())
					{
						if ($strDiscountCurrency != $arPriceDiscount['CURRENCY'])
						{
							$strDiscountCurrency = $arPriceDiscount['CURRENCY'];
							if ($arPriceDiscount['COUPON_ACTIVE'] != 'N')
								$arResult[] = $arPriceDiscount;
						}
					}

/*					$dbPriceDiscount = CCatalogDiscount::GetList(
						array(
							"VALUE" => "ASC",
							"SORT" => "ASC",
							"ID" => "DESC"
						),
						array_merge($arFilter, array("VALUE_TYPE" => "S")),
						false,
						array('nTopCount' => 1),
						array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "MAX_USES", "COUNT_USES", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "MIN_ORDER_SUM", "TIMESTAMP_X", "NOTES", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
					);

					if ($arPriceDiscount = $dbPriceDiscount->Fetch())
					{
						if ($arPriceDiscount['COUPON_ACTIVE'] != 'N')
							$arResult[] = $arPriceDiscount;
					} */
					$strDiscountCurrency = '';
					$dbPriceDiscount = CCatalogDiscount::GetList(
						array(
							"CURRENCY" => "ASC",
							"VALUE" => "DESC",
							"SORT" => "ASC",
						),
						array_merge($arFilter, array("VALUE_TYPE" => "S")),
						false,
						false,
						//array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "MAX_USES", "COUNT_USES", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "MIN_ORDER_SUM", "TIMESTAMP_X", "NOTES", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
						array("ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", "VALUE", "CURRENCY", "TIMESTAMP_X", "COUPON", "COUPON_ONE_TIME", "COUPON_ACTIVE")
					);

					while ($arPriceDiscount = $dbPriceDiscount->Fetch())
					{
						if ($strDiscountCurrency != $arPriceDiscount['CURRENCY'])
						{
							$strDiscountCurrency = $arPriceDiscount['CURRENCY'];
							if ($arPriceDiscount['COUPON_ACTIVE'] != 'N')
								$arResult[] = $arPriceDiscount;
						}
					}
				}
			}

			$stackCacheManager->Set("catalog_discount", $strCacheKey, $arResult);
		}

		return $arResult;
	}

	function HaveCoupons($ID, $excludeID = 0)
	{
		$ID = intval($ID);
		if ($ID <= 0)
			return false;

		$arFilter = array("DISCOUNT_ID" => $ID);

		$excludeID = intval($excludeID);
		if ($excludeID > 0)
			$arFilter["!ID"] = $excludeID;

		$dbRes = CCatalogDiscountCoupon::GetList(array(), $arFilter, false, array("nTopCount" => 1), array("ID"));
		if ($dbRes->Fetch())
			return true;
		else
			return false;
	}
}
?>