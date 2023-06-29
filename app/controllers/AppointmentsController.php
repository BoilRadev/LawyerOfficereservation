<?php

use DelaTask\Lawyers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use DelaTask\Appointments as AppointmentsAlias;

class AppointmentsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    public function displayCalendarAction(){

        $fcCollection = $this->assets->collection("fullCalendar");
        $fcCollection->addJs('js/moment.min.js');
        $fcCollection->addJs('js/fullcalendar.min.js');
        $fcCollection->addCss('css/fullcalendar.min.css');
    }

    public function jsonAction(){

        $this->view->disable();
        $dataPoints = \DelaTask\Appointments::find();
        $this->response->resetHeaders();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($dataPoints, JSON_NUMERIC_CHECK));
        return $this->response->send();
    }

    /**
     * Searches for appointments
     */
    public function searchAction()
    {

        $email = $this->session->get('auth')['email'];
        $lawyer = Lawyers::findFirstByEmail($email);

        $reviews = \DelaTask\Review::findByLawyerId($lawyer->getId());
        $appointments = [];
        $appointmentIds = [];
        foreach ($reviews as $review) {
            $appointment = \DelaTask\Appointments::findFirstById($review->getAppointmentId());
            $appointments[] = $appointment;
            $appointmentIds[]= $review->getAppointmentId();
        }

        $this->view->appointments = $appointments;

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, '\DelaTask\Appointments', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }

        $parameters["conditions"] = "";
        if (!empty($appointmentIds)) {
            $parameters["conditions"] = "id IN (" . implode(',', $appointmentIds) . ")";
        }
        $parameters["order"] = "id";

        $appointments = \DelaTask\Appointments::find($parameters);
        if (count($appointments) == 0) {
            $this->flash->notice("The search did not find any appointments");

            $this->dispatcher->forward([
                "controller" => "appointments",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $appointments,
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
        $this->view->lawyers = DelaTask\Lawyers::find();
    }

    /**
     * Edits a appointment
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $appointment = AppointmentsAlias::findFirstByid($id);
            if (!$appointment) {
                $this->flash->error("appointment was not found");

                $this->dispatcher->forward([
                    'controller' => "appointments",
                    'action' => 'index'
                ]);
                return;
            }

            $this->view->id = $appointment->getId();
            $this->tag->setDefault("id", $appointment->getId());
            $this->tag->setDefault("title", $appointment->getTitle());
            $this->tag->setDefault("appointment_date", $appointment->getAppointmentDate());
            $this->tag->setDefault("start_time", $appointment->getStartTime());
            $this->tag->setDefault("end_time", $appointment->getEndTime());
        }
    }

    /**
     * Creates a new appointment
     */



    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'index'
            ]);

            return;
        }

        $appointmentDate = $this->request->getPost("appointment_date");
        $startTime = $this->request->getPost("start_time");
        $endTime = $this->request->getPost("end_time");

        $existingAppointments = AppointmentsAlias::count([
            'appointment_date = :appointmentDate: AND ((start_time <= :startTime AND end_time >= :startTime) OR (start_time >= :startTime AND start_time <= :endTime))',
            'bind' => [
                'appointmentDate' => $appointmentDate,
                'startTime' => $startTime,
                'endTime' => $endTime
            ]
        ]);

        if ($existingAppointments > 0) {
            $this->flash->error("There is an existing booking for this period.");
            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'new'
            ]);

            return;
        }

        $appointment = new AppointmentsAlias();
        $appointment->setappointmentDate($this->request->getPost("appointment_date"));
        $appointment->setstartTime($this->request->getPost("start_time"));
        $appointment->setendTime($this->request->getPost("end_time"));
        $appointment->setstatus('pending');
        $appointment->settitle($this->request->getPost("title"));
        $appointment->setlawyer($this->request->getPost("lawyer")); // Add this line

        if (empty($appointment->getlawyer())) {
            $this->flash->error("Field lawyer is required.");
            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'new'
            ]);
        }
    }

    /**
     * Saves a appointment edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'index'
            ]);
            return;
        }

        $id = $this->request->getPost("id");
        $appointment = AppointmentsAlias::findFirstByid($id);

        if (!$appointment) {
            $this->flash->error("appointment does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'index'
            ]);
            return;
        }

        $appointment->setstartTime($this->request->getPost("start_time"));
        $appointment->setendTime($this->request->getPost("end_time"));
        $appointment->setstatus($this->request->getPost("status"));
        $appointment->settitle($this->request->getPost("title"));
        $appointment->setappointmentDate($this->request->getPost("appointment_date"));

        if (!$appointment->save()) {

            foreach ($appointment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'edit',
                'params' => [$appointment->getId()]
            ]);

            return;
        }

        $this->flash->success("appointment was updated successfully");

        $this->dispatcher->forward([
            'controller' => "appointments",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a appointment
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $appointment = AppointmentsAlias::findFirstByid($id);
        if (!$appointment) {
            $this->flash->error("appointment was not found");

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'index'
            ]);
            return;
        }

        if (!$appointment->delete()) {

            foreach ($appointment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'search'
            ]);
            return;
        }

        $this->flash->success("appointment was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "appointments",
            'action' => "index"
        ]);
    }

    public function approveAction($id)
    {
        $appointment = AppointmentsAlias::findFirstByid($id);
        $this->view->id = $appointment->getId();
        $appointment->setstatus('approved');

        if (!$appointment->save()) {

            foreach ($appointment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'search',
                'params' => [$appointment->getId()]
            ]);

            return;
        }

        $this->flash->success("Appointment approved");

        $this->dispatcher->forward([
            'controller' => "appointments",
            'action' => 'search'
        ]);
    }

    public function declineAction($id)
    {

        $appointment = AppointmentsAlias::findFirstByid($id);
        $this->view->id = $appointment->getId();
        $appointment->setstatus('declined');

        if (!$appointment->save()) {

            foreach ($appointment->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "appointments",
                'action' => 'search',
                'params' => [$appointment->getId()]
            ]);

            return;
        }

        $this->flash->success("Appointment declined");

        $this->dispatcher->forward([
            'controller' => "appointments",
            'action' => 'search'
        ]);
    }
}
