<?php

namespace DelaTask;

use Phalcon\Validation\Validator\Email as EmailValidator;

class Lawyers extends \Phalcon\Mvc\Model
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
     *
     * @var double
     * @Column(column="fee", type="double", length=10, nullable=true)
     */
    protected $fee;

    /**
     *
     * @var string
     * @Column(column="address", type="string", length=45, nullable=true)
     */
    protected $address;

    /**
     *
     * @var string
     * @Column(column="image", type="string", nullable=true)
     */
    protected $image;

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
     * Method to set the value of field fee
     *
     * @param double $fee
     * @return $this
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

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
     * Returns the value of field fee
     *
     * @return double
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("delatask");
        $this->setSource("lawyers");
        $this->hasMany('id', 'DelaTask\Review', 'lawyer_id', ['alias' => 'Review']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lawyers[]|Lawyers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lawyers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    public function validation()
    {
        $validator = new \Phalcon\Validation();
        $uValidator = new \Phalcon\Validation\Validator\Uniqueness(["message" => "This email has already been used by another user"]);
        $pValidator = new \Phalcon\Validation\Validator\PresenceOf();

        $validator->add('email', $uValidator);
        $validator->add('first_name', $pValidator);
        $validator->add('last_name', $pValidator);
        $validator->add('email', $pValidator);
        $validator->add('fee', $pValidator);
        $validator->add('address', $pValidator);

        return $this->validate($validator);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'lawyers';
    }

}
