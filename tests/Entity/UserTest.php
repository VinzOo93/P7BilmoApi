<?php

namespace App\Tests\Entity;

use App\Entity\Customer;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class UserTest extends TestCase
{
    /**
     * @return void
     */
    public function testEmail()
    {
        $user =  $this->getEntityUser();
        $user->setEmail('pablo@live.fr');
        $this->assertEquals('pablo@live.fr', $user->getEmail());
    }

    /**
     * @return void
     */
    public function testName()
    {
        $user =  $this->getEntityUser();
        $user->setName('Escobar');
        $this->assertEquals('Escobar', $user->getName());
    }

    /**
     * @return void
     */
    public function testFirstName()
    {
        $user =  $this->getEntityUser();
        $user->setFirstname('Pablo');
        $this->assertEquals('Pablo', $user->getFirstname());
    }

    /**
     * @return void
     */
    public function testCustomer()
    {
        $user =  $this->getEntityUser();
        $customer =  $this->getEntityCustomer();
        $user->setCustomer($customer->setCompany('Javier Cartel'));
        $this->assertEquals('Javier Cartel', $user->getCustomer()->getCompany());
    }

    /**
     * @return User
     */
    private function getEntityUser()
    {
        return new User();
    }

    /**
     * @return Customer
     */
    private function getEntityCustomer()
    {
        return new Customer();
    }
}
