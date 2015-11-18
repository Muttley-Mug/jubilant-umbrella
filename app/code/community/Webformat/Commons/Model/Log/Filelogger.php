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
 * Log model for file logging.
 */
class Webformat_Commons_Model_Log_Filelogger implements  Webformat_Commons_Model_Log_LogInterface {

    /**
     * Log in a file.
     *
     * @param mixed $value , message to log
     * @param string $level , log level (same of Zend_Log)
     * @param string $namespace , logs namespace (i.e. module_name or a log entries group)
     * @return void
     */
    public function addLog($value, $level, $namespace)
    {
        static $loggers = array();

        $levelReadable = Mage::helper('webformat_commons/log')->getReadableLogLevel($level);
        $file = $namespace . '/' . date('Y-m-d') . '-' . $levelReadable . '.log';

        try {
            if (!isset($loggers[$file])) {
                $logDir  = Mage::getBaseDir('var').DS. 'log';
                $logFile = $logDir . DS . $file;

                if (!is_dir($logDir)) {
                    mkdir($logDir);
                    chmod($logDir, 0750);
                }

                if (!file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    chmod($logFile, 0640);
                }

                $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
                $formatter = new Zend_Log_Formatter_Simple($format);
                $writer = new Zend_Log_Writer_Stream($logFile);
                $writer->setFormatter($formatter);
                $loggers[$file] = new Zend_Log($writer);
            }

            if (is_array($value) || is_object($value)) {
                $value = print_r($value, true);
            }

            $loggers[$file]->log($value, $level);
        }catch (Exception $e) {
        }
    }
}
