					<noindex><a href="#" class="authLink" id="private">Вход в личный кабинет</a></noindex>
					<div class="block430a" style="display: none;">
						<img src="/img/pic15b.gif" alt="" class="closer">
						<ul class="tabs24">
							<li class="pt1 active"><a href="#">Войти</a></li>
							<li class="pt2"><a href="#">Зарегистрироваться</a></li>
							<li class="pt3"><a href="#">Забыли пароль?</a></li>
						</ul>
					
						<div class="authData" style="display: block; ">
							
							<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "def", array(
								"REGISTER_URL" => "",
								"FORGOT_PASSWORD_URL" => "javascript:show_tab3();",
								"PROFILE_URL" => "/personal/",
								"SHOW_ERRORS" => "Y"
								),
								false
							);?>
							<script type="text/javascript">
								function show_tab3()
								{
									$(".tabs24 .pt3").trigger("click");
								};
							</script>
						</div>
					
						<div class="authData" id="reg_target_div" style="display: none; ">
							<form method="post" action="" name="reg_form" id="reg_form">
								<table>
									<tbody><tr>
										<td><span>Имя:</span><input name="reg[first_name]" type="text" class="w205"></td>
										<td rowspan="3"><p class="q5"><script type="text/javascript">document.write("Для оформления заказа регистрация необязательна, но она позволит Вам принимать участие в акциях и пользоваться дополнительными сервисами, такими как «приведите друга и получите скидку», «SMS-напоминание» о покупке новой упаковки линз и другими.");</script></p></td>
									</tr>
									<tr>
										<td><span>Фамилия:</span><input type="text" name="reg[last_name]" class="w205"></td>
									</tr>
									<tr>
										<td><span>E-mail:</span><input type="text" name="reg[email]" class="w205"></td>
									</tr>
								</tbody></table>
								<table>
									<tbody><tr>
										<td>
											<span>Телефон:</span>
											<ul class="triplePhone">
												<li>+7</li>
												<li><input type="text" name="reg[phone_code]" class="w35" maxlength="4"></li>
												<li><input type="text" name="reg[phone]" class="w100" maxlength="11"></li>																						
											</ul>
										</td>
										<td><?/*<p class="q6">Внимание! В телефонных номерах допустимы только цифры.</p>*/?></td>
									</tr>
									<tr>
										<td><span>Пароль:</span><input type="password" name="reg[pass1]" class="w165"></td>
										<td></td>
									</tr>
									<tr>
										<td><span>Подтвердите пароль:</span><input type="password" name="reg[pass2]" class="w165"></td><? //FOCUS CLASS = "alerted"?>
										<td><?/*<p class="q6">Внимание! Пароль не совпадает. Проверьте правильность символов.</p>*/?></td>
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
										<?/*<td><a href="#" class="q4">Показать другие символы</a></td>*/?>
									</tr>
									<tr>
										<td colspan="2"><input type="text" name="reg[captcha_entered]" class="w165"></td>
									</tr>
								</tbody></table>
								<div class="enterBt"><a href="#" id="reg_butt"><img src="/img/bt166.gif" alt=""></a></div>
							</form>
						</div>
						<script type="text/javascript">
							$("#reg_butt").live("click", function(){
								$("#reg_target_div").load("/inc/ajax_register.php", $("#reg_form").serializeArray(), function(resp){
								});
								return false;
							});
						</script>
						
						<div class="authData" id="forgot_pass" style="display: none; ">
						<?/*
						<? $APPLICATION->IncludeComponent("bitrix:system.auth.forgotpasswd", "def", array(
								"PROFILE_URL" => "/personal/",
								"SHOW_ERRORS" => "Y"
								),
								false
							);?>
							*/?>
							<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
							<? if (strlen($arResult["BACKURL"]) > 0) { ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><? } ?>
								<table id="form_tab1">
									<tbody><tr>
										<td><span>Адрес электронной почты:</span><input type="text" name="email_field" id="email_field" class="w205" value="" /></td>
										<td><p class="q5" id="enter_mail_please" style="display: none;"><script type="text/javascript">document.write("Введите адрес электронной почты, на который будет выслана ссылка для смены пароля");</script></p></td>
									</tr>
								</tbody></table>
								<div class="enterBt" id="pwd1"><img src="/img/bt148b.gif" id="send_button"></div>
							</form>
						</div>
						<script type="text/javascript">
							/*
							$('#pwd1').click(function() {
								$(this).css('display','none');					  
								$(this).prev().css('display','none');
								$(this).next().css('display','block');
								return false;	
							});
							*/
							$(document).ready(function(){
								//butt OK
								$('.pass_remind_closer').live("click", function() {
									//alert("zz");
									$("#forgot_pass").html('<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>"><? if (strlen($arResult["BACKURL"]) > 0) { ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><? } ?><table id="form_tab1"><tbody><tr><td><span>Адрес электронной почты:</span><input type="text" name="email_field" id="email_field" class="w205" value="" /></td><td><p class="q5" id="enter_mail_please" style="display: none;">Введите адрес электронной почты, на который будет выслана ссылка для смены пароля</p></td></tr></tbody></table><div class="enterBt" id="pwd1"><img src="/img/bt148b.gif" id="send_button"></div></form>');
									$('.block430a').css('display','none');
									return false;	
								});
								
								// butt SEND
								$("#send_button").live("click", function(){
									if($("#email_field").attr("value")=="")
									{
										$("#enter_mail_please").css("display", "block");
									}
									else
									{
										$.post("/inc/ajax_forgot_pass.php", {use_mail:$("#email_field").attr("value")}, function(resp){
											//console.log(resp);
											$("#forgot_pass").html(resp);
										},
										"html");
									};
									return false;
								});
							});
						</script>
					</div>
