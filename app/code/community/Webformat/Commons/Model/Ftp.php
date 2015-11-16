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
 * Manages ftp connection, takes care of connecting and destroing connection on exit
 * maps the basic functions of ftp_*. Methods are not commented being self-commenting
 *
 * @package Webformat_Commons
 * @author Michele Ongaro
 **/
class Webformat_Commons_Model_Ftp extends Mage_Core_Model_Abstract {

	function __construct() {
		$ftpConfig = Mage::getStoreConfig('webformat/ftp');
		$this->setFTPConfig($ftpConfig);

		$ftpConfig = $this->getFTPConfig();
		$conn_id = ftp_connect($ftpConfig['host']);
		if (!$conn_id) {
			throw new Zend_Exception("Error Connecting to the FTP server", 1);
		}
		$this->setConnectionId($conn_id);

		$loginResult = ftp_login($this->getConnectionId(), $ftpConfig['username'], $ftpConfig['password']);
		if (!$loginResult) {
			throw new Zend_Exception("Error athenticating with the FTP server", 1);
		}
		ftp_pasv($this->getConnectionId(), true);
	}

	function __destruct() {
		ftp_close($this->getConnectionId());
	}

	public function getSize($path) {
		return ftp_size($this->getConnectionId(), $path);
	}

	public function deleteResource($path) {
		return ftp_delete($this->getConnectionId(), $path);
	}

	public function nList($basePath) {
		return ftp_nlist($this->getConnectionId(), $basePath);
	}

	public function retrieveResource($local_file, $server_file, $mode = FTP_BINARY) {
		Mage::log($server_file . ":" . $this->getSize($server_file), null, 'debug_immagini_size');
		return ftp_get($this->getConnectionId(), $local_file, $server_file, $mode);
	}

	public function putResource($remote_file, $file, $mode = FTP_BINARY) {
		return ftp_put($this->getConnectionId(), $remote_file, $file, $mode);
	}

}
