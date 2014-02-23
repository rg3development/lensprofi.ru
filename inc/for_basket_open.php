<?if(isset($_REQUEST['sb']) and $_REQUEST['sb']=='Y'):?>
	<script type="text/javascript">
		$(document).ready(function(){
			load_basket();
		});
	</script>
<?endif;?>
<?
// показывваем форму входа в случае ошибок
global $USER;
if(isset($_REQUEST['AUTH_FORM']) and $_REQUEST['AUTH_FORM']='Y' and $_REQUEST['login']=='yes' and !$USER->IsAuthorized())
{
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#private").trigger("click");
		});
	</script>
	<?
}
?>