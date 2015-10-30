/*
 *  Project: Qiris Platform
 *  Description: 
 *  Author: Mikelantonio
 *  License: 
 */
(function($){
	$.qirisError = function(element, options){
        this.$el = $(element);
        this._init(options);
    };
	
	$.qirisError.defaults = {
			owner_id : 0,
			owner_type : ""
	};
	var methods={
			
			_init :  function(options){
				
				var $this = $(this);
	            var instance = this;

	            // Get settings from stored instance
	            var settings = $this.data('settings');

	            // Create or update the settings of the current gallery instance
	            if (typeof(settings) == 'undefined') {
	                settings = $.extend(true, {}, $.qirisError.defaults, options);
	            }
	            else {
	                settings = $.extend(true, {}, settings, options);
	            }
	            $this.data('settings', settings);
				
			},
			
			message: function(options){
				
				var $this = $(this);
	            var $instance = $(this.$el);
	            
	            var settings = $this.data('settings');
	            
				var options = $.extend( {}, settings, options);
				
				var modal=options.modal;
				
				var subid=$($instance[0]).attr('id');
				
				modal=modal.replace(/__SUB/g, subid);
				
				$instance.append(modal);
				
				
			},
			
			error: function(options){
				
				var errors=options.errors;
				var title=options.title;
				var formId=options.formId;
				var entity=options.entity;
				var nested=options.nested;
				var message="";
				if(title==null) title ='';
				$(".error").removeClass('error');
				
				var processedErrorList = new Array();
				
				$('#'+formId+' *[name]').each(function(){
					
					if($(this).attr("type")!="hidden" && $(this).is(':disabled') == false){
						
						name = $(this).attr('name');
						name = name.replace(/\[\]/g, '');
						name = name.replace(/\]\[/g, '_');
						name = name.replace(/\[/g,  '_');
						name = name.replace(/\]/g,  '');
						name = name.replace(/ /g,  '_');
						
						for (var key in errors){
							if (name == key) {
								
								if (errors[key][0].length > 0 && $.inArray($(this).attr('name'), processedErrorList) ==-1) {
									message = message+"<br/>"+errors[key];
									processedErrorList.push($(this).attr('name'));
								}
							
								if ($(this).prev().hasClass("token-input-list-bootstrap"))
									$(this).prev().addClass("error");
								else
									$(this).parents('.form-group').addClass('has-error');
							}
							message = message.replace(".,", ".<br/>");
						}
					}
				});
				
				$(".toast-item-wrapper", document).remove();
				
				$().toastmessage('showToast',{
					text: message,
					sticky: true,
					position: 'top-right',
					type: 'error',
					title: title
				});
			}
	}

	
	$.fn.qirisError = function (method) {
		
		// Method calling logic
        if ( methods[method] ) {
          return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.init.apply( this, arguments );
        } else {
          $.error( 'Method ' +  method + ' does not exist on qirisError' );
        }
	};
	
	/***********************PRIVATE METHODS***********************/
	
	function processNest(error, message, entity, key){
		
		if(jQuery.type(error) === "array"){
		
			var msg="";
			for(var i in error){
				msg = msg + processNest(error[i], message, entity, i);
			}
			return msg;
			
		}else if (jQuery.type(error) === "object"){
			
			var msg="";
			for(var i in error){
				msg = msg + processNest(error[i], message, entity, i);	
			}
			
			return msg;
			
		}else if(jQuery.type(error) === "string"){
			
			return error+"<br/>";
		}else
			return "";
		
	}
	
	/***********************END PRIVATE METHODS*******************/
}(jQuery));