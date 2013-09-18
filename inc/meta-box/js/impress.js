jQuery(document).ready( function( $ ) {

    $('.upload_image_button').click(function() {
      //  var iid = '#' + this.id.substring(0, this.id.length - 7);
        formfield = $(this).prev().attr('name');
        prev = $(this).prev();
        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
        return false;
    });

    window.send_to_editor = function(html,x,y) {

        imgurl = $('img',html).attr('src');
        $(prev).val(imgurl);
        tb_remove();
    }

});