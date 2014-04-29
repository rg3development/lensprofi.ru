<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule("sale");

if(isset($_REQUEST['prof_id']))
{
		// update profile
		$arFields = array(
							
						);
		if (!CSaleOrderUserProps::Delete($_REQUEST['prof_id']))
		{
			die('prof not deleted!');
		};
		
		global $USER;
		
		//выводим в новом порядке
		$db_sales = CSaleOrderUserProps::GetList(
			array("DATE_UPDATE" => "DESC"),
			array("USER_ID" => $USER->GetID())
		);
		while($one_prof = $db_sales->GetNext())
		{
			$arr_profiles[$one_prof['ID']] = $one_prof;
		};

		$prof_props = array();

		$db_propVals = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), Array('USER_ID'=>$arResult['ID'])); //"USER_PROPS_ID"=>$ID
		while ($arr_prop_vals = $db_propVals->Fetch())
		{
		   $prof_props[$arr_prop_vals['USER_PROPS_ID']][$arr_prop_vals['CODE']] = $arr_prop_vals;
		};


		// echo '<pre>';
		// print_R($arr_profiles);
		// echo '</pre>';
		?>

		<?if(count($arr_profiles)>0):?>
			<?foreach($arr_profiles as $one_prof):?>
				<?
						$addr='';
						//$prof_props[$one_prof['ID']]['address']['VALUE']
						$addr = $one_prof['NAME'];
						if(mb_strlen($one_prof['NAME'])>20)
						{
							$addr = mb_substr($addr, 0, 20).'...';
						}
				?>
				<option value="<?=$one_prof['ID']?>"><?=$addr?></option>
			<?endforeach;?>
		<?else:?>
			<option value=""></option>
		<?endif;?>

<?
};
?>
