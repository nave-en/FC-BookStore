<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
    protected $table = "book_author_details";
    use HasFactory;
    public function book() {
        return $this->hasOne(BookDetails::class, "id", "book_id");
    }
    public function author() {
        return $this->hasOne(AuthorDetails::class, "id", "author_id");
    }
}
