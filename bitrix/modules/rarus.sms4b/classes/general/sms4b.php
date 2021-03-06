<? 
require_once("CSms4bBase.php");

class CSms4BitrixWrapper extends Csms4bBase {
	
	//address of non default functions
	protected $arFuncAddr = array("CheckUser" => array("addr" => "/webservices/bitrix.asmx", "client" => "for bitrix"),
								"LoadOutReports" => array("addr" => "/webservices/bitrix.asmx", "client" => "for bitrix"),
								"ChangePassword" => array("addr" => "/webservices/bitrix.asmx", "client" => "for bitrix"),
								"ChangeUserPassword" => array("addr" => "/webservices/bitrix.asmx", "client" => "for bitrix"),
								);
								
	function CSms4BitrixWrapper()
	{
		$info = CModule::CreateModuleObject('rarus.sms4b');
		
		$this->login = " b" . $info->MODULE_VERSION . " " . COption::GetOptionString("rarus.sms4b", "login");
		$this->password = COption::GetOptionString("rarus.sms4b", "password");
		$this->gmt = COption::GetOptionString("rarus.sms4b", "gmt");

		$this->serv_addr = COption::GetOptionString("rarus.sms4b", "host");
		$this->serv_port = COption::GetOptionString("rarus.sms4b", "port");

		$this->proxy_host = COption::GetOptionString("rarus.sms4b", "proxy_host");
		$this->proxy_port = COption::GetOptionString("rarus.sms4b", "proxy_port");
		$this->proxy_use = COption::GetOptionString("rarus.sms4b", "proxy_use");

		$this->inc_date = COption::GetOptionString("rarus.sms4b", "inc_date");

		$this->UpdateSID();

		//now check if default number is correct
		if (COption::GetOptionString("rarus.sms4b", "defsender") <> '')
		{   
			$current_def_num = COption::GetOptionString("rarus.sms4b", "defsender");
			if (!in_array($current_def_num,$this->arBalance["Addresses"]))
			{   
				COption::SetOptionString("rarus.sms4b","defsender",$this->arBalance["Addresses"][0]);
			}
		}
		
		//setting default sender
		if (COption::GetOptionString("rarus.sms4b", "defsender") <> '') 
		{
			$this->DefSender = COption::GetOptionString("rarus.sms4b", "defsender");
		}
		else
		{
			$this->DefSender = $this->arBalance["Addresses"][0];
			COption::SetOptionString("rarus.sms4b","defsender",$this->arBalance["Addresses"][0]);
		}

		$this->use_translit = COption::GetOptionString("rarus.sms4b", "use_translit");

		$this->sms_sym_count = COption::GetOptionString("rarus.sms4b", "sms_sym_count");

		return;
	}

	/**********************************
SMS functions
**********************************/

/************************************
returns ready state xml list of parameters
************************************/
	protected function getbodyrec($funcname='',$param=array(),$nameclient)
	{
		$bodyrec = '<'.$funcname.' xmlns="SMS '.$nameclient.'">'."\r\n";

		foreach ($param as $name => $val)
		{
			//���� ������� SaveMessages, �� � ��� �������� "list" ����������� ������������, �������
		//��������� ��� ��� ��������� ��������� ������� ....
			if ($funcname == "SaveMessages" && $name == "List")
			{
				$head_schema = '<List>
<xsd:schema id="NewDataSet" xmlns="" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata">
<xsd:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
<xsd:complexType>
<xsd:choice minOccurs="0" maxOccurs="unbounded">
<xsd:element name="Table1">
<xsd:complexType>
<xsd:sequence>
<xsd:element name="SessionID" type="xsd:int" minOccurs="0" />
<xsd:element name="guid" type="xsd:string" minOccurs="0" />
<xsd:element name="StartUp" type="xsd:string" minOccurs="0" />
<xsd:element name="Period" type="xsd:string" minOccurs="0" />
<xsd:element name="Destination" type="xsd:string" minOccurs="0" />
<xsd:element name="Source" type="xsd:string" minOccurs="0" />
<xsd:element name="Body" type="xsd:string" minOccurs="0" />
<xsd:element name="Encoded" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="dton" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="dnpi" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="ston" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="snpi" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="TimeOff" type="xsd:string" minOccurs="0" />
<xsd:element name="Priority" type="xsd:unsignedByte" minOccurs="0" />
<xsd:element name="NoRequest" type="xsd:string" minOccurs="0" />
</xsd:sequence>
</xsd:complexType>
</xsd:element>
</xsd:choice>
</xsd:complexType>
</xsd:element>
</xsd:schema>
<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
<NewDataSet xmlns="">
';
				$sms = array();
				$sms = $val;

				$i=1;
				$bodyrec.= $head_schema;
				foreach($sms as $key=>$value)
				{
					$bodyrec.= '<Table1 diffgr:id="Table1%table_num%" msdata:rowOrder="0" diffgr:hasChanges="inserted">'."\r\n";
					$bodyrec = str_replace("%table_num%",$i,$bodyrec);
					$i++;
					foreach($value as $xml_tag_name=>$xml_tag_value)
					{
						$bodyrec .= '<'.$xml_tag_name.'>'.$xml_tag_value.'</'.$xml_tag_name.'>'."\r\n";
					}
					$bodyrec.= '</Table1>'."\r\n";
				}

				$bodyrec.= '</NewDataSet>'."\r\n";
				$bodyrec.= '</diffgr:diffgram>'."\r\n";
				$bodyrec.= '</List>'."\r\n";
			}
			else if ($funcname == "SaveGroup" && $name == "List")
			{
			$head_schema = '<List>
  <xsd:schema id="NewDataSet" xmlns="" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata">
   <xsd:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
	<xsd:complexType>
	 <xsd:choice minOccurs="0" maxOccurs="unbounded">
	  <xsd:element name="Table1">
	   <xsd:complexType>
		<xsd:sequence>
		 <xsd:element name="G" type="xsd:string" minOccurs="0" />
		 <xsd:element name="D" type="xsd:string" minOccurs="0" />
		 <xsd:element name="T" type="xsd:int" minOccurs="0" />
		 <xsd:element name="N" type="xsd:int" minOccurs="0" />
		 <xsd:element name="E" type="xsd:int" minOccurs="0" />
		 <xsd:element name="B" type="xsd:string" minOccurs="0" />
		</xsd:sequence>
	   </xsd:complexType>
	  </xsd:element>
	 </xsd:choice>
	</xsd:complexType>
   </xsd:element>
  </xsd:schema>
  <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
	<NewDataSet xmlns="">
';
			$sms = array();
			$sms = $val;

			$i=1;
			$bodyrec.= $head_schema;
			foreach($sms as $key=>$value)
			{
				$bodyrec.= '<Table1 diffgr:id="Table1%table_num%" msdata:rowOrder="0" diffgr:hasChanges="inserted">'."\r\n";
				$bodyrec = str_replace("%table_num%",$i,$bodyrec);
				$i++;
				foreach($value as $xml_tag_name=>$xml_tag_value)
				{
					$bodyrec .= '<'.$xml_tag_name.'>'.$xml_tag_value.'</'.$xml_tag_name.'>'."\r\n";
				}
				$bodyrec.= '</Table1>'."\r\n";
			}

			$bodyrec.= '</NewDataSet>'."\r\n";
			$bodyrec.= '</diffgr:diffgram>'."\r\n";
			$bodyrec.= '</List>'."\r\n";
			}
			else
			{
				$bodyrec .= '<'.$name.'>'.$val.'</'.$name.'>'."\r\n";
			}
		}
		$bodyrec .= '</'.$funcname.'>'."\r\n";
		$bodyrec = $this->xml_header.$bodyrec.$this->xml_footer;
		return $bodyrec;
	}


		/*************************
	Making request to the server
	where
	$funcname - name of function-webservice on the server,
	$param - params for $funcname-function
	*************************/
	
	public function GetSOAP ($funcname='',$param=array())
	{
		$this->LastError = '';
		$response = $this->makeRequest( $funcname,
										$param,
										(array_key_exists($funcname,$this->arFuncAddr) ? $this->arFuncAddr[$funcname]["client"] : $this->defClient),
										(array_key_exists($funcname,$this->arFuncAddr) ? $this->arFuncAddr[$funcname]["addr"] : $this->defAddr)
									);
		if ($response != -1)
		{
			switch ($funcname)
			{
				case "StartSession":
						if ($this->sid > 0)
						{
							return true;
						}
						
						$this->sid = $this->StartSession($response);
						
						if (!$this->sid < 0)
						{
							$this->LastError = "����������� ������ ��������� ������.";
							return false;
						}
						elseif($this->sid === 0)
						{
							$this->LastError = "���c�� ��� �����������, �� ����� ������ �������.";
							return false;
						}
						else
						{
							COption::SetOptionString("rarus.sms4b", "sid", $this->sid);
							$this->LastError = '';
							return true;
						}
				break;

				case "LoadMessage":
						$result = $this->LoadMessage($response);

						if ($result["Result"] < 0)
						{
							$this->LastError = "������ ������ LoadMessage";
							return -1;
						}
						elseif ($result["Result"] == 0)
						{
							return 0;
						}
						else
						{
							return $result;
						}
				break;

				case "LoadResponse":
						$result = $this->LoadResponse($response);
						return $result;
				break;

				case "CloseSession":
					if ($this->GetSID > 1)
					{
						$closeid = $this->CloseSession($response);
						if($closeid > 0)
						{
							$this->LastError = "";
							return true;
						}
						elseif($closeid === 0)
						{
							$this->LastError = "";
							return true;
						}
						else
						{
							$this->LastError = "�� ������� ������� ������. ������ ����� �".$closeid;
							return false;
						}
					}
					else
					{
						$this->LastError = "��� �������� ������";
						return false;
					}
				break;

				case "AccountParams":
						if($this->AccountParams($response))
							return true;
						else
							return false;
				break;

				case "SaveMessage":

						$saveMessageResult = $this->SaveMessage($response);
	
						if ($saveMessageResult > 0)
						{
							$ok = '';
							$ok = intval($saveMessageResult);
							$arrSaveres["SEND"] = 255 & $ok;
							$arrSaveres["OK"] = 255 & ($ok >> 8);
							
							return $arrSaveres;
						}
						else
						{
							$this->AnalyzeResultSaveMessage($saveMessageResult);
							return false;
						}

				break;
				
				case "SaveMessages":
					
					$saveMessagesResult = $this->SaveMessages($response);
					return $saveMessagesResult;
					
				break;
				case "SaveGroup":
					return $this->SaveGroup($response);
				break;

				case "CheckUser":
						$res = $this->CheckUser($response);
						if($res >= 0)
						{
							$this->LastError = "";
							$this->user_check = true;
							return true;
						}
						else
						{
							$this->LastError = '������ ��������';
							$this->user_check = false;
							return false;
						}
				break;

				case "ChangePassword":

						$res = $this->ChangePassword($response);

						if($res > 0)
						{
							$this->LastError = "";
							$old_pass = $this->password;
							$this->password = $param["NewPassword"];
							$this->user_check = true;

							$sms4b_db->Update(array("ID" => $this->uid, "Login" => $this->login, "Password" => $this->password, "OldPassword"=>$old_pass));
							return true;
						}
						else
						{
							$this->LastError = '������ ����� ������';
							$this->user_check = false;
							return false;
						}
				break;

				case "ChangeUserPassword":
						$res = $this->ChangeUserPassword($response);
						if($res > 0)
						{
							$this->LastError = "";
							return true;
						}
						else
						{
							$this->LastError = '������ ����� ����������������� ������';
							return false;
						}
				break;


				case "LoadOutReports":
						$LoadOutReportsResult = $this->LoadOutReports($response);
						return $LoadOutReportsResult;
				break;

				case "LoadIn":

						$LoadInResult = $this->LoadIn($response);
						if(count($LoadInResult) > 0)
							return $LoadInResult;
						else
							return false;
				break;

				default:
				{
						$this->LastError = "����������� �������";
						return false;
				}
			}//endswitch
		}
		else
		{
				$this->LastError = '�� ������� ����������� � ������� SMS4B';
				return false;
		}//endif
	}//endfunction

	/*************************
	function-parsers
	**************************/
	/*read incoming messages*/
	protected function LoadIn($xml)
	{
		$param_array =
		array("GUID","Moment","TimeOff","Source","Destination","Coding","Body","Total","Part");

		$resultArray = $this->ParserTableResp($xml,$param_array);
		//��������� �� �������� ���-��� (�.� ����� ��� �� ���������) 
		if (count($resultArray[0])!= 9)
			return 0;
		else
			return $resultArray;
	}

	
	/*parse server response*/
	protected function LoadMessage($xml)
	{

		$param_array =
		array("Result","MessageID","GUID","TimeOff","Moment","SrcTON","SrcNPI","Source",
		"DstTON","DstNPI","Destination","Coding","Body","Total","Part","SMSCID","Receiption"
		,"NeedAnswer");

		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		$resultArray = $this->ParserResp($xml,$param_array);

		return $resultArray;
	}

	/*parse server response*/
	protected function LoadResponse($xml)
	{
		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		preg_match("/<LoadResponseResult>([0-9]+?)<\/LoadResponseResult>/",$xml,$find);
		$loadResponseResult = intval($find[1]);
		return $loadResponseResult;
	}

	/*parse server response*/
	protected function CheckUser($xml)
	{
		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		preg_match("/<CheckUserResult>([\-0-9]+?)<\/CheckUserResult>/",$xml,$find);
		if (is_numeric($find[1]))
			$sid = intval($find[1]);
		else
			$sid = false;
		return $sid;
	}

	/*parse server response*/
	protected function ChangePassword($xml)
	{
		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		preg_match("/<ChangePasswordResult>([\-0-9]+?)<\/ChangePasswordResult>/",$xml,$find);
		$sid = intval($find[1]);
		return $sid;
	}

	/*parse server response*/
	protected function ChangeUserPassword($xml)
	{
		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		preg_match("/<ChangeUserPasswordResult>([\-0-9]+?)<\/ChangeUserPasswordResult>/",$xml,$find);
		$sid = intval($find[1]);
		return $sid;
	}
	
	/*parse server response*/
	protected function LoadOutReports($xml)
	{
		$xml = str_replace("\r\n",'',$xml);
		$xml = str_replace("\n",'',$xml);

		$this->LastError = '';

		preg_match_all("/<Table.+?>(.+?)<\/Table>/i",$xml,$find);

		foreach($find[1] as $key => $val)
		{
			$arReports[] = $this->ParserResp($val,array(	"SenderName",
															"Destination",
															"PutInTurn",
															"StartSend",
															"LastModified",
															"Status",
															"ID",
															"GUID",
															"CountPart",
															"CodeType",
															"TextMessage",
														)
											);
		}
		return $arReports;
	}

	/*codes one symbol
	$symbol: symbol for coding (for example 'a')
	$type_of_encoding:
	 0-DefaultAlphabet
	 1-UTF16*/
	public function enCoding($symbol,$type_of_encoding)
	{
		if (strlen($symbol)==0 || $type_of_encoding > 1  || $type_of_encoding < 0)
		{
			$this->LastError = "������� ������� ��������� ������ ������� enCoding";
			return false;
		}
		else
		{
			switch($type_of_encoding)
			{
				case 0:
					if ($symbol == "@")
					{
						return "00";
						break;
					}
					elseif ($symbol =="$")
					{
						return "02";
						break;
					}

					$code = ord($symbol);
					$str16x = "";

					for($i = 0; $i < 8; $i++)
					{
						$bit = $code & 1;
						switch ($bit)
						{
							case 0: $str16x.="0";break;
							case 1: $str16x.="1";break;
						}
						$code = $code >> 1;
					}

					$str16x = strrev($str16x);

					$high_part = $str16x[0].$str16x[1].$str16x[2].$str16x[3];
					$low_part  = $str16x[4].$str16x[5].$str16x[6].$str16x[7];

					$high_part = $this->bin_to_hex($high_part);
					$low_part = $this->bin_to_hex($low_part);

					$str16x = $high_part.$low_part;
					return $str16x;
					break;
				case 1:
					if (defined('BX_UTF') && BX_UTF)
					{
						$symbol = mb_convert_encoding($symbol, "UTF-16", "UTF-8");
					}
					else
					{
						$symbol = mb_convert_encoding($symbol, "UTF-16", "Windows-1251");						                                                         
					}

					$code = (ord($symbol[0])*256+ord($symbol[1]));

					$str16x = "";

					for($i = 0; $i < 16; $i++)
					{
						$bit = $code & 1;
						switch ($bit)
						{
							case 0: $str16x.="0";break;
							case 1: $str16x.="1";break;
						}
						$code = $code >> 1;
					}


					$str16x = strrev($str16x);

					$first_part = $str16x[0].$str16x[1].$str16x[2].$str16x[3];
					$second_part  = $str16x[4].$str16x[5].$str16x[6].$str16x[7];
					$third_part = $str16x[8].$str16x[9].$str16x[10].$str16x[11];
					$fourth_part = $str16x[12].$str16x[13].$str16x[14].$str16x[15];

					$first_part = $this->bin_to_hex($first_part);
					$second_part = $this->bin_to_hex($second_part);
					$third_part = $this->bin_to_hex($third_part);
					$fourth_part = $this->bin_to_hex($fourth_part);

					$str16x = $first_part.$second_part.$third_part.$fourth_part;
					return $str16x;
					break;
			}//end switch
		}//end if
	}

	//function for coding the hole message
	public function enCodeMessage($message)
	{
		if ($message == "")
		{
			$this->LastError= "� ��������� �������� �������!";
			return false;
		}
		else
		{

			$type_of_encoding=$this->get_type_of_encoding($message);
			
			if (!defined('BX_UTF'))
			{
				for($i = 0; $i < strlen($message);$i++)
				{
					$encoded_string.=$this->enCoding($message[$i],$type_of_encoding);
				}
			}
			else
			{ 
				for($i = 0; $i < strlen($message);$i++)
				{
					$encoded_string.=$this->enCoding(mb_substr($message,$i,1),$type_of_encoding);
				}
			}

			return $encoded_string;
		}
	}

	//events handler
	public function GetPhoneOrder($id_order)
	{
		$db_vals = CSaleOrderPropsValue::GetList(
			array("SORT" => "ASC"),
			array(
					"ORDER_ID" => $id_order,
					"CODE" => "sms_events"
					)
		);
		if ($arrOrder = $db_vals->Fetch())
			return $arrOrder["VALUE"];
		else
			false;
	}

	public function GetEventTemplate($id_template)
	{
		$arFilter = Array("TYPE_ID" => $id_template);
		$rsMess = CEventMessage::GetList($by="site_id", $order="desc", $arFilter);
		if ($text = $rsMess->fetch())
			return $text;
		else
			false;
	}

	//events handler
	public function Events($event_name, $site, &$params)
	{
		global $SMS4B;
		
		$save_event_name = $event_name; 
		
		if(preg_match("/^SALE_STATUS_CHANGED_(.+?){1}$/",$event_name,$find))
		{
			$event_name = "SALE_STATUS_CHANGED";
			$id_sale_status_changed = $find[1];
		}

		switch ($event_name)
		{
			//when status changed
			case "SALE_STATUS_CHANGED":
				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_sale_status_changed");

				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name.'_'.$id_sale_status_changed);
					$phone_num = $SMS4B->GetPhoneOrder($params["ORDER_ID"]);
					if ($phone_num && is_array($text))
					if ($phone_num = $SMS4B->is_phone($phone_num))
					{
						$text["MESSAGE"] = str_replace("#ORDER_ID#", $params["ORDER_ID"],$text["MESSAGE"]);
						$text["MESSAGE"] = str_replace("#ORDER_DESCRIPTION#", $params["ORDER_DESCRIPTION"], $text["MESSAGE"]);
						$text["MESSAGE"] = str_replace("#TEXT#", $params["TEXT"],$text["MESSAGE"]);
						
						$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]) : $text["MESSAGE"], $phone_num, false, $params["ORDER_ID"], false, $event_name.'_'.$id_sale_status_changed);
					}
				}
				
				//return template
				$event_name = $save_event_name;
			break;
			
			//when registration on delivery 
			case "SUBSCRIBE_CONFIRM":
				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_subscribe_confirm");
			  
				if ($b_send == "Y")
				{
					 
					if(preg_match("/^([\+\-\(\)0-9]+?)@phone.sms$/i",$params["EMAIL"],$find))
					{
						$phone_num = $find[1];
						$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);

						if ($phone_num && is_array($text))
						if ($phone_num = $SMS4B->is_phone($phone_num))
						{
							$text["MESSAGE"] = str_replace("#ID#",$params["ID"],$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PHONE_TO#",$phone_num,$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PHONE#",$phone_num,$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#CONFIRM_CODE#",$params["CONFIRM_CODE"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#SUBSCR_SECTION#",'', $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#USER_NAME#", $params["USER_NAME"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#DATE_SUBSCR#",$params["DATE_SUBSCR"], $text["MESSAGE"]);
							$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]):$text["MESSAGE"], $phone_num, false, false, false, $event_name);
							global $APPLICATION;
							$params["EMAIL"] = '';
							$APPLICATION->throwException("SMS � ����� ������������ ����������");
							return false;
						}
					}
				}
			break;
			case "SALE_RECURRING_CANCEL":
			break;
			case "SALE_ORDER_PAID":
				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_sale_order_paid");

				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);
					$phone_num = $SMS4B->GetPhoneOrder($params["ORDER_ID"]);
					if ($phone_num && is_array($text))
					{
						if ($phone_num = $SMS4B->is_phone($phone_num))
						{
							$text["MESSAGE"] = str_replace("#ORDER_ID#", $params["ORDER_ID"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_DESCRIPTION#", $params["ORDER_DESCRIPTION"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#TEXT#", $params["TEXT"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PRICE#", $params["PRICE"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_CANCEL_DESCRIPTION#", $params["ORDER_CANCEL_DESCRIPTION"], $text["MESSAGE"]);
							$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]):$text["MESSAGE"], $phone_num,false,$params["ORDER_ID"],false,$event_name);
						}
					}
				}
			break;
			case "SALE_ORDER_DELIVERY":

				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_sale_order_delivery");

				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);
					$phone_num = $SMS4B->GetPhoneOrder($params["ORDER_ID"]);
					if ($phone_num && is_array($text))
					{
						if ($phone_num = $SMS4B->is_phone($phone_num))
						{
							$text["MESSAGE"] = str_replace("#ORDER_ID#", $params["ORDER_ID"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_DESCRIPTION#", $params["ORDER_DESCRIPTION"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#TEXT#", $params["TEXT"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PRICE#", $params["PRICE"], $text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_CANCEL_DESCRIPTION#", $params["ORDER_CANCEL_DESCRIPTION"], $text["MESSAGE"]);
							$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]):$text["MESSAGE"], $phone_num, false, $params["ORDER_ID"], false, $event_name);
						}
					}
				}
			break;
			case "SALE_ORDER_CANCEL":
			
				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_sale_order_cancel");

				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);
					$phone_num = $SMS4B->GetPhoneOrder($params["ORDER_ID"]);
					if ($phone_num && is_array($text))
					{
						if ($phone_num = $SMS4B->is_phone($phone_num))
						{
							$text["MESSAGE"] = str_replace("#ORDER_ID#",$SMS4B->Translit($params["ORDER_ID"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_DESCRIPTION#",$SMS4B->Translit($params["ORDER_DESCRIPTION"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#TEXT#",$SMS4B->Translit($params["TEXT"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PRICE#",$SMS4B->Translit($params["PRICE"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_CANCEL_DESCRIPTION#",$SMS4B->Translit($params["ORDER_CANCEL_DESCRIPTION"]),$text["MESSAGE"]);
							$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]):$text["MESSAGE"], $phone_num, false, $params["ORDER_ID"], false, $event_name);
						}
					}
				}
			break;
			case "SALE_NEW_ORDER":
			
				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_sale_new_order");
				
				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);
					$phone_num = $SMS4B->GetPhoneOrder($params["ORDER_ID"]);
					if ($phone_num && is_array($text))
					{
						if ($phone_num = $SMS4B->is_phone($phone_num))
						{
							$text["MESSAGE"] = str_replace("#ORDER_ID#",$SMS4B->Translit($params["ORDER_ID"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_DESCRIPTION#",$SMS4B->Translit($params["ORDER_DESCRIPTION"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#TEXT#",$SMS4B->Translit($params["TEXT"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#PRICE#",$SMS4B->Translit($params["PRICE"]),$text["MESSAGE"]);
							$text["MESSAGE"] = str_replace("#ORDER_CANCEL_DESCRIPTION#",$SMS4B->Translit($params["ORDER_CANCEL_DESCRIPTION"]),$text["MESSAGE"]);
							$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text["MESSAGE"]):$text["MESSAGE"], $phone_num, false, $params["ORDER_ID"], false, $event_name);
						}
					}
				}
			break;
			
			//tech support new ticket and ticket change
			case "TICKET_NEW_FOR_TECHSUPPORT":
			case "TICKET_CHANGE_FOR_TECHSUPPORT":

				$b_send = '';
				$b_send = COption::GetOptionString("rarus.sms4b", "event_ticket_new_for_techsupport");
				
				if ($b_send == "Y")
				{
					$text = $SMS4B->GetEventTemplate("SMS4B_".$event_name);
					
					//take groups id of support-group and admins
					$sgroup = array_merge(CTicket::GetGroupsByRole("T"),CTicket::GetGroupsByRole("W"));
					
					$filter = Array("ACTIVE" => "Y");
					
					//���� ���� ��������������, �� �������� ����������� �������������� ��� ���������� �� � ������� �� ���������
					if($params["RESPONSIBLE_USER_ID"] == '')
					{
						$filter["GROUPS_ID"] = $sgroup;
						$filter["EMAIL"] = $params["SUPPORT_EMAIL"];
					}
					else
						$filter["ID"] = $params["RESPONSIBLE_USER_ID"];
										
					$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter); //take users
					
					while($ob = $rsUsers->Fetch())
					{				
						$phone_num = $ob["WORK_PHONE"];
						
						if ($phone_num && is_array($text))
						{
							if ($phone_num = $SMS4B->is_phone($phone_num))
							{
								$text2 = $text;
								$text2["MESSAGE"] = str_replace("#ID#", $params["ID"], $text2["MESSAGE"]);
								$text2["MESSAGE"] = str_replace("#PHONE_TO#", $phone_num, $text2["MESSAGE"]);
								$text2["MESSAGE"] = str_replace("#CRITICAL#", $params["CRITICALITY"], $text2["MESSAGE"]);
								$text2["MESSAGE"] = str_replace("#DATE_TICKET#",$params["DATE_CREATE"], $text2["MESSAGE"]);
								$text2["MESSAGE"] = str_replace("#WHAT_CHANGE#",$params["WHAT_CHANGE"], $text2["MESSAGE"]);
								$text2["MESSAGE"] = str_replace("#MESSAGE_BODY#",$params["MESSAGE_BODY"], $text2["MESSAGE"]);
								
								$SMS4B->SendSMS($SMS4B->use_translit == "Y"?$SMS4B->Translit($text2["MESSAGE"]):$text2["MESSAGE"], $phone_num, false, false, false, $event_name);
							}
						}
					}
				}
				
			break;
		}
	}
	
	public function AddIBlock(&$arFields)
	{
		global $USER,$SMS4B;

		$b_send = '';
		$b_add = COption::GetOptionString("rarus.sms4b", "event_corp_add_calendar");

		//�������� ID ��������, ������� �����������
		$resi = CIBlockElement::GetByID($arFields["ID"]);
		$ari = $resi->Fetch();
		
		$res_sec = CIBlockSection::GetByID($ari["IBLOCK_SECTION_ID"]);
		$ar_res_sec = $res_sec->Fetch();		
				
		if($b_add == "Y")
		{ 
			if(COption::GetOptionInt('intranet', 'iblock_calendar') == $ari["IBLOCK_ID"])
			{
				if($ar_res_sec["CREATED_BY"] <> $USER->GetID())
				{
					
					$rsUser = CUser::GetByID($ar_res_sec["CREATED_BY"]);
					$arUser = $rsUser->Fetch();

					if($phone_num = $SMS4B->is_phone($arUser["PERSONAL_MOBILE"]))
					{
						$text = "����� ������� � ����� �������� - ".$ari["NAME"].". "."
".$ari["DETAIL_TEXT"]; 
						
						$SMS4B->SendSMS($SMS4B->Translit($text), $phone_num, false, false, false, 'corp_add_calendar');
					}
				}
			}
		}
	}	

	public function UpdateIBlock(&$arFields)
	{
		global $USER,$SMS4B;
		
		$b_send = '';
		$b_update = COption::GetOptionString("rarus.sms4b", "event_corp_update_calendar");

		if($b_update == "Y")
		{
			if(COption::GetOptionInt('intranet', 'iblock_calendar') == $arFields["IBLOCK_ID"])
			{
				 if($arFields["CREATED_BY"] <> $USER->GetID())
				 {
					 $res = CIBlockElement::GetByID($arFields["ID"]);
					 $ar_res = $res->Fetch();
					 
					 $rsUser = CUser::GetByID($ar_res["CREATED_BY"]);
					 $arUser = $rsUser->Fetch();
			 
					 if($phone_num = $SMS4B->is_phone($arUser["PERSONAL_MOBILE"]))
					 {			 	
						$text = "��������� � ������� - ".$arFields["NAME"]." �� ".$arFields["ACTIVE_FROM"].". "."
".$arFields["DETAIL_TEXT"]; 
						
						$SMS4B->SendSMS($SMS4B->Translit($text), $phone_num, false, false, false, 'corp_update_calendar');
					 }
				 }
			}
		}
	}

	public function EventsPosting($arFields)
	{
		//check sms or email dispatch
		$rsPosting = CPosting::GetByID($arFields["POSTING_ID"]);
		$arPosting = $rsPosting->Fetch();

		$rass_from = $arPosting["FROM_FIELD"];
		$rass_to = $arFields["EMAIL"];

		global $SMS4B;
		//�������� ������� ��  ���������� �����
		if(preg_match("/^([\+\-\(\)0-9a-zA-Z]+?)@phone.sms$/",$rass_from,$find) || strtoupper($rass_from) == 'PHONE@PHONE.SMS')
		{
			if(strtoupper($rass_from) == 'PHONE@PHONE.SMS')
				$rass_from = $SMS4B->DefSender;
			else
				$rass_from = $find[1];

			$phone_num = preg_match("/^([\+\-\(\)0-9]+?)@phone.sms$/",$rass_to,$find);

			$phone_num = $SMS4B->is_phone($find[1]);

			if ($rass_from && $SMS4B->is_phone($phone_num))
			{
				$rsPosting = CPosting::GetByID($arFields["POSTING_ID"]);
				$arPosting = $rsPosting->Fetch();
				$arPosting["SUBJECT"] .= '';
				
				//if enabled translit in parameters of module
				$mess = $SMS4B->use_translit == "Y" ? $SMS4B->Translit($arPosting["SUBJECT"]) : $arPosting["SUBJECT"];
				if ($mess <> '' ) $mess .= '. ';
				$mess .= $SMS4B->use_translit == "Y" ? $SMS4B->Translit($arFields["BODY"]) : $arFields["BODY"];

				//only if message of type "text"
				if($arPosting["BODY_TYPE"] == 'text')
					$SMS4B->SendSMS($mess,$phone_num,$rass_from,false,$arFields["POSTING_ID"]);
			}
			//return false;
		}
		//if destination - phone, and source - e-mail
		elseif(preg_match("/^([\+\-\(\)0-9]+?)@phone.sms$/",$rass_to,$find))
			return false;

		return $arFields;
	}

	/*sends sms*/
	public function SendSMS($message, $to, $sender='', $IDOrder = 0, $Posting = 0, $TypeEvents = '')
	{
		global $SMS4B;
		
		if($sender == '')
			$sender = $SMS4B->DefSender;
		
		$to = $SMS4B->is_phone($to);
		if(strlen($sender) > 0 && $SMS4B->is_phone($to) && strlen($message) > 0)
		{
			$ston = $SMS4B->get_ton($sender);
			$snpi = $SMS4B->get_npi($sender);

			$dton = $SMS4B->get_ton($to);
			$dnpi = $SMS4B->get_npi($to);
			
			$body = $SMS4B->enCodeMessage($message);
			$encoded = $SMS4B->get_type_of_encoding($message);
			$sess_id = $SMS4B->GetSID();
			$date_actual = date("Ymd H:i:s",(time()+86400*7));
			$outsms_guid = $SMS4B->CreateGuid();


			$params_sms = 	array(
						"SessionID" => $SMS4B->GetSID(),
						"guid" => $outsms_guid,
						"Destination" => $to,
						"Source" => $sender,
						"Body" => $body,
						"Encoded" => $encoded,
						"dton" => $dton,
						"dnpi" => $dton,
						"ston" => $ston,
						"snpi" => $snpi,
						"TimeOff" =>$date_actual,
						"Priority" => 0,
						"NoRequest" => 0
				);

			$resSendMess = $SMS4B->GetSOAP("SaveMessage",$params_sms);
			
			$arrparam[] = array(
								"GUID" => $outsms_guid,
								"SenderName" => $sender,
								"Destination" => $to,
								"StartSend" => date("Y-m-d H:i:s", time()),
								"LastModified" => date("Y-m-d H:i:s", time()),
								"CountPart" => $resSendMess["SEND"] > 0 ? $resSendMess["SEND"] : 0,
								"SendPart" => $resSendMess["OK"] > 0 ? $resSendMess["OK"] : 0,
								"CodeType" => $encoded,
								"TextMessage" => $message,
								"Sale_Order" => $IDOrder ? $IDOrder : 0,
								"Status" => 5,
								"Posting" => $Posting ? $Posting : 0,
								"Events" => $TypeEvents ? $TypeEvents : ''
			);

			$SMS4B->ArrayAdd($arrparam);

			if($resSendMess)
				return true;
			else
				return false;
		}
	}
	
	public function SendSmsPack($message, $to, $sender='', $startUp_p='', $dateActual_p='', $period_p = '')
	{
		global $SMS4B;
		
		$startUp = $startUp_p;
		$dateActual = $dateActual_p;
		$period = $period_p;
		
		//����������� �� ���������, �������� ���������
		$to = $this->parse_numbers($to);
		
		if($sender == '')
			$sender = $SMS4B->DefSender;
			
		$ston = $SMS4B->get_ton($sender);
		$snpi = $SMS4B->get_npi($sender);
		$body = $SMS4B->enCodeMessage($message);
		$encoded = $SMS4B->get_type_of_encoding($message);	
		$sms_package = array();
		
		if ($this->UpdateSID())
		{
			$sid = $this->sid;
		}
		
		//preparing data
		foreach($to as $arInd)
		{
			$dton = $SMS4B->get_ton($arInd);
			$dnpi = $SMS4B->get_npi($arInd);
			//���������� ���������
			$outsms_guid = $SMS4B->CreateGuid();
			
			$one_sms = '';
			$one_sms = array(
						"SessionID"		=> $sid,
						"guid"			=> $outsms_guid,/*guid*/
						"StartUp"		=> $startUp,	/*����� ���������� ��������*/
						"Period"		=> $period,		/*������ �����, ����� �������� ���������*/
						"Destination"	=> $arInd,		/*����� ����������*/
						"Source"		=> $sender,		/*����� ���������*//*"1C-WebSMS-R"*/
						"Body"			=> $body,		/*����� ���������*/
						"Encoded" 		=> $encoded,	/*��� ���������*/
						"dton" 			=> $dton,		/*��� ������ ����������*/
						"dnpi" 			=> $dton,		/*numeric plan indicator*/
						"ston" 			=> $ston,		/*��� ������ �����������*/
						"snpi" 			=> $snpi,		/*numeric plan indicator*/
						"TimeOff" 		=> $dateActual,	/*����� ������������ ���������*/
						"Priority"		=> 0,			/*���������*/
						"NoRequest"		=> $request		/*����� � ��������*/
				);
			
			$sms_package[] = $one_sms;
		}
		
		//����� ������ ���������� �������
		$results_of_package_send = array();
		//����� �������� ������ �������� �� ����� ��� ������� � ��������� ������ sms4b_bitrix maxPackage
		if (count($sms_package) < $SMS4B->maxPackage)
		{
			$results_of_package_send = $SMS4B->GetSOAP("SaveMessages",array("List"=>$sms_package));
			
			$sendingResults = array_reverse($results_of_package_send["FOR_ADDING_TO_BASE"]);
			
			$arrparam = array();
			$i = 0;
			
			foreach($sms_package as $arIndex)
			{
				$arrparam[] = array(
								"GUID" => $arIndex["guid"],
								"SenderName" => $arIndex["Source"],
								"Destination" => $arIndex["Destination"],
								"StartSend" => date("Y-m-d H:i:s", time()),
								"LastModified" => date("Y-m-d H:i:s", time()),
								"CountPart" => $sendingResults[$i]["SEND"],
								"SendPart" => $sendingResults[$i]["OK"],
								"CodeType" => $arIndex["Encoded"],
								"TextMessage" => $this->decode($arIndex["Body"],$arIndex["Encoded"]),
								"Sale_Order" => $IDOrder ? $IDOrder : 0,
								"Status" => 5,
								"Posting" => $Posting ? $Posting : 0,
								"Events" => $TypeEvents ? $TypeEvents : ''
								);
				$i++;
			}
			$SMS4B->ArrayAdd($arrparam);
		}
		else
		{
			//��������� ������ �� ����� ������ maxPackage
			$big_array = array_chunk($sms_package,$SMS4B->maxPackage,true);
			//������ ��� �������� ������� �� ������� �� ������� ��������� ���������
			$dest_numbers = array();
			$results_of_package_send["SEND"] = 0;
			$results_of_package_send["NOT_SEND"] = 0;
			
			//��������� �������� �� ����
			foreach($big_array as $arIndex)
			{
				$temp = array();
		
				//��� ������
				$temp = $SMS4B->GetSOAP("SaveMessages",array("List"=>$arIndex));
			
				
				$sendingResults = array_reverse($temp["FOR_ADDING_TO_BASE"]);
			
				$arrparam = array();
				
				$i = 0;
			
				foreach($arIndex as $arInd)
				{
					$arrparam[] = array(
									"GUID" => $arInd["guid"],
									"SenderName" => $arInd["Source"],
									"Destination" => $arInd["Destination"],
									"StartSend" => date("Y-m-d H:i:s", time()),
									"LastModified" => date("Y-m-d H:i:s", time()),
									"CountPart" => $sendingResults[$i]["SEND"],
									"SendPart" => $sendingResults[$i]["OK"],
									"CodeType" => $arInd["Encoded"],
									"TextMessage" => $this->decode($arInd["Body"],$arInd["Encoded"]),
									"Sale_Order" => $IDOrder ? $IDOrder : 0,
									"Status" => 5,
									"Posting" => $Posting ? $Posting : 0,
									"Events" => $TypeEvents ? $TypeEvents : ''
									);
					$i++;
				}
				
				$SMS4B->ArrayAdd($arrparam);
			
				//�������� �������������� ���������� �� "����������" ��������
				$results_of_package_send["WAS_SEND"] += $temp["WAS_SEND"];
				$results_of_package_send["NOT_SEND"] += $temp["NOT_SEND"];
				$dest_numbers = array_merge($dest_numbers,$temp["ARRAY_NUMBERS_ON_NOT_SEND"]); 	
			}
			$results_of_package_send["DEST_NOT_SEND"] = $dest_numbers;
						
		}
		
		return $results_of_package_send;
	}

	public function UpdateStatusSms ($ID)
	{
		$sms = $this->GetByID($ID);
		if($sms["id"] > 0 && ($sms["CountPart"] == 0 || $sms["CountPart"] <> $sms["SendPart"]))
		{
			if($sms["CountPart"] == 0)
			{				
				$ston = $this->get_ton($sms["SenderName"]);
				$snpi = $this->get_npi($sms["SenderName"]);

				$dton = $this->get_ton($sms["Destination"]);
				$dnpi = $this->get_npi($sms["Destination"]);

				$body = $this->enCodeMessage($sms["TextMessage"]);
				$encoded = $this->get_type_of_encoding($sms["TextMessage"]);

				$date_actual = date("Ymd H:i:s",(time()+86400*7));
				$sms["Destination"] = $this->is_phone($sms["Destination"]);
				$arrParam = array(
									"SessionID" => $this->sid,/*session*/
									"guid" => $sms["GUID"],/*guid*/
									"Destination" => $sms["Destination"],/*address of destination*/
									"Source" => $sms["SenderName"],/*address of sender*/
									"Body" => $body,/*message text*/
									"Encoded" => $encoded,/*encoding type of message*/
									"dton" => $dton,/*number type of destination address*/
									"dnpi" => $dnpi,/*numeric plan indicator*/
									"ston" => $ston,/*number type of sender address*/
									"snpi" => $snpi,/*numeric plan indicator*/
									"TimeOff" =>$date_actual,/*urgency time of message*/
									"Priority" => 0, /*priority*/
									"NoRequest" => 0/*delivery report*/
									);
				$sms["StartSend"] = date("Y-m-d H:i:s",time());
			}
			elseif($sms["CountPart"] <> $sms["SendPart"])
			{
				$arrParam = array(
									"SessionID" => $this->sid,
									"guid" => $sms["GUID"],
									"Destination" => '',
									"Source" => '',
									"Body" => '',
									"Encoded" => 0,
									"dton" => 0,
									"dnpi" => 0,
									"ston" => 0,
									"snpi" => 0,
									"TimeOff" =>0,
									"Priority" => 0, 
									"NoRequest" => 0
								);
			}
			
			$resSendMess = $this->GetSOAP("SaveMessage",$arrParam);

			$sms["StartSend"] = ($sms["StartSend"] == '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",time()) : $sms["StartSend"];
			$sms["LastModified"] = ($sms["LastModified"] == '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",time()) : $sms["LastModified"];

			$arrparam[] = array(
								"GUID" => $sms["GUID"],
								"SenderName" => $sms["SenderName"],
								"Destination" => $sms["Destination"],
								"StartSend" => $sms["StartSend"],
								"LastModified" => date("Y-m-d H:i:s",time()),
								"CountPart" => $resSendMess["SEND"],
								"SendPart" => $resSendMess["OK"],
								"CodeType" => $sms["CodeType"],
								"TextMessage" => $sms["TextMessage"]
								);

			$this->ArrayAdd($arrparam);

			if($resSendMess)
				return true;
			else
				return false;
		}
	}

	protected function UpdateSID()
	{
		if ($this->sid == 0)
			$this->sid = COption::GetOptionString("rarus.sms4b", "sid");
		
		if(!$this->GetSOAP("AccountParams",array("SessionID" => $this->sid)))
		{
			$this->sid = 0;
			$arParam = array(	
								"Login" => $this->login,
								"Password"=> $this->password,
								"Gmt" => $this->gmt
			);
			
			if($this->GetSOAP("StartSession",$arParam))
			{
				$this->GetSOAP("AccountParams",array("SessionID" => $this->sid));
				return true;
			}
			else
				return false;
		}
		else
			return true;
	}

	/*download incoming messages*/
	public function LoadIncoming()
	{
		$loadMore = true;
		while($loadMore)
		{
			$incs = $this->GetSOAP("LoadIn",array("SessionID" => $this->sid,"StartChanges" => $this->inc_date));
			if (!$incs)
				$loadMore = false;
				
			if($this->inc_date <> '')
			{
				$time = explode(' ', $this->inc_date);
				$arrd = explode('-', $time[0]);
				$arrt = explode(':', $time[1]);
				$time = mktime($arrt[0], $arrt[1], $arrt[2], $arrd[1], $arrd[2], $arrd[0]);
			}
			else
				$time = 0;

			foreach ($incs as $inc)
			{
				if(count($inc) > 0)
					$this->AddIncoming($inc);

				$inc["Moment"] = explode('.',$inc["Moment"]);
				$inc["Moment"] = explode(' ', $inc["Moment"][0]);
				$arrd = explode('-', $inc["Moment"][0]);
				$arrt = explode(':', $inc["Moment"][1]);

				$timen = mktime($arrt[0], $arrt[1], $arrt[2], $arrd[1], $arrd[2], $arrd[0]);
				if($timen > $time)
				{
					$time = $timen+1;
					COption::SetOptionString("rarus.sms4b", "inc_date", date("Y-m-d H:i:s",$time));
					$this->inc_date = date("Y-m-d H:i:s",$time);
				}

			}
		}
	}
	
	public function GetFormatDateForSmsForm($date)
	{
		$date = htmlspecialchars($date);
		
		$forShortTime = date("H:i:s");
		if (preg_match("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/",$date))
			return ConvertDateTime($date, "YYYYMMDD $forShortTime", "ru");
		if (preg_match("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/",$date))
			return ConvertDateTime($date, "YYYYMMDD HH:MI:SS", "ru");
		
		return -1;
	}
	
	public function ForDb($date)
	{
		$date = htmlspecialchars($date);
		
		$forShortTime = date("H:i:s");
		if (preg_match("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/",$date))
			return ConvertDateTime($date, "YYYY-MM-DD $forShortTime", "ru");
		if (preg_match("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}:[0-9]{2}$/",$date))
			return ConvertDateTime($date, "YYYY-MM-DD HH:MI:SS", "ru");
		
		return -1;	
	}
	
}
	
	
?>