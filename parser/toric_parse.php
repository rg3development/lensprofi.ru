<?
require($_SERVER['DOCUMENT_ROOT'].'/parser/reader.php');

$SHOW_FORM=true;

if(isset($_REQUEST['form_exist']))
{

	if($_REQUEST['filename']!='' and $_REQUEST['filenum']!='' and $_REQUEST['booknum']!='')
	{	
			$SHOW_FORM=false;
			
			/*
			$xxx = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$_REQUEST['filename']);
			if(!$xxx)
			{
				echo 'error';
			}
			else
			{
				echo $xxx;
				die();
			}
			*/
			

			//echo $_SERVER['DOCUMENT_ROOT'].'/'.$_REQUEST['filename'].'**';
			//if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$_REQUEST['filename'])) echo 'not exists';
			
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('windows-1251');
			$data->read($_SERVER['DOCUMENT_ROOT'].'/'.$_REQUEST['filename']);
			
			
			$bookNumStart = $_REQUEST['booknum'];

			//$bookNumEnd = 10; // 11 книг в файле


			$read = array();

			for ($i = 0; $i <= $data->sheets[$bookNumStart]['numRows']; $i++) 
			{
				
				for($j=1; $j<=114; $j++)
				{
					/*
					if(trim($data->sheets[$bookNum]['cells'][$i][$j])!='')
					{
						//$arr_goods[$k][$j] = preg_replace('/^[0-9A-Za-zа-яА-Я\$\%\@\,\.\-\_\&\#]/' , '', trim($data->sheets[$bookNum]['cells'][$i][$j]));
						
						//$arr_goods[$k][$j] = iconv('windows-1251', 'utf-8', trim($data->sheets[$bookNum]['cells'][$i][$j]));
						
						$arr_goods[$k][$j] = trim($data->sheets[$bookNum]['cells'][$i][$j]);
					};
					*/
					$read[$i][$j] = $data->sheets[$bookNumStart]['cells'][$i][$j];
				};		
					
			};

			// echo '<pre>';
			// print_R($read);
			// echo '</pre>';

			$RES = array();

			$RES['BC'] = $read[2][2];
			$RES['cylinders'] = array();
			for($c=1; $c<=114; $c=$c+19)
			{
				//echo $c;
				$RES['cylinders'][$read[3][$c+1]] = array();
				$cyl_arr = array();
				for($row=0; $row<=17; $row++)
				{
					for($rec=0; $rec<=56; $rec++)
					{
						$cyl_arr[($row+1)*10][$read[5+$rec][1]] = ($read[5+$rec][$c+1+$row]=='')?'0':$read[5+$rec][$c+1+$row];
					};
				};
				$RES['cylinders'][$read[3][$c+1]] = $cyl_arr;	
			}

			$ser_res = serialize($RES);
			
			//echo $_SERVER['DOCUMENT_ROOT']."/toric_book". $_REQUEST['filenum'].".php";
			
			$fp = fopen ($_SERVER['DOCUMENT_ROOT']."/toric_book". $_REQUEST['filenum'].".php", "w");
			fwrite($fp, '<? $arr_ser=\''.$ser_res.'\';?>');
			fclose($fp); 

			echo 'файл toric_book'.$_REQUEST['filenum'].'.php создан';

			// echo '<pre>';
			// print_R($RES);
			// echo '</pre>';

	};
};
?>

<?if($SHOW_FORM):?>
<div style="padding: 40px ">
<style type="text/css">
	.smt tr td
	{
		padding: 5px;
		vertical-align: middle;
	}
</style>
<form method ="post" action="">
	<table class="verst smt">
		<tr>
			<td>
				Имя файла (toric.xls ?)
			</td>
			<td>
				<input name="filename" type="text" value="toric.xls" />
			</td>
		</tr>
		<tr>
			<td>
				Номер книги в .xls файле (отсчитывается с нуля!)
			</td>
			<td>
				<input name="booknum" type="text" value="" />
			</td>
		</tr>
		<tr>
			<td>
				Номер результирующего файла
			</td>
			<td>
				<input name="filenum" type="text" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="form_exist" value="Парсить" />
			</td>
		</tr>
	</table>
</form>
</div>
<?endif;?>