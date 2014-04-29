<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?

$opros_iblock = 6;

$arr_elem = array();

$arr_order= array('SORT'=>'ASC');
$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_ip', 'PROPERTY_question');
$arr_filter=array('IBLOCK_ID'=>$opros_iblock, 'ACTIVE'=>'Y');
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);

if($one=$res->GetNext())
{
	$arr_elem[] = $one;
};

$arr_elem = $arr_elem[0];


$SHOW_VOTE = true;
$arr_ip = array();
if($arr_elem['PROPERTY_IP_VALUE']!='')
{
	$arr_ip = unserialize(htmlspecialchars_decode($arr_elem['PROPERTY_IP_VALUE']));
};

// echo '<pre>';
// print_R($arr_ip);
// echo '</pre>';

if(in_array($_SERVER['REMOTE_ADDR'], $arr_ip))
{
	$SHOW_VOTE = false;
};

?>
<h3 style="padding-bottom: 5px"><?=$arr_elem['NAME']?></h3>
<div id="opros_target">
	<?if($SHOW_VOTE):?>
		<form name="orpos" id="opros">
			<ul>
				<?foreach($arr_elem['PROPERTY_QUESTION_VALUE'] as $key=>$question):?>
					<li><input type="radio" <?=($key==0)?'checked="checked"':'';?> name="answer" id="q<?=$key?>" value="<?=$key?>" /><label for="q<?=$key?>"><?=$question?></label></li>
				<?endforeach;?>
			</ul>
			<input type="hidden" id="razbor" name="vote" value="y" />
			<input type="hidden" id="opros_id" name="id" value="<?=$arr_elem['ID']?>" />
		</form>
		<a href="#" id="post_result" class="formSender1">Отправить</a>
		<a href="#" id="show_result" class="formSender1">Показать результаты</a>
	<?endif;?>
</div>
<div style="clear:both;"></div>
<script type="text/javascript">
	$(document).ready(function(){
		
		<?if(!$SHOW_VOTE):?>
			$("#opros_target").load("/inc/ajax_opros.php", {id:<?=$arr_elem['ID']?>}, function(resp){
				//console.log(resp);
			});
		<?endif;?>
		
		$("#post_result").live("click", function(){
			//console.log($("#opros").serializeArray());
			$("#opros_target").load("/inc/ajax_opros.php", $("#opros").serializeArray(), function(resp){
				//console.log(resp);
			});
			return false;
		});
		
		$("#show_result").live("click", function(){
			//console.log($("#opros_id").attr("value"));
			$("#opros_target").load("/inc/ajax_opros.php", {id:$("#opros_id").attr("value")}, function(resp){
				//console.log(resp);
			});
			return false;
		});
	});
</script>