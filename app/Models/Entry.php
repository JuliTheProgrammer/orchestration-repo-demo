<?php

namespace App\Models;

use Database\Factories\EntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    /** @use HasFactory<EntryFactory> */
    use HasFactory;

    protected $fillable = ['title', 'body'];
}
