<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
include(GetLangFileName(dirname(__FILE__)."/", "/qiwi.php"));

if(function_exists("file_get_contents"))
	$DATA = file_get_contents("php://input");
elseif(isset($GLOBALS["HTTP_RAW_POST_DATA"]))
	$DATA = &$GLOBALS["HTTP_RAW_POST_DATA"];
else
	$DATA = false;

$shopID = CSalePaySystemAction::GetParamValue("SHOP_ID");
$password = CSalePaySystemAction::GetParamValue("SHOP_PASS");

$result = "";
if(strlen($DATA) > 0)
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/xml.php");
	$objXML = new CDataXML();
	$objXML->LoadString($DATA);
	$arResult = $objXML->GetArray();

	if(!empty($arResult))
	{
		$method = $arResult["Envelope"]["#"]["Body"][0]["#"]["updateBill"];
		
		if(!empty($method))
		{
			$orderID = $method[0]["#"]["txn"][0]["#"];
			$status = $method[0]["#"]["status"][0]["#"];
			$result = '<ns2:updateBillResponse xmlns:ns2="http://client.ishop.mw.ru/">';
			if($method[0]["#"]["login"][0]["#"] != $shopID || $method[0]["#"]["password"][0]["#"] != ToUpper(md5($orderID.ToUpper(md5($password)))))
			{
				$result .= '<updateBillResult>150</updateBillResult>';//wrong login/password
			}
			else
			{
				if($arOrder = CSaleOrder::GetByID($orderID))
				{
					$strPS_STATUS_MESSAGE = GetMessage("CLASS_STATUS_".$status);

					$arFields = array(
							"PS_STATUS" => ($status == 60 ? "Y" : "N"),
							"PS_STATUS_CODE" => $status,
							"PS_STATUS_DESCRIPTION" => "",
							"PS_STATUS_MESSAGE" => $strPS_STATUS_MESSAGE,
							"PS_RESPONSE_DATE" => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG))),
						);

					// You can comment this code if you want PAYED flag not to be set automatically
					if(IntVal($status) == 60)
						CSaleOrder::PayOrder($arOrder["ID"], "Y", true, true);
					CSaleOrder::Update($arOrder["ID"], $arFields);
					
					$result .= '<updateBillResult>0</updateBillResult>';
				}
				else
				{
					$result .= '<updateBillResult>210</updateBillResult>';//order not found
				}
			}
			$result .= '</ns2:updateBillResponse>';
		}
	}
}

$bDesignMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas() && is_object($GLOBALS["USER"]) && $GLOBALS["USER"]->IsAdmin();
if(!$bDesignMode)
{
	$content = '<?xml version="1.0" encoding="'.LANG_CHARSET.'"?>
	<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body>'.$result.'</soap:Body></soap:Envelope>';

	$APPLICATION->RestartBuffer();
	header("Pragma: no-cache");
	header("Content-type: application/soap+xml; charset=".LANG_CHARSET);
	header("Content-Length: ".strlen($content));
	echo $content;
	die();
}
?>