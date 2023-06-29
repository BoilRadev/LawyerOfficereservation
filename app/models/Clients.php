<?php

namespace DelaTask;

use Phalcon\Validation;


class Clients extends \Phalcon\Mvc\Model
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
     * @Column(column="first_name", type="string", length=45, nullable=true)
     */
    protected $first_name;

    /**
     *
     * @var string
     * @Column(column="last_name", type="string", length=45, nullable=true)
     */
    protected $last_name;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(column="password", type="string", length=100, nullable=true)
     */
    protected $password;

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
     * Method to set the value of field first_name
     *
     * @param string $first_name
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $last_name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Returns the value of field first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Returns the value of field last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
//    public function validation()
//    {
//        $validator = new Validation();
//
//        $validator->add(
//            'email',
//            new EmailValidator(
//                [
//                    'model'   => $this,
//                    'message' => 'Please enter a correct email address',
//                ]
//            )
//        );
//
//        return $this->validate($validator);
//    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("delatask");
        $this->setSource("clients");
        $this->hasMany('id', 'DelaTask\Review', 'client_id', ['alias' => 'Review']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'clients';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Clients[]|Clients|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Clients|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    public function validation(){
        $validator = new \Phalcon\Validation();
        $uValidator = new \Phalcon\Validation\Validator\Uniqueness(["message" => "This email has already been used by another user"]);
        $pValidator = new \Phalcon\Validation\Validator\PresenceOf();

        $validator->add('email', $uValidator);
        $validator->add('first_name', $pValidator);
        $validator->add('last_name', $pValidator);
        $validator->add('password', $pValidator);

        return $this->validate($validator);
    }
}
