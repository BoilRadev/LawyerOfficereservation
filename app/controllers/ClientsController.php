<?php

use DelaTask\Clients;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ClientsController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }
    public function loginAction(){

    }
    public function logoutAction()
    {
        $this->session->destroy();
        $this->flash->success("You have been logged out.");
        return $this->dispatcher->forward(["controller" => "index", "action" => "index"]);
    }

    public function authorizeAction(){
        $email = $this->request->getPost('email');
        $pass = $this->request->getPost('password');
        $user = \DelaTask\Clients::findFirstByEmail($email);
        if ($user){
            if ($this->security->checkHash($pass, $user->getPassword())){
                $this->session->start();
                $this->session->set('auth',
                    ['email' => $user->getEmail(),
                    'role' => 'client']);
                $this->flash->success("Welcome back " . $user->getFirstname());
                return $this->dispatcher->forward(["controller" => "index", "action" => "index"]);
            }
            else{
                $this->flash->error("Your password is incorrect - try again");
                return $this->dispatcher->forward(["controller" => "clients", "action" => "loginAs"]);
            }
        }
        else{
            $this->flash->error("That username was not found - try again ");
            return $this->dispatcher->forward(["controller" => "clients", "action" => "loginAs"]);
        }
        return $this->dispatcher->forward(["controller" => "clients", "action" => "index"]);
    }
    /**
     * Searches for clients
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'DelaTask\Clients', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $clients = Clients::find($parameters);
        if (count($clients) == 0) {
            $this->flash->notice("The search did not find any clients");

            $this->dispatcher->forward([
                "controller" => "clients",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $clients,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a client
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $client = Clients::findFirstByid($id);
            if (!$client) {
                $this->flash->error("client was not found");

                $this->dispatcher->forward([
                    'controller' => "clients",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $client->getId();

            $this->tag->setDefault("id", $client->getId());
            $this->tag->setDefault("first_name", $client->getFirstName());
            $this->tag->setDefault("last_name", $client->getLastName());
            $this->tag->setDefault("email", $client->getEmail());
            $this->tag->setDefault("password", $client->getPassword());
            
        }
    }

    /**
     * Creates a new client
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $password = $this->request->getPost("password");
        $confirmPassword = $this->request->getPost("confirmPassword");

        if ($password !== $confirmPassword) {
            $this->flash->error("Passwords do not match.");
            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'new'
            ]);

            return;
        }

        $lawyer = new Clients();
        $lawyer->setfirstName($this->request->getPost("first_name"));
        $lawyer->setlastName($this->request->getPost("last_name"));
        $lawyer->setemail($this->request->getPost("email", "email"));
        $lawyer->setpassword($this->security->hash($password));

        if (!$lawyer->save()) {
            foreach ($lawyer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Profile created successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a client edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $client = Clients::findFirstByid($id);

        if (!$client) {
            $this->flash->error("client does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        $client->setfirstName($this->request->getPost("first_name"));
        $client->setlastName($this->request->getPost("last_name"));
        $client->setemail($this->request->getPost("email", "email"));
        $client->setpassword($this->request->getPost("password"));
        

        if (!$client->save()) {

            foreach ($client->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'edit',
                'params' => [$client->getId()]
            ]);

            return;
        }

        $this->flash->success("client was updated successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a client
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $client = Clients::findFirstByid($id);
        if (!$client) {
            $this->flash->error("client was not found");

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'index'
            ]);

            return;
        }

        if (!$client->delete()) {

            foreach ($client->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "clients",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("client was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "clients",
            'action' => "index"
        ]);
    }

}
