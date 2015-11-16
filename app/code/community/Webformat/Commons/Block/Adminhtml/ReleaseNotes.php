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
 * Rel notes block.
 */
class Webformat_Commons_Block_Adminhtml_ReleaseNotes extends Mage_Adminhtml_Block_Widget_Container {
    /**
     * Construct
     */
     protected function _construct()
     {
         $this->_headerText = "Webformat Release Notes";
         parent::_construct();
     }
     
     /**
      * Gets release notes collection.
      * @return \Varien_Data_Collection
      */
     public function getReleaseNotes() {
        $rv = new Varien_Data_Collection();
        /* @var $helper Webformat_Commons_Helper_Data */
        $helper = Mage::helper('webformat_commons');
        foreach ($helper->getModulesVersion() as $module) {
            $notes = $this->_findReleaseNotes($module);
            if ($notes === false) {
                continue;
            }

            $item = new Varien_Object();
            $item->setTitle(sprintf('%s (Ver. <code>%s</code>)', $module->getName(), $module->getVersion()));
            $item->setDescription($module->getDescription());
            $item->setText($notes);
            $rv->addItem($item);
        }

        return $rv;
     }

    private function _findReleaseNotes($module)
    {
        $config = $module->getConfig();
        $pool = (string) $config->codePool;
        $name = preg_replace('|_|', '/', $module->getModuleName());
        $path = sprintf('%s/%s/%s/docs/release-notes', Mage::getBaseDir('code'), $pool, $name);

        if (is_dir($path)) {
            $rv = "";
            foreach ($this->_concatenateFiles($path) as $rn) {
                $rv .= '<h3>Release notes ' . $rn->getVersion() . '</h3>';
                $rv .= $rn->getText();
            }
        } else {
            return false;
        }
        return $rv;
    }

    private function _concatenateFiles($path)
    {
        $rv = new Varien_Data_Collection();
        $dh = opendir($path);
        $files = array();
        while (($file = readdir($dh)) !== false) {
            if (!is_file($path .'/' . $file)) {
                continue;
            }
            if (preg_match('|^\.|', $file)) {
                continue;
            }
            $files[] = $file;
        }
        asort($files);
        foreach ($files as $file) {
            $item = new Varien_Object();
            $item->setVersion(preg_replace('|(-\d{8})?.\w+$|', '', $file));
            $item->setText(file_get_contents($path . DS . $file));
            if (preg_match('|-\d{8}\.|', $file)) {
                $rDt = preg_replace('|.*-(\d{8})\..*|', '$1', $file);
            } else {
                $rDt = gmdate("Ymd", filemtime($path . DS . $file));
            }
            $item->setReleaseDate($this->formatDate($rDt));
            $rv->addItem($item);
        }
        closedir($dh);
        
        return $rv;
    }

}
