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
class Webformat_Commons_Helper_Log extends Mage_Core_Helper_Abstract
{

    const DEFAULT_LOGGER = "filesystem";

    /**
     * Log levels.
     */
    static $LOG_LEVELS = array(
        Zend_Log::EMERG => "Emerg",
        Zend_Log::ALERT => "Alert",
        Zend_Log::CRIT => "Crit",
        Zend_Log::ERR => "Err",
        Zend_Log::WARN => "Warn",
        Zend_Log::NOTICE => "Notice",
        Zend_Log::INFO => "Info",
        Zend_Log::DEBUG => "Debug"
    );

    protected $namespace;

    /** @var Webformat_Commons_Model_Log_LogInterface */
    private static $_logger;

    public function setNamespace($namespace){
        $this->namespace = $namespace;
    }

    public function getNamespace(){
        return isset($this->namespace) ?  $this->namespace : 'webformat_commons_logs';
    }

    /**
     * Emergency: system is unusable
     */
    public function logEmerg($value)
    {
        $this->_log($value, Zend_Log::EMERG);
    }

    /**
     * Alert: action must be taken immediately
     */
    public function logAlert($value)
    {
        $this->_log($value, Zend_Log::ALERT);
    }

    /**
     * Critical: critical conditions
     */
    public function logCrit($value)
    {
        $this->_log($value, Zend_Log::CRIT);
    }

    /**
     * Error: error conditions
     */
    public function logErr($value)
    {
        $this->_log($value, Zend_Log::ERR);
    }

    /**
     * Warning: warning conditions
     */
    public function logWarn($value)
    {
        $this->_log($value, Zend_Log::WARN);
    }

    /**
     * Notice: normal but significant condition
     */
    public function logNotice($value)
    {
        $this->_log($value, Zend_Log::NOTICE);
    }

    /**
     * Informational: informational messages
     */
    public function logInfo($value)
    {
        $this->_log($value, Zend_Log::INFO);
    }

    /**
     * Debug: debug messages
     */
    public function logDebug($value)
    {
        $this->_log($value, Zend_Log::DEBUG);
    }

    /**
     * Log generic
     */
    public function log($value, $level)
    {
        $this->_log($value, $level);
    }


    /**
     * Private log function.
     */
    private function _log($value, $level, $namespace = null)
    {
        if(empty($namespace)){
            $namespace = $this->getNamespace();
        }
        if (!is_dir(Mage::getBaseDir('log') . DS . $namespace)) {
            mkdir(Mage::getBaseDir('log') . DS . $namespace);
        }

        if(isset(self::$LOG_LEVELS [$level])){
            $level = self::$LOG_LEVELS [$level];
        }else{
            $level = 'EXT';
        }

        /** @var $logger Webformat_Commons_Model_Log_LogInterface */
        $logger = $this->getLogger();
        $logger->_log($value, $level, $namespace);
    }

    /**
     * Get logger as configurated.
     * If no logger is specified or logger is invalid, default logger will be used.
     *
     * @return Webformat_Commons_Model_Log_LogInterface
     */
    private function getLogger(){

        if(self::$_logger == null){
            $loggingKey  = Mage::getConfig()->getNode('webformat_commons/logger/backend');
            if(empty($loggingKey)){
                $loggingKey = self::DEFAULT_LOGGER;
            }

            $loggerClassModel = Mage::getConfig()->getNode('webformat_commons/logger/'.$loggingKey.'/class');
            if(empty($loggerClassModel)){
                $loggerClassModel = Mage::getConfig()->getNode('webformat_commons/logger/'.self::DEFAULT_LOGGER.'/class');
            }

            /* @var Webformat_Commons_Model_Log_LogInterface */
            self::$_logger = Mage::getSingleton($loggerClassModel);
        }
        return self::$_logger;
    }

}
