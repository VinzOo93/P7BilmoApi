<?php

namespace App\Tests\Entity;

use App\Entity\Customer;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class CustomerTest extends TestCase
{

    /**
     * @return void
     */
    public function testRole()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setRoles(['ROLE_USER']);
        $this->assertEquals(['ROLE_USER'], $customer->getRoles());
    }

    /**
     * @return void
     */
    public function testUsername()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setUsername('Pepin');
        $this->assertEquals('Pepin', $customer->getUsername());
    }

    /**
     * @return void
     */
    public function testPassword()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setPassword('pa$$word');
        $this->assertEquals('pa$$word', $customer->getPassword());
    }

    /**
     * @return void
     */
    public function testCompagny()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setCompany('Lutor Corp');
        $this->assertEquals('Lutor Corp', $customer->getCompany());
    }

    /**
     * @return void
     */
    public function testAddress()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setAddress('7 rue de la soif');
        $this->assertEquals('7 rue de la soif', $customer->getAddress());
    }

    /**
     * @return void
     */
    public function testCity()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setCity('Amsterdam');
        $this->assertEquals('Amsterdam', $customer->getCity());
    }

    /**
     * @return void
     */
    public function testPhoneNume()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setPhoneNumber('060606060');
        $this->assertEquals('060606060', $customer->getPhoneNumber());
    }

    /**
     * @return void
     */
    public function testCountry()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setCountry('France');
        $this->assertEquals('France', $customer->getCountry());
    }

    /**
     * @return void
     */
    public function testPostalCode()
    {
        $customer =  $this->getEntityCustomer();
        $customer->setPostalCode('69000');
        $this->assertEquals('69000', $customer->getPostalCode());
    }

    /**
     * @return void
     */
    public function testUsers()
    {
        $customer =  $this->getEntityCustomer();
        $user = $this->getEntityUser();
        $user->setName('Pepin');
        $customer->addUser($user);
        $this->assertEquals('Pepin', $customer->getUsers()[0]->getName());

    }

    /**
     * @return Customer
     */
    private function getEntityCustomer()
    {
        return new Customer();
    }

    /**
     * @return User
     */
    private function getEntityUser()
    {
        return new User();
    }
}
