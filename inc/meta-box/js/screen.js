jQuery( document ).ready( function( $ ){

	function adapt(){
		var w = $('#screen_frame').width();
		$('#screen_frame').height(w*.44);
	}
	$(window).resize(function(){
		adapt();
	});
	adapt();

} );