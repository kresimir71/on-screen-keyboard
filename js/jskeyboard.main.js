     jQuery(function () {
         onScreenKeyboardJS.init("onScreenKeyboardElmId");

         //first input focus: one (call it first) input element will get focus
         var $firstInput = jQuery(':input').first().focus();
         onScreenKeyboardJS.currentElement = $firstInput;
         onScreenKeyboardJS.currentElementCursorPosition = 0;
     });
