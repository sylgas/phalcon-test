<?php

error_reporting(E_ALL);

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Micro($di);

    $application->get('/person', function () use ($application) {
        $phql = "SELECT * FROM Person";
        $persons = $application->modelsManager->executeQuery($phql);

        $data = array();
        foreach ($persons as $person) {
            $data[] = $person->getSerializedData();
        }

        echo json_encode($data);
    });

    $application->get('/person/{id:[0-9]+}', function ($id) use ($application) {
        $phql = "SELECT * FROM Person WHERE id = :id:";
        $person = $application->modelsManager->executeQuery($phql, array(
            'id' => $id
        ))->getFirst();;

        //Create a response
        $response = new Phalcon\Http\Response();

        if ($person == false) {
            $response->setJsonContent(array('status' => 'NOT-FOUND'));
        } else {
            $response->setJsonContent($person->getSerializedData());
        }

        $response->send();
    });

    $application->get('/person/{id:[0-9]+}/connection', function ($id) use ($application) {
        $phql = "SELECT * FROM Connection WHERE initperson_id = :id: OR answerperson_id = :id:";
        $connections = $application->modelsManager->executeQuery($phql, array(
            'id' => $id
        ));

        $data = array();
        foreach ($connections as $connection) {
            $phql = "SELECT * FROM Person WHERE id = :id:";
            $answer_person = $application->modelsManager->executeQuery($phql, array(
                'id' => $connection->answerperson_id
            ))->getFirst();

            $init_person = $application->modelsManager->executeQuery($phql, array(
                'id' => $connection->initperson_id
            ))->getFirst();

            $data[] = array(
                'id' => $connection->id,
                'duration' => $connection->duration,
                'cdate' => $connection->cdate,
                'initperson' => $init_person->getSerializedData(),
                'answerperson' => $answer_person->getSerializedData()
            );
        }

        echo json_encode($data);
    });

    $application->post('/person/create', function () use($application) {

        $person = $application->request->getJsonRawBody();

        $phql = "INSERT INTO Person (firstname, lastname) VALUES (:firstname:, :lastname:)";

        $status = $application->modelsManager->executeQuery($phql, array(
            'firstname' => $person->firstname,
            'lastname' => $person->lastname
        ));

        //Create a response
        $response = new Phalcon\Http\Response();

        //Check if the insertion was successful
        if ($status->success() == true) {

            //Change the HTTP status
            $response->setStatusCode(201, "Created");

            $person->id = $status->getModel()->id;
            $response->setJsonContent(array($person->getSerializedData()));
        } else {
            //Change the HTTP status
            $response->setStatusCode(409, "Conflict");

            //Send errors to the client
            $errors = array();
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
        }

        $response->send();

    });

    $application->delete('/person/{id:[0-9]+}/delete', function ($id) use($application) {
        $phql = "DELETE FROM Person WHERE id = :id:";
        $status = $application->modelsManager->executeQuery($phql, array(
            'id' => $id
        ));

        //Create a response
        $response = new Phalcon\Http\Response();

        if ($status->success() == true) {
            $response->setJsonContent(array('status' => 'OK'));
        } else {

            //Change the HTTP status
            $response->setStatusCode(409, "Conflict");

            $errors = array();
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));

        }

        $response->send();
    });

    echo $application->handle();

} catch (\Exception $e) {
    echo $e->getMessage();
}
