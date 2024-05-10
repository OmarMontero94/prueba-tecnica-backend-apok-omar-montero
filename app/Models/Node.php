<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Node extends Model
{
    use HasFactory;
   
    protected $attributes = [
        'parent' => null,
        'title' =>null,
        // 'language' => "en",
        // 'timezone' => "America/Caracas"

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

    public function childrens()
    {
        return $this->hasMany(Node::class, 'parent');
    }


    public function childrenRecursive()
    {   
        if($this->level == 0){
            return false;
        }else{
            $this->level--; 
            return $this->childrens()->with("childrenRecursive");
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

}
