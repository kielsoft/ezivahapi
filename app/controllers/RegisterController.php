<?php


namespace App\Controllers;


use App\Models\DeviceTokens;
use App\Models\Register;
use App\Models\Reviews;

class RegisterController extends BaseInjectable
{

    public function createRegister(){

        $register   = new Register();
        if($register->create($this->_requestTypeQuery()) != false){
            $this->response->setJsonContent(
                [
                    "status"    => "OK",
                    "results"   => $this->_requestTypeQuery()
                ]
            )->send();
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "results"   => $register->getMessages()
                ]
            )->send();
        }

    }

    public function setDeviceToken(){
//        $checkDeviceToken   = DeviceTokens::findFirstByToken($this->_requestTypeQuery()['token']);
//        if(!$checkDeviceToken){
            $tableDeviceToken   = new DeviceTokens();
            if($tableDeviceToken->create($this->_requestTypeQuery())){
                $this->response->setJsonContent(
                    [
                        "status"    => "OK",
                        "results"   => $this->_requestTypeQuery()
                    ]
                )->send();
            }
            else{
                $this->response->setJsonContent(
                    [
                        "status"    => "ERROR",
                        "results"   => implode(",",$tableDeviceToken->getMessages())
                    ]
                )->send();
            }
//        }
//        else{
//            $this->response->setJsonContent(
//                [
//                    "status"    => "ERROR",
//                    "results"   => "Device Tokened Already"
//                ]
//            )->send();
//        }
    }
}