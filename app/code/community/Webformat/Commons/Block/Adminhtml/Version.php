<?php
/**
 * Copyright (C) 2015 Webformat S.r.l.
 * http://www.webformat.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
?>
<?php

/**
 * Test block to queue task.
 */
class Webformat_Commons_Block_Adminhtml_Version extends Mage_Adminhtml_Block_Abstract {

    /**
     * Construct the block.
     */
    public function __construct() {
        $this->setTemplate("webformat/commons/version.phtml");
        parent::__construct();
    }
    
    /**
     * Title of the block.
     * @return string
     */
    public function getTitle() {
        return $this->__("Plugin versions");
    }
    
    /**
     * Get module versions.
     * @return \Varien_Data_Collection
     */
    public function getModulesVersion() {
        /* @var $helper Webformat_Commons_Helper_Data */
        $helper = Mage::helper('webformat_commons');
        return $helper->getModulesVersion();
    }
    
    /**
     * Only in debug mode.
     * @return typeDo print version?
     */
    public function doPrintVersion() {
        /* @var $helper Webformat_Commons_Helper_Data */
        $helper = Mage::helper('webformat_commons');
        return $helper->isDebug();
    }
}
