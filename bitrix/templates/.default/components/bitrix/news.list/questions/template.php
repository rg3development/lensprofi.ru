<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="block730b">
	<div class="block240">
		<?$APPLICATION->IncludeFile("/inc/quest_consult.php",  Array(), Array("MODE"=>"html"));?>
	</div>
	<div class="block470">
		<h1>Офтальмологический кабинет</h1>
		<span class="srv1">Задать вопрос специалисту</span>
		<dl>
			<?foreach($arResult['ITEMS'] as $key=>$item):?>
				<dt><span><?=$item['NAME']?> <span>[<?=$DB->FormatDate($item['DATE_CREATE'], 'DD.MM.YYYY HH:MI:SS', 'DD.MM HH:MI');?>]</span></span>
				<?=$item['PREVIEW_TEXT']?>
					<div style="text-align: right; padding: 4px 0 0 0">
						<a href="#" style="border-bottom: 1px dashed" onClick="$('.answ<?=$key?>').css('display', 'block'); return false;">Посмотреть ответ</a>
					</div>
				</dt>
				<dd class="answ<?=$key?>"><?=$item['DETAIL_TEXT']?></dd>
			<?endforeach?>
		</dl>
	</div>
	<div style="padding: 10px 100px 0 250px">
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
			<br /><?=$arResult["NAV_STRING"]?>
		<?endif;?>
	</div>
</div>
<?require($_SERVER['DOCUMENT_ROOT'].'/inc/block190.php');?>
<div id="formHolder">
	<?/*
	<div id="formPopup">
		<form id="add_question" name="add_question">
			<img src="/img/pic15c.gif" alt="" class="formRemover1" />
			<p>(Публикация вопросов осуществляется после проверки модератором. Не пропускаются оскорбления, нецензурные выражения, реплики не по теме, риторические вопросы, развернутые комментарии, не содержащие вопроса. Кроме того, вопрос может быть отклонен, если дублирует уже заданные).</p>
			<ul>
				<li><span>Имя:</span><input type="text" name="form[fio]" value="" class="w237" /></li>
				<li><span>E-mail:</span><input name="form[email]" type="text" value="" class="w237" /></li>
				<li><span>Текст вопроса:</span><textarea rows="5" cols="40" class="w136" name="form[question]"></textarea></li>		
				<li><a id="sub_but" href="#"><img src="/img/bt148c.gif" alt="" /></a></li>
			</ul>
		</form>
	</div>
	*/?>
</div>
<script type="text/javascript">
	
	function qform_close(){
		$('#formHolder').css('display','none');
		$('#formHolder').html(""); // clear it
	};
	
	$(document).ready(function(){
		
		// from script.js
		$('.block470 dt').each(function(index) {
			$(this).bind('click',function() {
			$(this).next().css('display','block');
			});
		});
		
		$('.formRemover1').click(function() {
			$('#formHolder').css('display','none');
			return false;							  
		});
		//---
		
		//send data
		$("#sub_but").live("click", function(){
			//console.log($("#add_question").serialize());
			$.post("/inc/add_question.php", $("#add_question").serialize(), function(resp){
				$('#formHolder').html(resp);
				//autoclose
				setTimeout(qform_close, 3000);
			},
			"html");
			return false;
		});
		
		// show
		$('.srv1').live("click", function() {
			$.post("/inc/add_question.php", $("#add_question").serializeArray(), function(resp){
				//$("#formPopup").html(resp);
				$('#formHolder').html(resp);
				$('#formHolder').css('display','block');
			},
			"html");
			return false;	
		});
		
		//close
		$('#formPopup .formRemover1').live("click", function() {
			qform_close();
			return false;	
		});
		
	});		
</script>
<?
// echo '<pre>';
// print_R($arResult);
// echo '</pre>';
?>

