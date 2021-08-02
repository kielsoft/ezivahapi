<?php


namespace App\Controllers;


use App\Models\Register;

class LoginController extends BaseInjectable
{

    public function checkUserSubmitParams(){
        $email      = $this->_requestTypeQuery()['email'];
        $password   = $this->_requestTypeQuery()['password'];
        $register   = Register::findFirstByEmail($email);
        if($register != false){
            $passwordState  = $this->security->checkHash($password, $register->password);
            if($passwordState){
                $this->response->setJsonContent(
                    [
                        "status"    => "OK",
                        "results"   => [
                            "token" => $register->token,
                            "email" => $register->email,
                            "name"  => ucwords($register->first_name." ".$register->last_name),
                            "joined"=> date("F d, Y", strtotime($register->date_created))
                        ]
                    ]
                )->send();
            }
            else{
                $this->response->setJsonContent(
                    [
                        "status"    => "ERROR",
                        "results"   => "Incorrect Password"
                    ]
                )->send();
            }
        }
        else{
            $this->response->setJsonContent(
                [
                    "status"    => "ERROR",
                    "results"   => "Email Not Found"
                ]
            )->send();
        }
    }
}