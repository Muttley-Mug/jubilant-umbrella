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
/*
@method getConnection() Zend_Db_Adapter_Pdo_Mssql
 */
abstract class Webformat_Commons_Model_Exporter_AbstractDBExporter extends Webformat_Commons_Model_Exporter_Abstract {

	/**
	 *
     */
	function _construct() {
		$setupName = $this->getSetupName();

		/** @var Mage_Core_Model_Resource_Type_Db $dbh */
        $dbh = Mage::getSingleton('webformat_commons/resource_db_external', array("setupName" => $setupName))->getConnection();
		$this->setConnection($dbh);
	}

	protected function getSetupName(){
        //keep default
        return null;
    }
}
