/*
 *
 * IMPRESS_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function impress_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.impress-radio-img-'+labelclass).removeClass('impress-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('impress-radio-img-selected');
}//function