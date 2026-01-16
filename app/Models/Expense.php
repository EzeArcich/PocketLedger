<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'spent_at',
        'description',
        // 'category_id', a implementar a futuro
        // 'payment_method_id', a implementar a futuro
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

