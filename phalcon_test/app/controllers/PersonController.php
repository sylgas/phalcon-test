<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PersonController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for person
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Person", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $person = Person::find($parameters);
        if (count($person) == 0) {
            $this->flash->notice("The search did not find any person");

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $person,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a person
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $person = Person::findFirstByid($id);
            if (!$person) {
                $this->flash->error("person was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "person",
                    "action" => "index"
                ));
            }

            $this->view->id = $person->id;

            $this->tag->setDefault("id", $person->id);
            $this->tag->setDefault("firstname", $person->firstname);
            $this->tag->setDefault("lastname", $person->lastname);
            
        }
    }

    /**
     * Creates a new person
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "index"
            ));
        }

        $person = new Person();

        $person->firstname = $this->request->getPost("firstname");
        $person->lastname = $this->request->getPost("lastname");
        

        if (!$person->save()) {
            foreach ($person->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "new"
            ));
        }

        $this->flash->success("person was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "person",
            "action" => "index"
        ));

    }

    /**
     * Saves a person edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $person = Person::findFirstByid($id);
        if (!$person) {
            $this->flash->error("person does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "index"
            ));
        }

        $person->firstname = $this->request->getPost("firstname");
        $person->lastname = $this->request->getPost("lastname");
        

        if (!$person->save()) {

            foreach ($person->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "edit",
                "params" => array($person->id)
            ));
        }

        $this->flash->success("person was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "person",
            "action" => "index"
        ));

    }

    /**
     * Deletes a person
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $person = Person::findFirstByid($id);
        if (!$person) {
            $this->flash->error("person was not found");

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "index"
            ));
        }

        if (!$person->delete()) {

            foreach ($person->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "person",
                "action" => "search"
            ));
        }

        $this->flash->success("person was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "person",
            "action" => "index"
        ));
    }

}
