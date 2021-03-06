<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/general/order.php");

class CSaleOrder extends CAllSaleOrder
{
	function Add($arFields)
	{
		global $DB;

		$arFields1 = array();
		foreach ($arFields as $key => $value)
		{
			if (substr($key, 0, 1)=="=")
			{
				$arFields1[substr($key, 1)] = $value;
				unset($arFields[$key]);
			}
		}

		if (!CSaleOrder::CheckFields("ADD", $arFields))
			return false;

		$db_events = GetModuleEvents("sale", "OnBeforeOrderAdd");
		while ($arEvent = $db_events->Fetch())
			if (ExecuteModuleEventEx($arEvent, Array(&$arFields))===false)
				return false;

		$arInsert = $DB->PrepareInsert("b_sale_order", $arFields);

		if (!array_key_exists("DATE_STATUS", $arFields))
		{
			$arInsert[0] .= ", DATE_STATUS";
			$arInsert[1] .= ", ".$DB->GetNowFunction();
		}
		if (!array_key_exists("DATE_INSERT", $arFields))
		{
			$arInsert[0] .= ", DATE_INSERT";
			$arInsert[1] .= ", ".$DB->GetNowFunction();
		}
		if (!array_key_exists("DATE_UPDATE", $arFields))
		{
			$arInsert[0] .= ", DATE_UPDATE";
			$arInsert[1] .= ", ".$DB->GetNowFunction();
		}

		foreach ($arFields1 as $key => $value)
		{
			if (strlen($arInsert[0])>0)
			{
				$arInsert[0] .= ", ";
				$arInsert[1] .= ", ";
			}
			$arInsert[0] .= $key;
			$arInsert[1] .= $value;
		}

		$strSql =
			"INSERT INTO b_sale_order(".$arInsert[0].") ".
			"VALUES(".$arInsert[1].")";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		$ID = IntVal($DB->LastID());

		$events = GetModuleEvents("sale", "OnOrderAdd");
		while ($arEvent = $events->Fetch())
			ExecuteModuleEventEx($arEvent, Array($ID, $arFields));

		return $ID;
	}

	function Update($ID, $arFields, $bDateUpdate = true)
	{
		global $DB;

		$ID = IntVal($ID);

		$arFields1 = array();
		foreach ($arFields as $key => $value)
		{
			if (substr($key, 0, 1)=="=")
			{
				$arFields1[substr($key, 1)] = $value;
				unset($arFields[$key]);
			}
		}

		if (!CSaleOrder::CheckFields("UPDATE", $arFields))
			return false;

		$db_events = GetModuleEvents("sale", "OnBeforeOrderUpdate");
		while ($arEvent = $db_events->Fetch())
			if (ExecuteModuleEventEx($arEvent, Array($ID, &$arFields))===false)
				return false;

		$strUpdate = $DB->PrepareUpdate("b_sale_order", $arFields);

		foreach ($arFields1 as $key => $value)
		{
			if (strlen($strUpdate)>0) $strUpdate .= ", ";
			$strUpdate .= $key."=".$value." ";
		}

		$strSql =
			"UPDATE b_sale_order SET ".
			"	".$strUpdate." ";
		if($bDateUpdate)
			$strSql .=	",	DATE_UPDATE = ".$DB->GetNowFunction()." ";
		$strSql .=	"WHERE ID = ".$ID." ";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		unset($GLOBALS["SALE_ORDER"]["SALE_ORDER_CACHE_".$ID]);

		$events = GetModuleEvents("sale", "OnOrderUpdate");
		while ($arEvent = $events->Fetch())
			ExecuteModuleEventEx($arEvent, Array($ID, $arFields));

		return $ID;
	}

	function PrepareGetListArray($key, &$arFields, &$arPropIDsTmp)
	{
		$propIDTmp = false;
		if (StrPos($key, "PROPERTY_ID_") === 0)
			$propIDTmp = IntVal(substr($key, StrLen("PROPERTY_ID_")));
		elseif (StrPos($key, "PROPERTY_NAME_") === 0)
			$propIDTmp = IntVal(substr($key, StrLen("PROPERTY_NAME_")));
		elseif (StrPos($key, "PROPERTY_VALUE_") === 0)
			$propIDTmp = IntVal(substr($key, StrLen("PROPERTY_VALUE_")));
		elseif (StrPos($key, "PROPERTY_CODE_") === 0)
			$propIDTmp = IntVal(substr($key, StrLen("PROPERTY_CODE_")));
		elseif (StrPos($key, "PROPERTY_VAL_BY_CODE_") === 0)
			$propIDTmp = trim(substr($key, StrLen("PROPERTY_VAL_BY_CODE_")));

		if (strlen($propIDTmp) > 0 || $propIDTmp > 0)
		{
			if (!in_array($propIDTmp, $arPropIDsTmp))
			{
				$arPropIDsTmp[] = $propIDTmp;

				$arFields["PROPERTY_ID_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".ORDER_PROPS_ID = ".$propIDTmp." AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
				$arFields["PROPERTY_ORDER_PROPS_ID_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".ORDER_PROPS_ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".ORDER_PROPS_ID = ".$propIDTmp." AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
				$arFields["PROPERTY_NAME_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".ORDER_PROPS_ID = ".$propIDTmp." AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
				$arFields["PROPERTY_VALUE_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".VALUE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".ORDER_PROPS_ID = ".$propIDTmp." AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
				$arFields["PROPERTY_CODE_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".CODE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".ORDER_PROPS_ID = ".$propIDTmp." AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
				$arFields["PROPERTY_VAL_BY_CODE_".$propIDTmp] = array("FIELD" => "SP_".$propIDTmp.".VALUE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP_".$propIDTmp." ON (SP_".$propIDTmp.".CODE = '".$propIDTmp."' AND O.ID = SP_".$propIDTmp.".ORDER_ID)");
			}
		}
	}

	function GetList($arOrder = Array("ID"=>"DESC"), $arFilter = Array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		if (array_key_exists("DATE_FROM", $arFilter))
		{
			$val = $arFilter["DATE_FROM"];
			unset($arFilter["DATE_FROM"]);
			$arFilter[">=DATE_INSERT"] = $val;
		}
		if (array_key_exists("DATE_TO", $arFilter))
		{
			$val = $arFilter["DATE_TO"];
			unset($arFilter["DATE_TO"]);
			$arFilter["<=DATE_INSERT"] = $val;
		}
		
		if (array_key_exists("DATE_UPDATE_FROM", $arFilter))
		{
			$val = $arFilter["DATE_UPDATE_FROM"];
			unset($arFilter["DATE_UPDATE_FROM"]);
			$arFilter[">=DATE_UPDATE"] = $val;
		}
		if (array_key_exists("DATE_UPDATE_TO", $arFilter))
		{
			$val = $arFilter["DATE_UPDATE_TO"];
			unset($arFilter["DATE_UPDATE_TO"]);
			$arFilter["<=DATE_UPDATE"] = $val;
		}

		if (array_key_exists("DATE_STATUS_FROM", $arFilter))
		{
			$val = $arFilter["DATE_STATUS_FROM"];
			unset($arFilter["DATE_STATUS_FROM"]);
			$arFilter[">=DATE_STATUS"] = $val;
		}
		if (array_key_exists("DATE_STATUS_TO", $arFilter))
		{
			$val = $arFilter["DATE_STATUS_TO"];
			unset($arFilter["DATE_STATUS_TO"]);
			$arFilter["<=DATE_STATUS"] = $val;
		}
		if (array_key_exists("DATE_PAYED_FROM", $arFilter))
		{
			$val = $arFilter["DATE_PAYED_FROM"];
			unset($arFilter["DATE_PAYED_FROM"]);
			$arFilter[">=DATE_PAYED"] = $val;
		}
		if (array_key_exists("DATE_PAYED_TO", $arFilter))
		{
			$val = $arFilter["DATE_PAYED_TO"];
			unset($arFilter["DATE_PAYED_TO"]);
			$arFilter["<=DATE_PAYED"] = $val;
		}

		if (count($arSelectFields) <= 0)
			$arSelectFields = array("ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED", "EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID", "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE", "SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION", "ADDITIONAL_INFO", "PS_STATUS", "PS_STATUS_CODE", "PS_STATUS_DESCRIPTION", "PS_STATUS_MESSAGE", "PS_SUM", "PS_CURRENCY", "PS_RESPONSE_DATE", "COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID", "RECOUNT_FLAG", "USER_LOGIN", "USER_NAME", "USER_LAST_NAME", "USER_EMAIL", "DELIVERY_DOC_NUM", "DELIVERY_DOC_DATE");
		elseif (in_array("*", $arSelectFields))
			$arSelectFields = array("ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED", "EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID", "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE", "SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION", "ADDITIONAL_INFO", "PS_STATUS", "PS_STATUS_CODE", "PS_STATUS_DESCRIPTION", "PS_STATUS_MESSAGE", "PS_SUM", "PS_CURRENCY", "PS_RESPONSE_DATE", "COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID", "RECOUNT_FLAG", "USER_LOGIN", "USER_NAME", "USER_LAST_NAME", "USER_EMAIL", "DELIVERY_DOC_NUM", "DELIVERY_DOC_DATE");

		$maxLock = IntVal(COption::GetOptionString("sale", "MAX_LOCK_TIME", "60"));
		if(is_object($GLOBALS["USER"]))
			$userID = IntVal($GLOBALS["USER"]->GetID());
		else
			$userID = 0;

		// FIELDS -->
		$arFields = array(
			"ID" => array("FIELD" => "O.ID", "TYPE" => "int"),
			"LID" => array("FIELD" => "O.LID", "TYPE" => "string"),
			"PERSON_TYPE_ID" => array("FIELD" => "O.PERSON_TYPE_ID", "TYPE" => "int"),
			"PAYED" => array("FIELD" => "O.PAYED", "TYPE" => "char"),
			"DATE_PAYED" => array("FIELD" => "O.DATE_PAYED", "TYPE" => "datetime"),
			"EMP_PAYED_ID" => array("FIELD" => "O.EMP_PAYED_ID", "TYPE" => "int"),
			"CANCELED" => array("FIELD" => "O.CANCELED", "TYPE" => "char"),
			"DATE_CANCELED" => array("FIELD" => "O.DATE_CANCELED", "TYPE" => "datetime"),
			"EMP_CANCELED_ID" => array("FIELD" => "O.EMP_CANCELED_ID", "TYPE" => "int"),
			"REASON_CANCELED" => array("FIELD" => "O.REASON_CANCELED", "TYPE" => "string"),
			"STATUS_ID" => array("FIELD" => "O.STATUS_ID", "TYPE" => "char"),
			"DATE_STATUS" => array("FIELD" => "O.DATE_STATUS", "TYPE" => "datetime"),
			"PAY_VOUCHER_NUM" => array("FIELD" => "O.PAY_VOUCHER_NUM", "TYPE" => "string"),
			"PAY_VOUCHER_DATE" => array("FIELD" => "O.PAY_VOUCHER_DATE", "TYPE" => "date"),
			"EMP_STATUS_ID" => array("FIELD" => "O.EMP_STATUS_ID", "TYPE" => "int"),
			"PRICE_DELIVERY" => array("FIELD" => "O.PRICE_DELIVERY", "TYPE" => "double"),
			"ALLOW_DELIVERY" => array("FIELD" => "O.ALLOW_DELIVERY", "TYPE" => "char"),
			"DATE_ALLOW_DELIVERY" => array("FIELD" => "O.DATE_ALLOW_DELIVERY", "TYPE" => "datetime"),
			"EMP_ALLOW_DELIVERY_ID" => array("FIELD" => "O.EMP_ALLOW_DELIVERY_ID", "TYPE" => "int"),
			"PRICE" => array("FIELD" => "O.PRICE", "TYPE" => "double"),
			"CURRENCY" => array("FIELD" => "O.CURRENCY", "TYPE" => "string"),
			"DISCOUNT_VALUE" => array("FIELD" => "O.DISCOUNT_VALUE", "TYPE" => "double"),
			"SUM_PAID" => array("FIELD" => "O.SUM_PAID", "TYPE" => "double"),
			"USER_ID" => array("FIELD" => "O.USER_ID", "TYPE" => "int"),
			"PAY_SYSTEM_ID" => array("FIELD" => "O.PAY_SYSTEM_ID", "TYPE" => "int"),
			"DELIVERY_ID" => array("FIELD" => "O.DELIVERY_ID", "TYPE" => "string"),
			"DATE_INSERT" => array("FIELD" => "O.DATE_INSERT", "TYPE" => "datetime"),
			"DATE_INSERT_FORMAT" => array("FIELD" => "O.DATE_INSERT", "TYPE" => "datetime"),
			"DATE_UPDATE" => array("FIELD" => "O.DATE_UPDATE", "TYPE" => "datetime"),
			"USER_DESCRIPTION" => array("FIELD" => "O.USER_DESCRIPTION", "TYPE" => "string"),
			"ADDITIONAL_INFO" => array("FIELD" => "O.ADDITIONAL_INFO", "TYPE" => "string"),
			"PS_STATUS" => array("FIELD" => "O.PS_STATUS", "TYPE" => "char"),
			"PS_STATUS_CODE" => array("FIELD" => "O.PS_STATUS_CODE", "TYPE" => "string"),
			"PS_STATUS_DESCRIPTION" => array("FIELD" => "O.PS_STATUS_DESCRIPTION", "TYPE" => "string"),
			"PS_STATUS_MESSAGE" => array("FIELD" => "O.PS_STATUS_MESSAGE", "TYPE" => "string"),
			"PS_SUM" => array("FIELD" => "O.PS_SUM", "TYPE" => "double"),
			"PS_CURRENCY" => array("FIELD" => "O.PS_CURRENCY", "TYPE" => "string"),
			"PS_RESPONSE_DATE" => array("FIELD" => "O.PS_RESPONSE_DATE", "TYPE" => "datetime"),
			"COMMENTS" => array("FIELD" => "O.COMMENTS", "TYPE" => "string"),
			"TAX_VALUE" => array("FIELD" => "O.TAX_VALUE", "TYPE" => "double"),
			"STAT_GID" => array("FIELD" => "O.STAT_GID", "TYPE" => "string"),
			"RECURRING_ID" => array("FIELD" => "O.RECURRING_ID", "TYPE" => "int"),
			"RECOUNT_FLAG" => array("FIELD" => "O.RECOUNT_FLAG", "TYPE" => "char"),
			"AFFILIATE_ID" => array("FIELD" => "O.AFFILIATE_ID", "TYPE" => "int"),
			"LOCKED_BY" => array("FIELD" => "O.LOCKED_BY", "TYPE" => "int"),
			"LOCK_STATUS" => array("FIELD" => "if(DATE_LOCK is null, 'green', if(DATE_ADD(DATE_LOCK, interval ".$maxLock." MINUTE)<now(), 'green', if(LOCKED_BY=".$userID.", 'yellow', 'red')))", "TYPE" => "string"),
			"LOCK_USER_NAME" => array("FIELD" => "concat('(',UL.LOGIN,') ',UL.NAME,' ',UL.LAST_NAME)", "FROM" => "LEFT JOIN b_user UL ON (O.LOCKED_BY = UL.ID)", "TYPE" => "string"),
			"DELIVERY_DOC_NUM" => array("FIELD" => "O.DELIVERY_DOC_NUM", "TYPE" => "string"),
			"DELIVERY_DOC_DATE" => array("FIELD" => "O.DELIVERY_DOC_DATE", "TYPE" => "date"),


			"USER_LOGIN" => array("FIELD" => "U.LOGIN", "TYPE" => "string", "FROM" => "INNER JOIN b_user U ON (O.USER_ID = U.ID)"),
			"USER_NAME" => array("FIELD" => "U.NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_user U ON (O.USER_ID = U.ID)"),
			"USER_LAST_NAME" => array("FIELD" => "U.LAST_NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_user U ON (O.USER_ID = U.ID)"),
			"USER_EMAIL" => array("FIELD" => "U.EMAIL", "TYPE" => "string", "FROM" => "INNER JOIN b_user U ON (O.USER_ID = U.ID)"),
			"BUYER" => array("FIELD" => "U.LOGIN,U.NAME,U.LAST_NAME,U.EMAIL,U.ID", "WHERE_ONLY" => "Y", "TYPE" => "string", "FROM" => "INNER JOIN b_user U ON (O.USER_ID = U.ID)"),
			"BASKET_ID" => array("FIELD" => "B.ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_PRODUCT_ID" => array("FIELD" => "B.PRODUCT_ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_PRODUCT_XML_ID" => array("FIELD" => "B.PRODUCT_XML_ID", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_MODULE" => array("FIELD" => "B.MODULE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_NAME" => array("FIELD" => "B.NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_QUANTITY" => array("FIELD" => "B.QUANTITY", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_PRICE" => array("FIELD" => "B.PRICE", "TYPE" => "double", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_CURRENCY" => array("FIELD" => "B.CURRENCY", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_DISCOUNT_PRICE" => array("FIELD" => "B.DISCOUNT_PRICE", "TYPE" => "double", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_DISCOUNT_NAME" => array("FIELD" => "B.DISCOUNT_NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_DISCOUNT_VALUE" => array("FIELD" => "B.DISCOUNT_VALUE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),
			"BASKET_DISCOUNT_COUPON" => array("FIELD" => "B.DISCOUNT_COUPON", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_basket B ON (O.ID = B.ORDER_ID)"),

			"STATUS_PERMS_GROUP_ID" => array("FIELD" => "SS2G.GROUP_ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_VIEW" => array("FIELD" => "SS2G.PERM_VIEW", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_CANCEL" => array("FIELD" => "SS2G.PERM_CANCEL", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_DELIVERY" => array("FIELD" => "SS2G.PERM_DELIVERY", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_PAYMENT" => array("FIELD" => "SS2G.PERM_PAYMENT", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_STATUS" => array("FIELD" => "SS2G.PERM_STATUS", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_STATUS_FROM" => array("FIELD" => "SS2G.PERM_STATUS_FROM", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_UPDATE" => array("FIELD" => "SS2G.PERM_UPDATE", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),
			"STATUS_PERMS_PERM_DELETE" => array("FIELD" => "SS2G.PERM_DELETE", "TYPE" => "char", "FROM" => "INNER JOIN b_sale_status2group SS2G ON (O.STATUS_ID = SS2G.STATUS_ID)"),

			"PROPERTY_ID" => array("FIELD" => "SP.ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
			"PROPERTY_ORDER_PROPS_ID" => array("FIELD" => "SP.ORDER_PROPS_ID", "TYPE" => "int", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
			"PROPERTY_NAME" => array("FIELD" => "SP.NAME", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
			"PROPERTY_VALUE" => array("FIELD" => "SP.VALUE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
			"PROPERTY_CODE" => array("FIELD" => "SP.CODE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
			"PROPERTY_VAL_BY_CODE" => array("FIELD" => "SP.VALUE", "TYPE" => "string", "FROM" => "INNER JOIN b_sale_order_props_value SP ON (O.ID = SP.ORDER_ID)"),
		);
		// <-- FIELDS

		$arPropIDsTmp = array();
		foreach ($arOrder as $key => $value)
			CSaleOrder::PrepareGetListArray($key, $arFields, $arPropIDsTmp);

		foreach ($arFilter as $key => $value)
		{
			$arKeyTmp = CSaleOrder::GetFilterOperation($key);
			$key = $arKeyTmp["FIELD"];

			CSaleOrder::PrepareGetListArray($key, $arFields, $arPropIDsTmp);
		}

		if ($arGroupBy)
			foreach ($arGroupBy as $key => $value)
				CSaleOrder::PrepareGetListArray($key, $arFields, $arPropIDsTmp);

		foreach ($arSelectFields as $key => $value)
			CSaleOrder::PrepareGetListArray($key, $arFields, $arPropIDsTmp);

		$arSqls = CSaleOrder::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);

		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if (is_array($arGroupBy) && count($arGroupBy)==0)
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_sale_order O ".
				"	".$arSqls["FROM"]." ";
			if (strlen($arSqls["WHERE"]) > 0)
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if (strlen($arSqls["GROUPBY"]) > 0)
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			//echo "!1!=".htmlspecialchars($strSql)."<br>";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return False;
		}

		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
			"FROM b_sale_order O ".
			"	".$arSqls["FROM"]." ";
		if (strlen($arSqls["WHERE"]) > 0)
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if (strlen($arSqls["GROUPBY"]) > 0)
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if (strlen($arSqls["ORDERBY"]) > 0)
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";

		if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
				"FROM b_sale_order O ".
				"	".$arSqls["FROM"]." ";
			if (strlen($arSqls["WHERE"]) > 0)
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if (strlen($arSqls["GROUPBY"]) > 0)
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			//echo "!2.1!=".htmlspecialchars($strSql_tmp)."<br>";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if (strlen($arSqls["GROUPBY"]) <= 0)
			{
				if ($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			//echo "!2.2!=".htmlspecialchars($strSql)."<br>";

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if (is_array($arNavStartParams) && IntVal($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".$arNavStartParams["nTopCount"];

			//echo "!3!=".htmlspecialchars($strSql)."<br>";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}

		return $dbRes;
	}

	function GetLockStatus($ID, &$lockedBY, &$dateLock)
	{
		global $DB;

		$ID = IntVal($ID);
		if ($ID <= 0)
			return False;

		$maxLock = IntVal(COption::GetOptionString("sale", "MAX_LOCK_TIME", "60"));
		$userID = IntVal($GLOBALS["USER"]->GetID());

		$strSql =
			"SELECT LOCKED_BY, ".
			"	".$DB->DateToCharFunction("DATE_LOCK")." as DATE_LOCK, ".
			"	if(DATE_LOCK is null, 'green',  ".
			"		if(DATE_ADD(DATE_LOCK, interval ".$maxLock." MINUTE)<now(), 'green', ".
			"			if(LOCKED_BY=".$userID.", 'yellow', 'red'))) as LOCK_STATUS ".
			"FROM b_sale_order ".
			"WHERE ID = ".$ID." ";
		$dbRes = $DB->Query($strSql);
		$arRes = $dbRes->Fetch();

		$lockedBY = $arRes["LOCKED_BY"];
		$dateLock = $arRes["DATE_LOCK"];

		return $arRes["LOCK_STATUS"];
	}
}
?>