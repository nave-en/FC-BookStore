Online Book Store
Description
Create a simple online bookstore where users can view a list of books, search for books by title or author, add books to their cart, and place orders.
Scenario 1 →   get the list of books
In this scenario, we will return all the books in the database along with the details like book name, author name, publication name
Scenario 2 → search a book by author name
Case a : exact match
In this scenario if the user gives name like “Kumar”, we will search for the book which was written by Kumar only, we will skip the author name like Raj Kumar, Naveen Kumar.
Case b : like match 
In this scenario if the user gives name like “Kumar”, we will search for the book written by authors whose contains Kumar also like Raj Kumar, Naveen Kumar.
Scenario 3 → search a book by its name
Case a : exact match
In this scenario if the user gives name like “wings”, we will search for the book which contains Kumar only, we will skip the name like fire of wings, iron wings.
Case b : like match
In this scenario if the user gives name like “wings”, we will search for the book which contains wings, we will select the book which contains name like fire of wings, iron wings.
Scenario 4 → get the book detail
In this scenario, when user request to get the full details of the book, we will return to all details of the book like no of pages, rating, ISBN code.
Scenario 5 → add an item to the cart
In this scenario, first we will validate the input request like all the input keys are present in the request, then the record is existed in the cart, then ordered count not less than or equal to zero, then the ordered count should be less than or equal to available count
Case a : add a new item to the cart
Once the validation is done, we will check whether the record is already in the cart table or not for that user, if not then we will reduce the ordered count from the stock count and will update in the stock table, once the update is done we will add the item in the cart.
Case b : add an existing item to the cart
If the user adds an item which is already existing in the cart for that user, then we will update the count of that item in the cart, and we will reduce the count from the stock count.
	Note : if the sum of the already ordered count and newly ordered count by the user greater than available count then an exception will be thrown
Scenario 6 → Modify the cart item
In this scenario, first we will validate the input request like all the input keys are present in the request, then the record is existing in the cart
Case a : increase the count(i.e.increase the cart item count)
In this scenario, the sum of already existing item in the cart and ordered count greater than the available count an exception will be thrown, else the ordered count will be decrease from the stock count and the cart count will be updated.
Case b : decrease the count(i.e. Reduce a cart item count)
In this scenario, if the reduced count is greater than the cart count then an exception will be thrown, else the reduced count will be added to the stock count and the cart count will be updated.
Case c : decrease count equal to cart item(i.e. if the user decreases the count which is equal to cart items)
In this scenario, we will update the stock count, and then we will remove the record from the cart.

Note : Transactions are used in both scenario 5 and 6

Scenario 7 → view the cart details
In this scenario, we will return cart items of the user
Scenario 8 → delete an item in the cart
In this scenario, first we will update the stock item(i.e. we will add the cart item count to the stock item)  then we will delete the record in the cart
Scenario 9 → Delete all items in the cart
Similar to scenario 8, we will update the stock item for each cart item then we will delete the records
Scenario 10 → proceed to checkout
In this, we will move the items which are in the cart table for a user will move to the ordered table.
Scenario 11 → delete the expired records
In this scenario, when a book is which is stored more than one day , it will automatically be removed. For this we use a cron job which runs every morning at 5:30 which automatically delete the record from the cart and increases the stock count.

Ideas to try
Dynamic prize based on the book count
