jQuery( document ).ready( function( $ )
{
	toggle_remove_buttons();

	function add_cloned_fields( $input )
	{
		var $clone_last = $input.find( '.rwmb-clone:last' ),
			$clone = $clone_last.clone(true),
			$input, name;

		$clone.insertAfter( $clone_last );




        $input = $clone.find( '*' );

		// Reset value
		//$input.val( '' );

        //reset checkboxes
        $clone.find( 'input:checkbox').prop('checked', false);
        var next_id;
        for (var i = 0; i < $input.length; ++i) {
            // Get the field name, and increment
            if( $($input[i]).attr( 'name' ) ) {
                name = $($input[i]).attr( 'name' ).replace( /\[(\d+)\]/, function( match, p1 )
                {
                    next_id =  parseInt( p1 ) + 1;
                    return '[' + ( parseInt( p1 ) + 1 ) + ']';
                } );

                // Update the "name" attribute
                $($input[i]).attr( 'name', name );
            }

            if( $($input[i]).attr( 'id' ) ) {
                name = $($input[i]).attr( 'id' ).replace( /\[(\d+)\]/, function( match, p1 )
                {
                    return '[' + ( parseInt( p1 ) + 1 ) + ']';
                } );

                // Update the "name" attribute
                $($input[i]).attr( 'id', name );
            }




           /*
           if( $($input[i]).attr( 'data-editor' ) ) {
                name = $($input[i]).attr( 'data-editor' ).replace( /\[(\d+)\]/, function( match, p1 )
                {
                    return '[' + ( parseInt( p1 ) + 1 ) + ']';
                } );

                // Update the "name" attribute
                $($input[i]).attr( 'data-editor', name );
            }
            */
        }

        var wysiwyg_html = '<div id="wp-IMPRESS_slide[' + next_id + '][content]-wrap" class="wp-core-ui wp-editor-wrap tmce-active"><link rel="stylesheet" id="editor-buttons-css"  href="http://impress.ubuntumin/wp-includes/css/editor.min.css?ver=3.6" type="text/css" media="all" /><div id="wp-IMPRESS_slide[' + next_id + '][content]-editor-tools" class="wp-editor-tools hide-if-no-js"><a id="IMPRESS_slide[' + next_id + '][content]-html" class="wp-switch-editor switch-html" onclick="console.log(this);switchEditors.switchto(this);">Text</a><a id="IMPRESS_slide[' + next_id + '][content]-tmce" class="wp-switch-editor switch-tmce" onclick="console.log(this);switchEditors.switchto(this);">Visual</a><div id="wp-IMPRESS_slide[' + next_id + '][content]-media-buttons" class="wp-media-buttons"><a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="IMPRESS_slide[' + next_id + '][content]" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a></div></div><div id="wp-IMPRESS_slide[' + next_id + '][content]-editor-container" class="wp-editor-container"><textarea class="wp-editor-area" rows="20" cols="40" name="IMPRESS_slide[' + next_id + '][content]" id="IMPRESS_slide[' + next_id + '][content]"></textarea></div></div>';

        $wysiwyg = $clone.find( '.wp-editor-wrap').replaceWith(wysiwyg_html );



        //$wysiwyg = $clone.find( '.wp-editor-wrap textarea' );
        var text_area = $clone.find( '.wp-editor-wrap textarea' );
        var tid = text_area[0].id;
       //tinyMCEPreInit.mceInit[ tid];
       //tinymce.execCommand('mceAddControl',false,tid);



         newid = tid;

        first_init = {mode:"exact",width:"100%",theme:"advanced",skin:"wp_theme",language:"en",theme_advanced_toolbar_location:"top",theme_advanced_toolbar_align:"left",theme_advanced_statusbar_location:"bottom",theme_advanced_resizing:true,theme_advanced_resize_horizontal:false,dialog_type:"modal",formats:{
        alignleft : [
            {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'left'}},
            {selector : 'img,table', classes : 'alignleft'}
        ],
            aligncenter : [
            {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'center'}},
            {selector : 'img,table', classes : 'aligncenter'}
        ],
            alignright : [
            {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'right'}},
            {selector : 'img,table', classes : 'alignright'}
        ],
            strikethrough : {inline : 'del'}
    },relative_urls:false,remove_script_host:false,convert_urls:false,remove_linebreaks:true,gecko_spellcheck:true,fix_list_elements:true,keep_styles:false,entities:"38,amp,60,lt,62,gt",accessibility_focus:true,media_strict:false,paste_remove_styles:true,paste_remove_spans:true,paste_strip_class_attributes:"all",paste_text_use_dialog:true,webkit_fake_resize:false,preview_styles:"font-family font-weight text-decoration text-transform",schema:"html5",wpeditimage_disable_captions:false,wp_fullscreen_content_css:"http://impress.ubuntumin/wp-includes/js/tinymce/plugins/wpfullscreen/css/wp-fullscreen.css",plugins:"inlinepopups,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs",content_css:"//fonts.googleapis.com/css?family=Source+Sans+Pro%3A300%2C400%2C700%2C300italic%2C400italic%2C700italic%7CBitter%3A400%2C700&subset=latin%2Clatin-ext,http://impress.ubuntumin/wp-content/themes/twentythirteen/css/editor-style.css,http://impress.ubuntumin/wp-content/themes/twentythirteen/fonts/genericons.css",elements:"IMPRESS_slide[0][content]",wpautop:true,apply_source_formatting:false,theme_advanced_buttons1:"bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",theme_advanced_buttons2:"formatselect,underline,justifyfull,forecolor,pastetext,pasteword,removeformat,charmap,outdent,indent,undo,redo,wp_help",theme_advanced_buttons3:"",theme_advanced_buttons4:"",tabfocus_elements:":prev,:next",body_class:"IMPRESS_slide[0][content] post-type-impress post-status-publish",theme_advanced_resizing_use_cookie:true}
        ;


        tinyMCEPreInit = {
            base : "http://impress.ubuntumin/wp-includes/js/tinymce",
            suffix : "",
            query : "ver=358-24485",
         //   mceInit : {'IMPRESS_slide[1][content]':{elements: newid,wpautop:true,remove_linebreaks:true,apply_source_formatting:false,theme_advanced_buttons1:"bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",theme_advanced_buttons2:"formatselect,underline,justifyfull,forecolor,pastetext,pasteword,removeformat,charmap,outdent,indent,undo,redo,wp_help",theme_advanced_buttons3:"",theme_advanced_buttons4:"",tabfocus_elements:":prev,:next",body_class:"IMPRESS_slide[1][content] post-type-impress post-status-publish",theme_advanced_resizing_use_cookie:true}},
         //   qtInit : {'IMPRESS_slide[1][content]':{id:"IMPRESS_slide[1][content]",buttons:"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"}},
            ref : {plugins:"inlinepopups,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs",theme:"advanced",language:"en"},
            load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
        };
        tinyMCEPreInit.mceInit = {};
        tinyMCEPreInit.mceInit[newid] = {elements: newid ,wpautop:true,remove_linebreaks:true,apply_source_formatting:false,theme_advanced_buttons1:"bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",theme_advanced_buttons2:"formatselect,underline,justifyfull,forecolor,pastetext,pasteword,removeformat,charmap,outdent,indent,undo,redo,wp_help",theme_advanced_buttons3:"",theme_advanced_buttons4:"",tabfocus_elements:":prev,:next",body_class: newid +" post-type-impress post-status-publish",theme_advanced_resizing_use_cookie:true},
        tinyMCEPreInit.qtInit = {};
        tinyMCEPreInit.qtInit[newid] = {id:newid,buttons:"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"};




        var wpActiveEditor;

        (function(){
            var init, ed, qt, DOM, el, i, mce = 1;

            if ( typeof(tinymce) == 'object' ) {
            DOM = tinymce.DOM;
            // mark wp_theme/ui.css as loaded
            DOM.files[tinymce.baseURI.getURI() + '/themes/advanced/skins/wp_theme/ui.css'] = true;

            DOM.events.add( DOM.select('.wp-editor-wrap'), 'mousedown', function(e){
            if ( this.id )
            wpActiveEditor = this.id.slice(3, -5);
            });

        for ( ed in tinyMCEPreInit.mceInit ) {
            if ( first_init ) {
            init = tinyMCEPreInit.mceInit[ed] = tinymce.extend( {}, first_init, tinyMCEPreInit.mceInit[ed] );
        } else {
            init = first_init = tinyMCEPreInit.mceInit[ed];
            }

        if ( mce )
        try { tinyMCE.init(init); } catch(e){}
        }
        } else {
            if ( tinyMCEPreInit.qtInit ) {
            for ( i in tinyMCEPreInit.qtInit ) {
            el = tinyMCEPreInit.qtInit[i].id;
            if ( el )
            document.getElementById('wp-'+el+'-wrap').onmousedown = function(){ wpActiveEditor = this.id.slice(3, -5); }
        }
        }
        }

        if ( typeof(QTags) == 'function' ) {
            for ( qt in tinyMCEPreInit.qtInit ) {
            try { quicktags( tinyMCEPreInit.qtInit[qt] ); } catch(e){}
        }
            QTags._buttonsInit();
        }
        })();

        (function(){var t=tinyMCEPreInit,sl=tinymce.ScriptLoader,ln=t.ref.language,th=t.ref.theme,pl=t.ref.plugins;sl.markDone(t.base+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'_dlg.js');sl.markDone(t.base+'/themes/advanced/skins/wp_theme/ui.css');tinymce.each(pl.split(','),function(n){if(n&&n.charAt(0)!='-'){sl.markDone(t.base+'/plugins/'+n+'/langs/'+ln+'.js');sl.markDone(t.base+'/plugins/'+n+'/langs/'+ln+'_dlg.js');}});})();




//        tinyMCE.init(tinyMCEPreInit.mceInit[ tid]);
  //      tinyMCE.init(tinyMCEPreInit.qtInit[tid]);




		// Toggle remove buttons
		toggle_remove_buttons( $input );

		// Fix color picker
		if ( 'function' === typeof rwmb_update_color_picker )
			rwmb_update_color_picker();

		// Fix date picker
		if ( 'function' === typeof rwmb_update_date_picker )
			rwmb_update_date_picker();

		// Fix time picker
		if ( 'function' === typeof rwmb_update_time_picker )
			rwmb_update_time_picker();

		// Fix datetime picker
		if ( 'function' === typeof rwmb_update_datetime_picker )
			rwmb_update_datetime_picker();
	}

	// Add more clones
	$( '.add-clone' ).click( function()
	{
		var $input = $( this ).parents( '.rwmb-input' ),
			$clone_group = $( this ).parents( '.rwmb-field' ).attr( "clone-group" );

		// If the field is part of a clone group, get all fields in that
		// group and itterate over them
		if ( $clone_group )
		{
			// Get the parent metabox and then find the matching
			// clone-group elements inside
			var $metabox = $( this ).parents( '.inside' );
			var $clone_group_list = $metabox.find( 'div[clone-group="' + $clone_group + '"]' );

			$.each( $clone_group_list.find( '.rwmb-input' ),
				function( key, value )
				{
					add_cloned_fields( $( value ) );
				} );
		}
		else
			add_cloned_fields( $input );

		toggle_remove_buttons( $input );

		return false;
	} );

	// Remove clones
	$( '.rwmb-input' ).delegate( '.remove-clone', 'click', function()
	{
		var $this = $( this ),
			$input = $this.parents( '.rwmb-input' ),
			$clone_group = $( this ).parents( '.rwmb-field' ).attr( 'clone-group' );

		// Remove clone only if there're 2 or more of them
		if ( $input.find( '.rwmb-clone' ).length <= 1 )
			return false;

		if ( $clone_group )
		{
			// Get the parent metabox and then find the matching
			// clone-group elements inside
			var $metabox = $( this ).parents( '.inside' );
			var $clone_group_list = $metabox.find( 'div[clone-group="' + $clone_group + '"]' );
			var $index = $this.parent().index();

			$.each( $clone_group_list.find( '.rwmb-input' ),
				function( key, value )
				{
					$( value ).children( '.rwmb-clone' ).eq( $index ).remove();

					// Toggle remove buttons
					toggle_remove_buttons( $( value ) );
				} );
		}
		else
		{
			$this.parent().remove();

			// Toggle remove buttons
			toggle_remove_buttons( $input );
		}

		return false;
	} );

	/**
	 * Hide remove buttons when there's only 1 of them
	 *
	 * @param $el jQuery element. If not supplied, the function will applies for all fields
	 *
	 * @return void
	 */
	function toggle_remove_buttons( $el )
	{
		var $button;
		if ( !$el )
			$el = $( '.rwmb-field' );
		$el.each( function()
		{
			$button = $( this ).find( '.remove-clone' );
			$button.length < 2 ? $button.hide() : $button.show();
		} );
	}
} );