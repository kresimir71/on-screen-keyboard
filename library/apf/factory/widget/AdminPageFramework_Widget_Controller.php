<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

abstract class OnScreenKeyboardAdminPageFramework_Widget_Controller extends OnScreenKeyboardAdminPageFramework_Widget_View {
    public function setUp()
    {}
    public function load()
    {}
    protected function setArguments(array $aArguments=array())
    {
        $this->oProp->aWidgetArguments = $aArguments;
    }
}
