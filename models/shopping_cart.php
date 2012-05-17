<?php
class ShoppingCartException extends Exception{}

class ShoppingCart
{
	protected $items;

	public function __construct()
	{
		$this->items = array();
	}

	public function addItem($new_item=null)
	{
		$prdt_code = $new_item->product->product_code;
		$this->items[$prdt_code] = $new_item;
	}

	public function removeItem($prdt_code=null)
	{
		if (!isset($this->items[$prdt_code]))
		{
			throw new ShoppingCartException("No such cart item");
		}
		unset($this->items[$prdt_code]);
	}

	public function updateItemQty($prdt_code, $new_qty)
	{
		if (!isset($this->items[$prdt_code]))
		{
			throw new ShoppingCartException("No such cart item");
		}
		$this->items[$prdt_code]->setQty($new_qty);
	}

	public function totalQty()
	{
		$qty = 0;
		foreach($this->items as $cart_item)
		{
			$qty += $cart_item->getQty();
		}
		return $qty;
	}

	public function numProducts()
	{
		return count($this->items);
	}

	public function grandTotal()
	{
		$amount = 0;
		foreach($this->items as $cart_item)
		{
			$amount += $cart_item->getSubTotal();
		}
		return $amount;
	}
}
?>