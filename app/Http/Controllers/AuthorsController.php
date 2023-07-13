<?php

namespace App\Http\Controllers;

use App\Models\BookPublicationAuthor;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    /**
     * Method to get the book by author name
     * @param string $name
     * @param string $exactMatch
     */
    public function getBooksByAuthorName($name, $exactMatch) : string
    {
        $exactMatch = json_decode($exactMatch);
        return json_encode((new BookPublicationAuthor())->getBooksByAuthorName($name, $exactMatch));
    }
}
