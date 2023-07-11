<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelServizioCovid extends Model
{
    use HasFactory;


    protected $table = 'tblHotelServiziCovid';
    protected $guarded = ['id'];
}
