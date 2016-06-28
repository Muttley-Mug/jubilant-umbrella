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
class Webformat_Commons_Helper_Mail extends Mage_Core_Helper_Abstract
{
    /** Check if debug is enabled. */
    public function isActiveEmail()
    {
        return Mage::getStoreConfigFlag("webformat_commons/global/email");
    }

    /** Get email addresses. */
    public function getEmailAddresses()
    {
        return Mage::getStoreConfig("webformat_commons/global/email_to");
    }


    /**
     * Send an e-mail
     *
     * @param string $subject
     * @param string|array $messages
     * @param string $to destination (comma separated for multiple addresses)
     * @param string $from from email address
     * @param string $fromName from name
     *
     */
    public function send($subject, $messages, $to = '', $from = '', $fromName = '')
    {
        if(!$this->isActiveEmail()){
            return;
        }

        $message = $messages;
        if(is_array($messages)){
            $message = implode("\r\n",$messages);
        }

        if(empty($to)) {
            $addresses = explode(',', $this->getEmailAddresses());
        }else{
            $addresses = explode(',', $to);
        }

        $mail = new Zend_Mail();
        $mail->setBodyText($message, "UTF-8");
        $mail->setSubject($subject);
        $mail->setFrom(
            empty($from) ? Mage::getStoreConfig('trans_email/ident_general/email') : $from,
            empty($fromName) ? Mage::getStoreConfig('trans_email/ident_general/name') : $fromName
        );
        $mail->addTo($addresses);

        try {
            $mail->send();
        }
        catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
