<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/general/discount.php");

/***********************************************************************/
/***********  CCatalogDiscount  ****************************************/
/***********************************************************************/
class CCatalogDiscount extends CAllCatalogDiscount
{
	function _Add(&$arFields)
	{
		global $DB;
		global $stackCacheManager;

		if (!CCatalogDiscount::CheckFields("ADD", $arFields, 0))
			return false;

		$stackCacheManager->Clear("catalog_discount");

		$arInsert = $DB->PrepareInsert("b_catalog_discount", $arFields);

		$strSql =
			"INSERT INTO b_catalog_discount(".$arInsert[0].") ".
			"VALUES(".$arInsert[1].")";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		$ID = IntVal($DB->LastID());

		return $ID;
	}

	function GetByID($ID)
	{
		global $DB;

		$ID = IntVal($ID);
		if ($ID <= 0)
			return false;

		$strSql =
			"SELECT CD.ID, CD.SITE_ID, CD.ACTIVE, CD.NAME, CD.MAX_USES, ".
			"	CD.COUNT_USES, CD.COUPON, CD.SORT, CD.MAX_DISCOUNT, CD.VALUE_TYPE, ".
			"	CD.VALUE, CD.CURRENCY, CD.MIN_ORDER_SUM, CD.NOTES, CD.RENEWAL, ".
			"	".$DB->DateToCharFunction("CD.TIMESTAMP_X", "FULL")." as TIMESTAMP_X, ".
			"	".$DB->DateToCharFunction("CD.ACTIVE_FROM", "FULL")." as ACTIVE_FROM, ".
			"	".$DB->DateToCharFunction("CD.ACTIVE_TO", "FULL")." as ACTIVE_TO ".
			"FROM b_catalog_discount CD ".
			"WHERE CD.ID = ".$ID." ";

		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		if ($res = $db_res->Fetch())
			return $res;

		return false;
	}

	function PrepareSection4Where($val, $key, $operation, $negative, $field, &$arField, &$arFilter)
	{
		$val = IntVal($val);
		if ($val <= 0)
			return False;

		$dbSection = CIBlockSection::GetByID($val);
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
			$ids = "0";
			while ($arSectionTree = $dbSectionTree->Fetch())
			{
				$ids .= ",".IntVal($arSectionTree["ID"]);
			}

			return "(CDS.SECTION_ID ".(($negative == "Y") ? "NOT " : "")."IN (".$ids."))";
		}

		return False;
	}

	function GetList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
			"ID" => array("FIELD" => "CD.ID", "TYPE" => "int"),
			"SITE_ID" => array("FIELD" => "CD.SITE_ID", "TYPE" => "string"),
			"ACTIVE" => array("FIELD" => "CD.ACTIVE", "TYPE" => "char"),
			"ACTIVE_FROM" => array("FIELD" => "CD.ACTIVE_FROM", "TYPE" => "datetime"),
			"ACTIVE_TO" => array("FIELD" => "CD.ACTIVE_TO", "TYPE" => "datetime"),
			"RENEWAL" => array("FIELD" => "CD.RENEWAL", "TYPE" => "char"),
			"NAME" => array("FIELD" => "CD.NAME", "TYPE" => "string"),
			"MAX_USES" => array("FIELD" => "CD.MAX_USES", "TYPE" => "int"),
			"COUNT_USES" => array("FIELD" => "CD.COUNT_USES", "TYPE" => "int"),
			"SORT" => array("FIELD" => "CD.SORT", "TYPE" => "int"),
			"MAX_DISCOUNT" => array("FIELD" => "CD.MAX_DISCOUNT", "TYPE" => "double"),
			"VALUE_TYPE" => array("FIELD" => "CD.VALUE_TYPE", "TYPE" => "char"),
			"VALUE" => array("FIELD" => "CD.VALUE", "TYPE" => "double"),
			"CURRENCY" => array("FIELD" => "CD.CURRENCY", "TYPE" => "string"),
			"MIN_ORDER_SUM" => array("FIELD" => "CD.MIN_ORDER_SUM", "TYPE" => "double"),
			"TIMESTAMP_X" => array("FIELD" => "CD.TIMESTAMP_X", "TYPE" => "datetime"),
			"NOTES" => array("FIELD" => "CD.NOTES", "TYPE" => "string"),

			"PRODUCT_ID" => array("FIELD" => "CDP.PRODUCT_ID", "TYPE" => "int", "FROM" => "LEFT JOIN b_catalog_discount2product CDP ON (CD.ID = CDP.DISCOUNT_ID)"),
			"SECTION_ID" => array("FIELD" => "CDS.SECTION_ID", "TYPE" => "int", "FROM" => "LEFT JOIN b_catalog_discount2section CDS ON (CD.ID = CDS.DISCOUNT_ID)", "WHERE" => array("CCatalogDiscount", "PrepareSection4Where")),
			"GROUP_ID" => array("FIELD" => "CDG.GROUP_ID", "TYPE" => "int", "FROM" => "LEFT JOIN b_catalog_discount2group CDG ON (CD.ID = CDG.DISCOUNT_ID)"),
			"CATALOG_GROUP_ID" => array("FIELD" => "CDC.CATALOG_GROUP_ID", "TYPE" => "int", "FROM" => "LEFT JOIN b_catalog_discount2cat CDC ON (CD.ID = CDC.DISCOUNT_ID)"),
			"IBLOCK_ID" => array("FIELD" => "CDI.IBLOCK_ID", "TYPE" => "int", "FROM" => "LEFT JOIN b_catalog_discount2iblock CDI ON (CD.ID = CDI.DISCOUNT_ID)"),
			"COUPON" => array("FIELD" => "CDCP.COUPON", "TYPE" => "string", "FROM" => "LEFT JOIN b_catalog_discount_coupon CDCP ON (CD.ID = CDCP.DISCOUNT_ID)"),
			"COUPON_ACTIVE" => array("FIELD" => "CDCP.ACTIVE", "TYPE" => "char", "FROM" => "LEFT JOIN b_catalog_discount_coupon CDCP ON (CD.ID = CDCP.DISCOUNT_ID)"),
			"COUPON_ONE_TIME" => array("FIELD" => "CDCP.ONE_TIME", "TYPE" => "char", "FROM" => "LEFT JOIN b_catalog_discount_coupon CDCP ON (CD.ID = CDCP.DISCOUNT_ID)"),
		);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);//DISTINCT

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount CD ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount CD ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount CD ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetDiscountGroupsList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
				"ID" => array("FIELD" => "DG.ID", "TYPE" => "int"),
				"DISCOUNT_ID" => array("FIELD" => "DG.DISCOUNT_ID", "TYPE" => "int"),
				"GROUP_ID" => array("FIELD" => "DG.GROUP_ID", "TYPE" => "int")
			);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount2group DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount2group DG ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount2group DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetDiscountCatsList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
				"ID" => array("FIELD" => "DG.ID", "TYPE" => "int"),
				"DISCOUNT_ID" => array("FIELD" => "DG.DISCOUNT_ID", "TYPE" => "int"),
				"CATALOG_GROUP_ID" => array("FIELD" => "DG.CATALOG_GROUP_ID", "TYPE" => "int")
			);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount2cat DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount2cat DG ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount2cat DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetDiscountProductsList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
				"ID" => array("FIELD" => "DG.ID", "TYPE" => "int"),
				"DISCOUNT_ID" => array("FIELD" => "DG.DISCOUNT_ID", "TYPE" => "int"),
				"PRODUCT_ID" => array("FIELD" => "DG.PRODUCT_ID", "TYPE" => "int")
			);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount2product DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount2product DG ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount2product DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetDiscountSectionsList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
				"ID" => array("FIELD" => "DG.ID", "TYPE" => "int"),
				"DISCOUNT_ID" => array("FIELD" => "DG.DISCOUNT_ID", "TYPE" => "int"),
				"SECTION_ID" => array("FIELD" => "DG.SECTION_ID", "TYPE" => "int")
			);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount2section DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount2section DG ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount2section DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetDiscountIBlocksList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		$arFields = array(
				"ID" => array("FIELD" => "DG.ID", "TYPE" => "int"),
				"DISCOUNT_ID" => array("FIELD" => "DG.DISCOUNT_ID", "TYPE" => "int"),
				"IBLOCK_ID" => array("FIELD" => "DG.IBLOCK_ID", "TYPE" => "int")
			);

		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && empty($arGroupBy))
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_discount2iblock DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_catalog_discount2iblock DG ".
			"	".$arSqls["FROM"]." ";
		if (!empty($arSqls["WHERE"]))
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (!empty($arSqls["GROUPBY"]))
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (!empty($arSqls["ORDERBY"]))
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_catalog_discount2iblock DG ".
				"	".$arSqls["FROM"]." ";
			if (!empty($arSqls["WHERE"]))
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (!empty($arSqls["GROUPBY"]))
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (empty($arSqls["GROUPBY"]))
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function SaveFilterOptions()
	{
		global $DB;

		$valueProductFilter = "Y";
		$dbRes = $DB->Query(
			"SELECT 'x' ".
			"FROM b_catalog_discount D ".
			"	INNER JOIN b_catalog_discount2product D2P ON (D.ID = D2P.DISCOUNT_ID) ".
			"WHERE D.ACTIVE = 'Y' ".
			"	AND (D.ACTIVE_TO > ".$DB->CurrentTimeFunction()." OR D.ACTIVE_TO IS NULL) ".
			"LIMIT 0, 1"
		);
		if ($dbRes->Fetch())
			$valueProductFilter = "Y";
		else
			$valueProductFilter = "N";

		COption::SetOptionString("catalog", "do_use_discount_product", $valueProductFilter);

		$valueCatalogGroupFilter = "Y";
		$dbRes = $DB->Query(
			"SELECT 'x' ".
			"FROM b_catalog_discount D ".
			"	INNER JOIN b_catalog_discount2cat D2C ON (D.ID = D2C.DISCOUNT_ID) ".
			"WHERE D.ACTIVE = 'Y' ".
			"	AND (D.ACTIVE_TO > ".$DB->CurrentTimeFunction()." OR D.ACTIVE_TO IS NULL) ".
			"LIMIT 0, 1"
		);
		if ($dbRes->Fetch())
			$valueCatalogGroupFilter = "Y";
		else
			$valueCatalogGroupFilter = "N";

		COption::SetOptionString("catalog", "do_use_discount_cat_group", $valueCatalogGroupFilter);

		$valueSectionFilter = "Y";
		$dbRes = $DB->Query(
			"SELECT 'x' ".
			"FROM b_catalog_discount D ".
			"	INNER JOIN b_catalog_discount2section D2S ON (D.ID = D2S.DISCOUNT_ID) ".
			"WHERE D.ACTIVE = 'Y' ".
			"	AND (D.ACTIVE_TO > ".$DB->CurrentTimeFunction()." OR D.ACTIVE_TO IS NULL) ".
			"LIMIT 0, 1"
		);
		if ($dbRes->Fetch())
			$valueSectionFilter = "Y";
		else
			$valueSectionFilter = "N";

		COption::SetOptionString("catalog", "do_use_discount_section", $valueSectionFilter);

		$valueGroupFilter = "Y";
		$dbRes = $DB->Query(
			"SELECT 'x' ".
			"FROM b_catalog_discount D ".
			"	INNER JOIN b_catalog_discount2group D2G ON (D.ID = D2G.DISCOUNT_ID) ".
			"WHERE D.ACTIVE = 'Y' ".
			"	AND (D.ACTIVE_TO > ".$DB->CurrentTimeFunction()." OR D.ACTIVE_TO IS NULL) ".
			"LIMIT 0, 1"
		);
		if ($dbRes->Fetch())
			$valueGroupFilter = "Y";
		else
			$valueGroupFilter = "N";

		COption::SetOptionString("catalog", "do_use_discount_group", $valueGroupFilter);

		$valueIBlockFilter = "Y";
		$dbRes = $DB->Query(
			"SELECT 'x' ".
			"FROM b_catalog_discount D ".
			"	INNER JOIN b_catalog_discount2iblock D2P ON (D.ID = D2P.DISCOUNT_ID) ".
			"WHERE D.ACTIVE = 'Y' ".
			"	AND (D.ACTIVE_TO > ".$DB->CurrentTimeFunction()." OR D.ACTIVE_TO IS NULL) ".
			"LIMIT 0, 1"
		);
		if ($dbRes->Fetch())
			$valueIBlockFilter = "Y";
		else
			$valueIBlockFilter = "N";

		COption::SetOptionString("catalog", "do_use_discount_iblock", $valueIBlockFilter);
	}
}
?>