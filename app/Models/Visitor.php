<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'identification_number', 
        'identification_type',
        'name', 
        'email',
        'address', 
        'phone'];

    // Relationship: Each visitor has many visitor histories
    public function histories()
    {
        return $this->hasMany(VisitorHistory::class, 'visitor_id');
    }
}
