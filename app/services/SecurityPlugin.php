<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 11/1/2019
 * Time: 11:12 AM
 */

namespace App\Security;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class SecurityPlugin extends Plugin {

    public function __construct($app) {
        var_dump($app); exit;
    }

    public function beforeHandleRoute(Event $event, Dispatcher $dispatcher) {

    }

    public function afterHandleRoute(Event $event, Dispatcher $dispatcher) {

    }
}