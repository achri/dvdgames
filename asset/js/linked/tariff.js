function clearData() 
{
	jQuery("#origin_code").val(''); 
	jQuery("#destination_code").val('');	
}

function formatItem(row) 
{
	return row[0];;
}

function findValueOrigin(event,data) 
{ 
	jQuery("#origin").val(data[0]); 
	jQuery("#origin_code").val(data[1]);
		
	if(jQuery.trim(data[1])=='null')
	{
		jQuery("#origin").val(''); 
		jQuery("#origin_code").val('');
	} 
};

function findValueDestination(event,data) 
{ 
	jQuery("#destination").val(data[0]); 	
	jQuery("#destination_code").val(data[1]); 
	
	if(jQuery.trim(data[1])=='null')
	{
		jQuery("#destination").val(''); 
		jQuery("#destination_code").val('');
	} 
};

function tariffValidate()
{	
	if(jQuery("#origin_code").val()=='')
	{
		jQuery("#origin").focus();
		return false;	
	}	
	if(jQuery("#origin").val().length < 3)
	{
		jQuery("#origin").focus();		
		jQuery("#origin_code").val('');
		return false;	
	}
	
	if(jQuery("#destination_code").val()=='')
	{
		jQuery("#destination").focus();	
		return false;
	}
	if(jQuery("#destination").val().length < 3)
	{
		jQuery("#destination").focus();		
		jQuery("#destination_code").val('');
		return false;	
	}	
}

jQuery(document).ready(function(){
	jQuery("#weight").attr('autocomplete','off');	
	//origin
	jQuery("#origin").autocomplete("http://www.jne.co.id/tariff.php?ind=o",{minChars:3, matchSubset:1, matchContains:1, max:20, cacheLength:20, formatItem:formatItem, selectOnly:1, autoFill:false, cleanUrl:false, multiple:true, multipleSeparator:'|', scroll:false});	
	jQuery("#origin").result(findValueOrigin).next().click(function(){	});
	//destination
	jQuery("#destination").autocomplete("tariff.php",{width:200,minChars:3, matchSubset:1, matchContains:1, max:20, cacheLength:20, formatItem:formatItem, selectOnly:1, autoFill:false, cleanUrl:false, multiple:true, multipleSeparator:'|', scroll:false});	
	jQuery("#destination").result(findValueDestination).next().click(function(){	});

	jQuery("#clearForm").click(function(){ clearData();});
	
	//allowed character
	jQuery("#origin").alpha({allow:", "});
	jQuery("#destination").alpha({allow:", "});
	jQuery("#weight").numeric({allow:".,"});

	
	jQuery("#checktariff").click(function(){
		if(jQuery("#weight").val()=='')jQuery("#weight").val(1);
		return tariffValidate();			
	});
			
});
