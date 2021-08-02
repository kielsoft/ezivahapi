<?php
/**
 * Created by PhpStorm.
 * User: Theophilus
 * Date: 12/10/2018
 * Time: 11:42 PM
 */

use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;

$mailerConfig   = [
    'driver'     => 'mail',
    'from'       => [
        'email' => 'info@xtremeads.com',
        'name'  => 'Peppered Rice'
    ]
];

$paystackConfig = [
    "secret_key"    => "sk_test_425a6cb648b62d3da7b4f05cba33c48955cb2af7",
    "public_key"    => "pk_test_8f18f18010c2bb4bb269caff0b2bf594a46797da"
];

$dependencyInjector = new \Phalcon\Di\FactoryDefault();

$dependencyInjector->setShared('response', function (){
    $response   = new \Phalcon\Http\Response();
    $response->setContentType('application/json','utf-8');
    return $response;
});

$dependencyInjector->setShared('config', $config);

$dependencyInjector->setShared('configKey', $configKey);

$dependencyInjector->set("db", function () use ($config){
    return new MysqlAdapter(
        array(
            "host"      => $config->database->host,
            "username"  => $config->database->username,
            "password"  => $config->database->password,
            "dbname"    => $config->database->dbname
        )
    );
});

$dependencyInjector->setShared("api", function() use ($config){
    return $config->api;
});

$dependencyInjector->set("component", function(){
    $objectClass            = new stdClass();
    $objectClass->helper    = new \App\Components\Helper();
    return $objectClass;
});

$dependencyInjector->set("request", new \Phalcon\Http\Request());

$dependencyInjector->setShared("mailer", function() use ($mailerConfig){
    $mailer = new \Phalcon\Ext\Mailer\Manager($mailerConfig);
    return $mailer;
});

$dependencyInjector->setShared("session", function(){
    $session    = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});

$dependencyInjector->setShared("paystack", function() use($paystackConfig){
    return new \Yabacon\Paystack($paystackConfig['secret_key']);
});

$dependencyInjector->set("security", function(){
    $security   = new \Phalcon\Security();
    return $security;
}, true);

//Return custom components
$dependencyInjector->setShared('component', function(){
    $obj            = new \stdClass();
    $obj->helper    = new \App\Components\Helper();
    return $obj;
});


return $dependencyInjector;