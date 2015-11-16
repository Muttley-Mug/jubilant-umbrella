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
abstract class Webformat_Commons_Model_Importer_Abstract extends Webformat_Commons_Model_AbstractImportExport {

	/**
	 * process whatever import
	 * @return string
	 * @throws Zend_Exception
	 * @author Michele Ongaro
	 */

	public final function import() {
		if (!$this->isEnabled()) {
			Mage::helper('webformat_commons/log')->logWarn("Module import is disabled");
			return "Module import is disabled";
		}
		if (!$this->checkSemaphore()) {
			throw new Zend_Exception('Semaphore not found');
		}
		$this->_doImport();
		$this->removeSemaphore();
		return "Import done";
	}

	protected abstract function _doImport();

}
