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

class Webformat_Commons_Model_Semaphor_LocalSemaphore extends Webformat_Commons_Model_Semaphor_AbstractSemaphore {

    const CODE = "LOCAL_FILE";

    public function getCode()
    {
        return self::CODE;
    }

    public function getSize($path)
    {
        if(file_exists($path) && is_readable($path)) {
            return filesize($path);
        }else{
            return -1;
        }
    }

    public function deleteResource($path)
    {
        if(file_exists($path) && is_writable($path)) {
            return unlink($path);
        }else{
            return false;
        }
    }

    public function putResource($remote_file, $file, $mode = FTP_BINARY)
    {
        if(file_exists($remote_file) && is_readable($remote_file)) {
            return copy($remote_file, $file);
        }else{
            return false;
        }
    }
}