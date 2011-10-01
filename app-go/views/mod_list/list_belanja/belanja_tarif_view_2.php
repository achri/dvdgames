<?php echo $extraSubHeadContent;?>
<script type="text/javascript">
function formatItem(row) 
{
	return row[0];;
}
$(document).ready(function() {
	jQuery("#origin").autocomplete("http://www.jne.co.id/tariff.php?ind=o",{
		minChars:3, 
		matchSubset:1, 
		matchContains:1, 
		max:20, 
		cacheLength:20, 
		formatItem:formatItem, 
		selectOnly:1, 
		autoFill:false, 
		cleanUrl:false, 
		multiple:true, 
		multipleSeparator:'|', 
		scroll:false
	});
	
	$.ajax({
		url: "http://www.jne.co.id",
		type: 'POST',
		success: function(data) {
			alert(data);
		},
		error: function() {
			alert('error');
		}
	});
});
</script>

<input type="text" id="origin">