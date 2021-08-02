<?php


namespace App\Controllers;


use Yabacon\Paystack;
use Yabacon\Paystack\Exception\ApiException;

class VerifyController extends BaseInjectable {

    public function verifyReference($reference){
        //$reference  = $this->request->getQuery("reference");
        // verify using the library
        try{
            $transaction    = $this->paystack->transaction->verify(
                [
                    'reference' => $reference
                ]
            );
        }
        catch (ApiException $exception){
            return $this->response->setJsonContent(
                [
                    "status"    => false,
                    "data"      => $exception
                ]
            )->send();
        }
        if ('success' === $transaction->data->status) {
            return $this->response->setJsonContent(
                [
                    "status"    => true,
                    "data"      => $transaction->data
                ]
            )->send();
        }
        else{
            return $this->response->setJsonContent(
                [
                    "status"    => false,
                    "data"      => $transaction->data
                ]
            )->send();
        }
    }
}