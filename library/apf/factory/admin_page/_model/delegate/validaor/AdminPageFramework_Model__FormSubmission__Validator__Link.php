<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_Model__FormSubmission__Validator__Link extends OnScreenKeyboardAdminPageFramework_Model__FormSubmission__Validator_Base {
    public $sActionHookPrefix = 'try_validation_before_';
    public $iHookPriority = 30;
    public $iCallbackParameters = 5;
    public function _replyToCallback($aInputs, $aRawInputs, array $aSubmits, $aSubmitInformation, $oFactory)
    {
        $_sLinkURL = $this->_getPressedSubmitButtonData($aSubmits, 'href');
        if (! $_sLinkURL) {
            return;
        }
        $this->goToURL($_sLinkURL);
    }
}
