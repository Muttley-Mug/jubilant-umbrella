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
 * Helper data for logging pourpose.
 */
class Webformat_Commons_Helper_Data extends Mage_Core_Helper_Abstract
{

    /** Constructor. */
    public function __construct()
    {
        $this->_mageVersion = Mage::getVersionInfo();
        $this->_mageMajor = $this->_mageVersion['major'];
        $this->_mageMinor = $this->_mageVersion['minor'];
        $this->_mageRevision = $this->_mageVersion['revision'];
    }

    /** Check if debug is enabled. */
    public function isDebug()
    {
        return Mage::getStoreConfigFlag("webformat_commons/global/debug");
    }

    /** Enable Zend DB Profiler. */
    public function enableDbProfiler()
    {
        if (!$this->isDebug()) {
            return;
        }
        Mage::getSingleton('core/resource')->getConnection('core_write')->getProfiler()->setEnabled(true);
        Varien_Profiler::enable();
    }

    /** Flush Zend DB Profiler. */
    public function flushDbProfiler()
    {
        if (!$this->isDebug()) {
            return;
        }
        $profiler = Mage::getSingleton('core/resource')
                ->getConnection('core_write')
                ->getProfiler();

        $csvArray = array();
        foreach ($profiler->getQueryProfiles() as $q) {
            $csvArray[] = array($q->getElapsedSecs(), $q->getQuery());
        }
        $logDir = Mage::getBaseDir('var') . DS . 'log';
        $fp = fopen($logDir . DS . "profiler.csv", 'w');
        foreach ($csvArray as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    /** Is the action $action allowed? */
    public function isAllowed($section, $action)
    {
        return Mage::getSingleton('admin/session')
                        ->isAllowed("admin/webformat/$section/actions/$action");
    }

    /** Mage same or newer of (for backward compatibility). */
    public function isMageSameOrNewerOf($major, $minor, $revision = 0)
    {
        if ($this->_mageMajor < $major) {
            return false;
        } else if ($this->_mageMinor < $minor) {
            return false;
        } else if ($this->_mageRevision < $revision) {
            return false;
        }
        return true;
    }

    /**
     * For backward compatibility
     */
    public function deleteFromSelect($adapter, $select, $table)
    {
        $select = clone $select;
        $select->reset(Zend_Db_Select::DISTINCT);
        $select->reset(Zend_Db_Select::COLUMNS);

        return sprintf('DELETE %s %s', $adapter->quoteIdentifier($table), $select->assemble());
    }

    /**
     * Get module versions.
     * @return \Varien_Data_Collection
     */
    public function getModulesVersion()
    {
        $rv = new Varien_Data_Collection();
        $count = 0;
        foreach (Mage::getConfig()->getNode('modules')->children() as $moduleName => $moduleConfig) {
            if (preg_match('/^Webformat_.*/', $moduleName)) {
                $item = new Varien_Object();
                $item->setName(preg_replace('/^Webformat_/', '', $moduleName))
                        ->setVersion((string) $moduleConfig->version)
                        ->setConfig($moduleConfig)
                        ->setModuleName($moduleName)
                        ->setDescription((string) $moduleConfig->description);
                if ($count++ % 2 == 0) {
                    $item->setClass("even");
                }
                $rv->addItem($item);
            }
        }
        return $rv;
    }

    /**
     * Do reindex
     * @param int|string $indexId index to run in term of numeric id or reindex code
     */
    public function reindex($indexId)
    {
        if ($indexId) {

            try {
                if(is_int($indexId)) {
                    $process = Mage::getModel('index/process')->load($indexId);
                }else{
                    $process = Mage::getModel('index/indexer')->getProcessByCode($indexId);
                }
                $process->reindexAll();
            } catch (Exception $ex) {
                Mage::helper('webformat_commons/log')->logErr($ex->getMessage());
            }
        }
    }
}
