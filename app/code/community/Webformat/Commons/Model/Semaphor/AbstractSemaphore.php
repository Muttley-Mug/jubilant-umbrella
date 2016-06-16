<?php
/**
 * Copyright (C) 2016 Webformat S.r.l.
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

abstract class Webformat_Commons_Model_Semaphor_AbstractSemaphore extends Mage_Core_Model_Abstract {

    public abstract function getCode();

    public abstract function getSize($path);

    public abstract function deleteResource($path);

    public abstract function putResource($remote_file, $file, $mode = FTP_BINARY);

}