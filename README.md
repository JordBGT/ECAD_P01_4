# ecad_p01_4
GitHub repository for E-Commerce Application Development (ECAD) module assignment

Deliverables
---------------------------------------------------------------------------------------------------------------------------
Team
- A home page (named as index.php) with navigation system that integrates all
the functional areas of the online store. The home page should display the
products that are on offer currently.

- A login page that accepts email account and password for member’s
authentication. The login credentials, i.e. email account and password, should
be verified against records stored in the database
---------------------------------------------------------------------------------------------------------------------------
Individual
---------------------------------------------------------------------------------------------------------------------------
1. Membership Registration

Basic Requirements
- Allow a shopper to register as a member. Appropriate input data validation
should be incorporated.

- Allow an existing member to update the profile. Appropriate input data
validation should be incorporated.

- When registering and updating member profile, ensure that the email account
provided by the shopper is unique in the database.

Additional Requirements
- Provide the password to a member who has forgotten it after he has provided
the right answer to a question. The question and answer are captured during
member’s registration.
[You may omit using SMTP service to email the password to the member, just
display the password on screen after verification.]

- Allow registered member to give a ranking and post feedback regarding the
products or services provided by the merchant. The feedback is to be saved
in the database and be viewed by everybody. It cannot be retrieved for editing
once it is submitted.
---------------------------------------------------------------------------------------------------------------------------
2. Product Catalog

Basic Requirements
- Display the Product Catalogue.
  o The product catalogue should contain a “Categories” page showing all
    product categories available in the online store, sorted in alphabetical
    order.
  o The product catalogue should contain a “Product Listing” page
    showing all products of a selected category. The product list should
    be sorted in alphabetical order.
  o A “Product Details” page displaying detail information of a product and
    for the shopper to add the product to his shopping cart.

- Display “Out of Stock” indicator and disable the “add to cart” button.
For products which have an inventory level of zero or negative, an “Out of
Stock” indicator should be displayed and shopper should be prevented from
ordering such products by disabling the “add to cart” button.

- Provide a simple search feature for products.
A shopper should be able to search for products by supplying a partial search
string of the product’s name or description.

Additional Requirements
- Display an “On Offer” indicator on the “Product Listing” page for products
which are marked as “offered” within a period indicated in the database. (i.e.
“Offered” column in the “Product” table has a value of “1”.) On the “Product
Details” page, besides displaying the “On Offer” indicator, you should also
display the price before offer and strike it off.

- Provide an advanced product search feature that returns a list of products that
match certain search criteria, e.g. price range, products currently on offer.
---------------------------------------------------------------------------------------------------------------------------
3. Shopping Cart

Basic Requirements
- Able to add a product to the shopping cart.
When the shopper selects a product in the catalogue and adds to his shopping
cart, the shopping cart should be updated with the selected product. Note that
adding of duplicated product to the shopping cart should increase the quantity
of purchase for that product in the shopping cart.

- Display and update the content of the shopping cart.
  o It should display the product name, the unit price, quantity of purchase
    and the total purchase amount for each and all of the products in the
    shopping cart.
  o Shopper should be allowed to change (i.e. update) the quantity of
    purchase for each product in the shopping cart.
  o Allow the shopper to delete individual product from the shopping cart.
  
- After a member logs in successfully, his shopping cart which contains any
unchecked-out items from the last shopping session should be loaded.

Additional Requirements
- Waive the delivery charge (assumed express delivery) if subtotal amount is
more than S$200.

- Compute the number of items in cart by adding the quantity of purchase for
each item in the shopping cart, e.g. 2 pcs of Product 1 and 3 pcs of Product 2
in cart, the total number of items in cart should be 5.
---------------------------------------------------------------------------------------------------------------------------
4. Checkout

Basic Requirements
- Delivery is within Singapore only and there are 2 delivery modes. Calculate the
delivery charge based on the mode of delivery. Delivery charge is $5 per trip
for “Normal Delivery” (within 2 working days after an order is placed) and $10
per trip for “Express Delivery” (delivered within 24 hours after an order is
placed).

- Allow a user to checkout using PayPal (Note: Test with your PayPal Sandbox’s
accounts). When checkout is successful, a confirmation page with the
necessary information should be displayed. The confirmation page should
carry an order ID for future reference by the shopper.

- Update the inventory level in the database accordingly once the order is
confirmed,

Additional Requirements
- Check to ensure that the quantity of purchase for any product should not be
more than the quantity in stock during checkout.

- Calculate tax payable based on the current GST rate retrieved from the
database.
---------------------------------------------------------------------------------------------------------------------------
