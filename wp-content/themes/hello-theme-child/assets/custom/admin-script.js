jQuery( document ).ready( function( $ ) {
	// Double Scroll
 	//jQuery('#both-side-scroll').doubleScroll(); 

	jQuery('.toggle-row-label').click(function(){
		 jQuery(this).toggleClass('active-row'); 
		 jQuery(this).closest('.field-row').find('.toggle_field_row').toggle('slow');  
	});

	jQuery('.option_remove_media' ).on( 'click', function() {
		current_upload_btn = jQuery(this).closest(".option-image-uploader"); 
		current_upload_btn.find('input[type="url"]').val('');
		current_upload_btn.find(".image-preview").attr( 'src','');
	});  

	 function visibleFriday(date){ 
	 	var day = date.getDay();
         return [(day != 0 && day != 1 && day != 2 && day != 3 && day != 4 && day != 5)];        
      };


	/*jQuery('.date_field').datetimepicker({
		format:'Y-m-d',
		timepicker:false,
		minDate: 0,
		beforeShowDay: visibleFriday,
	});  */

	//jQuery('.datetime_field').datetimepicker({format:'Y-m-d H:i:s'});  
 
	jQuery('#wstheme_sel_all').click(function(){

		var thisvalue = jQuery(this).attr('checked');

		if(thisvalue){
			jQuery('.selected_items').attr('checked','checked');
		}else{
			jQuery('.selected_items').removeAttr('checked');
		}
	 	 
	});
	
	jQuery('.del').click(function(){ 
		var r = confirm("Are you sure you want to delete?.");
		if(r!=true) { 
		   return false;
		}  
	}); 

	var file_frame; 
	var wp_media_post_id = wp.media.model.settings.post.id;  
	// Store the old id 
		var current_upload_btn =''; 
	var set_to_post_id = ''; // Set this  

	jQuery('.upload_wp_img').click(function(event){  
		
		//file_frame.open(); 
		current_upload_btn = jQuery(this).closest(".upload_wp_img_section"); 
		event.preventDefault(); 
		if ( file_frame ) { 
			file_frame.uploader.uploader.param( 'post_id', set_to_post_id ); 
			file_frame.open(); 
			return; 
		} else {  
			wp.media.model.settings.post.id = set_to_post_id; 
		} 
		file_frame = wp.media.frames.file_frame = wp.media({ 
			title: 'Select a image to upload', 
			button: { 
				text: 'Use this image', 
			}, 
			multiple: false	 
		}); 
		file_frame.on( 'select', function(nameinputa) { 
			attachment = file_frame.state().get('selection').first().toJSON(); 
			current_upload_btn.find('input[type="url"]').val(attachment.url); 
			current_upload_btn.find(".image-preview").attr( 'src', attachment.url); 
			wp.media.model.settings.post.id = wp_media_post_id; 
		}); 
			file_frame.open();

	});  

	jQuery('.delivery_action_update').click(function(event){
		var row_btn = jQuery(this);  
		var row = row_btn.closest('tr'); 
		var  delivery_id = row.data('id');
		var  delivery_status = row.find('.delivery_status').val();
		var  delivery_remark = row.find('.delivery_remark').val();
		console.log('Test');
		jQuery.ajax({
          url: ajaxurl,            
          data : {
              action : 'delivery_status_update',
              delivery_id : delivery_id,
              delivery_status : delivery_status,
              delivery_remark : delivery_remark,
          },
          type: 'post',           
          dataType: 'json',
          cache: false,
          beforeSend:function(html){   
               var sub_confrm = confirm("Are you sure you want to update?");
                if(sub_confrm!=true) { 
                   return false;
                }else{
                	row_btn.text('Wait...');
                }
               
          },
          success: function (data) {
             var return_status = data.status;
             var return_redirect = data.redirect;
             if(return_status=='Valid' && return_redirect!=''){
             	window.location.href = return_redirect;
             }else if(return_status=='Valid'){
             	row_btn.text('Updated');
             }else if(return_status!='Valid'){
             	row_btn.text('Error');
             }
          },
          error: function (data) { 
              alert('Something wrong, Please refresh page.');
          } 
      });

	});

});

function getval(status)
{
    var statusVal = status.value;

}

function order_status_function(week_no, order_id, item_id){  

      jQuery.ajax({
          url: 'https://otterfresh.in/wp-admin/admin-ajax.php',            
          data : {
              action : 'order_status_change',
              week_no : week_no,
              order_id : order_id,
              item_id : item_id,
          },
          type: 'get',           
          dataType: 'json',
          cache: false,
          beforeSend:function(html){   
               var sub_confrm = confirm("Are you sure you want to change status?");
                if(sub_confrm!=true) { 
                   return false;
                }
               
          },
          success: function (results) {
             
          },
          error: function (response) {
              console.log('error');
          }
          
      });

      return false; 

  }