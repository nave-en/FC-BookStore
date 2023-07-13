<?php

namespace App\Http\Controllers;

use App\Models\BookPublicationAuthor;
use App\Models\BookDetails;
use Illuminate\Http\Request;

class BookDetailsController extends Controller
{
    /**
     * Method to get the list of all books
     * @return string
     */
    public function getAllBooks() : string
    {
        return json_encode(
            (new BookPublicationAuthor())->getAllBooks()
        );
    }

    /**
     * Method to search a book by its name
     * @param string $name
     * @param string $exactMatch
     * @return string
     */
    public function searchByBookName($name, $exactMatch) : string
    {
        $exactMatch = json_decode($exactMatch);

        return json_encode(
            (new BookPublicationAuthor())->getBooksByName($name, $exactMatch)
        );
    }

    /**
     * Methof to get a book detail by its id
     * @param int $bookId
     * @return string
     */
    public function gettheBookDetail($bookId) : string
    {
        return json_encode(
            (new BookDetails())->getBookDetailsById($bookId)
        );
    }
}
