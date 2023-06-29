<?php

use DelaTask\Appointments;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use DelaTask\Review;

class ReviewController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for review
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, '\DelaTask\Review', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "appointment_id";

        $review = Review::find($parameters);
        if (count($review) == 0) {
            $this->flash->notice("The search did not find any review");

            $this->dispatcher->forward([
                "controller" => "review",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $review,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction($lawyerId)
    {
        if(!$this->session->has('auth')){
            $this->response->redirect('LoginAs/loginAs');
        }
        $this->view->lawyerId = $lawyerId;

    }

    /**
     * Edits a review
     *
     * @param string $appointment_id
     */
    public function editAction($appointment_id)
    {
        if (!$this->request->isPost()) {

            $review = Review::findFirstByappointment_id($appointment_id);
            if (!$review) {
                $this->flash->error("review was not found");

                $this->dispatcher->forward([
                    'controller' => "review",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->appointment_id = $review->getAppointmentId();

            $this->tag->setDefault("appointment_id", $review->getAppointmentId());
            $this->tag->setDefault("client_id", $review->getClientId());
            $this->tag->setDefault("lawyer_id", $review->getLawyerId());


            
        }
    }

    /**
     * Creates a new review
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'index'
            ]);

            return;
        }

        $appointment = new Appointments();
        $appointment->setappointmentDate($this->request->getPost("appointment_date"));
        $appointment->setstartTime($this->request->getPost("start_time"));
        $appointment->setendTime($this->request->getPost("end_time"));
        $appointment->setstatus('pending');
        $appointment->settitle($this->request->getPost("title"));
        $appointment->save();

        $client = \DelaTask\Clients::findFirstByEmail($this->session->get('auth')['email']);

        $review = new Review();
        $review->appointments = $appointment;
        $review->setclientId($client->getId());
        $review->setlawyerId($this->request->getPost("lawyerId"));


        if (!$review->save()) {
            foreach ($review->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Appointment was created");

        $this->dispatcher->forward([
            'controller' => "review",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a review edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'index'
            ]);

            return;
        }

        $appointment_id = $this->request->getPost("appointment_id");
        $review = Review::findFirstByappointment_id($appointment_id);

        if (!$review) {
            $this->flash->error("review does not exist " . $appointment_id);

            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'index'
            ]);

            return;
        }

        $review->setappointmentId($this->request->getPost("appointment_id"));
        $review->setclientId($this->request->getPost("client_id"));
        $review->setlawyerId($this->request->getPost("lawyer_id"));
        

        if (!$review->save()) {

            foreach ($review->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'edit',
                'params' => [$review->getAppointmentId()]
            ]);

            return;
        }

        $this->flash->success("review was updated successfully");

        $this->dispatcher->forward([
            'controller' => "review",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a review
     *
     * @param string $appointment_id
     */
    public function deleteAction($appointment_id)
    {
        $review = Review::findFirstByappointment_id($appointment_id);
        if (!$review) {
            $this->flash->error("review was not found");

            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'index'
            ]);

            return;
        }

        if (!$review->delete()) {

            foreach ($review->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "review",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("review was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "review",
            'action' => "index"
        ]);
    }

}
