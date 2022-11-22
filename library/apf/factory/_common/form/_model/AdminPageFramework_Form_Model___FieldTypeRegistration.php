<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Form_Model___FieldTypeRegistration extends OnScreenKeyboardAdminPageFramework_FrameworkUtility {
    public function __construct(array $aFieldTypeDefinition, $sStructureType)
    {
        $this->_initialize($aFieldTypeDefinition, $sStructureType);
    }
    private function _initialize($aFieldTypeDefinition, $sStructureType)
    {
        if (is_callable($aFieldTypeDefinition[ 'hfFieldSetTypeSetter' ])) {
            call_user_func_array($aFieldTypeDefinition[ 'hfFieldSetTypeSetter' ], array( $sStructureType ));
        }
        if (is_callable($aFieldTypeDefinition[ 'hfFieldLoader' ])) {
            call_user_func_array($aFieldTypeDefinition[ 'hfFieldLoader' ], array());
        }
    }
}
