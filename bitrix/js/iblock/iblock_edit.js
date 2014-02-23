/*
 * arParams
 * 		PREFIX				- prefix for vars
 * 		FORM_ID				- id form
 * 		TABLE_PROP_ID		- id table with properties
 * 		PROP_COUNT_ID		- id field with count properties
 * 		IBLOCK_ID			- id iblock
 *		LANG				- lang id
 *		TITLE				- window title
 *		OBJ					- object var name
 *		SESS				- session id for get
 * Variables
 * 		this.PREFIX
 * 		this.PREFIX_TR
 * 		this.FORM_ID
 * 		this.FORM_DATA
 * 		this.TABLE_PROP_ID
 * 		this.PROP_TBL
 * 		this.PROP_COUNT_ID
 * 		this.PROP_COUNT
 * 		this.PROP_COUNT_VALUE
 * 		this.IBLOCK_ID
 * 		this.LANG
 * 		this.TITLE
 * 		this.CELLS
 * 		this.CELL_IND
 * 		this.CELL_CENT
 * 		this.OBJNAME
 * 		this.SESS
 */
function JCIBlockProperty(arParams)
{
	var _this = this;

	if (!arParams) return;

	this.intERROR = 0;
	this.PREFIX = arParams.PREFIX;
	this.PREFIX_TR = this.PREFIX+'ROW_';
	this.FORM_ID = arParams.FORM_ID;
	this.TABLE_PROP_ID = arParams.TABLE_PROP_ID;
	this.PROP_COUNT_ID = arParams.PROP_COUNT_ID;
	this.IBLOCK_ID = arParams.IBLOCK_ID;
	this.LANG = arParams.LANG;
	this.TITLE = arParams.TITLE;
	this.CELLS = [];
	this.CELL_IND = -1;
	this.CELL_CENT = [];
	this.OBJNAME = arParams.OBJ;
	if (!arParams.SESS)
		return;
	this.SESS = arParams.SESS;

	BX.ready(BX.delegate(this.Init,this));
}

JCIBlockProperty.prototype.Init = function()
{
	this.FORM_DATA = BX(this.FORM_ID);
	if (!this.FORM_DATA)
	{
		this.intERROR = -1;
		return;
	}
	this.PROP_TBL = BX(this.TABLE_PROP_ID);
	if (!this.PROP_TBL)
	{
		this.intERROR = -1;
		return;
	}
	this.PROP_COUNT = BX(this.PROP_COUNT_ID);
	if (!this.PROP_COUNT)
	{
		this.intERROR = -1;
		return;
	}
	var clButtons = BX.findChildren(this.PROP_TBL, {'tag': 'input','attribute': { 'type':'button'}}, true);
	if (clButtons)
	{
		for (var i = 0; i < clButtons.length; i++)
			BX.bind(clButtons[i], 'click', BX.proxy(function(e){this.ShowPropertyDialog(e);}, this));
	}

	BX.addCustomEvent(this.FORM_DATA, 'onAutoSaveRestore', BX.delegate(this.onAutoSaveRestore, this));
}

JCIBlockProperty.prototype.GetPropInfo = function(ID)
{
	if (0 > this.intERROR)
		return;

	ID = this.PREFIX + ID;

	arResult = {
		'FILE_TYPE': this.FORM_DATA[ID+'_FILE_TYPE'].value,
		'LIST_TYPE':  ('C' != this.FORM_DATA[ID+'_LIST_TYPE'].value ? 'L' : 'C'),
		'ROW_COUNT' : this.FORM_DATA[ID+'_ROW_COUNT'].value,
		'COL_COUNT' : this.FORM_DATA[ID+'_COL_COUNT'].value,
		'LINK_IBLOCK_ID' : this.FORM_DATA[ID+'_LINK_IBLOCK_ID'].value,
		'DEFAULT_VALUE' : this.FORM_DATA[ID+'_DEFAULT_VALUE'].value,
		'USER_TYPE_SETTINGS' : this.FORM_DATA[ID+'_USER_TYPE_SETTINGS'].value,
		'WITH_DESCRIPTION' : this.FORM_DATA[ID+'_WITH_DESCRIPTION'].value,
		'SEARCHABLE' : this.FORM_DATA[ID+'_SEARCHABLE'].value,
		'FILTRABLE' : this.FORM_DATA[ID+'_FILTRABLE'].value,
		'ACTIVE' : this.FORM_DATA[ID+'_ACTIVE'].value,
		'MULTIPLE_CNT' : this.FORM_DATA[ID+'_MULTIPLE_CNT'].value,
		'XML_ID' : BX.util.htmlspecialchars(this.FORM_DATA[ID+'_XML_ID'].value),
		'PROPERTY_TYPE' : this.FORM_DATA[ID+'_PROPERTY_TYPE'].value,
		'NAME' : BX.util.htmlspecialchars(this.FORM_DATA[ID+'_NAME'].value),
		'MULTIPLE' : (true == this.FORM_DATA[ID+'_MULTIPLE_Y'].checked ? this.FORM_DATA[ID+'_MULTIPLE_Y'].value : this.FORM_DATA[ID+'_MULTIPLE_N'].value),
		'IS_REQUIRED' : (true == this.FORM_DATA[ID+'_IS_REQUIRED_Y'].checked ? this.FORM_DATA[ID+'_IS_REQUIRED_Y'].value : this.FORM_DATA[ID+'_IS_REQUIRED_N'].value),
		'SORT' : this.FORM_DATA[ID+'_SORT'].value,
		'CODE' : BX.util.htmlspecialchars(this.FORM_DATA[ID+'_CODE'].value)
	};
	if ('L' == arResult.PROPERTY_TYPE)
	{
		arResult.VALUES = null;
		if (this.FORM_DATA[ID+'_VALUES'])
			arResult.VALUES = this.FORM_DATA[ID+'_VALUES'].value;
		arResult.VALUES_DEF = null;
		if (this.FORM_DATA[ID+'_VALUES_DEF'])
			arResult.VALUES_DEF = this.FORM_DATA[ID+'_VALUES_DEF'].value;
		arResult.VALUES_XML = null;
		if (this.FORM_DATA[ID+'_VALUES_XML'])
			arResult.VALUES_XML = this.FORM_DATA[ID+'_VALUES_XML'].value;
		arResult.VALUES_SORT = null;
		if (this.FORM_DATA[ID+'_VALUES_SORT'])
			arResult.VALUES_SORT = this.FORM_DATA[ID+'_VALUES_SORT'].value;
		arResult.CNT = 0;
		if (this.FORM_DATA[ID+'_CNT'])
			arResult.CNT = this.FORM_DATA[ID+'_CNT'].value;
	}
	return arResult;
}

JCIBlockProperty.prototype.SetPropInfo = function(ID,arProp,formsess)
{
	if (0 > this.intERROR)
		return;

	if (!formsess)
		return;
	if (this.SESS != formsess)
		return;

	ID = this.PREFIX+ID;

	this.FORM_DATA[ID+'_NAME'].value = BX.util.htmlspecialcharsback(arProp.NAME);
	this.FORM_DATA[ID+'_SORT'].value = arProp.SORT;
	this.FORM_DATA[ID+'_CODE'].value = BX.util.htmlspecialcharsback(arProp.CODE);
	this.FORM_DATA[ID+'_ROW_COUNT'].value = arProp.ROW_COUNT;
	this.FORM_DATA[ID+'_COL_COUNT'].value = arProp.COL_COUNT;
	this.FORM_DATA[ID+'_LIST_TYPE'].value = arProp.LIST_TYPE;
	this.FORM_DATA[ID+'_FILE_TYPE'].value = arProp.FILE_TYPE;
	this.FORM_DATA[ID+'_MULTIPLE_CNT'].value = arProp.MULTIPLE_CNT;
	this.FORM_DATA[ID+'_LINK_IBLOCK_ID'].value = arProp.LINK_IBLOCK_ID;
	this.FORM_DATA[ID+'_WITH_DESCRIPTION'].value = arProp.WITH_DESCRIPTION;
	this.FORM_DATA[ID+'_XML_ID'].value = BX.util.htmlspecialcharsback(arProp.XML_ID);
	this.FORM_DATA[ID+'_SEARCHABLE'].value = arProp.SEARCHABLE;
	this.FORM_DATA[ID+'_FILTRABLE'].value = arProp.FILTRABLE;
	this.FORM_DATA[ID+'_ACTIVE'].value = arProp.ACTIVE;
	this.FORM_DATA[ID+'_DEFAULT_VALUE'].value = arProp.DEFAULT_VALUE;
	var PropMulti = BX(ID+'_MULTIPLE_Y');
	PropMulti.checked = ('Y' == arProp.MULTIPLE ? true : false);
	var PropReq = BX(ID+'_IS_REQUIRED_Y');
	PropReq.checked = ('Y' == arProp.IS_REQUIRED ? true : false);
	this.FORM_DATA[ID+'_USER_TYPE_SETTINGS'].value = arProp.USER_TYPE_SETTINGS;
	if ('L' == arProp.PROPERTY_TYPE)
	{
		this.FORM_DATA[ID+'_VALUES'].value = arProp.VALUES;
		this.FORM_DATA[ID+'_VALUES_DEF'].value = arProp.VALUES_DEF;
		this.FORM_DATA[ID+'_VALUES_SORT'].value = arProp.VALUES_SORT;
		this.FORM_DATA[ID+'_VALUES_XML'].value = arProp.VALUES_XML;
		this.FORM_DATA[ID+'_CNT'].value = arProp.CNT;
	}
	for (i = 0; i < this.FORM_DATA[ID+'_PROPERTY_TYPE'].length; i++)
		if (arProp.PROPERTY_TYPE == this.FORM_DATA[ID+'_PROPERTY_TYPE'].options[i].value)
			this.FORM_DATA[ID+'_PROPERTY_TYPE'].options[i].selected = true;

	BX.fireEvent(this.FORM_DATA[ID+'_NAME'], 'change');
}

JCIBlockProperty.prototype.GetProperty = function(strName)
{
	if (0 > this.intERROR)
		return;

	if ((!strName) || (!this[strName])) return;
	return this[strName];
}

JCIBlockProperty.prototype.SetProperty = function(strName,value)
{
	if (0 > this.intERROR)
		return;

	if (strName)
		this[strName] = value;
}

JCIBlockProperty.prototype.JSParamsToPHP = function (ob, varname)
{
	var res, i, key;
	if(typeof(ob)=='object')
	{
		res = [];
		var isSimpleArray = false;
		if(ob instanceof Array)
		{
			isSimpleArray = true;
			for(i in ob)
			{
				if(parseInt(i)!=i)
				{
					isSimpleArray = false;
					break;
				}
			}
		}

		if(isSimpleArray)
		{
			for(i=0; i<ob.length; i++)
				res.push(this.JSParamsToPHP(ob[i], varname+'['+i+']'));
		}
		else
		{
			for(key in ob)
				res.push(this.JSParamsToPHP(ob[key], varname+'['+key+']'));
		}

		return res.join("&", res);
	}

	if(typeof(ob)=='boolean')
	{
		if(ob)
			return varname + '=1';
		return varname + "=0";
	}

	return varname + '=' + BX.util.urlencode(ob);
}

JCIBlockProperty.prototype.ShowPropertyDialog = function (e)
{
	if(!e)
		e = window.event;
	if (0 > this.intERROR)
		return;
	var s = (BX.browser.IsIE() ? e.srcElement.id : e.target.id);

	if (!s)
		return;

	s = s.replace(this.PREFIX,'');
	s = s.replace('_BTN','');
	var ID = s;

	arProp = this.GetPropInfo(ID);
	if (arProp)
	{
		arParams = {
			'PREFIX': this.PREFIX,
			'ID': ID,
			'IBLOCK_ID': this.IBLOCK_ID,
			'TITLE': this.TITLE,
			'RECEIVER': this.OBJNAME
		};
		(new BX.CAdminDialog({
			'title': this.TITLE,
		    'content_url': '/bitrix/admin/iblock_edit_property.php?lang='+this.LANG+'&propedit='+ID+'&bxpublic=Y&receiver='+this.OBJNAME,
		    'content_post': this.JSParamsToPHP(arParams, 'PARAMS')+ '&' +
		    this.JSParamsToPHP(arProp, 'PROP')+'&'+this.SESS,
			'draggable': true,
			'resizable': true,
			'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
		})).Show();
	}
}

JCIBlockProperty.prototype.SetCells = function(arCells,intIndex,arCenter)
{
	if (0 > this.intERROR)
		return;

	if (arCells)
		this.CELLS = BX.clone(arCells,true);
	for (var i = 0; i < this.CELLS.length; i++)
	{
		this.CELLS[i] = this.CELLS[i].replace(/PREFIX/ig, this.PREFIX);
	}
	if (intIndex)
		this.CELL_IND = intIndex;
	if (arCenter)
		this.CELL_CENT = BX.clone(arCenter,true)
}

JCIBlockProperty.prototype.addPropRow = function()
{
	if (0 > this.intERROR)
		return;
	var i = 0;
	var id = parseInt(this.PROP_COUNT.value);

	var newRow = this.PROP_TBL.insertRow(this.PROP_TBL.rows.length)
	newRow.id = this.PREFIX_TR+'n'+id;
	for (i = 0; i < this.CELLS.length; i++)
	{
		var oCell = newRow.insertCell(-1);
		var typeHtml = this.CELLS[i];
		typeHtml = typeHtml.replace(/tmp_xxx/ig, 'n'+id);
		oCell.innerHTML = typeHtml;
	}
	for (i = 0; i < this.CELL_CENT.length; i++)
	{
		var needCell = newRow.cells[this.CELL_CENT[i]-1];
		if (needCell)
		{
			needCell.setAttribute('align','center');
		}
	}
	if (newRow.cells[this.CELL_IND])
	{
		var needCell = newRow.cells[this.CELL_IND];
		var clButtons = BX.findChildren(needCell, {'tag': 'input','attribute': { 'type':'button'}}, true);
		if (clButtons)
		{
			for (var i = 0; i < clButtons.length; i++)
				BX.bind(clButtons[i], 'click', BX.proxy(function(e){this.ShowPropertyDialog(e);}, this));
		}
	}

	setTimeout(function() {
		var r = BX.findChildren(newRow.parentNode, {tag: /^(input|select|textarea)$/i}, true);
		if (r && r.length > 0)
		{
			for (var i=0,l=r.length;i<l;i++)
			{
				if (r[i].form && r[i].form.BXAUTOSAVE)
					r[i].form.BXAUTOSAVE.RegisterInput(r[i]);
				else
					break;
			}
		}
	}, 10);

	this.PROP_COUNT.value = id + 1;
}

JCIBlockProperty.prototype.onAutoSaveRestore = function(ob, data)
{
	while (data['IB_PROPERTY_n' + this.PROP_COUNT.value + '_NAME'])
	{
		this.addPropRow();
	}
}

function JCIBlockAccess(entity_type, iblock_id, id, arSelected, variable_name, table_id, href_id, sSelect, arHighLight)
{
	this.entity_type = entity_type;
	this.iblock_id = iblock_id;
	this.id = id;
	this.arSelected = arSelected;
	this.variable_name = variable_name;
	this.table_id = table_id;
	this.href_id = href_id;
	this.sSelect = sSelect;
	this.arHighLight = arHighLight;

	BX.ready(BX.delegate(this.Init, this));
}

JCIBlockAccess.prototype.Init = function()
{
	BX.bind(BX(this.href_id), 'click', BX.delegate(this.Add, this));
	var heading = BX(this.variable_name + '_heading');
	if(heading)
		BX.bind(heading, 'dblclick', BX.delegate(this.ShowInfo, this));
	BX.Access.Init(this.arHighLight);
	BX.Access.SetSelected(this.arSelected, this.variable_name);
}

JCIBlockAccess.prototype.Add = function()
{
	BX.Access.ShowForm({callback: BX.delegate(this.InsertRights, this), bind: this.variable_name})
}

JCIBlockAccess.prototype.InsertRights = function(obSelected)
{
	var tbl = BX(this.table_id);
	for(var provider in obSelected)
	{
		for(var id in obSelected[provider])
		{
			var cnt = tbl.rows.length;
			var row = tbl.insertRow(cnt-1);
			row.vAlign = 'top';
			row.insertCell(-1);
			row.insertCell(-1);
			row.cells[0].align = 'right';
			row.cells[0].innerHTML = BX.Access.GetProviderName(provider)+' '+obSelected[provider][id].name+':'+'<input type="hidden" name="'+this.variable_name+'[][RIGHT_ID]" value=""><input type="hidden" name="'+this.variable_name+'[][GROUP_CODE]" value="'+id+'">';
			row.cells[1].align = 'left';
			row.cells[1].innerHTML = this.sSelect + ' ' + '<a href="javascript:void(0);" onclick="JCIBlockAccess.DeleteRow(this, \''+id+'\', \''+this.variable_name+'\')" class="access-delete"></a><span title="'+BX.message('langApplyTitle')+'" id="overwrite_'+id+'"></span>';

			var parents = BX.findChildren(tbl, {'class' : this.variable_name + '_row_for_' + id}, true);
			if(parents)
			for(var i = 0; i < parents.length; i++)
				parents[i].className += ' iblock-strike-out';
		}
	}

	if(parseInt(this.id) > 0)
	{
		BX.ajax.loadJSON(
			'/bitrix/admin/iblock_edit.php'+
			'?ajax=y'+
			'&sessid='+BX.bitrix_sessid()+
			'&entity_type='+this.entity_type+
			'&iblock_id='+this.iblock_id+
			'&id='+this.id,
			{added: obSelected},
			function(result)
			{
				if(result)
				{
					for(var id in result)
					{
						var s = parseInt(result[id][0]);
						var e = parseInt(result[id][1]);
						var mess = '';
						if(s > 0 && e > 0)
							mess = BX.message('langApply1Title');
						else if (s > 0)
							mess = BX.message('langApply2Title');
						else if (e > 0)
							mess = BX.message('langApply3Title');

						if(mess)
							BX('overwrite_'+id).innerHTML = '<br><input type="checkbox" name="'+this.variable_name+'[][DO_CLEAN]" value="Y">'+mess+' ('+(s+e)+')';
					}
				}
			}
		);
	}
}

JCIBlockAccess.prototype.ShowInfo = function()
{
	var entity_type = this.entity_type;
	var iblock_id = this.iblock_id;
	var id = this.id;

	var btnOK = new BX.CWindowButton({
		'title': 'Query',
		'action': function()
		{
			var _user_id = BX('prompt_user_id');
			BX('info_result').innerHTML = '';
			BX.showWait();
			BX.ajax.loadJSON(
				'/bitrix/admin/iblock_edit.php'+
				'?ajax=y'+
				'&sessid='+BX.bitrix_sessid()+
				'&entity_type='+entity_type+
				'&iblock_id='+iblock_id+
				'&id='+id,
				{info: _user_id.value},
				function(result)
				{
					if(result)
					{
						for(var id in result)
						{
							BX('info_result').innerHTML += '<span style="display:inline-block;width:200px;height:15px;">' + id + '</span>';
						}
					}
					BX.closeWait();
				}
			);
		}
	})

	if (null == this.iblock_info_obDialog)
	{
		this.iblock_info_obDialog = new BX.CDialog({
			content: '<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr valign="top"><td width="50%" align="right">User ID:</td><td width="50%" align="left"><input type="text" size="6" id="prompt_user_id" value=""></td></tr><tr><td colspan="2" id="info_result"></td></tr></table>',
			buttons: [btnOK, BX.CDialog.btnCancel],
			width: 420,
			height: 200
		});
	}

	this.iblock_info_obDialog.Show();

	var inp = BX('prompt_user_id');
	inp.focus();
	inp.select();
}

JCIBlockAccess.DeleteRow = function(ob, id, variable_name)
{
	var row = BX.findParent(ob, {'tag':'tr'});
	var tbl = BX.findParent(row, {'tag':'table'});
	var parents = BX.findChildren(tbl, {'class' : variable_name + '_row_for_' + id + ' iblock-strike-out'}, true);
	if(parents)
	for(var i = 0; i < parents.length; i++)
		parents[i].className = variable_name + '_row_for_' + id;
	row.parentNode.removeChild(row);
	BX.Access.DeleteSelected(id, variable_name);
}

function addNewRow(tableID, row_to_clone)
{
	var tbl = document.getElementById(tableID);
	var cnt = tbl.rows.length;
	if(row_to_clone == null)
		row_to_clone = -2;
	var sHTML = tbl.rows[cnt+row_to_clone].cells[0].innerHTML;
	var oRow = tbl.insertRow(cnt+row_to_clone+1);
	var oCell = oRow.insertCell(0);

	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('[n',p);
		if(s<0)break;
		var e = sHTML.indexOf(']',s);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+2,e-s));
		sHTML = sHTML.substr(0, s)+'[n'+(++n)+']'+sHTML.substr(e+1);
		p=s+1;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('__n',p);
		if(s<0)break;
		var e = sHTML.indexOf('_',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'__n'+(++n)+'_'+sHTML.substr(e+1);
		p=e+1;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('__N',p);
		if(s<0)break;
		var e = sHTML.indexOf('__',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'__N'+(++n)+'__'+sHTML.substr(e+2);
		p=e+2;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('xxn',p);
		if(s<0)break;
		var e = sHTML.indexOf('xx',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'xxn'+(++n)+'xx'+sHTML.substr(e+2);
		p=e+2;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('%5Bn',p);
		if(s<0)break;
		var e = sHTML.indexOf('%5D',s+3);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+4,e-s));
		sHTML = sHTML.substr(0, s)+'%5Bn'+(++n)+'%5D'+sHTML.substr(e+3);
		p=e+3;
	}
	oCell.innerHTML = sHTML;

	var patt = new RegExp ("<"+"script"+">[^\000]*?<"+"\/"+"script"+">", "ig");
	var code = sHTML.match(patt);
	if(code)
	{
		for(var i = 0; i < code.length; i++)
		{
			if(code[i] != '')
			{
				var s = code[i].substring(8, code[i].length-9);
				jsUtils.EvalGlobal(s);
			}
		}
	}

	setTimeout(function() {
		var r = BX.findChildren(oCell, {tag: /^(input|select|textarea)$/i});
		if (r && r.length > 0)
		{
			for (var i=0,l=r.length;i<l;i++)
			{
				if (r[i].form && r[i].form.BXAUTOSAVE)
					r[i].form.BXAUTOSAVE.RegisterInput(r[i]);
				else
					break;
			}
		}
	}, 10);
}
