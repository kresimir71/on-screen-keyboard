var onScreenKeyboardJS = {
    settings: {
        buttonClass: "button_oskb_default", // default button class
        onclick: "onScreenKeyboardJS.write();", // default onclick event for button
        keyClass: "key_oskb", // default key class used to define style of text of the button
        // text: {
        //     close: "close"
        // }
    },
    "keyboard": {}, // different keyboards can be set to this variable in order to switch between keyboards easily.
    init: function(elem, keyboard) {
        onScreenKeyboardJS.keyboard["default"] = onScreenKeyboardJS.defaultKeyboard;
        onScreenKeyboardJS.keyboardLayout = elem;
	// If not 'numeric' then ignored. If 'numeric' then the numeric keyboard variant is taken without possibility to tap for another keyboard. 
	onScreenKeyboardJS.keyboardLayout_data_id = jQuery("#" + onScreenKeyboardJS.keyboardLayout).data('id'); // fancy for: element.getAttribute('data-id'); //kk71

	// The html input element (where we can see input text) can be specified.
	onScreenKeyboardJS.keyboardLayout_data_keyboard = jQuery("#" + onScreenKeyboardJS.keyboardLayout).data('keyboard'); // fancy for: element.getAttribute('data-keyboard'); //kk71

        if (keyboard != null && keyboard != undefined)
            onScreenKeyboardJS.generateKeyboard(keyboard);
        else
            onScreenKeyboardJS.generateKeyboard("default");
	
	if ( onScreenKeyboardJS.keyboardLayout_data_keyboard != null && onScreenKeyboardJS.keyboardLayout_data_keyboard == "numeric" ){
	    onScreenKeyboardJS.changeToOnlyNumber();
	}
        onScreenKeyboardJS.addKeyDownEvent();

        onScreenKeyboardJS.show();
        jQuery(':input').not('[type="reset"]').not('[type="submit"]').on('focus, click', function(e)
         {
            onScreenKeyboardJS.currentElement = jQuery(this);
            onScreenKeyboardJS.currentElementCursorPosition = jQuery(this).getCursorPosition();
            //console.log('keyboard is now focused on '+onScreenKeyboardJS.currentElement.attr('name')+' at pos('+onScreenKeyboardJS.currentElementCursorPosition+')');
         });

	if ( onScreenKeyboardJS.keyboardLayout_data_id != null){
	    // input element is given from html
	    onScreenKeyboardJS.currentElement = jQuery("#"+onScreenKeyboardJS.keyboardLayout_data_id);
	    onScreenKeyboardJS.currentElement.trigger( "focus" ); // same as focus()
	} else {
	    // If not specified then the first ':input' element will be focused.
	    // first input focus
	    var $firstInput;
	    $firstInput = null;
	    try {
		$firstInput = jQuery(':input').first().focus();
	    } catch (e) {
		// no input element
	    }
            onScreenKeyboardJS.currentElement = $firstInput;
	}

	onScreenKeyboardJS.currentElementCursorPosition =
	    onScreenKeyboardJS.currentElement != null
	    ? jQuery(onScreenKeyboardJS.currentElement).getCursorPosition()
	    : 0;
	
    },
    focus: function(t) {
        onScreenKeyboardJS.currentElement = jQuery(t);
        onScreenKeyboardJS.show();
    },
    keyboardLayout: "", // it shows the html element where keyboard is generated
    keyboardLayout_data_id: null, // id of the input html element, if given
    keyboardLayout_data_keyboard: null, // if given, it can be "numeric" or "standard"
    currentKeyboard: "default", // it shows the which keyboard is used. If it's not set default keyboard is used.
    currentElement: null,
    generateKeyboard: function(keyboard) {
        var bClass = "";
        var kClass = "";
        var onclick = "";
        var text = "";

        var s = "";
        s += "<div id=\"keyboardOskb\">";
        s += "<div id=\"keyboardOskbHeader\">";
        // s += "<div onclick=\"onScreenKeyboardJS.hide();\"><span>" + onScreenKeyboardJS.settings.text.close + "</span><span class=\"closex\"> X</span></div>"
        s += "</div>";

        /*small letter */
        s += "<div id=\"keyboardOskbSmallLetter\">";
        jQuery.each(onScreenKeyboardJS.keyboard[keyboard].smallLetter, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*capital letter*/
        s += "<div id=\"keyboardOskbCapitalLetter\">";
        jQuery.each(onScreenKeyboardJS.keyboard[keyboard].capitalLetter, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*number*/
        s += "<div id=\"keyboardOskbNumber\">";
        jQuery.each(onScreenKeyboardJS.keyboard[keyboard].number, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*symbols*/
        s += "<div id=\"keyboardOskbSymbols\">";
        jQuery.each(onScreenKeyboardJS.keyboard[keyboard].symbols, function(i, key) {
            generate(key);
        });
        s += "</div>";

	/*onlynumber*/
        s += "<div id=\"keyboardOskbOnlyNumber\">";
        jQuery.each(onScreenKeyboardJS.keyboard[keyboard].onlynumber, function(i, key) {
            generate(key);
        });
        s += "</div>";
		
        function generate(key) {
            bClass = key.buttonClass == undefined ? onScreenKeyboardJS.settings.buttonClass : key.buttonClass;
            kClass = key.keyClass == undefined ? onScreenKeyboardJS.settings.keyClass : key.keyClass;
            onclick = key.onclick == undefined ? onScreenKeyboardJS.settings.onclick.replace("()", "(" + key.value + ")") : key.onclick;

            text = (key.isChar != undefined || key.isChar == false) ? key.value : String.fromCharCode(key.value);

            s += "<div class=\"" + bClass + "\" onclick=\"" + onclick + "\"><div class=\"" + kClass + "\">" + text + "</div></div>";

            bClass = ""; kClass = ""; onclick = ""; text = "";
        }

        jQuery("#" + onScreenKeyboardJS.keyboardLayout).html(s);
    },
    addKeyDownEvent: function() {
        jQuery("#keyboardOskbCapitalLetter > div.button_oskb_default, #keyboardOskbSmallLetter > div.button_oskb_default, #keyboardOskbNumber > div.button_oskb_default, #keyboardOskbSymbols > div.button_oskb_default, #keyboardOskbOnlyNumber > div.button_oskb_default").
            bind('mousedown', (function() { jQuery(this).addClass("buttonOskbDown"); })).
            bind('mouseup', (function() { jQuery(this).removeClass("buttonOskbDown"); })).
            bind('mouseout', (function() { jQuery(this).removeClass("buttonOskbDown"); }));

            //key focus down on actual keyboard key presses
            //todo:....

    },
    changeToSmallLetter: function() {
        jQuery("#keyboardOskbCapitalLetter,#keyboardOskbNumber,#keyboardOskbSymbols").css("display", "none");
        jQuery("#keyboardOskbSmallLetter").css("display", "block");
	onScreenKeyboardJS.updateCursor();	
    },
    changeToCapitalLetter: function() {
        jQuery("#keyboardOskbCapitalLetter").css("display", "block");
        jQuery("#keyboardOskbSmallLetter,#keyboardOskbNumber,#keyboardOskbSymbols").css("display", "none");
	onScreenKeyboardJS.updateCursor();	
    },
    changeToNumber: function() {
        jQuery("#keyboardOskbNumber").css("display", "block");
        jQuery("#keyboardOskbSymbols,#keyboardOskbCapitalLetter,#keyboardOskbSmallLetter").css("display", "none");
	onScreenKeyboardJS.updateCursor();	
    },
    changeToSymbols: function() {
        jQuery("#keyboardOskbCapitalLetter,#keyboardOskbNumber,#keyboardOskbSmallLetter").css("display", "none");
        jQuery("#keyboardOskbSymbols").css("display", "block");
	onScreenKeyboardJS.updateCursor();
    },
    changeToOnlyNumber: function() {
        jQuery("#keyboardOskbSymbols,#keyboardOskbCapitalLetter,#keyboardOskbSmallLetter,#keyboardOskbNumber").css("display", "none");
        jQuery("#keyboardOskbOnlyNumber").css("display", "block");
    },

    updateCursor: function()
    {
        //input cursor focus and position during typing
	if( onScreenKeyboardJS.currentElement != null ){
            onScreenKeyboardJS.currentElement.setCursorPosition(onScreenKeyboardJS.currentElementCursorPosition);
	    onScreenKeyboardJS.currentElement.focus();
	}
    },
    write: function(m) {
	if( onScreenKeyboardJS.currentElement == null ) return;	    
	onScreenKeyboardJS.currentElementCursorPosition = jQuery(onScreenKeyboardJS.currentElement).getCursorPosition();
        var a = onScreenKeyboardJS.currentElement.val(),
            b = String.fromCharCode(m),
            pos = onScreenKeyboardJS.currentElementCursorPosition,
            output = [a.slice(0, pos), b, a.slice(pos)].join('');
        onScreenKeyboardJS.currentElement.val(output);
        onScreenKeyboardJS.currentElementCursorPosition++; //+1 cursor
        onScreenKeyboardJS.updateCursor();
	console.log(output);

	//jQuery("#" + onScreenKeyboardJS.keyboardLayout_data_id).val(output); //kk71
	try {
	    // automatically submit password if 12 digits (this can only happen if numeric keyboard is used, see php)
	    if(output.length == 12 && /^\d+$/.test(output) ) jQuery("#" + onScreenKeyboardJS.keyboardLayout_data_id+"OskbPasswordProtectedPageButton").click();
	} catch (e) {
	    // button does not exist
	}
    },
    del: function() {
	if( onScreenKeyboardJS.currentElement == null ) return;	    
	onScreenKeyboardJS.currentElementCursorPosition = jQuery(onScreenKeyboardJS.currentElement).getCursorPosition();
        var a = onScreenKeyboardJS.currentElement.val(),
            pos = onScreenKeyboardJS.currentElementCursorPosition,
            output = [a.slice(0, pos>0 ? pos-1: 0), a.slice(pos)].join('');
        onScreenKeyboardJS.currentElement.val(output);
        if (pos>0) onScreenKeyboardJS.currentElementCursorPosition--; //-1 cursor
        onScreenKeyboardJS.updateCursor();
	// if ( onScreenKeyboardJS.keyboardLayout_data_id != null){
	//     jQuery("#" + onScreenKeyboardJS.keyboardLayout_data_id).val(output); //kk71
	// }
    },
    enter: function() {
	if( onScreenKeyboardJS.currentElement == null ) return;	    
        var t = onScreenKeyboardJS.currentElement.val();
        onScreenKeyboardJS.currentElement.val(t + "\n");
    },
    space: function() {
	if( onScreenKeyboardJS.currentElement == null ) return;	    
	onScreenKeyboardJS.currentElementCursorPosition = jQuery(onScreenKeyboardJS.currentElement).getCursorPosition();	
        var a = onScreenKeyboardJS.currentElement.val(),
            b = " ",
            pos = onScreenKeyboardJS.currentElementCursorPosition,
            output = [a.slice(0, pos), b, a.slice(pos)].join('');
        onScreenKeyboardJS.currentElement.val(output);
        onScreenKeyboardJS.currentElementCursorPosition++; //+1 cursor
        onScreenKeyboardJS.updateCursor();
    },
    writeSpecial: function(m) {
	if( onScreenKeyboardJS.currentElement == null ) return;	    
	onScreenKeyboardJS.currentElementCursorPosition = jQuery(onScreenKeyboardJS.currentElement).getCursorPosition();	
        var a = onScreenKeyboardJS.currentElement.val(),
            b = String.fromCharCode(m),
            pos = onScreenKeyboardJS.currentElementCursorPosition,
            output = [a.slice(0, pos), b, a.slice(pos)].join('');
        onScreenKeyboardJS.currentElement.val(output);
        onScreenKeyboardJS.currentElementCursorPosition++; //+1 cursor
        onScreenKeyboardJS.updateCursor();
    },
    show: function() {
        jQuery("#keyboardOskb").animate({ "bottom": "0" }, "slow", function() { });
    },
    //hide: function() {
    //    jQuery("#keyboardOskb").animate({ "bottom": "-350px" }, "slow", function() { });
    //},
    defaultKeyboard: {
        capitalLetter:
            [
        // 1st row
               { value: 81 },{ value: 87 },{ value: 69 },{ value: 82 },{ value: 84 },{ value: 89 },
               { value: 85 },{ value: 73 },{ value: 79 },{ value: 80 },
               { value: "Delete", isChar: "false", onclick: "onScreenKeyboardJS.del()", buttonClass: "button_oskb_default button_oskb_del", keyClass: "key_oskb key_oskb_del" },
        // 2nd row
               { value: 65, buttonClass: "button_oskb_default button_oskb_a" },{ value: 83 },{ value: 68 },{ value: 70 },
               { value: 71 },{ value: 72 },{ value: 74 },{ value: 75 },{ value: 76 },
               { value: "Enter", isChar: "false", buttonClass: "button_oskb_default button_oskb_enter", onclick: "onScreenKeyboardJS.enter();", keyClass: "key_oskb key_oskb_enter" },
        // 3rd row
               { value: "abc", isChar: "false", buttonClass: "button_oskb_default button_oskb_smallletter", onclick: "onScreenKeyboardJS.changeToSmallLetter();", keyClass: "key_oskb key_oskb_smallletter" },
               { value: 90 },{ value: 88 },{ value: 67 },{ value: 86 },{ value: 66 },{ value: 78 },
               { value: 77 },{ value: 44 },{ value: 46 },{ value: 64 },
        // 4th row
               { value: "123", isChar: "false", buttonClass: "button_oskb_default button_oskb_numberleft", onclick: "onScreenKeyboardJS.changeToNumber();", keyClass: "key_oskb key_oskb_number" },
               { value: "Space", isChar: "false", buttonClass: "button_oskb_default button_oskb_space", onclick: "onScreenKeyboardJS.space();", keyClass: "key_oskb key_oskb_space" },
               { value: "#@+", isChar: "false", buttonClass: "button_oskb_default button_oskb_symbolsright", onclick: "onScreenKeyboardJS.changeToSymbols();", keyClass: "key_oskb key_oskb_symbols" }
            ],
        smallLetter: [
        // 1st row
                { value: 113 },{ value: 119 },{ value: 101 },{ value: 114 },{ value: 116 },
                { value: 121 },{ value: 117 },{ value: 105 },{ value: 111 },{ value: 112 },
                { value: "Delete", isChar: "false", onclick: "onScreenKeyboardJS.del()", buttonClass: "button_oskb_default button_oskb_del", keyClass: "key_oskb key_oskb_del" },
        // 2nd row
                { value: 97, buttonClass: "button_oskb_default button_oskb_a" },{ value: 115 },{ value: 100 },{ value: 102 },
                { value: 103 },{ value: 104 },{ value: 106 },{ value: 107 },{ value: 108 },
                { value: "Enter", isChar: "false", buttonClass: "button_oskb_default button_oskb_enter", onclick: "onScreenKeyboardJS.enter();", keyClass: "key_oskb key_oskb_enter" },
        // 3rd row
                { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterleft", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterleft" },
                { value: 122 },{ value: 120 },{ value: 99 },{ value: 118 },{ value: 98 },
                { value: 110 },{ value: 109 },{ value: 44 },{ value: 46 },{ value: 64 },
        // 4th row
                { value: "123", isChar: "false", buttonClass: "button_oskb_default button_oskb_numberleft", onclick: "onScreenKeyboardJS.changeToNumber();", keyClass: "key_oskb key_oskb_number" },
                // { value: 32, buttonClass: "button_oskb_default button_oskb_space" },
                { value: "Space", isChar: "false", buttonClass: "button_oskb_default button_oskb_space", onclick: "onScreenKeyboardJS.space();", keyClass: "key_oskb key_oskb_space" },
                { value: "#@+", isChar: "false", buttonClass: "button_oskb_default button_oskb_symbolsright", onclick: "onScreenKeyboardJS.changeToSymbols();", keyClass: "key_oskb key_oskb_symbols" }
            ],
        number: [
        // 1st row
                { value: 49 },{ value: 50 },{ value: 51 },{ value: 52 },{ value: 53 },{ value: 54 },
                { value: 55 },{ value: 56 },{ value: 57 },{ value: 48 },
                { value: "Delete", isChar: "false", onclick: "onScreenKeyboardJS.del()", buttonClass: "button_oskb_default button_oskb_del", keyClass: "key_oskb key_oskb_del" },
        // 2nd row
                { value: 45, buttonClass: "button_oskb_default button_oskb_dash" },{ value: 47 },{ value: 58 },{ value: 59 },
                { value: 40 },{ value: 41 },{ value: 36 },{ value: 38 },{ value: 64 },
                { value: "Enter", isChar: "false", buttonClass: "button_oskb_default button_oskb_enter", onclick: "onScreenKeyboardJS.enter();", keyClass: "key_oskb key_oskb_enter" },
        //3rd row
                // { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterleft", onclick: "onScreenKeyboardJS.changeToCapitalLetter()", keyClass: "key_oskb key_oskb_capitalletterleft" },
                { value: "", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterleft", onclick: "", keyClass: "key_oskb" },
                { value: 63 },{ value: 33 },{ value: 34 },{ value: 124 },{ value: 92 },{ value: 42 },{ value: 61 },{ value: 43 },
                // { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterright", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterright" },
                { value: "", isChar: "false", buttonClass: "button_oskb_default", onclick: "", keyClass: "key_oskb" },
                { value: "", isChar: "false", buttonClass: "button_oskb_default", onclick: "", keyClass: "key_oskb" },

        // 4th row
                { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_numberleft", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterleft" },
                { value: "Space", isChar: "false", buttonClass: "button_oskb_default button_oskb_space", onclick: "onScreenKeyboardJS.space();", keyClass: "key_oskb key_oskb_space" },
                { value: "#@+", isChar: "false", buttonClass: "button_oskb_default button_oskb_symbolsright", onclick: "onScreenKeyboardJS.changeToSymbols();", keyClass: "key_oskb key_oskb_symbols" }
            ],
        symbols: [
        // 1st row
            { value: 91 },{ value: 93 },{ value: 123 },{ value: 125 },{ value: 35 },{ value: 37 },
            { value: 94 },{ value: 42 },{ value: 43 },{ value: 61 },
            { value: "Delete", isChar: "false", onclick: "onScreenKeyboardJS.del()", buttonClass: "button_oskb_default button_oskb_del", keyClass: "key_oskb key_oskb_del" },
        // 2nd row
            { value: 95, buttonClass: "button_oskb_default button_oskb_underscore" },{ value: 92 },{ value: 124 },{ value: 126 },
            { value: 60 },{ value: 62 },
            { value: "€", isChar: "false", onclick: "onScreenKeyboardJS.writeSpecial('€');" },
            { value: 163 },{ value: 165 },
            { value: "Enter", isChar: "false", buttonClass: "button_oskb_default button_oskb_enter", onclick: "onScreenKeyboardJS.enter();", keyClass: "key_oskb key_oskb_enter" },
        // 3rd row
            // { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterleft", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterleft" },
            { value: "", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterleft", onclick: "", keyClass: "key_oskb" },
            { value: 46 },{ value: 44 },{ value: 63 },{ value: 33 },{ value: 39 },{ value: 34 },{ value: 59 },{ value: 92 },
            // { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_capitalletterright", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterright" },
            { value: "", isChar: "false", buttonClass: "button_oskb_default", onclick: "", keyClass: "key_oskb" },
            { value: "", isChar: "false", buttonClass: "button_oskb_default", onclick: "", keyClass: "key_oskb" },
        // 4th row
            { value: "123", isChar: "false", buttonClass: "button_oskb_default button_oskb_numberleft", onclick: "onScreenKeyboardJS.changeToNumber();", keyClass: "key_oskb key_oskb_number" },
            { value: "Space", isChar: "false", buttonClass: "button_oskb_default button_oskb_space", onclick: "onScreenKeyboardJS.space();", keyClass: "key_oskb key_oskb_space" },
            { value: "ABC", isChar: "false", buttonClass: "button_oskb_default button_oskb_symbolsright", onclick: "onScreenKeyboardJS.changeToCapitalLetter();", keyClass: "key_oskb key_oskb_capitalletterleft" },
         ],
        onlynumber: [
        // 1st row
                { value: 49 },{ value: 50 },{ value: 51 },{ value: 52 },{ value: 53 },{ value: 54 },
                { value: 55 },{ value: 56 },{ value: 57 },{ value: 48 },
                { value: "x", isChar: "false", onclick: "onScreenKeyboardJS.del()", buttonClass: "button_oskb_default button_oskb_del", keyClass: "key_oskb key_oskb_del" }
            ]
    }
}


// GET CURSOR POSITION
jQuery.fn.getCursorPosition = function(){
    if(this.lengh == 0) return -1;
    return jQuery(this).getSelectionStart();
};

jQuery.fn.getSelectionStart = function(){
    if(this.lengh == 0) return -1;
    input = this[0];

    var pos = input.value.length;

    if (input.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveEnd('character', input.value.length);
        if (r.text == '')
        pos = input.value.length;
        pos = input.value.lastIndexOf(r.text);
    } else if(typeof(input.selectionStart)!="undefined")
    pos = input.selectionStart;

    return pos;
};

//SET CURSOR POSITION
jQuery.fn.setCursorPosition = function(pos) {
  this.each(function(index, elem) {
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  });
  return this;
};
