<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

class OnScreenKeyboardAdminPageFramework_View__PageMataBoxRenderer extends OnScreenKeyboardAdminPageFramework_FrameworkUtility {
    public function render($sContext)
    {
        if (! $this->doesMetaBoxExist()) {
            return;
        }
        $this->_doRender($sContext, ++self::$_iContainerID);
    }
    private static $_iContainerID = 0;
    private function _doRender($sContext, $iContainerID)
    {
        echo "<div id='postbox-container-{$iContainerID}' class='postbox-container'>";
        do_meta_boxes('', $sContext, null);
        echo "</div>";
    }
}
