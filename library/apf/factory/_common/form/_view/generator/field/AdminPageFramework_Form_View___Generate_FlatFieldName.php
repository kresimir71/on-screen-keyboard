<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_View___Generate_FlatFieldName extends OnScreenKeyboardAdminPageFramework_Form_View___Generate_FieldName {
    public function get()
    {
        return $this->_getFiltered($this->_getFlatFieldName());
    }
    public function getModel()
    {
        return $this->get() . '|' . $this->sIndexMark;
    }
    protected function _getFlatFieldName()
    {
        $_sSectionIndex = isset($this->aArguments[ 'section_id' ], $this->aArguments[ '_section_index' ]) ? "|{$this->aArguments[ '_section_index' ]}" : '';
        return $this->getAOrB($this->_isSectionSet(), "{$this->aArguments[ '_section_path' ]}{$_sSectionIndex}|{$this->aArguments[ '_field_path' ]}", "{$this->aArguments[ '_field_path' ]}");
    }
}
