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
            'appKeyId'        => '',
            'appKeySecret'    => '',
        ]
    ]
);
