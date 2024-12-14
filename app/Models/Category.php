<?php

namespace App\Models;

use App\Models\Transactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }
}
