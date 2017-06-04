<?php
/**
 * Created by PhpStorm.
 * User: silvio
 * Date: 26/03/17
 * Time: 10.24
 */


class Webformat_Commons_Model_Slack_Connector {
//	const AUTH='xoxb-97920738147-atAcM601GUK9BZB1tjET8qlk';
    const AUTH='xoxb-138681129783-gh3pDYwagf8GdpPFP1LJ9zMY';
    const RPC_URL='https://slack.com/api/';
    private $conn;
    private $post_data;
    private $last_reply;
    private $identity;

    public function __construct(){
        $this->conn = curl_init();
    }

    public function setIdentity($token){
        if($this->setas_user(true)->auth_test())
            $this->identity = $token;
        else
            $this->identity = null;
    }

    public function __call($name,$args){
        if(is_null($this->identity)) return false;
        if(($field = preg_filter('/^set/','',$name)) && !empty($args)){
            $this->post_data[strtolower($field)] = $args[0];
            curl_setopt($this->conn,CURLOPT_POSTFIELDS,$this->post_data);
        }
        else{
            $method = preg_replace('/[^a-zA-Z]+/','.',$name);
            curl_setopt($this->conn,CURLOPT_URL,self::RPC_URL.$method);
            $this->post_data = array_merge($this->post_data,array("token"=>$this->identity,'as_user'=>true));
            $this->last_reply = json_decode(curl_exec($this->conn));
            return $this->last_reply->ok;
        }
        return $this;
    }

}

