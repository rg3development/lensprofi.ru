<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';


$check_erors = array();

$bad_news = false;
foreach($_REQUEST['reg'] as $field_name=>$field_value)
{
	if($field_value=='') 
	{
		$bad_news = true;
		$check_erors['all'] = 'Y';
		
		if($field_name=='first_name') 
		{
			$check_erors['first_name'] = 'Y';
		};
		
		if($field_name=='last_name') 
		{
			$check_erors['last_name'] = 'Y';
		};
		
		if($field_name=='email') 
		{
			$check_erors['email'] = 'Y';
		};
	}
	else
	{
		if($field_name=='phone_code' or $field_name=='phone')
		{
			if($field_value!='')
			{
				if(!is_numeric($field_value) or !is_numeric($field_value))
				{
					$check_erors['phone'] = 'Y';
					$bad_news = true;
				};
			};
		};
		
	};
	
};

if($_REQUEST['reg']['pass1']!=$_REQUEST['reg']['pass2'])
{
	$check_erors['pass'] = 'Y';
	$bad_news = true;
};	

$ARR_USERS = array();
$filter = array();
$rsUsers = CUser::GetList(($by="name"), ($order="asc"), $filter);
while($one_user = $rsUsers->GetNext())
{
	$ARR_USERS[$one_user['EMAIL']] = $one_user['ID'];
};

if($_REQUEST['reg']['email']!='')
{
	if(isset($ARR_USERS[$_REQUEST['reg']['email']]))
	{
		$check_erors['email'] = 'Y';
		$bad_news = true;
	};
};

if(!$APPLICATION->CaptchaCheckCode($_REQUEST['reg']['captcha_entered'], $_REQUEST['reg']['captcha_code']))
{
	$check_erors['captcha'] = 'Y';
	$bad_news = true;
}

if($bad_news):
?>
	<?/*
	if(isset($check_erors['all'])):?>
		<div class="q6">Пожалуйста, заполните все поля!</div>
	<?endif;
	*/?>
	
	<form method="post" action="" name="reg_form" id="reg_form">
		<table>
			<tbody>
			<tr>
				<td><span>Имя:</span><input name="reg[first_name]" type="text" class="w205" value="<?=$_REQUEST['reg']['first_name']?>" /></td>
				<td>
					<?if(isset($check_erors['first_name'])):?>
						<div class="q6">Заполните поле имя</div>
					<?endif;?>
				</td>
			</tr>
			<tr>
				<td><span>Фамилия:</span><input type="text" name="reg[last_name]" class="w205" value="<?=$_REQUEST['reg']['last_name']?>" /></td>
				<td>
					<?if(isset($check_erors['last_name'])):?>
						<div class="q6">Заполните поле фамилия</div>
					<?endif;?>
				</td>
			</tr>
			<tr>
				<td><span>E-mail:</span><input type="text" name="reg[email]" class="w205" value="<?=$_REQUEST['reg']['email']?>" /></td>
				<td>
					<?if(isset($check_erors['email'])):?>
						<div class="q6">Пользователь с таким E-mail уже зарегистрирован</div>
					<?endif;?>
				</td>
			</tr>
		</tbody></table>
		<table>
			<tbody><tr>
				<td>
					<span>Телефон:</span>
					<ul class="triplePhone">
						<li>+7</li>
						<li><input type="text" name="reg[phone_code]" class="w35" maxlength="4" value="<?=$_REQUEST['reg']['phone_code']?>" /></li>
						<li><input type="text" name="reg[phone]" class="w100" maxlength="11" value="<?=$_REQUEST['reg']['phone']?>" /></li>																						
					</ul>
				</td>
				<td><?if(isset($check_erors['phone'])):?><p class="q6">Внимание! В телефонных номерах допустимы только цифры.</p><?endif;?></td>
			</tr>
			<tr>
				<td><span>Пароль:</span><input type="password" name="reg[pass1]" class="w165" value="<?=$_REQUEST['reg']['pass1']?>" /></td>
				<td></td>
			</tr>
			<tr>
				<td><span>Подтвердите пароль:</span><input type="password" name="reg[pass2]" class="w165" value="<?=$_REQUEST['reg']['pass2']?>"></td><? //FOCUS CLASS = "alerted"?>
				<td><?if(isset($check_erors['pass'])):?><p class="q6">Внимание! Пароль не совпадает. Проверьте правильность символов.</p><?endif;?></td>
			</tr>
		</tbody></table>
		<table>
			<tbody><tr>
				<td colspan="2">Введите символы с картинки в поле:</td>
			</tr>
			<tr>
				<?
					include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
					$cpt = new CCaptcha();
					$captchaPass = COption::GetOptionString("main", "captcha_password", "");
					if(strlen($captchaPass) <= 0)
					{
						$captchaPass = randString(10);
						COption::SetOptionString("main", "captcha_password", $captchaPass);
					}
					$cpt->SetCodeCrypt($captchaPass);
				?>
				<td>
					<input name="reg[captcha_code]" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden">
					<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>"><?/*<img src="/img/captcha.gif" alt="" style="margin-right:10px">*/?></td>
				<td style="padding-left: 10px">
					<?if(isset($check_erors['captcha'])):?>
						<div class="q6" style="padding-top: 3px">Неправильно введены символы с изображения</div>
					<?endif;?>
				</td>
				<?/*<td><a href="#" class="q4">Показать другие символы</a></td>*/?>
			</tr>
			<tr>
				<td colspan="2"><input type="text" name="reg[captcha_entered]" class="w165"></td>
			</tr>
		</tbody></table>
		<div class="enterBt"><a href="#" id="reg_butt"><img src="/img/bt166.gif" alt=""></a></div>
	</form>
<?
else:
	/*
	global $USER;
	$res = $USER->Register(
					 $_REQUEST['reg']['email'],
					 $_REQUEST['reg']['first_name'],
					 $_REQUEST['reg']['last_name'],
					 $_REQUEST['reg']['pass1'],
					 $_REQUEST['reg']['pass2'],
					 $_REQUEST['reg']['email']
					);
	if($res['TYPE']='OK')
	{
	};
	*/
	// echo '<pre>';
	// print_R($res);
	// echo '</pre>';
	
	$user = new CUser;
	$arFields = Array(
					  "NAME"              => $_REQUEST['reg']['first_name'],
					  "LAST_NAME"         => $_REQUEST['reg']['last_name'],
					  "EMAIL"             => $_REQUEST['reg']['email'],
					  "LOGIN"             => $_REQUEST['reg']['email'],
					  "ACTIVE"            => "Y",
					  "GROUP_ID"          => array(2,3),
					  "PASSWORD"          => $_REQUEST['reg']['pass1'],
					  "CONFIRM_PASSWORD"  => $_REQUEST['reg']['pass2'],
					  "PERSONAL_PHONE"    => $_REQUEST['reg']['phone'],
					  "UF_PHONE_CODE"    => $_REQUEST['reg']['phone_code']
					);
	$ID = $user->Add($arFields);
	if (intval($ID) > 0)
	{
		global  $USER;
		$USER->Authorize($ID);
	};
	
	$arFields1 = Array(
	"USER_ID"=> $ID,
	"LOGIN" => $arFields['LOGIN'],
	"EMAIL" => $arFields['EMAIL'],
	"NAME" => $arFields['NAME'],
	"LAST_NAME" => $arFields['LAST_NAME'],
	"PASSWORD" => $arFields['PASSWORD']
	);
	
	CEvent::Send('NEW_USER', SITE_ID, $arFields1);
	
	?>
	<div style="padding: 20px">
		<span style="color: white">
			Поздавляем! Вы успешно зарегистрированы и авторизованы. Через несколько секунд вы автоматически передете на главную страницу сайта...
		</span>
	</div>
	<script type="text/javascript">
		var timer = setTimeout(function() {window.location="/"}, 3000);
	</script>
	<?
endif;
?>