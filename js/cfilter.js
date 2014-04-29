	
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