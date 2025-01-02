(function($) {

	"use strict";

	 $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
          includeSelectAllOption: true,
          buttonClass: 'form-control',
          selectAllJustVisible: false,
        });

       /* var ms  = document.getElementsByClassName('multiselect');
        console.log(ms);*/
    });
	 
})(jQuery);
