<?php

use Phalcon\Mvc\Model\Message;

class Person extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'firstname' => 'firstname',
            'lastname' => 'lastname'
        );
    }

    public function validation()
    {

        if (strlen($this->firstname) < 1) {
            $this->appendMessage(new Message("The firstname cannot be empty"));
        }

        if (strlen($this->lastname) < 1) {
            $this->appendMessage(new Message("The lastname cannot be empty"));
        }

        //Check if any messages have been produced
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function getSerializedData() {
        return array(
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        );
    }

}
