<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_View___Attribute_SectionTableBody extends OnScreenKeyboardAdminPageFramework_Form_View___Attribute_Base {
    public $sContext = 'section_table_content';
    protected function _getAttributes()
    {
        $_sCollapsibleType = $this->getElement($this->aArguments, array( 'collapsible', 'type' ), 'box');
        return array( 'class' => $this->getAOrB($this->aArguments[ '_is_collapsible' ], 'on-screen-keyboard-collapsible-section-content' . ' ' . 'on-screen-keyboard-collapsible-content' . ' ' . 'accordion-section-content' . ' ' . 'on-screen-keyboard-collapsible-content-type-' . $_sCollapsibleType, null), );
    }
}
