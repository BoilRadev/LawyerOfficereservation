<?php

namespace DelaTask;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

class Appointments extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(column="start_time", type="string", nullable=true)
     */
    protected $start_time;

    /**
     *
     * @var string
     * @Column(column="end_time", type="string", nullable=true)
     */
    protected $end_time;

    /**
     *
     * @var string
     * @Column(column="status", type="string", length=45, nullable=true)
     */
    protected $status;

    /**
     *
     * @var string
     * @Column(column="title", type="string", length=45, nullable=true)
     */
    protected $title;

    /**
     *
     * @var string
     * @Column(column="appointment_date", type="string", nullable=true)
     */
    protected $appointment_date;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field start_time
     *
     * @param string $start_time
     * @return $this
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;

        return $this;
    }

    /**
     * Method to set the value of field end_time
     *
     * @param string $end_time
     * @return $this
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field appointment_date
     *
     * @param string $appointment_date
     * @return $this
     */
    public function setAppointmentDate($appointment_date)
    {
        $this->appointment_date = $appointment_date;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field start_time
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Returns the value of field end_time
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field appointment_date
     *
     * @return string
     */
    public function getAppointmentDate()
    {
        return $this->appointment_date;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("delatask");
        $this->setSource("appointments");
        $this->hasMany('id', 'DelaTask\Review', 'appointment_id', ['alias' => 'Review']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'appointments';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Appointments[]|Appointments|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Appointments|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation(){
        $validator = new Validation();
        $pValidator = new PresenceOfValidator();

        $validator->add('title', $pValidator);
        $validator->add('appointment_date', $pValidator);
        $validator->add('start_time', $pValidator);
        $validator->add('end_time', $pValidator);

        return $this->validate($validator);
    }
}
