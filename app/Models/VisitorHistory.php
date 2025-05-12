<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorHistory extends Model
{
    //
    use HasFactory;

    // Table name
    protected $table = 'visitor_histories';

    // Fillable columns for mass assignment
    protected $fillable = [
        'visitor_id',
        'purpose',
        'status', 
        'check_in', 
        'check_out'
    ];

    // Relationship: Each visitor history belongs to a visitor
    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
}
