<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;
   
    protected $attributes = [
        'parent' => null,
        'title' =>null,
    ];

    protected $fillable = [
        'parent',
        'title'
    ];
    
    protected $guarded = [
        'id'
    ];

    
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent');
    }

    public function childrens()
    {
        return $this->hasMany(Node::class, 'parent');
    }

}
