<?php
/*
 * Admin Page Framework v3.9.1 by Michael Uno
 * Compiled with Admin Page Framework Compiler <https://github.com/michaeluno/on-screen-keyboard-compiler>
 * <https://en.michaeluno.jp/on-screen-keyboard>
 * Copyright (c) 2013-2022, Michael Uno; Licensed under MIT <https://opensource.org/licenses/MIT>
 */

abstract class OnScreenKeyboardAdminPageFramework_PluginBootstrap {
    public $sFilePath;
    public $bIsAdmin;
    public $sHookPrefix;
    public $sSetUpHook = 'plugins_loaded';
    public $iPriority = 10;
    public function __construct($sPluginFilePath, $sPluginHookPrefix='', $sSetUpHook='plugins_loaded', $iPriority=10)
    {
        if ($this->_hasLoaded()) {
            return;
        }
        $this->sFilePath = $sPluginFilePath;
        $this->bIsAdmin = is_admin();
        $this->sHookPrefix = $sPluginHookPrefix;
        $this->sSetUpHook = $sSetUpHook;
        $this->iPriority = $iPriority;
        $_bValid = $this->start();
        if (false === $_bValid) {
            return;
        }
        $this->setConstants();
        $this->setGlobals();
        $this->_registerClasses();
        register_activation_hook($this->sFilePath, array( $this, 'replyToPluginActivation' ));
        register_deactivation_hook($this->sFilePath, array( $this, 'replyToPluginDeactivation' ));
        if (! $this->sSetUpHook || did_action($this->sSetUpHook)) {
            $this->_replyToLoadPluginComponents();
        } else {
            add_action($this->sSetUpHook, array( $this, '_replyToLoadPluginComponents' ), $this->iPriority);
        }
        add_action('init', array( $this, 'setLocalization' ));
        $this->construct();
    }
    protected function _hasLoaded()
    {
        static $_bLoaded = false;
        if ($_bLoaded) {
            return true;
        }
        $_bLoaded = true;
        return false;
    }
    protected function _registerClasses()
    {
        if (! class_exists('OnScreenKeyboardAdminPageFramework_RegisterClasses', false)) {
            return;
        }
        new OnScreenKeyboardAdminPageFramework_RegisterClasses($this->getScanningDirs(), array(), $this->getClasses());
    }
    public function _replyToLoadPluginComponents()
    {
        if ($this->sHookPrefix) {
            do_action("{$this->sHookPrefix}_action_before_loading_plugin");
        }
        $this->setUp();
        if ($this->sHookPrefix) {
            do_action("{$this->sHookPrefix}_action_after_loading_plugin");
        }
    }
    public function setConstants()
    {}
    public function setGlobals()
    {}
    public function getClasses()
    {
        $_aClasses = array();
        return $_aClasses;
    }
    public function getScanningDirs()
    {
        return array();
    }
    public function replyToPluginActivation()
    {}
    public function replyToPluginDeactivation()
    {}
    public function setLocalization()
    {}
    public function setUp()
    {}
    protected function construct()
    {}
    public function start()
    {}
}
