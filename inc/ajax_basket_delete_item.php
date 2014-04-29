<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
// echo '<pre>';
// print_R($_POST);
// echo '</pre>';
if(isset($_REQUEST['control_name']) and ($_REQUEST['control_name']!=''))
{
	$id = str_replace('DELETE_','',$_REQUEST['control_name']);
	CModule::IncludeModule("sale");
	CSaleBasket::Delete($id);
	basket_recounter();
};
?>