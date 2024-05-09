<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Node;
use NumberFormatter;
class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lang = 'en';
        $formater = new NumberFormatter( $lang, NumberFormatter::SPELLOUT );

        for ($i=0; $i < 10; $i++) { 
            $node = Node::create([]);
            $node->title = $formater->format($node->id);
            $node->save();
        }

        $nodes = Node::get();
        
       for ($i=1; $i < $nodes->count() ; $i++) {
            $nodes[$i]->parent = intdiv($nodes[$i]->id, 2);
            $nodes[$i]->save();

        }
    }
}
