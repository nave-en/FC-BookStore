<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BookPublicationAuthor extends Model
{
    use HasFactory;
    protected $table = "book_publication_author";

    /**
     * Method to link book publication table
     */
    public function bookPublication()
    {
        return $this->hasOne(BookPublications::class, "id", "book_publication_id");
    }

    /**
     * Method to link book author table
     */
    public function bookAuthor()
    {
        return $this->hasOne(BookAuthor::class, "id", "book_author_id");
    }

    /**
     * Method to get the base query
     * @return object
     */
    public function getBaseQueryForFetchingBooks() : object
    {
        return self::join(
            "book_publications",
            "book_publications.id",
            "=",
            "book_publication_author.book_publication_id"
        )
        ->join(
            "book_author_details",
            "book_author_details.id",
            "=",
            "book_publication_author.book_author_id"
        )
        ->join(
            "book_details",
            "book_details.id",
            "=",
            "book_author_details.book_id"
        )
        ->join(
            "author_details",
            "author_details.id",
            "=",
            "book_author_details.author_id"
        )
        ->join(
            "publication_details",
            "publication_details.id",
            "=",
            "book_publications.publication_id"
        )
        ->join(
            "stocks",
            "stocks.book_publication_author_id",
            "=",
            "book_publication_author.id"
        )
        ->select([
            "book_publication_author.id as book_publication_author_id",
            "book_details.id as book_detail_id",
            "stocks.id as stock_id",
            "book_details.name as book_name",
            "author_details.name as author_name",
            "stocks.available_count as availble_count",
            "stocks.price as price_per_unit",
            "publication_details.name as publication_name"

        ]);
    }

    /**
     * Method to get all books
     * @return array
     */
    public function getAllBooks() : array
    {
        return $this->getBaseQueryForFetchingBooks()
            ->get()
            ->toArray();
    }

    /**
     * Method to get the book by author name
     * @param string $authorName
     * @param bool $exactMatch
     * @return array
     */
    public function getBooksByAuthorName($authorName, $exactMatch) : array
    {
        $baseQuery = $this->getBaseQueryForFetchingBooks();
        if ($exactMatch) {
            return $baseQuery->where("author_details.name", $authorName)
                ->get()
                ->toArray();
        } else {
            return $baseQuery->where("author_details.name", 'like', '%' . $authorName . '%')
                ->get()
                ->toArray();
        }
    }

    /**
     * Method to get the book by name
     * @param string $bookName
     * @param bool $exactMatch
     * @return array
     */
    public function getBooksByName($bookName, $exactMatch) : array
    {
        $baseQuery = $this->getBaseQueryForFetchingBooks();
        if ($exactMatch) {
            return $baseQuery->where("book_details.name", $bookName)
                ->get()
                ->toArray();
        } else {
            return $baseQuery->where("book_details.name", 'like', '%' . $bookName . '%')
                ->get()
                ->toArray();
        }
    }
}
