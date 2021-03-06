<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
if (strlen($arParams["PATH_TO_BASKET"]) <= 0)
	$arParams["PATH_TO_BASKET"] = "/personal/basket.php";

$arParams["PATH_TO_PERSONAL"] = Trim($arParams["PATH_TO_PERSONAL"]);
if (strlen($arParams["PATH_TO_PERSONAL"]) <= 0)
	$arParams["PATH_TO_PERSONAL"] = "/personal/";
	
$arParams["SHOW_PERSONAL_LINK"] = ($arParams["SHOW_PERSONAL_LINK"] == "N" ? "N" : "Y" );
	
if(!function_exists("BasketNumberWordEndings"))
{
	function BasketNumberWordEndings($num, $lang = false, $arEnds = false)
	{
		if ($lang===false)
			$lang = LANGUAGE_ID;

		if ($arEnds===false)
			$arEnds = array(GetMessage("TSB1_WORD_OBNOVL_END1"), GetMessage("TSB1_WORD_OBNOVL_END2"), GetMessage("TSB1_WORD_OBNOVL_END3"), GetMessage("TSB1_WORD_OBNOVL_END4"));

		if ($lang=="ru")
		{
			if (strlen($num)>1 && substr($num, strlen($num)-2, 1)=="1")
			{
				return $arEnds[0];
			}
			else
			{
				$c = IntVal(substr($num, strlen($num)-1, 1));
				if ($c==0 || ($c>=5 && $c<=9))
					return $arEnds[1];
				elseif ($c==1)
					return $arEnds[2];
				else
					return $arEnds[3];
			}
		}
		elseif ($lang=="en")
		{
			if (IntVal($num)>1)
			{
				return "s";
			}
			return "";
		}
		else
		{
			return "";
		}
	}
}
	
if(isset($_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID]))
{
	$num_products = $_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID];
}
else
{
	if(!CModule::IncludeModule("sale"))
	{
		ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
		return;
	}
	$fUserID = CSaleBasket::GetBasketUserID(True);
	$fUserID = IntVal($fUserID);
	$num_products = 0;
	if ($fUserID > 0)
	{
		$num_products = CSaleBasket::GetList(
			array(),
			array("FUSER_ID" => $fUserID, "LID" => SITE_ID, "ORDER_ID" => "NULL", "CAN_BUY" => "Y", "DELAY" => "N"),
			array()
		);
	}
	$_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID] = intval($num_products);
}

$arResult["NUM_PRODUCTS"] = $num_products;
if ($num_products>0)
	$arResult["PRODUCTS"] = str_replace("#END#", BasketNumberWordEndings($num_products), str_replace("#NUM#", $num_products, GetMessage("TSB1_BASKET_TEXT")));
else
	$arResult["ERROR_MESSAGE"] = GetMessage("TSB1_EMPTY");
	
$this->IncludeComponentTemplate();
?>