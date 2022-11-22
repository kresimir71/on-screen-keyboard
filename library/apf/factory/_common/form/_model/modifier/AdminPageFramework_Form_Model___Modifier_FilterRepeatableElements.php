<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_Model___Modifier_FilterRepeatableElements extends OnScreenKeyboardAdminPageFramework_Form_Model___Modifier_Base {
    public $aSubject = array();
    public $aDimensionalKeys = array();
    public function __construct()
    {
        $_aParameters = func_get_args() + array( $this->aSubject, $this->aDimensionalKeys, );
        $this->aSubject = $_aParameters[ 0 ];
        $this->aDimensionalKeys = array_unique($_aParameters[ 1 ]);
    }
    public function get()
    {
        foreach ($this->aDimensionalKeys as $_sFlatFieldAddress) {
            $this->unsetDimensionalArrayElement($this->aSubject, explode('|', $_sFlatFieldAddress));
        }
        return $this->aSubject;
    }
}
