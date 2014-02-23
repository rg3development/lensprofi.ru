<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arWizardDescription = Array(
	"NAME" => GetMessage("WD_TITLE"), 
	"DESCRIPTION" => GetMessage("WD_TITLE_DESCR"), 
	"ICON" => "images/icon/sms4b.gif",
	"COPYRIGHT" => "Rarus",
	"VERSION" => "1.0.0",
	"DEPENDENCIES" => Array( 
		"main" => "6.5.0",
	),
	"STEPS" => Array("Step0", "step0_1", "Step1", "Step2_1", "Step3_1", "Step4_1", "Step2_2", "Step3_2", "Step4_2", "Install1", "Install2", "FinalStep", "CancelStep"),
	"TEMPLATES" => Array("SCRIPT" => "wizard_template.php", "CLASS" => "DemoSiteTemplate"),
);

?>