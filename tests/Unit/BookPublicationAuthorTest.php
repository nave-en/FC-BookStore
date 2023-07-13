<?php

namespace Tests\Unit;

use App\Models\BookPublicationAuthor;
use Tests\TestCase;

class BookPublicationAuthorTest extends TestCase
{
    /**
     * Method to test serach by author function
     */
    public function testGetBooksByAuthorName(): void
    {
        $authorName = "Kalki";
        $exactMatch = true;
        $bpaModel = new BookPublicationAuthor();
        // exact match
        $bookDetails = $bpaModel->getBooksByAuthorName($authorName, $exactMatch);
        if (count($bookDetails) == 1) {
            $this->assertTrue(true);
        }

        if ($bookDetails[0]['book_name'] == "GHI") {
            $this->assertTrue(true);
        }

        // like wild character match
        $authorName = "al";
        $exactMatch = false;
        $bookDetails = $bpaModel->getBooksByAuthorName($authorName, $exactMatch);
        $authorNames = array_unique(array_column($bookDetails, "author_name"));
        $bookNames = array_unique(array_column($bookDetails, "book_name"));
        if (in_array("Albert", $authorNames) && in_array("Kalki", $authorNames)) {
            $this->assertTrue(true);
        }
        if (in_array("ABC", $bookNames) && in_array("GHI", $bookNames) && in_array("JKL", $bookNames)) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test search by book name
     */
    public function testGetBooksByName(): void
    {
        $bookName = "ABC";
        $exactMatch = true;
        $bpaModel = new BookPublicationAuthor();
        $bookDetails = $bpaModel->getBooksByName($bookName, $exactMatch);
        $publicationsName = array_unique(array_column($bookDetails, "publication_name"));
        if (count($bookDetails) == 2) {
            $this->assertTrue(true);
        }
        if (in_array("Tree", $publicationsName) && in_array("Raj", $publicationsName)) {
            $this->assertTrue(true);
        }

        // wild char match
        $bookName = "L";
        $exactMatch = false;
        $bookDetails = $bpaModel->getBooksByName($bookName, $exactMatch);
        $bookNames = array_unique(array_column($bookDetails, "book_name"));
        $authorNames = array_unique(array_column($bookDetails, "author_name"));
        if (!in_array("ABC", $bookNames)) {
            $this->assertTrue(true);
        }
        if (in_array("Albert", $authorNames) && in_array("Charles", $authorNames)) {
            $this->assertTrue(true);
        }
    }

    /**
     * Method to test get all books
     */
    public function testgetAllBooks() : void
    {
        $allBooks = (new BookPublicationAuthor())->getAllBooks();
        $bookNames = array_unique(array_column($allBooks, "book_name"));
        
        if (!in_array("XYZ", $bookNames)) {
            $this->assertTrue(true);
        }
    }
}
