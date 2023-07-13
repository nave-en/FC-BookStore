<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDetails extends Model
{
    use HasFactory;
    protected $table = "book_details";
    const ID = "id";

    /**
     * Method to get the book details
     * @param string $bookId
     * @return string
     */
    public function getBookDetailsById($bookId) : array
    {
        return self::where(self::ID, $bookId)
            ->first()
            ->toArray();
    }
}
