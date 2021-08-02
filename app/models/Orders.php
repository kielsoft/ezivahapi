<?php


namespace App\Models;


use Phalcon\Db\RawValue;

class Orders extends BaseModel
{

    public $order_id, $user_id, $book_id, $payment_method, $created, $amount, $description, $reference;
    public $status, $paid_at, $customer_email, $first_name, $last_name, $raw_data;

    public function beforeValidationOnCreate(){
        $this->payment_method   = "card";
        $this->created          = new RawValue("NOW()");
    }
}