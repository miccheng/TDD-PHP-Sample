<?php
require_once dirname(__FILE__) . '/../../models/product.php';

class CartItemTest extends PHPUnit_Framework_TestCase
{	
	protected $item;

	public function setup()
	{
		$product = new Product(1, "PID-12345", 2.35, 20);
		$this->item = new CartItem($product, 2);
	}

	public function testConstructor()
	{
		$this->assertEquals(2, $this->item->getQty());
	}

	public function testSubTotal()
	{
		$this->assertEquals(4.70, $this->item->getSubTotal());
	}

	public function testSetQty()
	{
		$this->assertEquals(2, $this->item->getQty());
		$this->item->setQty(4);
		$this->assertEquals(4, $this->item->getQty());
		$this->assertEquals(9.4, $this->item->getSubTotal());
	}
}
?>