<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author web
 */
namespace App\Models;

class Admin extends BaseModel {
    public function initialize(){
        $this->skipAttributesOnUpdate(array(
            'privy'
        ));
    }
}
