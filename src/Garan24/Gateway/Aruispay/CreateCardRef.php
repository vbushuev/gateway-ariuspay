<?php
namespace Garan24\Gateway\Aruispay;
use \Garan24\BaseConnector as BaseConnector;
use \Garan24\Gateway\Aruispay\Exception  as Garan24GatewayAruispayException;
class CreateCardRef extends BaseConnector{
    public function __construct($data=[]){
        parent::__construct(["endpoint"=>"1144"]);
        $this->_request_data = $data;
        $this->_method = "create-card-ref";
    }
    public function check(){

    }
    public function cancel(){
        $result = [];
        return $result;
    }
    protected function build(){
        $this->_request_data["login"] = $this->_merchant_login;
        $this->_request_data["control"] = $this->digest(
            $this->_merchant_login
            .trim($this->_request_data["client_orderid"])
            .trim($this->_request_data["orderid"])
            .$this->_merchant_key
        );
    }

}
?>
