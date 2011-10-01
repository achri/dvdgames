var modul;
// ROUTINE - AJAX LOAD MODULE
function menu_ajax(module,kat_id,dvd_nama)
{
	modul = module;
	
	if (kat_id == null)
		kat_id = 0;
		
	if (dvd_nama == null)
		dvd_nama = 0;
		
	var controller,
		mod_class = module.split('_');
	
	switch (module) {
		case 'home':
			controller = 'index.php/app_init/home';
		break;
		case 'about':
			controller = 'index.php/app_init/cs_page';
		break;
		default:
			controller = 'index.php/mod_'+mod_class[0]+'/'+module+'/index/'+kat_id+'/'+dvd_nama;
		break;
	}
	
	switch (mod_class[0]) {
		case 'tracking' :
			$("#content-wrap").hide();
			$("#content-wrap-2").load(controller,function(data){
				
			}).show();
		break;
		default:
			if (mod_class[1] == 'belanja'){
				$("#content-wrap").hide();
				$("#content-wrap-2").load(controller,function(data){
					
				}).show();
			} 
			else if (mod_class[1] == 'checklist'){
				$("#content-wrap").hide();
				$("#content-wrap-2").load(controller,function(data){
					
				}).show();
			} 
			else {
				$("#content-wrap-2").hide();
				$("#content-wrap").show();
				$("#content-ajax").load(controller,function(data){
				
				});
			}
		break;
	}
	
	return controller;
}

$(document).ready(function(){
	// ROUTINE - LAVALAMP CALLBACK
	var lava = $("ol.lavalamp");
	lava.lavaLamp({ 
		fx: "easeOutBack", 
		speed: 1000,
		click: function(event, menuItem) {
			var module = $('a',menuItem).attr('module');
			// CALL FUNC MENU AJAX
			if (module != '')
				menu_ajax(module);
			return false; 
		},
		startItem: 1, 
		autoReturn: true,
		returnDelay: 0,
		setOnClick: true,
		homeTop:146,
		homeLeft:220,
		homeWidth:0,
		homeHeight:0,
		returnHome:false
	});
	
	menu_ajax('home');
});