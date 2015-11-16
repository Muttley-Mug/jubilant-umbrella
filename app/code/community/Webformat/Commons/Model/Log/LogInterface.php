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
 * Log model interface
 */
interface Webformat_Commons_Model_Log_LogInterface {


    /**
     * @param mixed $value, message to log
     * @param string $level, log level (same of Zend_Log)
     * @param string $namespace, logs namespace (i.e. module_name or a log entries group)
     * @return void
     */
    public function _log($value, $level, $namespace);

}
