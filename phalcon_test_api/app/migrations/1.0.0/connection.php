<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class ConnectionMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'connection',
            array(
            'columns' => array(
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 10,
                        'first' => true
                    )
                ),
                new Column(
                    'initperson_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'answerperson_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'initperson_id'
                    )
                ),
                new Column(
                    'duration',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'answerperson_id'
                    )
                ),
                new Column(
                    'cdate',
                    array(
                        'type' => Column::TYPE_DATE,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'duration'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('initperson_id', array('initperson_id')),
                new Index('answerperson_id', array('answerperson_id'))
            ),
            'references' => array(
                new Reference('person_ibfk_1', array(
                    'referencedSchema' => 'test',
                    'referencedTable' => 'person',
                    'columns' => array('id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('person_ibfk_2', array(
                    'referencedSchema' => 'test',
                    'referencedTable' => 'person',
                    'columns' => array('id'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '3',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            )
        )
        );
    }
}
