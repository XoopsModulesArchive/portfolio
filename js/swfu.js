var swfu; 
window.onload = function () { 
	var settings = {
			flash_url : paramFlashUrl,
			upload_url: paramUploadUrl,
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.jpeg;*.gif;*.png",
			file_types_description : "Images",
			file_upload_limit : 100,
			file_queue_limit : 0,
			custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "btnCancel"
			},
			debug: false,
			post_params : {
				"op" : "savePhoto",
				"secret" : paramSecret,
				"sessionName" : paramSessionName,
				"sessionValue" : paramSessionValue
			},

			// Button settings
			button_image_url: "../images/TestImageNoText_65x29.png",
			button_width: "65",
			button_height: "29",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text: '<span class="theFont">'+paramButtonName +'</span>',
			button_text_style: ".theFont { font-size: 16; }",
			button_text_left_padding: 12,
			button_text_top_padding: 3,
			
			// The event handler functions are defined in handlers.js
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			queue_complete_handler : queueComplete	// Queue plugin event
		};
	
	swfu = new SWFUpload(settings);
};