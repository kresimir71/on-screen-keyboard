<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_View___CSS_widget extends OnScreenKeyboardAdminPageFramework_Form_View___CSS_Base {
    protected function _get()
    {
        return $this->_getWidgetRules();
    }
    private function _getWidgetRules()
    {
        return <<<CSSRULES
.widget .on-screen-keyboard-section .form-table>tbody>tr>td,.widget .on-screen-keyboard-section .form-table>tbody>tr>th{display:inline-block;width:100%;padding:0;float:right;clear:right}.widget .on-screen-keyboard-field,.widget .on-screen-keyboard-input-label-container{width:100%}.widget .sortable .on-screen-keyboard-field{padding:4% 4.4% 3.2% 4.4%;width:91.2%}.widget .on-screen-keyboard-field input{margin-bottom:.1em;margin-top:.1em}.widget .on-screen-keyboard-field input[type=text],.widget .on-screen-keyboard-field textarea{width:100%}@media screen and (max-width:782px){.widget .on-screen-keyboard-fields{width:99.2%}.widget .on-screen-keyboard-field input[type='checkbox'],.widget .on-screen-keyboard-field input[type='radio']{margin-top:0}}
CSSRULES;
    }
    protected function _getVersionSpecific()
    {
        $_sCSSRules = '';
        if (version_compare($GLOBALS[ 'wp_version' ], '3.8', '<')) {
            $_sCSSRules .= <<<CSSRULES
.widget .on-screen-keyboard-section table.mceLayout{table-layout:fixed}
CSSRULES;
        }
        if (version_compare($GLOBALS[ 'wp_version' ], '3.8', '>=')) {
            $_sCSSRules .= <<<CSSRULES
.widget .on-screen-keyboard-section .form-table th{font-size:13px;font-weight:400;margin-bottom:.2em}.widget .on-screen-keyboard-section .form-table{margin-top:1em}
CSSRULES;
        }
        return $_sCSSRules;
    }
}
