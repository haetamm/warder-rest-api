<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'body'];
    protected $hidden = [
        'seller_id',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
