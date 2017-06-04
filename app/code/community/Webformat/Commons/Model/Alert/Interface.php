<?php
/**
 * Created by PhpStorm.
 * User: silvio
 * Date: 26/03/17
 * Time: 10.11
 */
interface Webformat_Commons_Model_Alert_Interface {
    public function addRecipient($recipient,$key = null);
    public function removeRecipient($key);
    public function check();
}