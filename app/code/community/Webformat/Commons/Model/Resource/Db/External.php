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

    const __SETUP_NAME = "webformat_setup";


    /**
     * Init an external resource by input setup
     *
     * @param string $setupName name of setup to use
     */
    public function __construct($setupName = NULL) {

        parent::__construct();

        if($setupName == null) {
            $setupName = self::__SETUP_NAME;
        }
        $connConfig = Mage::getConfig()->getResourceConnectionConfig($setupName);
        $type = (string) $connConfig->type;
        $classNameNode = Mage::getConfig()->getResourceTypeConfig($type);
        $className = (string) $classNameNode->adapter;

        /** @var $connection Zend_Db_Adapter_Abstract */
        $connection = new $className((array) $connConfig);
        $this->__connection = $connection;
    }

    /**
     * Get external connection
     * @return Zend_Db_Adapter_Abstract
     */
    public function getConnection(){
        if(!$this->__connection->isConnected()){
            $this->__connection->getConnection();
        }
        return $this->__connection;
    }

    public function __destruct() {
        $this->getConnection()->closeConnection();
    }
}
