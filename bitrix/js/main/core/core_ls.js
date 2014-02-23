;(function(window){
if (window.BX.localStorage) return;

var 
	BX = window.BX, 
	localStorageInstance = null;

BX.localStorage = function()
{
	this.bSelfChange = false; // flag to skip self changes in IE

	BX.bind(
		(BX.browser.IsIE() && !BX.browser.IsIE9()) ? document : window, // HATE!
		'storage', 
		BX.proxy(this._onchange, this)
	);

	setInterval(BX.delegate(this._clear, this), 5000);
};

/* localStorage public interface */

BX.localStorage.checkBrowser = function()
{
	var support = false;
	try {
		support = !!localStorage.getItem;
	} catch(e) {}

	if (support)
		return 'native';
	else if (BX.browser.IsIE())
		return 'ie';
	else
		return false;
};

BX.localStorage.set = function(key, value, ttl)
{
	return BX.localStorage.instance().set(key, value, ttl);
};

BX.localStorage.get = function(key)
{
	return BX.localStorage.instance().get(key);
};

BX.localStorage.remove = function(key)
{
	return BX.localStorage.instance().remove(key);
};

BX.localStorage.instance = function()
{
	if (!localStorageInstance)
	{
		var support = BX.localStorage.checkBrowser();
		if (support == 'native')
			localStorageInstance = new BX.localStorage();
		else if (support == 'ie')
			localStorageInstance = new BX.localStorageIE();
	}
	return localStorageInstance;
};

/* localStorage prototype */

BX.localStorage.prototype._onchange = function(e)
{
	if (BX.browser.IsIE() && this.bSelfChange)
	{
		this.bSelfChange = false;
		return;
	}

	e = e || window.event;

	if (!!e.key && e.key.substring(0,3) == 'bx-')
	{
		var d = {
			key: e.key.substring(3, e.key.length),
			value: !!e.newValue? this._decode(e.newValue.substring(11, e.newValue.length)): null,
			oldValue: !!e.oldValue? this._decode(e.oldValue.substring(11, e.oldValue.length)): null
		};

		switch(d.key)
		{
			case 'BXGCE': // BX Global Custom Event
				if (d.value)
				{
					BX.onCustomEvent(d.value.e, d.value.p);
				}
			break;
			default:
				// normal event handlers
				if (e.newValue)
					BX.onCustomEvent(window, 'onLocalStorageSet', [d]);
				if (e.oldValue && !e.newValue)
					BX.onCustomEvent(window, 'onLocalStorageRemove', [d]);

				BX.onCustomEvent(window, 'onLocalStorageChange', [d]);
			break;
		}
	}
};

BX.localStorage.prototype._clear = function()
{
	var curDate = +new Date(), key, i;

	for (i=0; i<localStorage.length; i++)
	{
		key = localStorage.key(i);
		if (key.substring(0,3) == 'bx-')
		{
			var ttl = localStorage.getItem(key).split(':', 1)*1000;
			if (curDate >= ttl)
				localStorage.removeItem(key);
		}
	}
};

BX.localStorage.prototype._encode = function(value)
{
	if (typeof(value) == 'object')
		value = JSON.stringify(value)
	else
		value = value.toString();
	return value;
};

BX.localStorage.prototype._decode = function(value)
{
	var answer = null;
	if (value != null)
	{
		try {answer = JSON.parse(value);}
		catch(e) { answer = value; }
	}
	return answer;
};

BX.localStorage.prototype.set = function(key, value, ttl)
{
	if (!ttl || ttl <= 0)
		ttl = 60;
	
	if (value == undefined)
		return false;
		
	this.bSelfChange = true;
	localStorage.setItem(
		'bx-'+key,
		(Math.round((+new Date())/1000)+ttl)+':'+this._encode(value)
	);
};

BX.localStorage.prototype.get = function(key)
{
	var storageAnswer = localStorage.getItem('bx-'+key);

	if (storageAnswer)
		storageAnswer = storageAnswer.substring(11, storageAnswer.length);

	return this._decode(storageAnswer);
};

BX.localStorage.prototype.remove = function(key)
{
	this.bSelfChange = true;
	localStorage.removeItem('bx-'+key);
};

/************** IE 7 ******************/

BX.localStorageIE = function()
{
	this.NS = 'BXLocalStorage';
	this.__current_state = {};
	this.bSelfChange = false;

	BX.ready(BX.delegate(this._Init, this));
};

BX.extend(BX.localStorageIE, BX.localStorage);

BX.localStorageIE.prototype._Init = function()
{
	this.storage_element = document.body.appendChild(BX.create('DIV'));
	this.storage_element.addBehavior('#default#userData');
	this.storage_element.load(this.NS);

	var doc = this.storage_element.xmlDocument,
		len = doc.firstChild.attributes.length;

	for (var i = 0; i<len; i++)
	{
		if (!!doc.firstChild.attributes[i])
		{
			var k = doc.firstChild.attributes[i].nodeName;
			if (k.substring(0,3) == 'bx-')
			{
				this.__current_state[k] = doc.firstChild.attributes[i].nodeValue;
			}
		}
	}

	setInterval(BX.delegate(this._Listener, this), 500);
	setInterval(BX.delegate(this._clear, this), 5000);
};

BX.localStorageIE.prototype._Listener = function(bInit)
{
	this.storage_element.load(this.NS);

	var doc = this.storage_element.xmlDocument,
		len = doc.firstChild.attributes.length,
		i,k,v;

	var new_state = {}, arChanges = [];

	for (i = 0; i<len; i++)
	{
		if (!!doc.firstChild.attributes[i])
		{
			k = doc.firstChild.attributes[i].nodeName;
			if (k.substring(0,3) == 'bx-')
			{
				v = doc.firstChild.attributes[i].nodeValue;

				if (this.__current_state[k] != v)
				{
					arChanges.push({
						key: k, newValue: v, oldValue: this.__current_state[k]
					});
				}

				new_state[k] = v;
				delete this.__current_state[k];
			}
		}
	}

	for (i in this.__current_state)
	{
		arChanges.push({
			key: i, newValue: undefined, oldValue: this.__current_state[i]
		});
	}

	this.__current_state = new_state;

	for (i=0; i<arChanges.length; i++)
	{
		this._onchange(arChanges[i]);
	}
};

BX.localStorageIE.prototype._clear = function()
{
	this.storage_element.load(this.NS);

	var doc = this.storage_element.xmlDocument,
		len = doc.firstChild.attributes.length,
		curDate = +new Date(),
		i,k,v,ttl;

	for (i = 0; i<len; i++)
	{
		if (!!doc.firstChild.attributes[i])
		{
			k = doc.firstChild.attributes[i].nodeName;
			if (k.substring(0,3) == 'bx-')
			{
				v = doc.firstChild.attributes[i].nodeValue;
				ttl = v.split(':', 1)*1000
				if (curDate >= ttl)
				{
					doc.firstChild.removeAttribute(k)
				}
			}
		}
	}

	this.storage_element.save(this.NS);
};

BX.localStorageIE.prototype.set = function(key, value, ttl)
{
	if (!ttl || ttl <= 0)
		ttl = 60;

	this.storage_element.load(this.NS);

	var doc = this.storage_element.xmlDocument;

	this.bSelfChange = true;

	doc.firstChild.setAttribute(
		'bx-'+key,
		(Math.round((+new Date())/1000)+ttl)+':'+this._encode(value)
	);

	this.storage_element.save(this.NS);
};

BX.localStorageIE.prototype.get = function(key)
{
	this.storage_element.load(this.NS);
	var doc = this.storage_element.xmlDocument;

	var storageAnswer = doc.firstChild.getAttribute('bx-'+key);

	if (storageAnswer)
		storageAnswer = storageAnswer.substring(11, storageAnswer.length);

	return this._decode(storageAnswer);
};

BX.localStorageIE.prototype.remove = function(key)
{
	this.storage_element.load(this.NS);

	var doc = this.storage_element.xmlDocument;
	doc.firstChild.removeAttribute('bx-'+key);

	this.bSelfChange = true;
	this.storage_element.save(this.NS);

	return this._decode(storageAnswer);
};

BX.localStorage.instance();

/* additional functions */

BX.onGlobalCustomEvent = function(eventName, arEventParams, bSkipSelf)
{
	if (!!BX.localStorage.checkBrowser())
		BX.localStorage.set('BXGCE', {e:eventName,p:arEventParams}, 1);

	if (!bSkipSelf)
		BX.onCustomEvent(eventName, arEventParams);
};

})(window)
