<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
CModule::IncludeModule("iblock");
$cat_iblock = 4;

$arr_types = array();

$arr_order= array('SORT'=>'ASC');
$arr_filter = Array('IBLOCK_ID'=>$cat_iblock, 'GLOBAL_ACTIVE'=>'Y');  
$arr_select=array("ID", "IBLOCK_ID", "CODE", "DESCRIPTION", "IBLOCK_SECTION_ID", "NAME", "ACTIVE", "PICTURE", "UF_*");
$db_list = CIBlockSection::GetList($arr_order, $arr_filter, false, $arr_select);
while($one_sect = $db_list->GetNext())
{
	if($one_sect['ID']!=7) //HARDCODE
	{
		$arr_types[] = $one_sect;
	};		
};

// echo '<pre>';
// print_R($arr_types);
// echo '</pre>';

$arr_producers=array();
$arr_producers_invert=array();
$arr_periods = array();
$arr_goods = array();

$arr_order= array('id'=>'ASC');
$arr_select=array('ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'NAME', 'PROPERTY_PRODUCER', 'PROPERTY_BRAND', 'PROPERTY_USETIME');
$arr_filter=array('IBLOCK_ID'=>$cat_iblock, 'ACTIVE'=>'Y', '!SECTION_ID'=>'7');
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
$i=0;
while($one=$res->GetNext())
{
	
	$arr_goods[] = $one;

	/*
	if(!in_array(trim($one['PROPERTY_PRODUCER_VALUE']),$arr_producers) and $one['PROPERTY_PRODUCER_VALUE']!='')
	{
		$arr_producers[$one['ID']] = trim($one['PROPERTY_PRODUCER_VALUE']);
		$arr_producers_invert[trim($one['PROPERTY_PRODUCER_VALUE'])] = $one['ID'];
	};
	*/
	if(!in_array(trim($one['PROPERTY_BRAND_VALUE']),$arr_producers) and $one['PROPERTY_BRAND_VALUE']!='')
	{
		$arr_producers[$one['ID']] = trim($one['PROPERTY_BRAND_VALUE']);
		$arr_producers_invert[trim($one['PROPERTY_BRAND_VALUE'])] = $one['ID'];
	};
	
	
	/*
	if(!in_array(trim($one['PROPERTY_USETIME_VALUE']),$arr_periods) and $one['PROPERTY_USETIME_VALUE']!='')
	{
		$arr_periods[] = trim($one['PROPERTY_USETIME_VALUE']);
	};
	*/
};

// echo '<pre>';
// print_R($arr_producers);
// echo '</pre>';

asort($arr_producers);
reset($arr_producers);

$arr_periods = array(
				'1' => 'Один день',
				//'2' => 'Два дня',
				'14' => 'Две недели',
				'30' => 'Один месяц',
				'90' => 'Три месяца',
				'180' => 'Полгода',
				'365' => 'Год'
				);

// echo '<pre>';
// print_R($arr_periods);
// echo '</pre>';

$one_line = array();
$one_line['type0'] = 1;
foreach($arr_types as $arr_type)
{
	$one_line['type'.$arr_type['ID']] = 0;
};
$one_line['producer0'] = 1;
foreach($arr_producers as $prod_id=>$prod_name)
{
	$one_line['producer'.$prod_id] = 0;
};
$one_line['time0'] = 1;
foreach($arr_periods as $days=>$period_name)
{
	$one_line['time'.$days] = 0;
};


$arr_matrix = array();
$i=0;
foreach($arr_goods as $one_goods)
{
	$i = $one_goods['ID'];
	$arr_matrix[$i] = $one_line;
	$arr_matrix[$i]['type'.$one_goods['IBLOCK_SECTION_ID']] = 1;
	//$arr_matrix[$i]['producer'.$arr_producers_invert[trim($one_goods['PROPERTY_PRODUCER_VALUE'])]] = 1;
	$arr_matrix[$i]['producer'.$arr_producers_invert[trim($one_goods['PROPERTY_BRAND_VALUE'])]] = 1;
	$arr_matrix[$i]['time'.$one_goods['PROPERTY_USETIME_VALUE']] = 1;
	$arr_matrix[$i]['goods_id'] = $one_goods['ID'];
	//$i++;
	
};	

// echo '<pre>';
// print_R(json_encode($arr_matrix));
// echo '</pre>';

?>

<script type="text/javascript">
	
	var filter_href = "";
	
	/*  ------------- FILTER PANEL ----------------- */
	
	function fix_show_filter_panel1(){
		
		$('.holder960, .block960 table:eq(0)').css('display','block');
		$('.block960 table:eq(1)').css('display','none');
		$('.tabs25 li').removeClass('active');
		$('.tabs25 li:first').addClass('active');
		$('.t4,.t5,.t6,.t7').jScrollPane({showArrows:true});
	
		//window.location.hash="";
		window.location.hash="filter_top_point";		
		//chak 05/06/2012 filter cant be empty!
		if(filter_href=="") filter_href = "/catalog/?prod=l";
	}
	
	function fix_show_filter_panel2()
	{
		$('.holder960, .block960 table:eq(1)').css('display','block');								 
		$('.block960 table:eq(0)').css('display','none');
		$('.tabs25 li').removeClass('active');
		$('.tabs25 li:last').addClass('active');
		$('.t1,.t2,.t3').jScrollPane({showArrows:true});
		//window.location.hash="";
		window.location.hash="filter_top_point";
		//chak 05/06/2012 filter cant be empty!		
		if(filter_href=="") filter_href = "/catalog/?prod=a";
	
	}
	
	function show_filter_panel1()
	{
		fix_show_filter_panel1();
		fix_show_filter_panel2();
		fix_show_filter_panel1();
		//console.log(filter_href);
	}

	function show_filter_panel2()
	{
		fix_show_filter_panel2();
		fix_show_filter_panel1();
		fix_show_filter_panel2();		
		//console.log(filter_href);
	}

	function show_filter()
	{
		show_filter_panel1();
		return false;
	};
	/* --------------------------------------------- */

	/*   для панели фильтрации   */				
	$(document).ready(function(){	
			
			
			// переключатель
			$('.tabs25 li').each(function(index) {
				$(this).bind('click',function() {
					
					$('.tabs25 li').removeClass('active');
					$(this).addClass('active');
					$('.block960 table').css('display','none');
					$('.block960 table:eq('+index+')').css('display','block');
										

					//chak
					//$('.block960 table:eq('+index+')').height(265);
					//console.log($('.block960 table:eq('+index+')').height());
					
					$('.choice').jScrollPane({showArrows:true});
					
					if(index==0)
					{
						filter_href = "/catalog/?prod=l";
						
					}
					else
					{
						filter_href = "/catalog/?prod=a";
						
					};
					
					//chak
					$("#goods_string").html("");
					return false;
				});
			});
			
			// 2 открывалки
			$('#filter1').click(function() {
					show_filter_panel1();
					return false;	
				});
	
			$('#filter1-t').click(function() {
					show_filter_panel1();
					return false;	
				});
	
			$('#filter2').click(function() {
				show_filter_panel2();
				return false;	
			});
		
			//close filter panel
			$('.popupCloser, .bt141').click(function() {
					$('.holder960').css('display','none');
					/*
					if(navigator.appVersion.indexOf('MSIE 7')!=-1) {
						window.location.reload();
					}*/
					
					//location.href = "/catalog/";
					
					return false;	
				});
	});
	/* -------------------------- */
	
	
	//chak, added 03/05
	/* var filter_href = "/catalog/";  перенесено в вызов фильтра*/ 
	
	matrix = new Array(<?=json_encode($arr_matrix)?>);
	
	//console.log(matrix);
	
	function substr_count( haystack, needle, offset, length ) 
	{ // Count the number of substring occurrences
	    //
	    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 
	    var pos = 0, cnt = 0;
	 
	    if(isNaN(offset)) offset = 0;
	    if(isNaN(length)) length = 0;
	    offset--;
	 
	    while( (offset = haystack.indexOf(needle, offset+1)) != -1 ){
	        if(length > 0 && (offset+needle.length) > length){
	            return false;
	        } else{
	            cnt++;
	        }
		}
	 
	    return cnt;
	}
	
	function refresh_goods2()
	{
		//var type_id = $(".type_select li").find(".its_checked").parent().attr("id");
		var type_id = $(".type_select li.chosen").attr("id");
		var producer_id = $(".producer_select li.chosen").attr("id");
		var period_id = $(".period_select li.chosen").attr("id");
		
		
		//console.log(type_id+" "+producer_id+" "+period_id);
		
		$(".type_select li").css("display","none");
		$(".type_select #type0").css("display","block");
		
		$(".producer_select li").css("display","none");
		$(".producer_select #producer0").css("display","block");
		
		$(".period_select li").css("display","none");
		$(".period_select #time0").css("display","block");
		
		$(".goods_select li").css("display","none");
		$(".goods_select #0").css("display","block");
		
		jQuery.each(matrix[0], function(idx,value){
					
			if(value[type_id]==1)
			{
				if(value[producer_id]==1)
				{
					if(value[period_id]==1)
					{
						jQuery.each(value, function(arr_idx, arr_val){
							if(arr_val==1) $("#"+arr_idx).css("display","block");		
						});
						$(".goods_select #"+idx).css("display","block");
					};
				};
			};
		});
		
		//очищаем выбор товара
		$(".goods_select li").removeClass("chosen");
		$(".goods_select li:first").addClass("chosen");
		// и строку с его наименованием
		$("#goods_string").html("");
		// и меняем кнопку
		$("#set_filter_button").attr("src", "/img/bt229.gif");
		
		//формируем строку фильтрации
		filter_href = "/catalog/?prod=l&type="+type_id+"&producer="+producer_id+"&period="+period_id;
		//console.log(filter_href);
		
	};
	
	function refresh_filter_params()
	{
		var goods_id = $(".goods_select li.chosen").attr("id");
				
		if(goods_id!=0)
		{
			type_str = "";
			producer_str = "";
			jQuery.each(matrix[0][goods_id], function(arr_idx, arr_val){
								//console.log(arr_idx);
								if(arr_val==1) 
								{
									if(arr_idx!="goods_id")
									{
										$("#"+arr_idx).parent().find("li").removeClass("chosen");
										$("#"+arr_idx).addClass("chosen");
										
										
										
										if(substr_count(arr_idx, "producer")>0) producer_str = $("#"+arr_idx).html();
										if(substr_count(arr_idx, "type")>0) type_str = $("#"+arr_idx).html();
									};
								};
							});
			// записываем "текущий выбранный товар"
			goods_string = producer_str+" / "+type_str+" / "+$(".goods_select li.chosen").html();
			$("#goods_string").html(goods_string);
		}
		else
		{
			// all goods click
			$("#type0").trigger("click");
			$("#producer0").trigger("click");
			$("#time0").trigger("click");
		};
		
	};
	
	
	$(document).ready(function(){
				
		$(".type_select li").bind("click", function(){
			$(".type_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			refresh_goods2();
		});
			
		$(".producer_select li").bind("click", function(){
			$(".producer_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			refresh_goods2();
		});
		
		$(".period_select li").bind("click", function(){
			$(".period_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			refresh_goods2();
		});
		
		$(".goods_select li").bind("click", function(){
			$(".goods_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			// формируем ссылку на товар
			/*var goods_id = $(".goods_select li.chosen").attr("id");*/
			var goods_id = $(".goods_select li.chosen").attr("sid");
			if(goods_id!=0)  filter_href="/catalog/"+goods_id+"/";
			//тут мы должны изменить название кнопки
			$("#set_filter_button").attr("src", "/img/bt141.gif");
			
			refresh_filter_params();
			//alert(filter_href);
			//refresh_goods(); здесь не нужно
		});
		
		$(".goods_select li").bind("dblclick", function(){
			window.location=filter_href;
		});
		
		// ЗДЕСЬ НАВЕС НА КНОПКУ ФИЛЬТРАЦИИ
		$(".bt141").bind("click", function(){
			//console.log(filter_href);
			window.location=filter_href;
			return false;
		});
		
			//установленный по сессии фильтр
			<?
			if(isset($_SESSION['FILTER_ARR']) and $_SESSION['FILTER_ARR']['prod']=='l')
			{
			?>
				$(".type_select li").removeClass("chosen");
				$("#<?=$_SESSION['FILTER_ARR']['type']?>").addClass("chosen");
				
				$(".producer_select li").removeClass("chosen");
				$("#<?=$_SESSION['FILTER_ARR']['producer'];?>").addClass("chosen");
				
				$(".period_select li").removeClass("chosen");
				$("#<?=$_SESSION['FILTER_ARR']['period'];?>").addClass("chosen");
				
				refresh_goods2();
			<?
			};
			?>
	});
</script>

<div class="holder960"> 
	<a name="filter_top_point"></a>
  <div class="block960">
  	<img src="/img/pic15b.gif" class="popupCloser"  /> 		
    <ul class="tabs25"> 			
      <li><a href="#" >Контактные линзы</a></li>
      <li><a href="#" >Cредства ухода</a></li>
    </ul>
    <table> 			
		<tbody>
        <tr> 				
			<td style="border-image: initial; width: 240px; height: 265px"> 					
				<div class="choice t4" style="width: 240px; height: 265px"> 						
					<ul class="type_select">  							
						<li class="all_types chosen" id="type0"><span class="checker"><img src="/img/pic12a.gif"  /></span>Все типы</li>
						<?foreach($arr_types as $type):?>
							<li class="all_types" id="type<?=$type['ID']?>"><span class="checker"><img src="/img/pic12a.gif"  /></span><?=$type['NAME']?></li>
						<?endforeach;?>
						<?/*<li id=""><span class="checker"><img src="/img/pic12a.gif"  /></span>Тип 1</li>*/?>
               		</ul>
             	</div>
           	</td> 				
			<td style="border-image: initial; width: 236px; height: 265px"> 					
				<div class="choice t5" style="width: 236px; height: 265px"> 						
					<ul class="producer_select"> 							
						<li class="all_producers" id="producer0">Все бренды</li>
						<?foreach($arr_producers as $prod_id => $producer):?>
							<li class="all_producers" id="producer<?=$prod_id?>"><?=$producer?></li>
						<?endforeach;?>
						<?/*<li><span class="letter">a</span>Alcon</li>*/?>					
					</ul>
				</div>
           	</td> 				
			<td style="border-image: initial; width: 240px; height: 265px"> 					
				<div class="choice t6" style="width: 240px; height: 265px"> 						
					<ul class="period_select"> 							
						<li class="all_times" id="time0">Любой срок использования</li>
						<?foreach($arr_periods as $period=>$period_name):?>	
							<li class="all_times" id="time<?=$period?>"><?=$period_name;?></li>
						<?endforeach;?>
               		</ul>
				</div>
           	</td> 				
			<td style="border-image: initial; width: 240px; height: 265px"> 					
				<div class="choice t6" style="width: 240px; height: 265px"> 						
					<ul class="goods_select"> 							
						<li class="all_goods" id="0">Все товары</li>												
						<?foreach($arr_goods as $one):?>
							<li class="all_goods type<?=$one['IBLOCK_SECTION_ID']?> producer<?=$arr_producers_invert[$one['PROPERTY_PRODUCER_VALUE']]?> time<?=$one['PROPERTY_USETIME_VALUE']?> " sid="<?=$one['CODE']?>" id="<?=$one['ID']?>"><?=$one['NAME']?></li>
						<?endforeach;?>
						<?/*<li><span class="checker"><img src="/img/pic12a.gif"  /></span>Contact Day 30 Compatic</li>*/?>
               		</ul>
             	</div>
           	</td> 			
		</tr>
       	</tbody>
    </table>
 
 
<? //-------------------------------- CODE for accessories ------------------ ?> 
<?
$arr_access_producers=array();
$arr_access_producers_invert=array();
$arr_access_types = array();
$arr_access_types_invert = array();
$arr_access = array();

$arr_order= array('SORT'=>'ASC');
$arr_select=array('ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'NAME', 'PROPERTY_PRODUCER', 'PROPERTY_LENSTYPE', 'PROPERTY_BRAND');
$arr_filter=array('IBLOCK_ID'=>$cat_iblock, 'ACTIVE'=>'Y', "SECTION_ID"=>7);
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
$i=0;
while($one=$res->GetNext())
{
	
	$arr_access[] = $one;

	/*
	if(!in_array(trim($one['PROPERTY_PRODUCER_VALUE']), $arr_access_producers) and $one['PROPERTY_PRODUCER_VALUE']!='')
	{
		$arr_access_producers[$one['ID']] = trim($one['PROPERTY_PRODUCER_VALUE']);
	};
	*/
	if(!in_array(trim($one['PROPERTY_BRAND_VALUE']), $arr_access_producers) and $one['PROPERTY_BRAND_VALUE']!='')
	{
		$arr_access_producers[$one['ID']] = trim($one['PROPERTY_BRAND_VALUE']);
	};
	
	
	if(!in_array(trim($one['PROPERTY_LENSTYPE_VALUE']), $arr_access_types) and $one['PROPERTY_LENSTYPE_VALUE']!='')
	{
		$arr_access_types[$one['ID']] = trim($one['PROPERTY_LENSTYPE_VALUE']);
	};
};

asort($arr_access_producers);
reset($arr_access_producers);

$arr_access_producers_invert = array_flip($arr_access_producers);
$arr_access_types_invert = array_flip($arr_access_types);

// echo '<pre>';
// print_R($arr_access_producers_invert);
// echo '</pre>';


$one_access_line = array();
$one_access_line['access_type0'] = 1;
foreach($arr_access_types as $type_id=>$arr_type)
{
	$one_access_line['access_type'.$type_id] = 0;
};
$one_access_line['access_producer0'] = 1;
foreach($arr_access_producers as $prod_id=>$prod_name)
{
	$one_access_line['access_producer'.$prod_id] = 0;
};

// echo '<pre>';
// print_R($one_access_line);
// echo '</pre>';


$arr_access_matrix = array();

foreach($arr_access as $one_access)
{
	$i = 'access'.$one_access['ID'];
	$arr_access_matrix[$i] = $one_access_line;
	$arr_access_matrix[$i]['access_type'.$arr_access_types_invert[trim($one_access['PROPERTY_LENSTYPE_VALUE'])]] = 1;
	//$arr_access_matrix[$i]['access_producer'.$arr_access_producers_invert[trim($one_access['PROPERTY_PRODUCER_VALUE'])]] = 1;
	$arr_access_matrix[$i]['access_producer'.$arr_access_producers_invert[trim($one_access['PROPERTY_BRAND_VALUE'])]] = 1;
	$arr_access_matrix[$i]['access_id'] = $one_access['ID'];	
};	
?>


 <script type="text/javascript">
		
	access_matrix = new Array(<?=json_encode($arr_access_matrix)?>);
	
	//console.log(matrix);
	
	function refresh_access()
	{
		//var type_id = $(".type_select li").find(".its_checked").parent().attr("id");
		var access_type_id = $(".access_type_select li.chosen").attr("id");
		var access_producer_id = $(".access_producer_select li.chosen").attr("id");
		
		
		//console.log(type_id+" "+producer_id);
		
		$(".access_type_select li").css("display","none");
		$(".access_type_select #access_type0").css("display","block");
		
		$(".access_producer_select li").css("display","none");
		$(".access_producer_select #access_producer0").css("display","block");
		
	
		$(".access_select li").css("display","none");
		$(".access_select #access0").css("display","block");
		
		jQuery.each(access_matrix[0], function(idx,value){
					
			if(value[access_type_id]==1)
			{
				if(value[access_producer_id]==1)
				{
					jQuery.each(value, function(arr_idx, arr_val){
						if(arr_val==1) $("#"+arr_idx).css("display","block");		
					});
					$(".access_select #"+idx).css("display","block");
				};
			};
		});
		
		//очищаем выбор товара
		$(".access_select li").removeClass("chosen");
		$(".access_select li:first").addClass("chosen");
		// и строку с его наименованием
		$("#goods_string").html("");
		// и меняем кнопку
		$("#set_filter_button").attr("src", "/img/bt229.gif");
		
		//формируем строку фильтрации
		filter_href = "/catalog/?prod=a&access_type="+access_type_id+"&access_producer="+access_producer_id;
	};
	
	function access_refresh_filter_params()
	{
		var access_id = $(".access_select li.chosen").attr("id");
				
		if(access_id!='access0')
		{
			type_str = "";
			producer_str = "";
			jQuery.each(access_matrix[0][access_id], function(arr_idx, arr_val){
								//console.log(arr_idx);
								if(arr_val==1) 
								{
									if(arr_idx!="goods_id")
									{
										$("#"+arr_idx).parent().find("li").removeClass("chosen");
										$("#"+arr_idx).addClass("chosen");
										
										
										
										if(substr_count(arr_idx, "access_producer")>0) producer_str = $("#"+arr_idx).html();
										if(substr_count(arr_idx, "access_type")>0) type_str = $("#"+arr_idx).html();
									};
								};
							});
			goods_string = producer_str+" / "+type_str+" / "+$(".access_select li.chosen").html();
			$("#goods_string").html(goods_string);//
		}
		else
		{
			// all goods click
			$("#access_type0").trigger("click");
			$("#access_producer0").trigger("click");
		};
		
		
	};
	
	
	$(document).ready(function(){
				
		$(".access_type_select li").bind("click", function(){
			$(".access_type_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			refresh_access();
		});
			
		$(".access_producer_select li").bind("click", function(){
			$(".access_producer_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			refresh_access();
		});
		
			
		$(".access_select li").bind("click", function(){
			$(".access_select li").removeClass("chosen");
			$(this).addClass("chosen");
			
			// меняем кнопку
			$("#set_filter_button").attr("src", "/img/bt141.gif");
			// собираем ссылку
			var access_id = $(".access_select li.chosen").attr("id");
			if(access_id!="access0")  filter_href="/catalog/"+$(".access_select li.chosen").attr("sid")+"/";
			
			//console.log(filter_href);
			
			access_refresh_filter_params();		
		});
		
		$(".access_select li").bind("dblclick", function(){
			window.location=filter_href;
		});
		
		<?
		if(isset($_SESSION['FILTER_ARR']) and $_SESSION['FILTER_ARR']['prod']=='a')
		{			
			?>
				$(".access_type_select li").removeClass("chosen");
				$("#<?=$_SESSION['FILTER_ARR']['access_type']?>").addClass("chosen");
				
				$(".access_producer_select li").removeClass("chosen");
				$("#<?=$_SESSION['FILTER_ARR']['access_producer'];?>").addClass("chosen");
				
				refresh_access();
			<?
		};
		?>
	
	});
</script>
    <table> 			
		<tbody>
        <tr> 				
			<td style="border-image: initial; "> 					
				<div class="choice t1"> 						
					<ul class="access_type_select">
							<li class="all_access_type" id="access_type0">Все типы</li>
						<?foreach($arr_access_types as $type_id=>$one_type):?>
							<li class="all_access_type" id="access_type<?=$type_id?>"><?=$one_type?></li>
						<?endforeach;?>
               		</ul>
             	</div>
           	</td> 				 				
			<td style="border-image: initial; "> 					
				<div class="choice t2"> 						
					<ul class="access_producer_select"> 							
							<li class="all_access_producer" id="access_producer0">Все бренды</li>
						<?foreach($arr_access_producers as $access_producer_id=>$one_access_producer):?>					
							<li class="all_access_producer" id="access_producer<?=$access_producer_id?>"><?=$one_access_producer?></li>
						<?endforeach;?>
							<?/*<li><span class="letter">a</span>Alcon</li>*/?>
               		</ul>
             	</div>
           	</td> 				 				
			<td style="border-image: initial; "> 					
				<div class="choice t3"> 						
					<ul class="access_select"> 							
						<li class="all_access" id="access0">Все товары</li>
						<?foreach($arr_access as $one_access):?>			
							<li class="all_access" sid="<?=$one_access['CODE']?>" only_id="<?=$one_access['ID']?>" id="access<?=$one_access['ID']?>"><?=$one_access['NAME']?></li>
						<?endforeach;?>				 						
					</ul>
             	</div>
           	</td> 			
		</tr>
       	</tbody>
    </table>
   		
    <p class="outside"><span>Выбран товар:</span> <span style="font-size: 12px;" id="goods_string"></span></p>
   		<a href="" id="set_filter"><img id="set_filter_button" src="/img/bt229.gif" class="bt141" /></a></div>
 </div>
