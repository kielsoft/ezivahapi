<?php


namespace App\Models;

use Phalcon\Db\RawValue;
use Phalcon\Validation\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Register extends BaseModel
{

    public $email, $password, $status, $date_created, $date_modified;
    public $ip_address, $id, $role, $user_id;
    public $token_expires;
    public $token_created;
    public $token, $codename;
    public $last_modified;
    public $last_login_date;
    public $mark_assigned;
    public $ouath_provider;

    public function initialize(){
        $this->skipAttributesOnCreate(
            [
                "last_modified", "last_login_date"
            ]
        );
        $this->allowEmptyStringValues(
            [
                "ouath_provider",
                "last_name","image_url"
            ]
        );
    }

    public function beforeValidationOnCreate(){
        $this->password         = "admin";
        $this->role             = "user";
        $this->status           = "inactive";
        $this->token_created    = new RawValue("NOW()");
        $this->date_created     = new RawValue('NOW()');
        $this->last_modified    = new RawValue('NOW()');
        $this->token_expires    = date("Y-m-d H:i:s", strtotime('+3 hours'));
        $this->codename         = $this->getDI()->get('component')->helper->makeRandomInts();
        $this->token            = $this->getDI()->get("security")->getToken();
    }

    public function afterCreate(){
        //Send a mail to Reset Account
        $messageRow = $this->getDI()->getMailer()->createMessage()
            ->to($this->email)->subject("Registration Notification")
            ->content("Congratulations you successfully Registered. Your default password is <b>admin</b>. You can always login with your gmail or facebook account. Thank you");
        $messageRow->bcc("theophilus.alamu8@gmail.com");
        $messageRow->send();
    }

    public function validation(){
        $security   = new \Phalcon\Security();
        $validation = new \Phalcon\Validation();
        $validation->add('email', new Validator\Email(array(
            'model'     => $this,
            'message'   => 'Please enter correct email address'
        )));

        $validation->add('email', new Validator\Uniqueness(array(
            'model'     => $this,
            'message'   => 'Email address already existed'
        )));
        $this->password = $security->hash($this->password);
        return $this->validate($validation);
    }
}