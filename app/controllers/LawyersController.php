<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use DelaTask\Lawyers;

class LawyersController extends ControllerBase
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
        $user = DelaTask\Lawyers::findFirstByEmail($email);
        if ($user){
            if ($this->security->checkHash($pass, $user->getPassword())){
                $this->session->start();
                $this->session->set('auth',
                    ['email' => $user->getEmail(),
                    'role' => 'lawyer']);
                $this->flash->success("Welcome back " . $user->getFirstname());
                return $this->dispatcher->forward(["controller" => "appointments", "action" => "search"]);

            }
            else{
                $this->flash->error("Your password is incorrect - try again");
                return $this->dispatcher->forward(["controller" => "lawyers", "action" => "login"]);
            }
        }
        else{
            $this->flash->error("That username was not found - try again ");
            return $this->dispatcher->forward(["controller" => "lawyers", "action" => "login"]);
        }
        return $this->dispatcher->forward(["controller" => "index", "action" => "index"]);


    }
    /**
     * Searches for lawyers
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Lawyers', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $lawyers = Lawyers::find($parameters);
        if (count($lawyers) == 0) {
            $this->flash->notice("The search did not find any lawyers");

            $this->dispatcher->forward([
                "controller" => "lawyers",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $lawyers,
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
     * Edits a lawyer
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $lawyer = Lawyers::findFirstByid($id);
            if (!$lawyer) {
                $this->flash->error("lawyer was not found");

                $this->dispatcher->forward([
                    'controller' => "lawyers",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $lawyer->getId();

            $this->tag->setDefault("id", $lawyer->getId());
            $this->tag->setDefault("first_name", $lawyer->getFirstName());
            $this->tag->setDefault("last_name", $lawyer->getLastName());
            $this->tag->setDefault("email", $lawyer->getEmail());
            $this->tag->setDefault("password", $lawyer->getPassword());
            $this->tag->setDefault("image", $lawyer->getImage());

            $this->tag->setDefault("fee", $lawyer->getFee());
            $this->tag->setDefault("address", $lawyer->getAddress());

        }
    }

    /**
     * Creates a new lawyer
     */

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'index'
            ]);

            return;
        }

        $password = $this->request->getPost("password");
        $confirmPassword = $this->request->getPost("confirmPassword");

        if ($password !== $confirmPassword) {
            $this->flash->error("Passwords do not match.");
            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'new'
            ]);

            return;
        }

        $lawyer = new \DelaTask\Lawyers();
        $lawyer->setfirstName($this->request->getPost("first_name"));
        $lawyer->setlastName($this->request->getPost("last_name"));
        $lawyer->setemail($this->request->getPost("email", "email"));
        $lawyer->setpassword($this->security->hash($password));
        $lawyer->setimage(base64_encode(file_get_contents($this->request->getUploadedFiles()[0]->getTempName())));
        $lawyer->setfee($this->request->getPost("fee"));
        $lawyer->setaddress($this->request->getPost("address"));

        if (!$lawyer->save()) {
            foreach ($lawyer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Profile created successfully");

        $this->dispatcher->forward([
            'controller' => "lawyers",
            'action' => 'index'
        ]);
    }


    /**
     * Saves a lawyer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $lawyer = Lawyers::findFirstByid($id);

        if (!$lawyer) {
            $this->flash->error("lawyer does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'index'
            ]);

            return;
        }

        $lawyer->setfirstName($this->request->getPost("first_name"));
        $lawyer->setlastName($this->request->getPost("last_name"));
        $lawyer->setemail($this->request->getPost("email"));
        $lawyer->setpassword($this->request->getPost("password"));
        $lawyer->setimage(base64_encode(file_get_contents($this->request->getUploadedFiles()[0]->getTempName())));
        $lawyer->setfee($this->request->getPost("fee"));
        $lawyer->setaddress($this->request->getPost("address"));
        

        if (!$lawyer->save()) {

            foreach ($lawyer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'edit',
                'params' => [$lawyer->getId()]
            ]);

            return;
        }

        $this->flash->success("Lawyer was updated successfully");

        $this->dispatcher->forward([
            'controller' => "lawyers",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a lawyer
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $lawyer = Lawyers::findFirstByid($id);
        if (!$lawyer) {
            $this->flash->error("Lawyer was not found");

            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'index'
            ]);

            return;
        }

        if (!$lawyer->delete()) {

            foreach ($lawyer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lawyers",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Lawyer was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "lawyers",
            'action' => "index"
        ]);
    }

}
