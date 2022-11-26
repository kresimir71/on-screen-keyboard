     jQuery(function () {
         onScreenKeyboardJS.init("onScreenKeyboardElmId");

         //first input focus
         //var $firstInput = $(':input').first().focus();
		 var $firstInput = jQuery(':input').first().focus(); //kk71
         onScreenKeyboardJS.currentElement = $firstInput;
         onScreenKeyboardJS.currentElementCursorPosition = 0;
     });
