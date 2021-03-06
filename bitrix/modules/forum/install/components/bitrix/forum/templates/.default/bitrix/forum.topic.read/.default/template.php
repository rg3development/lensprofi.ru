<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/js/main/utils.js"></script>', true);
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/.default/script.js"></script>', true);
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$arParams["SHOW_MAIL"] = (($arParams["SEND_MAIL"] <= "A" || ($arParams["SEND_MAIL"] <= "E" && !$GLOBALS['USER']->IsAuthorized())) ? "N" : "Y");
$arParams["SHOW_ICQ"] = ($arParams["SHOW_ICQ"] == "Y" ? "Y" : "N");

$arParams["AJAX_TYPE"] = ($arParams["AJAX_TYPE"] == "Y" ? "Y" : "N");
$arParams["SHOW_RSS"] = ($arParams["SHOW_RSS"] == "N" ? "N" : "Y");
$arParams["SHOW_FIRST_POST"] = ($arParams["SHOW_FIRST_POST"] == "Y" ? "Y" : "N");
if ($arParams["SHOW_RSS"] == "Y"):
	$arParams["SHOW_RSS"] = (!$USER->IsAuthorized() ? "Y" : (( CForumNew::GetUserPermission($arParams["FID"], array(2)) > "A" || $GLOBALS['USER']->IsAdmin() ) ? "Y" : "N"));
	if ($arParams["SHOW_RSS"] == "Y"):
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" href="'.$arResult["URL"]["RSS_DEFAULT"].'" />');
	endif;
endif;
$arParams["iIndex"] = $iIndex = rand();
if ($_SERVER['REQUEST_METHOD'] == "POST"):
	$message = $_POST["message_id"];
	$action = strToUpper($_POST["ACTION"]);
else:
	$message = $_GET["message_id"];
	$action = strToUpper($_GET["ACTION"]);
endif;
$message = (is_array($message) ? $message : array($message));
$arParams["SHOW_NAME_LINK"] = ($arParams["SHOW_NAME_LINK"] == "N" ? "N" : "Y");

$arUserSettings = array("first_post" => "show");
if ($arParams["SHOW_FIRST_POST"] == "Y" && $GLOBALS["USER"]->IsAuthorized())
{
	require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".strToLower($GLOBALS["DB"]->type)."/favorites.php");
	$arUserSettings = @unserialize(CUserOptions::GetOption("forum", "default_template", ""));
	$arUserSettings["first_post"] = ($arUserSettings["first_post"] == "hide" ? "hide" : "show");
}
$arParams["SHOW_VOTE"] = ($arParams["SHOW_VOTE"] == "Y" ? "Y" : "N");
$arParams["VOTE_TEMPLATE"] = (strlen(trim($arParams["VOTE_TEMPLATE"])) > 0 ? trim($arParams["VOTE_TEMPLATE"]) : "light");
$arParams["SEO_USER"] = ($arParams["SEO_USER"] == "Y" ? "Y" : "N");
/********************************************************************
				/Input params
********************************************************************/
$bShowedHeader = false;

IncludeAJAX();
	
$path = str_replace(array("\\", "//"), "/", dirname(__FILE__)."/template_message.php");
include($path);


if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-success">
	<div class="forum-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></div>
</div>
<?
endif;

/*if ($arResult["USER"]["RIGHTS"]["MODERATE"] == "Y"):
?>
<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST" <?
	?>onsubmit="return Validate(this)" name="MESSAGES_<?=$iIndex?>" id="MESSAGES_<?=$iIndex?>">
<?
endif;
*/
if ($arParams["SHOW_FIRST_POST"] == "Y" && $arResult["NAV_RESULT"] && $arResult["NAV_RESULT"]->NavPageNomer > 1):
	$bShowedHeader = true;
?>
<div class="forum-header-box">
	<div class="forum-header-options">
<?
if ($GLOBALS["USER"]->IsAuthorized()):
?>
		<span class="forum-option-additional"><a href="#postform" onclick="ShowFirstPost(this); return false;"><?=(
			$arUserSettings["first_post"] == "show" ? GetMessage("F_COLLAPSE") : GetMessage("F_SHOW"))?></a></span>
<?
endif;
if ($arParams["SHOW_RSS"] == "Y"):
?>
		&nbsp;&nbsp;
		<span class="forum-option-feed"><a href="<?=$arResult["URL"]["RSS_DEFAULT"]?>" onclick="window.location='<?=addslashes(htmlspecialchars($arResult["URL"]["~RSS"]))?>'; return false;">RSS</a></span>
<?
endif;
if ($USER->IsAuthorized())
{
	if (empty($arResult["USER"]["SUBSCRIBE"])) {
?>
		&nbsp;&nbsp;
		<span class="forum-option-subscribe forum-option-do-subscribe"><noindex><a rel="nofollow" title="<?=GetMessage("F_SUBSCRIBE_TITLE")?>" href="<?
			?><?=$APPLICATION->GetCurPageParam("TOPIC_SUBSCRIBE=Y&".bitrix_sessid_get(), array("FORUM_SUBSCRIBE", "FORUM_SUBSCRIBE_TOPIC", "sessid"))?><?
				?>"><?=GetMessage("F_SUBSCRIBE")?></a></noindex></span>
<?
	} else {
?>
		&nbsp;&nbsp;
		<span class="forum-option-subscribe forum-option-do-unsubscribe"><noindex><a rel="nofollow" title="<?=GetMessage("F_UNSUBSCRIBE_TITLE")?>" href="<?
			?><?=$APPLICATION->GetCurPageParam("TOPIC_UNSUBSCRIBE=Y&".bitrix_sessid_get(), array("FORUM_UNSUBSCRIBE", "FORUM_UNSUBSCRIBE_TOPIC", "sessid"))?><?
				?>"><?=GetMessage("F_UNSUBSCRIBE")?></a></noindex></span>
<?
	}
}
?>
	</div>
	<div class="forum-header-title"><span><?
	if ($arResult["TOPIC"]["STATE"] != "Y"):
		?><span class="forum-header-title-closed">[ <span><?=GetMessage("F_CLOSED")?></span> ]</span> <?
	endif;
	?><?=trim($arResult["TOPIC"]["TITLE"])?><?
 		if (strlen($arResult["TOPIC"]["DESCRIPTION"])>0):
			?>, <?=trim($arResult["TOPIC"]["DESCRIPTION"])?><?
		endif;
	
	?></span></div>
</div><?
?><div class="forum-block-container forum-first-post"><?
	?><div class="forum-block-outer" style="display:<?=($arUserSettings["first_post"] == "show" ? "block" : "none")?>">
		<div class="forum-block-inner">
<?
	__forum_default_template_show_message(array($arResult["MESSAGE_FIRST"]), $message, $arResult, array("single_message" => "Y"), $arParams, $this);
?>
		</div>
	</div>
</div>
<?
endif;

if ($arResult["NAV_RESULT"] && $arResult["NAV_RESULT"]->NavPageCount > 0):
?>
<div class="forum-navigation-box forum-navigation-top">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?
	if ($arResult["USER"]["RIGHTS"]["ADD_MESSAGE"] == "Y"):
?>
	<div class="forum-new-post">
		<a href="#postform" onclick="return fReplyForm();"><span><?=GetMessage("F_REPLY")?></span></a>
	</div>
<?
	endif;
?>
	<div class="forum-clear-float"></div>
</div>
<?
endif;

?>
<div class="forum-header-box">
<?
if (!$bShowedHeader):
?>
	<div class="forum-header-options">
<?
	if ($arParams["SHOW_RSS"] == "Y"):
?>
		<span class="forum-option-feed"><a href="<?=$arResult["URL"]["RSS_DEFAULT"]?>" onclick="window.location='<?=CUtil::JSEscape($arResult["URL"]["~RSS"])?>'; return false;">RSS</a></span>
<?
	endif;
	if ($USER->IsAuthorized())
	{
		if ($arParams["SHOW_RSS"] == "Y") {
			?>&nbsp;&nbsp;<?
		}

		if (empty($arResult["USER"]["SUBSCRIBE"]))
		{
?>
			<span class="forum-option-subscribe forum-option-do-subscribe"><noindex><a rel="nofollow" title="<?=GetMessage("F_SUBSCRIBE_TITLE")?>" href="<?
				?><?=$APPLICATION->GetCurPageParam("TOPIC_SUBSCRIBE=Y&".bitrix_sessid_get(), array("FORUM_SUBSCRIBE", "FORUM_SUBSCRIBE_TOPIC", "sessid"))?><?
					?>"><?=GetMessage("F_SUBSCRIBE")?></a></noindex></span>
<?
		} else {
?>
			<span class="forum-option-subscribe forum-option-do-unsubscribe"><noindex><a rel="nofollow" title="<?=GetMessage("F_UNSUBSCRIBE_TITLE")?>" href="<?
				?><?=$APPLICATION->GetCurPageParam("TOPIC_UNSUBSCRIBE=Y&".bitrix_sessid_get(), array("FORUM_UNSUBSCRIBE", "FORUM_UNSUBSCRIBE_TOPIC", "sessid"))?><?
					?>"><?=GetMessage("F_UNSUBSCRIBE")?></a></noindex></span>
<?
		}
	}
?>
	</div>
	<div class="forum-header-title"><span>
<?
	if ($arResult["TOPIC"]["STATE"] != "Y"):
		?><span class="forum-header-title-closed">[ <span><?=GetMessage("F_CLOSED")?></span> ]</span> <?
	endif;
	?><?=trim($arResult["TOPIC"]["TITLE"])?><?
 		if (strlen($arResult["TOPIC"]["DESCRIPTION"])>0):
			?>, <?=trim($arResult["TOPIC"]["DESCRIPTION"])?><?
		endif;
?>
	</span></div>
<?
else:
?>
	<div class="forum-header-title"><span><?=GetMessage("F_POSTS")?></span></div>
<?
endif;
?>
</div>

<div class="forum-block-container">
	<div class="forum-block-outer">
	<!--FORUM_INNER--><div class="forum-block-inner">
<?
if (!empty($arResult["MESSAGE_LIST"]))
{
				__forum_default_template_show_message($arResult["MESSAGE_LIST"], $message, $arResult, array("single_message" => "N"), $arParams, $this);
?>
				 <tfoot>
					<tr>
						<td colspan="5" class="forum-column-footer">
							<div class="forum-footer-inner">
<?
if ($arResult["USER"]["RIGHTS"]["MODERATE"] == "Y"):
?>
								<?=bitrix_sessid_post()?>
								<input type="hidden" name="type" value="messages" />
								<input type="hidden" name="PAGE_NAME" value="read" />
								<input type="hidden" name="FID" value="<?=$arParams["FID"]?>" />
								<input type="hidden" name="TID" value="<?=$arParams["TID"]?>" />
								<input type="hidden" name="ACTION" value="" />
								<div class="forum-post-moderate">
									&nbsp;&nbsp;<span class="forum-footer-option forum-footer-selectall forum-footer-option-first"><?
								?><noindex><a rel="nofollow" href="javascript:void(0);" onclick="SelectPosts('<?=$arParams["iIndex"]?>');" name=""><?=GetMessage("F_SELECT_ALL")?></a></noindex></span>
								</div>
								<div class="forum-post-moderate">
									<select name="ACTION_MESSAGE">
										<option value=""><?=GetMessage("F_MANAGE_MESSAGES")?></option>
										<option value="HIDE"><?=GetMessage("F_HIDE_MESSAGES")?></option>
										<option value="SHOW"><?=GetMessage("F_SHOW_MESSAGES")?></option>
										<option value="MOVE"><?=GetMessage("F_MOVE_MESSAGES")?></option>
<?
	if ($arResult["USER"]["RIGHTS"]["EDIT"] == "Y"):
?>
										<option value="DEL"><?=GetMessage("F_DELETE_MESSAGES")?></option>
<?
	endif;
?>
									</select>&nbsp;<input onmousedown="this.form.type.value='messages';this.form.ACTION.value=this.form.ACTION_MESSAGE.value;" <?
										?>type="submit" value="OK" />
								</div>
								<div class="forum-topic-moderate">
									<select name="ACTION_TOPIC">
										<option value=""><?=GetMessage("F_MANAGE_TOPIC")?></option>
										<option value="<?=($arResult["TOPIC"]["APPROVED"] == "Y" ? "HIDE_TOPIC" : "SHOW_TOPIC")?>"><?
											?><?=($arResult["TOPIC"]["APPROVED"] == "Y" ? GetMessage("F_HIDE_TOPIC") : GetMessage("F_SHOW_TOPIC"))?></option>
										<option value="<?=($arResult["TOPIC"]["SORT"] != 150 ? "SET_ORDINARY" : "SET_TOP")?>"><?
											?><?=($arResult["TOPIC"]["SORT"] != 150 ? GetMessage("F_UNPINN_TOPIC") : GetMessage("F_PINN_TOPIC"))?></option>
										<option value="<?=($arResult["TOPIC"]["STATE"] == "Y" ? "STATE_N" : "STATE_Y")?>"><?
											?><?=($arResult["TOPIC"]["STATE"] == "Y" ? GetMessage("F_CLOSE_TOPIC") : GetMessage("F_OPEN_TOPIC"))?></option>
										<option value="MOVE_TOPIC"><?=GetMessage("F_MOVE_TOPIC")?></option>
<?
	if ($arResult["USER"]["RIGHTS"]["EDIT"] == "Y"):
?>
										<option value="EDIT_TOPIC"><?=GetMessage("F_EDIT_TOPIC")?></option>
										<option value="DEL_TOPIC"><?=GetMessage("F_DELETE_TOPIC")?></option>
<?
	endif;
?>
									</select>&nbsp;<input onmousedown="this.form.type.value='topic';this.form.ACTION.value=this.form.ACTION_TOPIC.value;" <?
										?>type="submit" value="OK" />
								</div>
<?
else:
?>
							&nbsp;
<?
endif;
?>
							</div>
						</td>
					</tr>
				</tfoot>
<?$lastMessage = end($arResult['MESSAGE_LIST']);?>
			</table><!--MSG_END_<?=$lastMessage['ID']?>-->
<?
if ($arResult["USER"]["RIGHTS"]["MODERATE"] == "Y"):
?>
		</form>
<?
endif; 
}
?>
		</div><!--FORUM_INNER_END-->
	</div>
</div>
<?
if ($arResult["NAV_RESULT"] && $arResult["NAV_RESULT"]->NavPageCount > 0):
?>
<div class="forum-navigation-box forum-navigation-bottom">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?
if ($arResult["USER"]["RIGHTS"]["ADD_MESSAGE"] == "Y"):
?>
	<div class="forum-new-post">
		<a href="#postform" onclick="return fReplyForm();"><span><?=GetMessage("F_REPLY")?></span></a>
	</div>
<?
endif;
?>
	<div class="forum-clear-float"></div>
</div>

<?
endif;
if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-success">
	<div class="forum-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></div>
</div>
<?
endif;

// View new posts
if ($arResult["VIEW"] == "Y"):
?>
<div class="forum-preview">
<div class="forum-header-box">
	<div class="forum-header-title"><span><?=GetMessage("F_VIEW")?></span></div>
</div>

<div class="forum-info-box forum-post-preview">
	<div class="forum-info-box-inner">
		<div class="forum-post-entry">
			<div class="forum-post-text"><?=$arResult["MESSAGE_VIEW"]["TEXT"]?></div>
<?
		if (!empty($arResult["MESSAGE_VIEW"]["FILES"])):
?>
			<div class="forum-post-attachments">
				<label><?=GetMessage("F_ATTACH_FILES")?></label>
<?
			foreach ($arResult["MESSAGE_VIEW"]["FILES"] as $arFile): 
?>
				<div class="forum-post-attachment"><?
				?><?$GLOBALS["APPLICATION"]->IncludeComponent(
					"bitrix:forum.interface", "show_file",
					Array(
						"FILE" => $arFile,
						"WIDTH" => $arResult["PARSER"]->image_params["width"],
						"HEIGHT" => $arResult["PARSER"]->image_params["height"],
						"CONVERT" => "N",
						"FAMILY" => "FORUM",
						"SINGLE" => "Y",
						"RETURN" => "N",
						"SHOW_LINK" => "Y"),
					null,
					array("HIDE_ICONS" => "Y"));
				?></div>
<?
			endforeach;
?>
			</div>
<?
		endif;
?>
		</div>
	</div>
</div>
</div>
<?
endif;
	

?><script type="text/javascript">
<?if (intVal($arParams["MID"]) > 0):?>
location.hash = 'message<?=$arParams["MID"]?>';
<?endif;?>
if (typeof oText != "object")
	var oText = {};
oText['cdt'] = '<?=CUtil::addslashes(GetMessage("F_DELETE_TOPIC_CONFIRM"))?>';
oText['cdm'] = '<?=CUtil::addslashes(GetMessage("F_DELETE_CONFIRM"))?>';
oText['cdms'] = '<?=CUtil::addslashes(GetMessage("F_DELETE_MESSAGES_CONFIRM"))?>';
oText['ml'] = '<?=CUtil::addslashes(GetMessage("F_ANCHOR_TITLE"))?>';
oText['no_data'] = '<?=CUtil::addslashes(GetMessage('JS_NO_MESSAGES'))?>';
oText['no_action'] = '<?=CUtil::addslashes(GetMessage('JS_NO_ACTION'))?>';
oText['quote_text'] = '<?=CUtil::addslashes(GetMessage("JQOUTE_AUTHOR_WRITES"));?>';
oText['show'] = '<?=CUtil::addslashes(GetMessage("F_SHOW"))?>';
oText['hide'] = '<?=CUtil::addslashes(GetMessage("F_HIDE"))?>';
oText['wait'] = '<?=CUtil::addslashes(GetMessage("F_WAIT"))?>';
if (typeof phpVars != "object")
	var phpVars = {};
phpVars.bitrix_sessid = '<?=bitrix_sessid()?>';

if (typeof oForum != "object")
	var oForum = {};
oForum.page_number = <?=intval($arResult['PAGE_NUMBER']);?>;
oForum.topic_read_url = '<?=CUtil::JSUrlEscape($arResult['CURRENT_PAGE']);?>';

function reply2author(name)
{
    name = name.replace(/&lt;/gi, "<").replace(/&gt;/gi, ">").replace(/&quot;/gi, "\"");

	if (window.oLHE)
	{
		var content = '';
		if (window.oLHE.sEditorMode == 'code')
			content = window.oLHE.GetCodeEditorContent();
		else
			content = window.oLHE.GetEditorContent();
<? if ($arResult["FORUM"]["ALLOW_BIU"] == "Y") { ?>
		content += "[B]"+name+"[/B]";
<? } ?>
		content += " \n";
		if (window.oLHE.sEditorMode == 'code')
			window.oLHE.SetContent(content);
		else
			window.oLHE.SetEditorContent(content);
		setTimeout(function() { window.oLHE.SetFocusToEnd();}, 300);
	} 

	return false;
}
<?
if ($GLOBALS["USER"]->IsAuthorized() && $bShowedHeader):
?>
function ShowFirstPost(oA)
{
	var div = oA.parentNode.parentNode.parentNode.nextSibling.firstChild;
	if (div.style.display == 'none')
	{
		div.style.display = '';
		oA.innerHTML = '<?=CUtil::JSEscape(GetMessage("F_COLLAPSE"))?>';
	}
	else
	{
		div.style.display = 'none';
		oA.innerHTML = '<?=CUtil::JSEscape(GetMessage("F_SHOW"))?>';
	}
	
	var TID = CPHttpRequest.InitThread();
	CPHttpRequest.SetAction(TID, function(){});
	CPHttpRequest.Send(TID, '/bitrix/components/bitrix/forum/templates/.default/user_settings.php', 
	{"save":'first_post', "value":(div.style.display == 'none' ? 'hide' : 'show'), "sessid":'<?=bitrix_sessid()?>'});
	return false;
}
<?
endif;
?>
</script>
