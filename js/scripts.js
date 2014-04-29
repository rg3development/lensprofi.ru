/* basket api */
function load_basket()
{
	$("#float_basket_target").load("/inc/ajax_basket_big.php");
};

function refresh_basket()
{
	update_basket_string();
	load_basket();	
};

function update_basket_string()
{
	$.post("/inc/ajax_basket_small.php", {}, function(resp){
		$(".bskContent").html(resp);
	},
	"html");
};

/* ----- for STAT basket ------ */

function load_stat_basket()
{
	$("#ajax_stat_basket_target").load("/inc/ajax_stat_basket.php");
};

function refresh_stat_basket()
{
	update_basket_string();
	load_stat_basket();	
};
/* ---------------------------- */


/*SELECTION PREVENT HACK*/

jQuery.fn.extend({
    disableSelection : function() {
            this.each(function() {
                    this.onselectstart = function() { return false; };
                    this.unselectable = "on";
                    jQuery(this).css('-moz-user-select', 'none');
            });
    },
    enableSelection : function() {
            this.each(function() {
                    this.onselectstart = function() {};
                    this.unselectable = "off";
                    jQuery(this).css('-moz-user-select', 'auto');
            });
    }
});


function preventSelection(element){
  var preventSelection = false;

  function addHandler(element, event, handler){
    if (element.attachEvent) 
      element.attachEvent('on' + event, handler);
    else 
      if (element.addEventListener) 
        element.addEventListener(event, handler, false);
  }
  function removeSelection(){
    if (window.getSelection) { window.getSelection().removeAllRanges(); }
    else if (document.selection && document.selection.clear)
      document.selection.clear();
  }
  function killCtrlA(event){
    var event = event || window.event;
    var sender = event.target || event.srcElement;

    if (sender.tagName.match(/INPUT|TEXTAREA/i))
      return;

    var key = event.keyCode || event.which;
    if (event.ctrlKey && key == 'A'.charCodeAt(0))  // 'A'.charCodeAt(0) можно заменить на 65
    {
      removeSelection();

      if (event.preventDefault) 
        event.preventDefault();
      else
        event.returnValue = false;
    }
  }

  // не даем выделять текст мышкой
  addHandler(element, 'mousemove', function(){
    if(preventSelection)
      removeSelection();
  });
  addHandler(element, 'mousedown', function(event){
    var event = event || window.event;
    var sender = event.target || event.srcElement;
    preventSelection = !sender.tagName.match(/INPUT|TEXTAREA/i);
  });

  // борем dblclick
  // если вешать функцию не на событие dblclick, можно избежать
  // временное выделение текста в некоторых браузерах
  addHandler(element, 'mouseup', function(){
    if (preventSelection)
      removeSelection();
    preventSelection = false;
  });

  // борем ctrl+A
  // скорей всего это и не надо, к тому же есть подозрение
  // что в случае все же такой необходимости функцию нужно 
  // вешать один раз и на document, а не на элемент
  addHandler(element, 'keydown', killCtrlA);
  addHandler(element, 'keyup', killCtrlA);
}
/*-----------------*/

$(document).ready(function(){
		$(".all_producers, .all_types, .all_times, .all_goods, .all_types, .all_access_type, .all_access_producer").live("click", function(){
		var originalHeigh = 300; 
		
		if ($(".all_types").height() < originalHeigh)
		$(".t1").jScrollPane({showArrows:true});
		else
		$(".t1").jScrollPane({showArrows:true});

		if ($(".all_access_type").height() < originalHeigh)
		$(".t2").jScrollPane({showArrows:true});
		else
		$(".t2").jScrollPane({showArrows:true});

		if ($(".all_access_producer").height() < originalHeigh)
		$(".t3").jScrollPane({showArrows:true});
		else
		$(".t3").jScrollPane({showArrows:true});

		if ($(".type_select").height() < originalHeigh)
		$(".t4").jScrollPane({showArrows:true});
		else
		$(".t4").jScrollPane({showArrows:true});

		if ($(".producer_select").height() < originalHeigh)
		$(".t5").jScrollPane({showArrows:true});
		else
		$(".t5").jScrollPane({showArrows:true});

		if ($(".period_select").height() < originalHeigh)
		$(".t6").jScrollPane({showArrows:true});
		else
		$(".t6").jScrollPane({showArrows:true});

		if ($(".goods_select").height() < originalHeigh)
		$(".t7").jScrollPane({showArrows:true});
		else
		$(".t7").jScrollPane({showArrows:true});
		
		});
});



$(document).ready(function() {
	
	//DISABLE SELECTION
	//$('div.jScrollPaneContainer ul li').disableSelection(); 
	
	$('.formSender1:first').css('border-right','1px solid #8b8b8b');
	$('.pager ul li:last').css('border-right','1px solid #cbcbcb');
	$('.list273 li:last').css('border-right','none').css('padding-right','0');
	$('.foot ul li:eq(2)').css('border-right','none').css('font-size','13px');
	$('.foot ul li:last').css('display','none');
	$('.pager table img:first').css('margin-right','5px');
	$('.pager table img:last').css('margin-left','5px');	
	$('.positions td').has('img').css('background','none');
	//$('.data1 td:first-child').width('227px').css('background','#f00');
	$('.type1 li:eq(1)').css('padding-right','0');
	$('.smallCrumbs li:last-child').css('background','none');
	$('.extra188a ul li:last').css('border','none');
	$('.data2 tr td:last-child').css('text-align','right');
	$('.data2 tr:last td').css('background','none');
	$('.analytic td:last').css('background','#fff');
	$('.analytic table td:first-child').css('text-align','right').width('100px');
	$('.analytic td ul li:last').css('padding','0');
	$('.analytic td:last').css('padding','0');
	/*
	вот это подход к верстке (
	$('.performance table td:first-child').css('border-left','1px solid #cacbcb').css('padding-left','10px');
	$('.performance table td:last-child').css('border-right','1px solid #cacbcb').css('padding-right','10px').css('text-align','right');
	$('.performance table td:last').css('border','1px solid #444').css('border-top','1px solid #cacbcb').css('background','#444').css('padding','5px 3px 0 5px');
	*/
	$('.data4 tr:last td').css('background','none');
	$('.authData:first').css('display','block');
	$('.choice ul li:first-child').addClass('chosen');
	$('.reserveBlock table td:first').css('padding-right','20px');
	$('.maincrumbs li span:last').css('display','none');
	
	$('.jCalendar th:nth-child(5n)').css('background','#891313');
	$('.jCalendar th:nth-child(6n)').css('background','#891313');
	

	
	/* CHAK for select linz from menu line  */
	$('select[name=list1]').change(function() {
		location.href = "/catalog/" + $(this).find("option:selected").attr("sid") + "/";
	});
	
	
	/* chak basket functions */
		/*---------------- для стат корзины ------------------ */
		// удалялка позиций
		$('.stat_basket_remover').live("click", function(){
			$.post("/inc/ajax_basket_delete_item.php", {control_name:$(this).attr('delid')}, function(resp){
				refresh_stat_basket();
			},
			"html");
			return false;
		});
		//пересчитываем
		$(".stat_basket_quantity").live("change", function(){
			$.post("/inc/ajax_basket_change_quantity.php", {control_name:$(this).attr("name"),quantity:$(this).attr("value")}, function(resp){
				refresh_stat_basket();
			});
			return false;
		});
		/*-----------------------------------------------------*/
		
		//закрывает корзину
		$('.bskCloser, #continue').live("click", function() {
			$("#float_basket_target").html("");
			return false;	
		});
		
		//пересчитываем
		$(".prod_quantity").live("change", function(){
			$.post("/inc/ajax_basket_change_quantity.php", {control_name:$(this).attr("name"),quantity:$(this).attr("value")}, function(resp){
				//alert(resp);
				refresh_basket();
			});
			return false;
		});
		
		// удалялка позиций
		$('.remover_in_float').live("click", function(){
			$.post("/inc/ajax_basket_delete_item.php", {control_name:$(this).parent().find('input[type=checkbox]').attr('name')}, function(resp){
				//alert(resp);
				refresh_basket();
			},
			"html");
			return false;
		});
		
		// показывает корзину
		$('#enterBsk').live("click", function(){
			load_basket();
			return false;
		});
		
		//оформляем заказ - ИЗ ВЫСПЛЫВАЮЩЕЙ КОРЗИНЫ
		$('#order_basket').live("click", function(){
			$("#float_basket_target").html(""); // закрываем корзину
			window.location="/personal/order.php";
			return false;
		});
	/* END OF basket functions*/
	
	
	
	

	// here was click handler for EMAIL and SMS checkbox in order form
	
	
	$('.w250').change(function() {
		var sel = $('option:selected',this).text();
		if(sel.indexOf('Новый')!=-1) {
			$('.hid').css('display','block');
		}
		
		else {
			$('.hid').css('display','none');
		}
	});
	
	
	$('#userMenu').click(function() {
		$('.block146a').show();
		return false;	
	});

	$('.block146a ul li:last').click(function() {
		//$('.block146a').slideUp('slow');
		//return false;	
	});
	
	

	
	$('#fpass').click(function(){
		//alert();
		$('.tabs24 li:last').click();
		
	});
	

	$('.tabs24 li').each(function(index) {
		$(this).addClass('pt'+(index+1));
	});
	
	$('.tabs25 li').each(function(index) {
		$(this).addClass('pt'+(index+1));
	});
	
	$('.oneItem').hover(function() {
		$(this).addClass('slt1');
	
	}, function() {
		$(this).removeClass('slt1');
	});
	
	// BUTTON hide all characteristics
	$('.srv3').click(function() {
		$('.tdHide').slideToggle('slow').toggleClass('visible');
		$(this).toggleClass('t2');
		return false;	
	});
	
	
	$('<div style="clear:both"></div>').insertAfter('.qBlock p:last');
	
	if(navigator.appVersion.indexOf('MSIE 6.0')!=-1) {
	$('.tabs24 li').corner('5px tl tr');
	$('.tabs25 li').corner('5px tl tr');
	$('.type2 li:eq(1)').css('position','relative').css('top','3px');
	
	$('img').each(function() {
		var picSource = $(this).attr('src');
		if(picSource.indexOf('.png')!= -1 ) {
			$(this).addClass('png');			
			}
		});
	}
	
	$('#private').click(function() {
		$('.block430a').css('display','block');
		return false;	
	});
	
	$('.closer').live("click", function() {
		$('.block430a').css('display','none');
		return false;	
	});
	
        $('#pwd').click(function(){
           $('.pwd').show();
           return false;	
        });
        
	
	
	$('.tabs24 li').each(function(index) {
		$(this).bind('click',function() {
			$('.tabs24 li').removeClass('active');
			$(this).addClass('active');
			$('.authData').css('display','none');
			$('.authData:eq('+index+')').css('display','block');			
			return false;	
		});
		
	});
	
	// here were filter func. now they are in filter template
	
	$('.switchOn').each(function(index) {
		$(this).bind('click',function() {
			$(this).closest('table').find('td').addClass('dsbl1').disableTextSelect();
			$(this).css('display','none');
			$('.switchOff:eq('+index+')').css('display','block');
			return false;	
		});
	});
	
	$('.switchOff').each(function(index) {
		$(this).bind('click',function() {
			$(this).closest('table').find('td').removeClass('dsbl1').enableTextSelect();
			$(this).css('display','none');
			$('.switchOn:eq('+index+')').css('display','block');
			return false;	
		});
	});
	
	$('.positions').each(function(index) {
		$('.srvBlock1 ul li span:first',this).html((index+1)+'.');
	});
	
	/*$('.remover').each(function(index) {
		$(this).bind('click',function() {
			$(this).closest('.positions').parent().parent().remove();
			$('.positions').each(function(test) {
				$('span:first',this).html((test+1)+'.');
			});
		return false;	
		});
	});*/
	
	$('.oneItem').each(function(index) {
	if( $(this).html()=='') {
			$(this).css('background','none').css('border','none');
		}
	});
	
	if(navigator.appName.indexOf('Opera')!=-1) {
		$('.block339 ul li label').css('top','-2px');
		$('.type3 li label').css('top','0');
		$('.rel10').css('top','0');
	}
});

