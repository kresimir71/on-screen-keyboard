     jQuery(function () {
         onScreenKeyboardJS.init("onScreenKeyboardElmId");

         //first input focus
	 var $firstInput = jQuery(':input').first().focus();
         onScreenKeyboardJS.currentElement = $firstInput;
         onScreenKeyboardJS.currentElementCursorPosition = 0;
     });
