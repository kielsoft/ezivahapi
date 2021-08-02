<?php


namespace App\Models;


class Reviews extends BaseModel
{
    public $id;
    public $register;
    public $book_id;
    public $ratings;
    public $description;
    public $created;
    public $status;
    public $title;

    public function beforeValidationOnCreate(){
        $this->created  = new \Phalcon\Db\RawValue("NOW()");
        $this->status   = 0;
    }
}