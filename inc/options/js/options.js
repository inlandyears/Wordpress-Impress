jQuery(document).ready(function(){
	
	
	if(jQuery('#last_tab').val() == ''){

		jQuery('.impress-opts-group-tab:first').slideDown('fast');
		jQuery('#impress-opts-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown('fast');
		jQuery('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
	jQuery('input[name="'+impress_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(impress_opts.reset_confirm)){
			return false;
		}
	});
	
	jQuery('.impress-opts-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
		jQuery('.impress-opts-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				jQuery(this).delay(200).fadeIn(300);
			}else{
				jQuery(this).fadeOut(300);
			}
			
		});
		
		jQuery('.impress-opts-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});
	});
	
	
	
	
	if(jQuery('#impress-opts-save').is(':visible')){
		jQuery('#impress-opts-save').delay(4000).slideUp('slow');
	}
	
	if(jQuery('#impress-opts-imported').is(':visible')){
		jQuery('#impress-opts-imported').delay(4000).slideUp('slow');
	}	
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#impress-opts-save-warn').slideDown('slow');
	});
	
	
	jQuery('#impress-opts-import-code-button').click(function(){
		if(jQuery('#impress-opts-import-link-wrapper').is(':visible')){
			jQuery('#impress-opts-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#impress-opts-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#impress-opts-import-link-button').click(function(){
		if(jQuery('#impress-opts-import-code-wrapper').is(':visible')){
			jQuery('#impress-opts-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#impress-opts-import-link-wrapper').fadeIn('slow');
	});
	
	
	
	
	jQuery('#impress-opts-export-code-copy').click(function(){
		if(jQuery('#impress-opts-export-link-value').is(':visible')){jQuery('#impress-opts-export-link-value').fadeOut('slow');}
		jQuery('#impress-opts-export-code').toggle('fade');
	});
	
	jQuery('#impress-opts-export-link').click(function(){
		if(jQuery('#impress-opts-export-code').is(':visible')){jQuery('#impress-opts-export-code').fadeOut('slow');}
		jQuery('#impress-opts-export-link-value').toggle('fade');
	});
	

	
	
});