<?php


namespace App\Models;


use Phalcon\Db\RawValue;

class History extends BaseModel {

    public $time_date, $year, $month;

    public function initialize(){
        $this->setSource("order_history");
    }

    public function beforeValidationOnCreate(){
        $this->year         = date('Y');
        $this->month        = date('M');
        $this->time_date    = new RawValue("NOW()");
    }
}