<?php
//error_reporting(E_ERROR);
ini_set('display_errors', 1);

if (!function_exists('json_encode')) {  
    function json_encode($value) 
    {
        if (is_int($value)) {
            return (string)$value;   
        } elseif (is_string($value)) {
	        $value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"), 
	                             array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
	        $convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
	        $result = "";
	        for ($i = mb_strlen($value) - 1; $i >= 0; $i--) {
	            $mb_char = mb_substr($value, $i, 1);
	            if (mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match)) {
	                $result = sprintf("\\u%04x", $match[1]) . $result;
	            } else {
	                $result = $mb_char . $result;
	            }
	        }
	        return '"' . $result . '"';                
        } elseif (is_float($value)) {
            return str_replace(",", ".", $value);         
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $with_keys = false;
            $n = count($value);
            for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
                        if (key($value) !== $i) {
			      $with_keys = true;
			      break;
                        }
            }
        } elseif (is_object($value)) {
            $with_keys = true;
        } else {
            return '';
        }
        $result = array();
        if ($with_keys) {
            foreach ($value as $key => $v) {
                $result[] = json_encode((string)$key) . ':' . json_encode($v);    
            }
            return '{' . implode(',', $result) . '}';                
        } else {
            foreach ($value as $key => $v) {
                $result[] = json_encode($v);    
            }
            return '[' . implode(',', $result) . ']';
        }
    } 
}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("fix_class", "OnBeforeIBlockElementAddHandler"));

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("fix_class", "OnBeforeIBlockElementUpdateHandler"));


class fix_class
{

    function OnBeforeIBlockElementAddHandler(&$arFields)
    { 
		$arFields['SEARCHABLE_CONTENT'] ='';
        return $arFields;   
    }
	

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    { 
		$arFields['SEARCHABLE_CONTENT'] ='';
        return $arFields;   
    }
}





/*
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddWatermarkToImg");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddWatermarkToImg");

function AddWatermarkToImg(&$arFields)
{
	// echo '<pre>';
	// print_R($arFields['DETAIL_PICTURE']);
	// print_R($arFields['PREVIEW_PICTURE']);
	// echo '</pre>';
	// die();
   
		  $arFilter_WM = Array(
			 array("name" => "watermark", 
			 "position" => "mc", 
			 "size"=>"real", 
			 "alpha_level"=>"99",
			 "file"=>$_SERVER['DOCUMENT_ROOT']."/water_goods.png")
		  );   
      
		
		 $v = CFile::SaveFile(CFile::MakeFileArray($arFields['DETAIL_PICTURE']['tmp_name']), "abc");
		   //тупак битрикса
		   $arSizeORIG = getimagesize($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($v));
		   $widthBIG =  intval($arSizeORIG[0])-1;
		   $heightBIG =  intval($arSizeORIG[0])-1;
		   //конец тупака битрикса
		   $v = CFile::ResizeImageGet($v, Array('width' => $widthBIG, 'height' => $heightBIG), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilter_WM);
		   $arFields['DETAIL_PICTURE'] = CFile::MakeFileArray($v["src"]);  
		
		
		  $tmp = Array();
		  foreach($arFields['PROPERTY_VALUES'] as $key => $value)
			 foreach($value as $key2 => $value2)
				if($value2["tmp_name"]){
				   $v = CFile::SaveFile(CFile::MakeFileArray($value2["tmp_name"]), "abc");
				   //тупак битрикса
				   $arSizeORIG = getimagesize($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($v));
				   $widthBIG =  intval($arSizeORIG[0])-1;
				   $heightBIG =  intval($arSizeORIG[0])-1;
				   //конец тупака битрикса
				   $v = CFile::ResizeImageGet($v, Array('width' => $widthBIG, 'height' => $heightBIG), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilter_WM);
				   $arFields['PROPERTY_VALUES'][$key][$key2] = CFile::MakeFileArray($v["src"]);               
		
   
}
*/



CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
class kt {
	
	public function noun($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $number." ".$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
	
	public function getSectionID($elid) {
		$res = CIBlockElement::GetById($elid);
		$row = $res -> GetNext();
		return $row['IBLOCK_SECTION_ID'];	
	}
	
	public function listLenses() {
		$html = '';
		$res = CIblockElement::GetList(array(), array("IBLOCK_ID" => 4, "PROPERTY_TOHEAD_VALUE" => "Да"));
		while($row = $res -> GetNext()) {
			$sid = kt::getSectionID($row['ID']);
			$html .= "<option value=\"{$row['ID']}\" sid=\"{$sid}\">{$row['NAME']}</option>";
		}
		return $html;
	}
	
	public function getSectionName($ibid, $id) {
		$res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $ibid, 'ID' => $id));
		$row = $res -> GetNext();
		return $row['NAME'];
	}
	
	public function getPreviewPicture($id) {
		$res = CIBlockElement::GetById($id);
		$row = $res -> GetNext();
		return CFile::GetPath($row["PREVIEW_PICTURE"]);
	}
	
	
	
	
}
?>
