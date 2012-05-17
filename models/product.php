<?php
class ProductCreationException extends Exception{}

class Product
{
	public $id;
	public $product_code;
	public $unit_price = 0;
	public $stock_qty = 0;

	/**
	 * Create new instance of Product
	 * @param mixed $id
	 * @param string $code
	 * @param float $price
	 * @param int $stock
	 * @throws Exception 
	 */
	public function __construct($id=null, $code=null, $price=0, $stock=0)
	{
		if (is_null($id) || is_null($code))
		{
			throw new ProductCreationException("Unable to create product");
		}
		$this->id = $id;
		$this->product_code = $code;
		if ($price > 0)
		{
			$this->unit_price = $price;
		}
		if ($stock > 0)
		{
			$this->stock_qty = $stock;
		}
	}

	/**
	 * Return whether there are stock available.
	 * @return boolean 
	 */
	public function has_stock()
	{
		if ($this->stock_qty > 0)
		{
			return true;
		}
		return false;
	}
}
?>