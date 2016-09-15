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
    
    protected function getSemaphoreType(){
        return "".Webformat_Commons_Model_Semaphor_FtpSemaphore::CODE;
    }

    /**
     * Get available semaphore
     *
     * @return Webformat_Commons_Model_Semaphor_AbstractSemaphore
     **/
    protected function getSemaphore() {
        if (!$this->hasData('semaphore')) {
        
            $semaphore = null;
            switch ($this->getSemaphoreType()){                
                case Webformat_Commons_Model_Semaphor_FtpSemaphore::CODE:
                    $semaphore = Mage::getSingleton('webformat_commons/semaphor_ftpSemaphore');
                    break;
                case Webformat_Commons_Model_Semaphor_LocalSemaphore::CODE:
                    $semaphore = Mage::getSingleton('webformat_commons/semaphor_localSemaphore');
                    break;
                default:
                    $semaphore = Mage::getSingleton('webformat_commons/semaphor_ftpSemaphore');
            }
            $this->setSemaphore($semaphore);

        }
        return $this->getData('semaphore');
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
		$esito = false;
		$file = $this->getSemaphoreFileName();
        if (!empty($file)) {
			try {
                $size = $this->getSemaphore()->getSize($file);
				$esito = ($size > -1 ? true : false);
			} catch (Exception $e) {
				$esito = false;
			}
		}

		if($esito){
			$this->alertLocked();
		}

		return $esito;
	}

	/**
	 * Remove semaphore
	 *
	 * @return boolean
	 * @author Michele Ongaro
	 **/
	function removeSemaphore() {
		$file = $this->getSemaphoreFileName();
		$result = false;
		if (!empty($file)) {
			try {
				$result = $this->getSemaphore()->deleteResource($file);
			} catch (Exception $e) {
				return false;
			}
		}

		return $result;
	}

	/**
	 * Create semaphore
	 *
	 * @return boolean
	 * @author Michele Ongaro
	 **/
	function createSemaphore() {
		$file = $this->getSemaphoreFileName();
        $result = false;
        if(Webformat_Commons_Model_Semaphor_FtpSemaphore::CODE == $this->getSemaphoreType()) {

            $tmpDir = Mage::getBaseDir("tmp");
            $tmpFile = $tmpDir . DS . 'temp_empty_file.txt';
            $vFile = new Varien_Io_File();
            $vFile->open(array('path' => $tmpDir));
            $vFile->mkdir($tmpDir);
            if (!empty($file)) {
                try {
                    $vFile->write($tmpFile, '');
                    $vFile->close($tmpFile);
                    $result = $this->getSemaphore()->putResource($file, $tmpFile);
                } catch (Exception $e) {
                    return false;
                }
            }
        }else{

            $pathDir = pathinfo($file, PATHINFO_DIRNAME);
            if(!file_exists($pathDir)){
                mkdir($pathDir,750,true);
            }

            if(file_exists($pathDir) && is_dir_writeable($pathDir)){
                $result  = touch($file);
            }
        }

		return $result;
	}

	protected function alertLocked(){

		$sendLockedMail = Mage::getStoreConfigFlag("webformat_commons/global/email_lock");
		if($sendLockedMail) {
			$semaphoreName = pathinfo($this->getSemaphoreFileName(), PATHINFO_FILENAME);
			Mage::helper("webformat_commons/mail")->send(
                    sprintf("Import task locked [%s].", $semaphoreName),
					sprintf("Lock file [%s]. Task [%s]", $this->getSemaphoreFileName(), get_class())
			);
		}
	}
}
