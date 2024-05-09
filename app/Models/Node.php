<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Node extends Model
{
    use HasFactory;

    public $level = 1;

    protected $attributes = [
        'level'
    ];
    protected $fillable = [
        'parent',
        'title'
    ];
    
    protected $guarded = [
            'id',
            'parent'
    ];

    // protected $appends = [
    //     'level'
    // ];
    
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent');
    }

    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }

    public function parentRecursiveFlatten()
    {
        $result = collect();
        $item = $this->parentRecursive;
        if ($item instanceof Node) {
            $result->push($item);
            $result = $result->merge($item->parentRecursiveFlatten());
        }
        return $result;
    }

    public function children()
    {
        return $this->hasMany(Node::class, 'parent');
    }


    public function childrenRecursive()
    {   
        if($this->level == 0){
            return false;
        }else{
            $this->level--; 
            return $this->children()->with("childrenRecursive");
        }
        
        
    }

    public function childrenRecursiveFlatten()
    {
        $result = collect();
        $item = $this->childrenRecursive();
        if ($item instanceof Node) {
            $result->push($item);
            $result = $result->merge($item->childrenRecursiveFlatten());
        }
        return $result;
    }

    // public function getLevelAttribute()
    // {
    //     return 0;
    // }
}
