<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPublications extends Model
{
    use HasFactory;
    protected $table = "book_publications";

    /**
     * Method to link the book table
     */
    public function book()
    {
        return $this->hasOne(BookDetails::class, "id", "book_id");
    }

    /**
     * Method to link the publication table
     */
    public function publication()
    {
        return $this->hasOne(Publications::class, "id", "publication_id");
    }
}
