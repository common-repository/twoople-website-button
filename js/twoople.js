jQuery(function(){
	
	jQuery("#widget-generator input#user-name").keypress(function(e){
		if(e.keyCode == 32)
			return false;
	});
	
	jQuery("#widget-generator input#user-name").on('input', function(e){
		var username = jQuery(this).val();
		jQuery("#twoople-chat-button .address").text('twoople.com/'+username);
	});
	
		
	jQuery( "#widget-generator" ).change(function() {
		var username = jQuery('input#user-name').val();
		var position = jQuery('select#widget-position').val();
		var header = jQuery('select#widget-header').val();
		var style = jQuery('select#widget-style').val();
		jQuery("#twoople-chat-button").removeClass();
		jQuery("#twoople-chat-button").addClass(style);
		jQuery("#twoople-chat-button .header").html(header);			
	});

});