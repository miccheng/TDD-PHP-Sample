Test Folder
------------

**Different way to fun a PHPUnit Test:**

1. Start your terminal / cosole and navigate to the TDD-PHP-Sample folder
2. `cd` into the `tests` folder.

	* `-v` switch refer to verbose mode (more explanations at the end if things fail).
	* `--debug` switch shows you all the individual test cases that are being run.

3. To run individual test case:

		$ phpunit --colors --debug -v ./models/ProductTest
		$ phpunit --colors --debug -v ./models/CartItemTest

4. To run all cases inside the `./model`

		$ phpunit --colors --debug -v ./models/

5. To run the BDD:

		$ phpunit --colors --printer PHPUnit_Extensions_Story_ResultPrinter_Text ShoppingCartSpec
		$ phpunit --colors --printer PHPUnit_Extensions_Story_ResultPrinter_HTML ShoppingCartSpec > result.html

6. To run the Spec Test Runner (or with a different config file):

		$ phpunit -c spec.xml