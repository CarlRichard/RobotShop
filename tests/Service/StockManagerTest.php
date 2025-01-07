<?php 
namespace App\Service;

use App\Entity\Robot;
use App\Service\StockManager;
use PHPUnit\Framework\TestCase;

class StockManagerTest extends TestCase
{
    private $stockManager = null;
    protected function setUp() : void
    {
        $this->stockManager = new StockManager();
    }
    public function testSetAndGetStock(): void
    {
        $product = new Robot();
        $this->stockManager->setStock($product, 10);
        $this->assertEquals(10, $this->stockManager->getStock($product));
    }

    public function testCanProcessOrder():void 
    {
        $product = new Robot();
        $this->stockManager->setStock($product, 10);
        $this->assertTrue($this->stockManager->canProcessOrder($product, 5));   
        $this->assertFalse($this->stockManager->canProcessOrder($product, 15));
    }

    public function testProcessOrder(): void
    {
        $product = new Robot();
        $this->stockManager->setStock($product, 10);
        $this->stockManager->processOrder($product, 5);
        $this->assertEquals(5, $this->stockManager->getStock($product));
    }

    public function testProcessOrderWithInsufficientStock(): void
    {
        $product = new Robot();
        $this->stockManager->setStock($product, 10);
        $this->expectException(\RuntimeException::class);
        $this->stockManager->processOrder($product, 15);
    }

    public function testProcessOrderWithNoStock(): void
    {
        $product = new Robot();
        $this->expectException(\RuntimeException::class);
        $this->stockManager->processOrder($product, 15);
    }
}

