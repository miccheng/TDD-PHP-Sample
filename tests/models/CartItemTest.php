<?php
require_once dirname(__FILE__) . '/../../models/product.php';
require_once dirname(__FILE__) . '/../../models/cart_item.php';

class CartItemTest extends PHPUnit_Framework_TestCase
{
	protected $product;
	protected $item;

	public function setup()
	{
		$this->product = new Product(1, "PID-12345", 2.35, 20);
		$this->item = new CartItem($this->product, 2);
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

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage Invalid quantity
	 */
	public function testInvalidCartQty()
	{
		$result = new CartItem($this->product, -20);
	}

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage Invalid Cart Item
	 */
	public function testInvalidCartProduct()
	{
		$result = new CartItem(null, 2);
	}

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage Invalid quantity
	 */
	public function testSetQtyException()
	{
		$this->item->setQty(-20);
	}

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage There is no stock
	 */
	public function testNoStock()
	{
		$this->product = new Product(1, "PID-12345", 2.35, 0);
		$this->item = new CartItem($this->product, 2);
	}

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage Insufficient stock
	 */
	public function testSetInvalidQty()
	{
		$this->product = new Product(1, "PID-12345", 2.35, 10);
		$this->item = new CartItem($this->product, 15);
	}

	/**
	 * @expectedException CartItemException
	 * @expectedExceptionMessage Insufficient stock
	 */
	public function testSetInvalidNewQty()
	{
		$this->item->setQty(25);
	}
}
?>
