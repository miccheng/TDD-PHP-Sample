<?php
require_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../models/product.php';
require_once dirname(__FILE__) . '/../../models/cart_item.php';
require_once dirname(__FILE__) . '/../../models/shopping_cart.php';

class ShoppingCartSpec extends PHPUnit_Extensions_Story_TestCase
{
	/**
	 * @scenario
	 */
	public function cartHasNoItems()
	{
		$this->given('New cart')
			->then('Total quantity should be', 0)
			->then('Number of products should be', 0);
	}

	/**
	 * @scenario
	 */
	public function addFirstItemToCart()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, 5)
			->then('Total quantity should be', 5)
			->then('Number of products should be', 1);
	}

	/**
	 * @scenario
	 */
	public function addSecondItemToCart()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, 5)
			->when('Add new item', 2, "PID-12346", 2.75, 20, 1)
			->then('Total quantity should be', 6)
			->then('Number of products should be', 2);
	}

	/**
	 * @scenario
	 */
	public function removeFirstItemFromCart()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, 5)
			->when('Add new item', 2, "PID-12346", 2.75, 20, 1)
			->when('Remove item', "PID-12345")
			->then('Total quantity should be', 1)
			->then('Number of products should be', 1);
	}

	/**
	 * @scenario
	 */
	public function updateQtyOfItemInCart()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, 5)
			->when('Update item qty', "PID-12345", 2)
			->then('Total quantity should be', 2)
			->then('Number of products should be', 1);
	}
	
	/**
	 * @scenario
	 */
	public function updateQtyOfItemInvalid()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, 5)
			->when('Update item qty', "PID-12345", -20)
			->then("Returns error", 'Invalid quantity')
			->then('Total quantity should be', 5)
			->then('Number of products should be', 1);
	}
	
	/**
	 * @scenario
	 */
	public function addInvalidItem()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 2.35, 20, -10)
			->then("Returns error", 'Invalid quantity')
			->then('Total quantity should be', 0)
			->then('Number of products should be', 0);
	}
	
	/**
	 * @scenario
	 */
	public function removeInvalidItem()
	{
		$this->given('New cart')
			->when('Remove item', "PID-12345")
			->then("Returns error", 'No such cart item')
			->then('Total quantity should be', 0)
			->then('Number of products should be', 0);
	}
	
	/**
	 * @scenario
	 */
	public function updateInvalidItem()
	{
		$this->given('New cart')
			->when('Update item qty', "PID-12345", 2)
			->then("Returns error", 'No such cart item')
			->then('Total quantity should be', 0)
			->then('Number of products should be', 0);
	}

	/**
	 * @scenario
	 */
	public function calculateTotalAmount()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 3, 20, 5)
			->when('Add new item', 2, "PID-12346", 2, 20, 4)
			->then("Grand total", 23)
			->then('Total quantity should be', 9)
			->then('Number of products should be', 2);
	}
	
	/**
	 * @scenario
	 */
	public function productHasInsufficientStock()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 3, 2, 5)
			->then("Returns error", 'Insufficient stock')
			->then('Total quantity should be', 0)
			->then('Number of products should be', 0);
	}
	
	/**
	 * @scenario
	 */
	public function updateQtyOfProductWithInsufficientStock()
	{
		$this->given('New cart')
			->when('Add new item', 1, "PID-12345", 3, 20, 5)
			->when('Update item qty', "PID-12345", 25)
			->then("Returns error", 'Insufficient stock')
			->then('Total quantity should be', 5)
			->then('Number of products should be', 1);
	}

	public function runGiven(&$world, $action, $arguments)
    {
        switch($action)
		{
			case "New cart":
			{
				$world['cart'] = new ShoppingCart();
			}
			break;
            default:
			{
                return $this->notImplemented($action);
            }
        }
    }

    public function runWhen(&$world, $action, $arguments)
    {
        switch($action)
		{
			case 'Add new item':
			{
				try
				{
					$new_product = new Product($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
					$new_item = new CartItem($new_product, $arguments[4]);
					$world['cart']->addItem($new_item);
				}
				catch (Exception $ex)
				{
					$world['errors'][] = $ex->getMessage();
				}
			}
			break;
			case 'Remove item':
			{
				try
				{
					$world['cart']->removeItem($arguments[0]);
				}
				catch (Exception $ex)
				{
					$world['errors'][] = $ex->getMessage();
				}
			}
			break;
			case "Update item qty":
			{
				try
				{
					$world['cart']->updateItemQty($arguments[0], $arguments[1]);
				}
				catch (Exception $ex)
				{
					$world['errors'][] = $ex->getMessage();
				}
			}
			break;
            default:
			{
                return $this->notImplemented($action);
            }
        }
    }

    public function runThen(&$world, $action, $arguments)
    {
        switch($action)
		{
			case "Grand total":
			{
				$this->assertEquals($arguments[0], $world['cart']->grandTotal());
			}
			break;
			case "Total quantity should be":
			{
				$this->assertEquals($arguments[0], $world['cart']->totalQty());
			}
			break;
			case "Number of products should be":
			{
				$this->assertEquals($arguments[0], $world['cart']->numProducts());
			}
			break;
			case "Returns error":
			{
				if (!isset($world['errors'])) $world['errors'] = array();
				$this->assertTrue(in_array($arguments[0], $world['errors']));
			}
			break;
            default:
			{
                return $this->notImplemented($action);
            }
        }
    }
}
?>