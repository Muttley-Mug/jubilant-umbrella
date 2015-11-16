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

class Webformat_Commons_Model_Resource_Type_Db_Pdo_Mssql extends Mage_Core_Model_Resource_Type_Db {

	/**
	 * Get connection
	 *
	 * @param array $config Connection config
	 * @return Varien_Db_Adapter_Pdo_Mysql
	 */
	public function getConnection($config) {
		$configArr = (array) $config;
		$configArr['profiler'] = !empty($configArr['profiler']) && $configArr['profiler'] !== 'false';

		$conn = $this->_getDbAdapterInstance($configArr);

		if (!empty($configArr['initStatements']) && $conn) {
			$conn->query($configArr['initStatements']);
		}
		return $conn;
	}

	/**
	 * Create and return DB adapter object instance
	 *
	 * @param array $configArr Connection config
	 * @return Varien_Db_Adapter_Pdo_Mysql
	 */
	protected function _getDbAdapterInstance($configArr) {
		$className = $this->_getDbAdapterClassName();
		$adapter = new $className($configArr);
		$adapter->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $adapter;
	}

	/**
	 * Retrieve DB adapter class name
	 *
	 * @return string
	 */
	protected function _getDbAdapterClassName() {
		return 'Zend_Db_Adapter_Pdo_Mssql';
	}

}
