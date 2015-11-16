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
<?
class Webformat_Commons_Block_Adminhtml_Widget_Button extends Mage_Adminhtml_Block_Widget_Button
{
    protected function _construct()
    {
        parent::_construct();
        $this->setParams("");
    }

    /**
     * Set url.
     * @param type $url
     * @param type $params
     */
    public function setUrl($url, $params = "") {
        parent::setButtonUrl($url);
        $this->setParams($params);
    }

    public function getOnClick() {
        $url = $this->getButtonUrl();
        $params = array();
        parse_str($this->getParams(), $params);
        if ($this->hasConfirm()) {
			$onclick = 'deleteConfirm(\'' . $this->getConfirm()
                . '\', \'' . Mage::helper('adminhtml')->getUrl($url, $params) . '\')';
		} else {
            $onclick = 'location.href = \'' . Mage::helper('adminhtml')->getUrl($url, $params) . '\'';
		}
        return $onclick;
    }
}
