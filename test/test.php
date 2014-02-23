<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?

if(!CModule::IncludeModule('rarus.sms4b')) echo 'fallure';

//$num_to1 ="89534286348";
$num_to = '89207452699';
$SMS  = 'работает';

$SMS4B = new Csms4b();
if($SMS4B->SendSMS($SMS, $num_to)) {echo 'ok <br />';};//
?>