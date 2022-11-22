<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

abstract class OnScreenKeyboardAdminPageFramework_Model_Menu extends OnScreenKeyboardAdminPageFramework_Controller_Page {
    public function __construct($sOptionKey=null, $sCallerPath=null, $sCapability='manage_options', $sTextDomain='on-screen-keyboard')
    {
        parent::__construct($sOptionKey, $sCallerPath, $sCapability, $sTextDomain);
        new OnScreenKeyboardAdminPageFramework_Model_Menu__RegisterMenu($this);
    }
}
