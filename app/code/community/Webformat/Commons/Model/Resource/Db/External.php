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

class Webformat_Commons_Model_Resource_Db_External extends Mage_Core_Model_Abstract {
    public function _construct() {
        $name = "webformat_setup";
        $connConfig = Mage::getConfig()->getResourceConnectionConfig($name);
        $type = (string) $connConfig->type;
        $classNameNode = Mage::getConfig()->getResourceTypeConfig($type);
        $className = (string) $classNameNode->adapter;
        /** @var Webformat_Commons_Model_Resource_Type_Db_Pdo_Mssql $connection */
        $connection = new $className((array) $connConfig);
        $this->setConnection($connection);
    }

    public function __destruct() {
        $this->getConnection()->closeConnection();
    }
}
