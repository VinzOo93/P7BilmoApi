<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ProductTest extends TestCase
{
    /**
     * @return void
     */
    public function testBrand()
    {
        $product = $this->getEntityProduct();
        $product->setBrand('Apple');
        $this->assertEquals('Apple', $product->getBrand());
    }

    /**
     * @return void
     */
    public function testModel()
    {
        $product = $this->getEntityProduct();
        $product->setModel('S21');
        $this->assertEquals('S21', $product->getModel());
    }

    /**
     * @return void
     */
    public function testDescription()
    {
        $product = $this->getEntityProduct();
        $product->setDescription('joli telephone');
        $this->assertEquals('joli telephone', $product->getDescription());
    }

    /**
     * @return void
     */
    public function testQuantity()
    {
        $product = $this->getEntityProduct();
        $product->setQuantity(32);
        $this->assertEquals(32, $product->getQuantity());
    }

    /**
     * @return void
     */
    public function testPrice()
    {
        $product = $this->getEntityProduct();
        $product->setPrice(25);
        $this->assertEquals(25, $product->getPrice());
    }

    /**
     * @return Product
     */
    private function getEntityProduct()
    {
        return new Product();
    }
}
