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

/** Abstract Exporter Model. */
abstract class Webformat_Commons_Model_AbstractImportExport extends Mage_Core_Model_Abstract {

	/**
	 * ftp lazy loading
	 *
	 * @return Webformat_Commons_Model_Ftp
	 * @author Michele Ongaro
	 **/
	function getFtp() {
		if (!$this->hasData('ftp')) {
			$this->setFtp(Mage::getSingleton('webformat_commons/ftp'));
		}
		return $this->getData('ftp');
	}

	/**
	 * Get file name.
	 *
	 * @return string
	 */
	protected function getSemaphoreFileName() {
		return false;
	}
	/**
	 * Is export enabled.
	 *
	 * @return boolean
	 */
	protected abstract function isEnabled();

	/**
	 * checks if the semaphore exists remotely
	 *
	 * @return boolean
	 * @author Michele Ongaro
	 **/

	public function checkSemaphore() {
		$file = $this->getSemaphoreFileName();
		if (!empty($file)) {
			try {
				$size = $this->getFtp()->getSize($file);
				return $size > -1 ? true : false;
			} catch (Exception $e) {
				return false;
			}
		}

		return true;
	}

	/**
	 * TODO: removes semaphore
	 *
	 * @return boolean
	 * @author Michele Ongaro
	 **/
	function removeSemaphore() {
		$file = $this->getSemaphoreFileName();
		$result = false;
		if (!empty($file)) {
			try {
				$result = $this->getFtp()->deleteResource($file);
			} catch (Exception $e) {
				return false;
			}
		}

		return $result;
	}

	/**
	 * TODO: creates semaphore
	 *
	 * @return boolean
	 * @author Michele Ongaro
	 **/
	function createSemaphore() {
		$file = $this->getSemaphoreFileName();
		$result = false;

		$tmpDir = Mage::getBaseDir("tmp");
		$tmpFile = $tmpDir . DS . 'temp_empty_file.txt';
		$vFile = new Varien_Io_File();
		$vFile->open(array('path' => $tmpDir));
		$vFile->mkdir($tmpDir);
		if (!empty($file)) {
			try {
				$vFile->write($tmpFile, '');
				$vFile->close($tmpFile);
				$result = $this->getFtp()->putResource($file, $tmpFile);
			} catch (Exception $e) {
				return false;
			}
		}

		return $result;
	}
}
