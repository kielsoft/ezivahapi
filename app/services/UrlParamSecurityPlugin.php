<?php


namespace App\Security;


use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class UrlParamSecurityPlugin extends Plugin {
    const GUEST = 'guest';
    const USER  = 'user';
    const ADMIN = 'admin';

    protected $_publicResources = array(
        'index' => ['*'],
    );

    protected $_userResources = array(
        'dashboard' => ['*'],
    );

    protected $_adminResources = array(
        'admin' => ['*']
    );

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {

    }

    public function beforeHandleRoute(Event $event, Dispatcher $dispatcher) {

    }

    public function afterHandleRoute(Event $event, Dispatcher $dispatcher) {

    }

    protected function _getAcl() {
        if(!isset($this->persistent->acl)){

            $acl = new \Phalcon\Acl\Adapter\Memory();
            $acl->setDefaultAction(Acl::DENY);
            $roles = array(
                'user'  => new Acl\Role(self::USER),
                'guest' => new Acl\Role(self::GUEST),
                'admin' => new Acl\Role(self::ADMIN),
            );

            foreach($roles as $role){
                $acl->addRole($role);
            }

            //Public Resources
            foreach($this->_publicResources as $resource => $action){
                $acl->addResource(new Acl\Resource($resource), $action);
            }

            //User Resources
            foreach($this->_userResources as $resource => $action){
                $acl->addResource(new Acl\Resource($resource), $action);
            }

            //Admin Resources
            foreach($this->_adminResources as $resource => $action){
                $acl->addResource(new Acl\Resource($resource), $action);
            }

            //Allow All Roles to access the Public Resources
            foreach($roles as $role){
                foreach($this->_publicResources as $resource => $actions){
                    $acl->allow($role->getName(), $resource, $actions);
                }
            }

            //Allow Users and Admin to access the User Resources
            foreach($this->_userResources as $resource => $actions){
                foreach($actions as $action){
                    $acl->allow(self::USER, $resource, $action);
                    $acl->allow(self::ADMIN, $resource, $action);
                }
            }

            //Allow Admin to accesst the Admin Resources
            foreach($this->_adminResources as $resource => $actions){
                foreach($actions as $action){
                    $acl->allow(self::ADMIN, $resource, $action);
                }
            }
            $this->persistent->acl = $acl;
        }
        return $this->persistent->acl;
    }
}