$(document).ready(function() {

	var $drop = $("#drop_here"),
		$drag = $(".drag_this");
			
	$("li", $drag).draggable({
		cancel: "a.ui-icon", 
		revert: "invalid", 
		//containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", 
		helper: "clone",
		cursor: "move"
	});

	$drop.droppable({
		accept: ".meja-menu > li",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			//deleteImage( ui.draggable );
			alert(ui.draggable);
		}
	});

});