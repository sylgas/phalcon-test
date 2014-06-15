<?php

class Connection extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $initperson_id;

    /**
     *
     * @var integer
     */
    public $answerperson_id;

    /**
     *
     * @var integer
     */
    public $duration;

    /**
     *
     * @var string
     */
    public $cdate;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'initperson_id' => 'initperson_id', 
            'answerperson_id' => 'answerperson_id', 
            'duration' => 'duration', 
            'cdate' => 'cdate'
        );
    }
}