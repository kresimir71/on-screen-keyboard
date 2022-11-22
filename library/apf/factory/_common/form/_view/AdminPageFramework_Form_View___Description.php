<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_View___Description extends OnScreenKeyboardAdminPageFramework_FrameworkUtility {
    public $aDescriptions = array();
    public $sClassAttribute = 'on-screen-keyboard-form-element-description';
    public function __construct()
    {
        $_aParameters = func_get_args() + array( $this->aDescriptions, $this->sClassAttribute, );
        $this->aDescriptions = $this->getAsArray($_aParameters[ 0 ]);
        $this->sClassAttribute = $_aParameters[ 1 ];
    }
    public function get()
    {
        if (empty($this->aDescriptions)) {
            return '';
        }
        $_aOutput = array();
        foreach ($this->aDescriptions as $_sDescription) {
            $_aOutput[] = "<p class='" . esc_attr($this->sClassAttribute) . "'>" . "<span class='description'>" . $_sDescription . "</span>" . "</p>";
        }
        return implode(PHP_EOL, $_aOutput);
    }
}
