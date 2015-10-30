/**
 * jQuery yiiactiveform dynamic plugin file, extension of the standard yiiactiveform plugin file
 *
 * @author Mikelantonio <mikelantonio.trizio@gmail.com>
 * @link http://www.qiris.it/
 * @copyright Copyright &copy; 2013 QIRIS
 * @license http://www.yiiframework.com/license/
 */

(function($){
	
	$.yiiDynActiveForm = function(element, options){
        this.$el = $(element);
        this._init(options);
    };
	
	$.yiiDynActiveForm.defaults = {
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
	                settings = $.extend(true, {}, $.yiiDynActiveForm.defaults, options);
	            }
	            else {
	                settings = $.extend(true, {}, settings, options);
	            }
	            $this.data('settings', settings);
				
			},
			
			/**
			 * @var scope string the html to append
			 */
			addModelToValidation: function(options){
				
				return $(this).each(function(){
					
					var form  = $(this);
					var settings = form.data('settings');
					
					$('input, select, textarea', options.scope).each(function(){
						
						var el = $(this);
						var id = el.attr('id');
						var name = el.attr('name');
						var model = el.data('model');
						
						settings.attributes.push({
							enableAjaxValidation : true,
		                    errorCssClass : 'error',
		                    errorID: id+'_em',
		                    hideErrorMessage: true,
		                    id: id,
		                    inputContainer: 'div.control-group',
		                    inputID: id,
		                    model: model,
		                    name: name,
		                    validationDelay: 200,
		                    validateOnSubmit : false,
		                    validateOnChange : true,
		                    validateOnType : false,
		                    status: 1,
		                    successCssClass : 'success',
		                    validatingCssClass : 'validating',
		                    validationDelay: 200,
		                    value: '' 
						});
					});
					
					form.data('settings', settings);
				});
				
			},
			
			/**
			 * @var target jquery object the button itself
			 * @var rowClass string the class of the row
			 */
			removeModelFromValidation: function(options){
				
				return $(this).each(function(){
					
					var form  = $(this);
					
					//get last row
					var lastRow = $('.'+options.rowClass, '#'+form.attr('id')).last();
					
					//get settings
					var settings = form.data('settings');
					
					//remove validation attribute of the last row
					$('input, select, textarea', lastRow).each(function(){
						
						var base = $(this);
						var id = base.attr('id');
						
						settings.attributes = $.grep(settings.attributes, function(el, i){
							
							if(el.id == id)
								return false;
							
							return true;
						});
						
					});
					
					//remove the selected row
					$(options.target).parents('.'+options.rowClass).remove();
					
					//reindexing rows
					$('.'+options.rowClass, '#'+form.attr('id')).each(function(index, el){
						
							$('input, select, textarea', el).each(function(i, input){
								$(input).attr('id', $(input).attr('id').replace(/\d+/, index));
								$(input).attr('name', $(input).attr('name').replace(/\d+/, index));
							});
					});
					
					//save settings
					form.data('settings', settings);
				});
			}
			
	};

	
	$.fn.yiiDynActiveForm = function (method) {
		
		// Method calling logic
        if ( methods[method] ) {
          return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.init.apply( this, arguments );
        } else {
          $.error( 'Method ' +  method + ' does not exist on yiiDynActiveForm' );
        }
	};
	
	/***********************PRIVATE METHODS***********************/
	
	/***********************END PRIVATE METHODS*******************/
}(jQuery));