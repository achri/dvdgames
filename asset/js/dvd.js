$(document).ready(function() {
	// LIST DVD
	var $dvd_list = $('#product-list'), $cart = $('#cart-list');
	
	var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a>";
	function deleteImage( $item ) {
		$item.fadeOut(function() {
			var $list = $( "ul", $cart ).length ?
				$( "ul", $cart ) :
				$( "<ul class='product ui-helper-reset'/>" ).appendTo( $cart );
			$item.find( "a.ui-icon-trash" ).remove();
			$item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
				$item
				.animate({ width: "48px" })
				.find( "table" )
				.animate({ height: "48px" })
				.find( "img" )
				.animate({ width: "90%" });
			});
		});
	}

	// image recycle function
	var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";
	function recycleImage( $item ) {
		$item.fadeOut(function() {
			$item
			.find( "a.ui-icon-refresh" )
			.remove()
			.end()
			.css( "width", "96px")
			.append( trash_icon )
			.find( "img" )
			.css( "height", "72px" )
			.end()
			.appendTo( $dvd_list )
			.fadeIn();
		});
	}
		
	$('#product-list li, .product li').draggable({
		cancel: 'a.ui-icon',
		revert: 'invalid', 
		containment: $('#content-wrap').length ? '#content-wrap' : 'document', 
		helper: 'clone',
		cursor: 'move'
	});

	$cart.droppable({
		accept: '#product-list > li',
		activeClass: 'ui-state-error',
		drop: function(ev, ui) {
			//deleteImage(ui.draggable);
			var $item = ui.draggable,
				dvd_id = $item.attr('id'),
				kat_id = $item.attr('kat_id');
			
			add_toCart(dvd_id,kat_id);
		}
	});

	$dvd_list.droppable({
		accept: '#cart-list li',
		activeClass: 'ui-state-error',
		drop: function(ev, ui) {
			//recycleImage(ui.draggable);
			var $item = ui.draggable,
				dvd_id = $item.attr('id');
			
			add_toList(dvd_id);
		}
	});

	$('ul.product > li').dblclick(function(ev){
		var $item = $(this),
			dvd_id = $item.attr('id'),
			kat_id = $item.attr('kat_id');
		if ($('> a',this).hasClass('ui-icon-cart')) {
			add_toCart(dvd_id,kat_id);
		} else if ($('> a',this).hasClass('ui-icon-refresh')) {
			add_toList(dvd_id);
		}
	}).click(function(ev) {
		var $item = $(this);
		var $target = $(ev.target);
		var kat_id = $item.attr('kat_id');
		var dvd_id = $item.attr('id');
		var dvd_nama = $('img',this).attr('judul');
		
		if ($target.is('a.ui-icon-cart')) {
			add_toCart(dvd_id,kat_id);
		} else if ($target.is('a.ui-icon-zoomin')) {
			rev_call(dvd_nama,dvd_id,kat_id);
		} else if ($target.is('a.ui-icon-refresh')) {
			add_toList(dvd_id);
		}
		return false;
	});
		
	$(".product img").tooltip({
		delay: 1200,
		showURL: false,
		//track:true,
		extraClass:'ui-corner-all',
		bodyHandler: function() {
			return $("<img/>").css({
				//width: '400px', height: '300px'
			}).attr("src", this.src);
		}
	});
});