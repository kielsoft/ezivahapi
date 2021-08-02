<?php
/**
 * Created by PhpStorm.
 * User: Theophilus
 * Date: 12/10/2018
 * Time: 11:51 PM
 */

return new Phalcon\Config(
    [
        'database'  => [

        ],
        'application'   => [
            'controllerDir' => 'app/controllers',
            'modelsDir'     => 'app/models',
            'baseUri'       => '/'
        ],
        'apiKeyToken'       => [
            'appKeyId'        => '59146428466177992482',
            'appKeySecret'    => 'tkQ03VCcziNtG5qmrh1cKXKQDEEkqojUGnL7tYgW',
        ]
    ]
);