<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
// echo '<pre>';
// print_R($_POST);
// echo '</pre>';
if(isset($_REQUEST['control_name']) and ($_REQUEST['control_name']!=''))
{
	$id = str_replace('QUANTITY_','',$_REQUEST['control_name']);
	CModule::IncludeModule("sale");
	if(is_numeric($id))
	{
		$arFields = array("QUANTITY" => $_REQUEST['quantity']);
		CSaleBasket::Update($id, $arFields);
		basket_recounter();
	};
};
?>