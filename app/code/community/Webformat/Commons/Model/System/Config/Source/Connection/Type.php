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

class Webformat_Commons_Model_System_Config_Source_Connection_Type {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 0,
                'label' => Mage::helper('webformat_commons')->__('Remote server')),
            array('value' => 1,
                'label' => Mage::helper('webformat_commons')->__('Local server')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            0 => Mage::helper('webformat_commons')->__('Remote server'),
            1 => Mage::helper('webformat_commons')->__('Local server'),
        );
    }

}
