<?php

namespace DelaTask;

class Review extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Column(column="appointment_id", type="integer", length=11, nullable=true)
     */
    protected $appointment_id;

    /**
     *
     * @var integer
     * @Column(column="client_id", type="integer", length=11, nullable=true)
     */
    protected $client_id;

    /**
     *
     * @var integer
     * @Column(column="lawyer_id", type="integer", length=11, nullable=true)
     */
    protected $lawyer_id;

    /**
     * Method to set the value of field appointment_id
     *
     * @param integer $appointment_id
     * @return $this
     */
    public function setAppointmentId($appointment_id)
    {
        $this->appointment_id = $appointment_id;

        return $this;
    }

    /**
     * Method to set the value of field client_id
     *
     * @param integer $client_id
     * @return $this
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * Method to set the value of field lawyer_id
     *
     * @param integer $lawyer_id
     * @return $this
     */
    public function setLawyerId($lawyer_id)
    {
        $this->lawyer_id = $lawyer_id;

        return $this;
    }

    /**
     * Returns the value of field appointment_id
     *
     * @return integer
     */
    public function getAppointmentId()
    {
        return $this->appointment_id;
    }

    /**
     * Returns the value of field client_id
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Returns the value of field lawyer_id
     *
     * @return integer
     */
    public function getLawyerId()
    {
        return $this->lawyer_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("delatask");
        $this->setSource("review");
        $this->belongsTo('appointment_id', 'DelaTask\Appointments', 'id', ['alias' => 'Appointments']);
        $this->belongsTo('client_id', 'DelaTask\Clients', 'id', ['alias' => 'Clients']);
        $this->belongsTo('lawyer_id', 'DelaTask\Lawyers', 'id', ['alias' => 'Lawyers']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'review';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Review[]|Review|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Review|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
