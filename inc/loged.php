<? global $USER; ?> <noindex><a href="/personal/" rel="nofollow" class="authLink" id="userMenu" ><?=(mb_strlen($USER->GetLogin())>13)?mb_substr($USER->GetLogin(), 0, 20).'...':$USER->GetLogin(); ?><span><img src="/img/arrow7down.gif"  /><span></span></span></a></noindex> 
<div class="block146a"> 	 
  <ul> 		 
    <li><noindex><a href="/personal/" rel="nofollow" ><?='Личный кабинет';//(mb_strlen($USER->GetLogin())>13)?mb_substr($USER->GetLogin(), 0, 13).'...':$USER->GetLogin(); ?></a></noindex></li>
   		 
    <li><noindex><a href="/personal/history.php" rel="nofollow" >Последний заказ</a></noindex></li>
   		 
    <li><noindex><a href="/invite_friend/" rel="nofollow" >&laquo;Приведи друга&raquo;</a></noindex></li>
   		 
    <li><noindex><a href="/logout/" rel="nofollow" >Выход</a></noindex></li>
   	</ul>
 </div>
 
<script type="text/javascript">
	$("body").live("click", function(){
		if($(".block146a").css("display")=="block")
		{
			$(".block146a").css("display", "none");
		};
	});
</script>
