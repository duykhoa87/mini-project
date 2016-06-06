<?php

namespace App;

use Moloquent;

class Event extends Moloquent
{
    protected $table = 'events';
    protected $fillable = ['name', 'venue', 'address', 'address2', 'postcode', 'date', 'time', 'logo', 'banner', 'description'];
}
