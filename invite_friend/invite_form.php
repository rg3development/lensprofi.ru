<?
$SHOW_FORM = true;

// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';

if(isset($_REQUEST['form_send']))
{

	if($_REQUEST['email']!='')
	{
		$ARR_USERS = array();
		$filter = array();
		$rsUsers = CUser::GetList(($by="name"), ($order="asc"), $filter);
		while($one_user = $rsUsers->GetNext())
		{
			$ARR_USERS[] = $one_user['EMAIL'];
		};
		
		// echo '<pre>';
		// print_R($_REQUEST);
		// echo '</pre>';
		
		if(in_array(trim($_REQUEST['email']), $ARR_USERS))
		{	
			echo '<div style="padding: 15px 0; color: red; text-decoration:blink">Пользователь с таким email уже зарегистрирован!</div>';
		}
		else
		{
			
			$invite_iblock_id = 7;
			
			$already_invite = false;
			
			// check exists invite list
			$arr_order= array('SORT'=>'ASC');
			$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_email');
			$arr_filter=array('IBLOCK_ID'=>$invite_iblock_id);
			$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
			$i=0;
			while($one=$res->GetNext())
			{
				if($one['PROPERTY_EMAIL_VALUE']==$_REQUEST['email']) 
				{
					$already_invite = true;
					break;
				};
			};
			
			if($already_invite) 
			{
				echo '<div style="padding: 15px 0; color: red; text-decoration:blink">Пользователь с таким email уже приглашен!</div>';
			}
			else
			{
					$SHOW_FORM = false;
					
					global $USER;
					
					// отправляем сообщение
					$arFields = Array(
					"C_EMAIL"=>$_REQUEST['email'],
					"C_NAME" => $USER->GetFirstName(),
					"C_LAST_NAME" => $USER->GetLastName()
					);
					
					if(intval(CEvent::Send('CHAK_INVITE_FRIEND', SITE_ID, $arFields))>0) 
					{
						// save invite
						$elem = new CIBlockElement;
						$PROP = array();
						
						$PROP['email'] = trim($_REQUEST['email']);
							
						$arr_fields = Array(		
												"MODIFIED_BY"    => $USER->GetID(),  
												"IBLOCK_ID"      => $invite_iblock_id,  
												"PROPERTY_VALUES"=> $PROP,  
												"NAME" => $USER->GetID(),  
												"ACTIVE" => "N",
											);			
							
						$new_child_id= $elem->Add($arr_fields, false, true, true);
						//echo $new_child_id;
							
						echo '<div style="padding: 15px 0; color: green;">Приглашение на email '.$_REQUEST['email'].' успешно отправлено </div>';
					};
			
			}
		}
	}
	else
	{
		echo '<div style="padding: 15px 0; color: red; text-decoration:blink">Заполните поле "Адрес электронной почты"</div>';
	};
};
?>
<?if($SHOW_FORM):?>
<form name="inv_form" action="" method="post">
	<table>
		<tbody><tr>
			<td><span>Адрес электронной почты:</span><input type="text" name="email" style=" font: 12px/25px Trebuchet Ms; height: 25px; margin-right: 10px; padding-left: 10px; width: 195px; display: block" value="<?=($_REQUEST['email']!='')?$_REQUEST['email']:''?>" /></td>
		</tr>
	</tbody></table>
	<div style="padding: 15px 0 0 0">
		<input type="hidden" name="form_send" value="form_send" />
		<input type="image" src="/img/bt148c.gif" name="subm" value="subm" />
	</div>
</form>
<?endif;?>