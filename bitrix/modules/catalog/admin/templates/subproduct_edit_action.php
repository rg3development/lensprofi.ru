<?
//$CATALOG_RIGHT = $APPLICATION->GetGroupRight("catalog");
//if ($CATALOG_RIGHT=="W"):
if ($USER->CanDoOperation('catalog_price')) :
//****************************************************************

include(GetLangFileName($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/lang/", "/templates/product_edit_action.php"));

if (strlen($strWarning) <= 0)
{
	$bUseExtForm = ($_POST['subprice_useextform'] == 'Y');

	$arCatalog = CCatalog::GetByID($IBLOCK_ID);

	$arCatalogPrice_tmp = array();
	$intBasePriceCount = count($arCatalogBasePrices);
	$dbCatGroups = CCatalogGroup::GetList(array(), array("!BASE" => "Y"));
	while ($arCatGroups = $dbCatGroups->Fetch())
	{
		unset($arCatalogPrice_tmp);
		$arCatalogPrice_tmp = array();

//		for ($i = 0; $i < count($arCatalogBasePrices); $i++)
		for ($i = 0; $i < $intBasePriceCount; $i++)
		{
			${"SUBCAT_PRICE_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]} = str_replace(",", ".", ${"SUBCAT_PRICE_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]});

/*			$arCatalogPrice_tmp[$i] = array(
					"ID" => IntVal(${"SUBCAT_ID_".$arCatGroups["ID"]}[$arCatalogBasePrices[$i]["IND"]]),
					"EXTRA_ID" => ${"SUBCAT_EXTRA_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]}
						? IntVal(${"SUBCAT_EXTRA_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]})
						: IntVal(${"SUBCAT_EXTRA_".$arCatGroups["ID"]}),
					"PRICE" => ${"SUBCAT_PRICE_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]},
					"CURRENCY" => Trim(${"SUBCAT_CURRENCY_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]}),
					"QUANTITY_FROM" => $arCatalogBasePrices[$i]["QUANTITY_FROM"],
					"QUANTITY_TO" => $arCatalogBasePrices[$i]["QUANTITY_TO"]
				); */
			$arCatalogPrice_tmp[$i] = array(
					"ID" => IntVal(${"SUBCAT_ID_".$arCatGroups["ID"]}[$arCatalogBasePrices[$i]["IND"]]),
					"EXTRA_ID" => ${"SUBCAT_EXTRA_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]}
						? IntVal(${"SUBCAT_EXTRA_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]})
						: 0,
					"PRICE" => ${"SUBCAT_PRICE_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]},
					"CURRENCY" => Trim(${"SUBCAT_CURRENCY_".$arCatGroups["ID"]."_".$arCatalogBasePrices[$i]["IND"]}),
					"QUANTITY_FROM" => $arCatalogBasePrices[$i]["QUANTITY_FROM"],
					"QUANTITY_TO" => $arCatalogBasePrices[$i]["QUANTITY_TO"]
				);

			if (strlen($arCatalogPrice_tmp[$i]["CURRENCY"]) <= 0)
			{
				$arCatalogPrice_tmp[$i]["CURRENCY"] = $arCatalogBasePrices[$i]["CURRENCY"];
			}

			if ($arCatalogPrice_tmp[$i]["EXTRA_ID"] > 0)
			{
				if (0 < doubleval($arCatalogBasePrices[$i]["PRICE"]))
				{
					$arCatalogPrice_tmp[$i]["CURRENCY"] = $arCatalogBasePrices[$i]["CURRENCY"];
					$arCatalogExtra = CExtra::GetByID($arCatalogPrice_tmp[$i]["EXTRA_ID"]);
					$arCatalogPrice_tmp[$i]["PRICE"] = RoundEx($arCatalogBasePrices[$i]["PRICE"] * (1 + DoubleVal($arCatalogExtra["PERCENTAGE"]) / 100), CATALOG_VALUE_PRECISION);
				}
				else
				{
					$arCatalogPrice_tmp[$i]["EXTRA_ID"] = 0;
				}
			}
		}

		$arCatalogPrices[$arCatGroups["ID"]] = $arCatalogPrice_tmp;
	}

	$PRODUCT_ID = CIBlockElement::GetRealElement($ID);
	$arUpdatedIDs = array();

	$arFields = array(
			"ID" => $PRODUCT_ID,
			"QUANTITY" => $SUBCAT_BASE_QUANTITY,
			"QUANTITY_TRACE" => $SUBCAT_BASE_QUANTITY_TRACE,
			"WEIGHT" => $SUBCAT_BASE_WEIGHT,
			"VAT_ID" => $SUBCAT_VAT_ID,
			"VAT_INCLUDED" => $SUBCAT_VAT_INCLUDED,
			"PRICE_TYPE" => false,
			"RECUR_SCHEME_TYPE" => false,
			"RECUR_SCHEME_LENGTH" => false,
			"TRIAL_PRICE_ID" => false,
			"WITHOUT_ORDER" => false
		);

	if ($arCatalog["SUBSCRIPTION"] == "Y")
	{
		$arFields["PRICE_TYPE"] = $SUBCAT_PRICE_TYPE;
		$arFields["RECUR_SCHEME_TYPE"] = $SUBCAT_RECUR_SCHEME_TYPE;
		$arFields["RECUR_SCHEME_LENGTH"] = $SUBCAT_RECUR_SCHEME_LENGTH;
		$arFields["TRIAL_PRICE_ID"] = $SUBCAT_TRIAL_PRICE_ID;
		$arFields["WITHOUT_ORDER"] = $SUBCAT_WITHOUT_ORDER;
	}

	CCatalogProduct::Add($arFields);

	$intCountBasePrice = count($arCatalogBasePrices);
	//for ($i = 0; $i < count($arCatalogBasePrices); $i++)
	for ($i = 0; $i < $intCountBasePrice; $i++)
	{
		if (strlen($arCatalogBasePrices[$i]["PRICE"]) > 0)
		{
			$arCatalogFields = array(
					"EXTRA_ID" => false,
					"PRODUCT_ID" => $PRODUCT_ID,
					"CATALOG_GROUP_ID" => $arCatalogBaseGroup["ID"],
					"PRICE" => DoubleVal($arCatalogBasePrices[$i]["PRICE"]),
					"CURRENCY" => $arCatalogBasePrices[$i]["CURRENCY"],
					"QUANTITY_FROM" => ($arCatalogBasePrices[$i]["QUANTITY_FROM"] > 0 ? $arCatalogBasePrices[$i]["QUANTITY_FROM"] : False),
					"QUANTITY_TO" => ($arCatalogBasePrices[$i]["QUANTITY_TO"] > 0 ? $arCatalogBasePrices[$i]["QUANTITY_TO"] : False)
				);

			if ($arCatalogBasePrices[$i]["ID"] > 0)
			{
				$arCatalogPrice = CPrice::GetByID($arCatalogBasePrices[$i]["ID"]);
				if ($arCatalogPrice && $arCatalogPrice["PRODUCT_ID"] == $PRODUCT_ID)
				{
					$arUpdatedIDs[] = $arCatalogBasePrices[$i]["ID"];
					if (!CPrice::Update($arCatalogBasePrices[$i]["ID"], $arCatalogFields))
						$strWarning .= str_replace("#ID#", $arCatalogBasePrices[$i]["ID"], GetMessage("C2IT_ERROR_PRPARAMS"))."<br>";
				}
				else
				{
					$ID_tmp = CPrice::Add($arCatalogFields);
					$arUpdatedIDs[] = $ID_tmp;
					if (!$ID_tmp)
						$strWarning .= str_replace("#PRICE#", $arCatalogFields["PRICE"], GetMessage("C2IT_ERROR_SAVEPRICE"))."<br>";
				}
			}
			else
			{
				$ID_tmp = CPrice::Add($arCatalogFields);
				$arUpdatedIDs[] = $ID_tmp;
				if (!$ID_tmp)
					$strWarning .= str_replace("#PRICE#", $arCatalogFields["PRICE"], GetMessage("C2IT_ERROR_SAVEPRICE"))."<br>";
			}
		}
	}

	foreach ($arCatalogPrices as $catalogGroupID => $arCatalogPrice_tmp)
	{
		$intCountPrices = count($arCatalogPrice_tmp);
		//for ($i = 0; $i < count($arCatalogPrice_tmp); $i++)
		for ($i = 0; $i < $intCountPrices; $i++)
		{
			if (strlen($arCatalogPrice_tmp[$i]["PRICE"]) > 0)
			{
				$arCatalogFields = array(
						"EXTRA_ID" => ($arCatalogPrice_tmp[$i]["EXTRA_ID"] > 0 ? $arCatalogPrice_tmp[$i]["EXTRA_ID"] : false),
						"PRODUCT_ID" => $PRODUCT_ID,
						"CATALOG_GROUP_ID" => $catalogGroupID,
						"PRICE" => DoubleVal($arCatalogPrice_tmp[$i]["PRICE"]),
						"CURRENCY" => $arCatalogPrice_tmp[$i]["CURRENCY"],
						"QUANTITY_FROM" => ($arCatalogPrice_tmp[$i]["QUANTITY_FROM"] > 0 ? $arCatalogPrice_tmp[$i]["QUANTITY_FROM"] : False),
						"QUANTITY_TO" => ($arCatalogPrice_tmp[$i]["QUANTITY_TO"] > 0 ? $arCatalogPrice_tmp[$i]["QUANTITY_TO"] : False)
					);

				if ($arCatalogPrice_tmp[$i]["ID"] > 0)
				{
					$arCatalogPrice = CPrice::GetByID($arCatalogPrice_tmp[$i]["ID"]);
					if ($arCatalogPrice && $arCatalogPrice["PRODUCT_ID"] == $PRODUCT_ID)
					{
						$arUpdatedIDs[] = $arCatalogPrice_tmp[$i]["ID"];
						if (!CPrice::Update($arCatalogPrice_tmp[$i]["ID"], $arCatalogFields))
							$strWarning .= str_replace("#ID#", $arCatalogPrice_tmp[$i]["ID"], GetMessage("C2IT_ERROR_PRPARAMS"))."<br>";
					}
					else
					{
						$ID_tmp = CPrice::Add($arCatalogFields);
						$arUpdatedIDs[] = $ID_tmp;
						if (!$ID_tmp)
							$strWarning .= str_replace("#PRICE#", $arCatalogFields["PRICE"], GetMessage("C2IT_ERROR_SAVEPRICE"))."<br>";
					}
				}
				else
				{
					$ID_tmp = CPrice::Add($arCatalogFields);
					$arUpdatedIDs[] = $ID_tmp;
					if (!$ID_tmp)
						$strWarning .= str_replace("#PRICE#", $arCatalogFields["PRICE"], GetMessage("C2IT_ERROR_SAVEPRICE"))."<br>";
				}
			}
		}
	}

	CPrice::DeleteByProduct($PRODUCT_ID, $arUpdatedIDs);

	if ($arCatalog["SUBSCRIPTION"] == "Y")
	{
		$arCurProductGroups = array();

		$dbProductGroups = CCatalogProductGroups::GetList(
				array(),
				array("PRODUCT_ID" => $ID),
				false,
				false,
				array("ID", "GROUP_ID", "ACCESS_LENGTH", "ACCESS_LENGTH_TYPE")
			);
		while ($arProductGroup = $dbProductGroups->Fetch())
		{
			$arCurProductGroups[IntVal($arProductGroup["GROUP_ID"])] = $arProductGroup;
		}

		$arAvailContentGroups = array();
		$availContentGroups = COption::GetOptionString("catalog", "avail_content_groups");
		if (strlen($availContentGroups) > 0)
			$arAvailContentGroups = explode(",", $availContentGroups);

		$dbGroups = CGroup::GetList(
				($b = "c_sort"),
				($o = "asc"),
				array("ANONYMOUS" => "N")
			);
		while ($arGroup = $dbGroups->Fetch())
		{
			$arGroup["ID"] = IntVal($arGroup["ID"]);

			if ($arGroup["ID"] == 2
				|| !in_array($arGroup["ID"], $arAvailContentGroups))
			{
				if (array_key_exists($arGroup["ID"], $arCurProductGroups))
					CCatalogProductGroups::Delete($arCurProductGroups[$arGroup["ID"]]["ID"]);

				continue;
			}

			if (array_key_exists($arGroup["ID"], $arCurProductGroups))
			{
				if (isset(${"SUBCAT_USER_GROUP_ID_".$arGroup["ID"]}) && ${"SUBCAT_USER_GROUP_ID_".$arGroup["ID"]} == "Y")
				{
					if (IntVal(${"SUBCAT_ACCESS_LENGTH_".$arGroup["ID"]}) != IntVal($arCurProductGroups[$arGroup["ID"]]["ACCESS_LENGTH"])
						|| ${"SUBCAT_ACCESS_LENGTH_TYPE_".$arGroup["ID"]} != $arCurProductGroups[$arGroup["ID"]]["ACCESS_LENGTH_TYPE"])
					{
						$arCatalogFields = array(
								"ACCESS_LENGTH" => IntVal(${"SUBCAT_ACCESS_LENGTH_".$arGroup["ID"]}),
								"ACCESS_LENGTH_TYPE" => ${"SUBCAT_ACCESS_LENGTH_TYPE_".$arGroup["ID"]}
							);
						CCatalogProductGroups::Update($arCurProductGroups[$arGroup["ID"]]["ID"], $arCatalogFields);
					}
				}
				else
				{
					CCatalogProductGroups::Delete($arCurProductGroups[$arGroup["ID"]]["ID"]);
				}
			}
			else
			{
				if (isset(${"SUBCAT_USER_GROUP_ID_".$arGroup["ID"]}) && ${"SUBCAT_USER_GROUP_ID_".$arGroup["ID"]} == "Y")
				{
					$arCatalogFields = array(
							"PRODUCT_ID" => $ID,
							"GROUP_ID" => $arGroup["ID"],
							"ACCESS_LENGTH" => IntVal(${"SUBCAT_ACCESS_LENGTH_".$arGroup["ID"]}),
							"ACCESS_LENGTH_TYPE" => ${"SUBCAT_ACCESS_LENGTH_TYPE_".$arGroup["ID"]}
						);
					CCatalogProductGroups::Add($arCatalogFields);
				}
			}
		}
	}
}

//****************************************************************
endif;
?>