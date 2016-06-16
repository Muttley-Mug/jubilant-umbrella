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

/** Abstract Importer Model. */
abstract class Webformat_Commons_Model_Exporter_Abstract extends Webformat_Commons_Model_AbstractImportExport {

    /**
     * process whatever export
     * @return string
     * @throws Zend_Exception
     */

	public final function export() {
		if (!$this->isEnabled()) {
			Mage::helper('webformat_commons/log')->logWarn("Module export is disabled");
			return "Module export is disabled";
		}
		if ($this->checkSemaphore()) {
            Mage::helper('webformat_commons/log')->logWarn("Semaphore still there, aborting!");
			throw new Zend_Exception("Semaphore still there, aborting!", 1);
		}

		$semaphore = $this->createSemaphore();
		if (!$semaphore) {
            Mage::helper('webformat_commons/log')->logWarn("Could not create remote Semaphore!");
		    throw new Zend_Exception("Could not create remote Semaphore!", 1);
		};

        $this->_doExport();
        $this->removeSemaphore();
		return "Import done";
	}

	public final function createCsv($filename, $data) {
		$tmpDir  = Mage::getBaseDir("tmp");
		$tmpFile = $tmpDir . DS . $filename;

		$vFile = new Varien_Io_File();
		$vFile->open(array('path' => $tmpDir));

		$string = '';
		foreach ($data as $row) {
			$string .= join("\t", $row) . chr(13) . chr(10);
		}
		$vFile->write($tmpFile, $string);

# 		does not support empty enclosure, must do it by hand
		#		$csv = new Varien_File_Csv();
		#		$csv->setDelimiter("	");
		#		$csv->setEnclosure('');
		#		$csv->saveData($tmpFile, $data);
	}

	public final function uploadCsv($localname, $remotepath) {
		$result  = false;
		$tmpDir  = Mage::getBaseDir("tmp");
		$tmpFile = $tmpDir . DS . $localname;
		$vFile   = new Varien_Io_File();
		$vFile->open(array('path' => $tmpDir));
		if (!empty($remotepath)) {
			$result = $this->getFtp()->putResource($remotepath, $tmpFile);
		}

		return $result;
	}

	/**
	 * exporter implementation
	 * in this method you can put the logic and call the createCsv and uploadCsv to upload the csv
	 *
	 * @return void
	 * @author Michele Ongaro
	 **/
	protected abstract function _doExport();
}
