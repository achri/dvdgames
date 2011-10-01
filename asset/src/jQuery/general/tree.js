$(document).ready(function() {
	$('#tree').dynatree({
	    rootVisible: true,
	    persist: false,
	    selectMode: 1,
	    keyboard: true,
	    autoFocus: false,
		activeVisible: true,
		//autoCollapse: true,
	    fx: { height: "toggle", duration: 200 }
	});
});