function collapseCat(catID)
{
	$(document).ready(function(){
		$('#c' + catID + ' .forum').hide();
		$('#c' + catID + ' .tableKey').hide();

		$('#collapseForum-c' + catID).html('<a href="javascript: void(0);" onclick="expandCat(\'' + catID + '\');">&#x25BC;</a>');

		var date = new Date;
		date.setFullYear(date.getFullYear() + 1);
		document.cookie = 'collapseCat' + catID + '=1; expires=' + date.toGMTString() + ';';
	});
}

function expandCat(catID)
{
	$(document).ready(function(){
		$('#c' + catID + ' .forum').show();
		$('#c' + catID + ' .tableKey').show();

		$('#collapseForum-c' + catID).html('<a href="javascript: void(0);" onclick="collapseCat(\'' + catID + '\');">&#x25B2;</a>');

		delete_cookie('collapseCat' + catID);
	});
}

function checkForCollapse(catID)
{
	var results = document.cookie.match('(^|;) ?' + 'collapseCat' + catID + '=([^;]*)(;|$)');

	if (results){
		$(document).ready(function(){
			$('#c' + catID + ' .forum').hide();
			$('#c' + catID + ' .tableKey').hide();

			$('#collapseForum-c' + catID).html('<a href="javascript: void(0);" onclick="expandCat(\'' + catID + '\');">&#x25BC;</a>');
		});
	} else {
	    return false;
	}
}

function delete_cookie(cookie_name)
{
	var cookie_date = new Date();
	cookie_date.setTime(cookie_date.getTime() - 1);
	document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}