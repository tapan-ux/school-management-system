<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmailApiModel extends Model
{
    use HasFactory;
    protected $table = 'gmail_api';

    protected $guarded = [];
}
