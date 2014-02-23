<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if(!isset($_REQUEST['toric_id']) or $_REQUEST['toric_id']=='') die("toric id non exists");
	// item id => toric_book_id
	
	$arr_comparision = array(
							/*292 => 5, //PURE VISION TORIC (6 линз)*/
							/*293 => 6, //SOFLENS TORIC (6 линз)*/
							/*294 => 7, //PROCLEAR TORIC (3 линзы)*/
							/*295 => 8, //PROCLEAR TORIC (6 линз)*/
							/*296 => 9, //BIOMEDICS TORIC (6 линз)*/
							/*297 => 10, // OKVISION PRIMA BIO TORIC (6 линз)*/
							/*287 => 0, // 1-DAY ACUVUE for ASTIGMATISM 30 линз)*/
							/*288 => 1, //1-DAY ACUVUE MOIST for ASTIGMATISM (30 линз) NEW*/
							/*289 => 2, //ACUVUE OASYS for ASTIGMATISM (6 линз)*/
							/*290 => 3, //AIR OPTIX for ASTIGMATISM (3 линзы)*/
							/*291 => 4 //FOCUS DAILIES TORIC (30 линз)*/
							);
	
	$arr_carusel_elems=array();
	$arr_order= array('SORT'=>'ASC');
	$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_toric_book');
	$arr_filter=array('IBLOCK_ID'=>4, 'ACTIVE'=>'Y', 'SECTION_ID'=>2 );
	$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
	$i=0;
	while($one=$res->GetNext())
	{
		$arr_comparision[$one['ID']] = $one['PROPERTY_TORIC_BOOK_VALUE'];
	};
		
	
	if(!isset($arr_comparision[$_REQUEST['toric_id']]) or $arr_comparision[$_REQUEST['toric_id']]=='')  die("toric id non exists or empty or wrong");
	
	require($_SERVER['DOCUMENT_ROOT'].'/toric_book'.$arr_comparision[$_REQUEST['toric_id']].'.php');
	$arr_source = unserialize($arr_ser);
	$radius = $arr_source['BC']
?>

<?
	function cyl_not_empty($arr_cyl)
	{
		$res = false;
		foreach($arr_cyl as $arr_axis)
		{
			foreach($arr_axis as $one_val)
			{
				if($one_val!=0) $res = true;
			}
		};
		return $res;
	};
	
	function axis_not_empty($arr_axis)
	{
		$res = false;		
		foreach($arr_axis as $one_val)
		{
			if($one_val!=0) $res = true;
		}
		return $res;
	};
		
	$arr_eyes = array('l', 'r');
	
	foreach($arr_eyes as $eye):?>
		<td>
			<h3><?echo ($eye=='l')?'Левый':'Правый';?> глаз</h3>
			<? // ниже везде кроме name будут ПЕРЕПУТАНЫ СИЛА и РАДИУС?>
			<table>
				<tr>
					<td>Радиус кривизны (BC):</td>
					<td>
						<select id="force_select_<?=$eye?>"  size="1" class="w60"  name="radius_<?=$eye?>">
								<option value="<?=$radius?>"><?=$radius ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Сила цилиндра (CYL):</td>
					<td>
						<?
						$first_non_empty = '';
						if(isset($_REQUEST['cyl_'.$eye]) and $_REQUEST['cyl_'.$eye]!='')
						{
							$first_non_empty = $_REQUEST['cyl_'.$eye];
						};
						?>
						<select id="cyl_<?=$eye?>"  size="1" class="w60" onChange="character_change();"  name="cyl_<?=$eye?>">
							<?foreach($arr_source['cylinders'] as $cyl_val=>$cyl_array):?>
								<?if(cyl_not_empty($cyl_array)):?>
									<option <?=($first_non_empty==$cyl_val)?'selected="selected"':''?> value="<?=$cyl_val?>"><?=$cyl_val?></option>
									<?if($first_non_empty=='') $first_non_empty=$cyl_val;?>
								<?endif;?>
							<?endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Ось (AX):</td>
					<td>
						<? 
						$non_empty_axis = '';
						if(axis_not_empty($arr_source['cylinders'][$first_non_empty][$_REQUEST['axis_'.$eye]]))
						{
							$non_empty_axis = $_REQUEST['axis_'.$eye];
						};
						?>
						<select id="axis_<?=$eye?>"  size="1" class="w60" onChange="character_change();"  name="axis_<?=$eye?>">
							<?foreach($arr_source['cylinders'][$first_non_empty] as $axis_val=>$arr_forces):?>
								<?if(axis_not_empty($arr_forces)):?>
									<option <?=($non_empty_axis==$axis_val)?'selected="selected"':''?> value="<?=$axis_val?>"><?=$axis_val?></option>
									<?if($non_empty_axis=='') $non_empty_axis=$axis_val;?>
								<?endif;?>
							<?endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Оптическая сила (SPH):</td>
					<td>
						<?
						$sel_force = '';
						//****
						$sel_force = -100;
						foreach($arr_source['cylinders'][$first_non_empty][$non_empty_axis] as $force=>$its_exist):
							if($force>$sel_force and $force<=0) $sel_force = $force;
						endforeach;
						//****
						if(isset($arr_source['cylinders'][$first_non_empty][$non_empty_axis][$_REQUEST['force_'.$eye]]))
						{
							$sel_force = $_REQUEST['force_'.$eye];
						};
						?>
						<select id="force_<?=$eye?>"  size="1" class="w60" onChange="character_change();"  name="force_<?=$eye?>">
							<?foreach($arr_source['cylinders'][$first_non_empty][$non_empty_axis] as $force=>$its_exist):?>
									<?if($its_exist==1):?>
										<option <?=($sel_force==$force)?'selected="selected"':''?> value="<?=$force?>"><?=$force?></option>
									<?endif;?>
							<?endforeach;?>
						</select>
						<?
						// CHAK here is BUG explain!!!
						// echo '<pre>';
						// print_R($first_non_empty.'  ');
						// print_R($non_empty_axis);
						// echo '</pre>';
						?>
					</td>
				</tr>
				<tr>
					<td>Количество упаковок:</td>
					<td>
						<select id="quantity_<?=$eye?>" size="1" class="w60" name="count_<?=$eye?>">
							<?for($i=0; $i<=5; $i++):?>
								<option <?if($eye=='l' and $i==1):?>selected="selected"<?endif;?> value="<?=$i?>"><?=$i?></option>
							<?endfor;?>
						</select>
					</td>
				</tr>
			</table>
			<?
			// echo '<pre>';
			// print_R($_REQUEST);
			// echo '</pre>';
			?>
			<input type="hidden" name="toric_id" value="<?=$_REQUEST['toric_id']?>" />
		</td>
	<?endforeach;?>
