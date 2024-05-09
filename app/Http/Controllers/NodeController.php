<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Validator;
use NumberFormatter;

use App\Models\Node;

use Illuminate\Http\Request;

use App\Http\Resources\NodeResource;

use App\Traits\FormaterTrait;

class NodeController extends Controller
{
    use FormaterTrait;

    public function create(Request $request){
        try {
            $lang = $request->header("language");
            $validator = Validator::make($request->all(), [
                'parents'=> 'nullable|array',
                'parents.*'=> 'exists:App\Models\Node,id',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation errors', $validator->errors()->getMessages(), 500);
            }

            if(!$request->all()){
                $node = Node::create([]);
                $node->title = $this->titleTranslation($node->id);
                $node->save();
                $node->language = $lang;
                $node->timeZone = null;

                return $this->sendResponse( new NodeResource($node), 'Node(s) created successfully', 200);

            }

            $nodes = [];

            foreach ($request->parents as $parent) {
                $node = Node::create(["parent" => $parent]);
                $node->title = $this->titleTranslationdDefault($node->id);
                $node->save();
                $node->language = $lang;
                $node->timeZone = null;
                $nodes[] = $node;
            }

            // $nodes["language"] = $lang;
            // $nodes["timeZone"] = null;

            return $this->sendResponse(NodeResource::collection($nodes), 'Node(s) created successfully', 200);

        } catch (\Exception $e) {
            return $this->sendError("error", $e);
        }
        
    }

    public function indexParents(Request $request){
        try {
            $lang = $request->header("language");

            $parentNodes = Node::has('children')->get();

            $parentNodes->map( function ($node) use($lang){
                $node->language = $lang;
                $node->timeZone = null;
                return $node;
            });

            return $this->sendResponse(NodeResource::collection($parentNodes), 'Node(s) returned successfully', 200);

        } catch (\Exception $e) {
            return $this->sendError("error", $e);
        }
        
    }

    public function indexChildByParent($parent, Request $request){
        try {

            $validator = Validator::make($request->all(), [
                 'level'=> 'nullable|integer|gte:0',
             ]);

            

            if ($validator->fails()) {
                
                return $this->sendError('Validation errors', $validator->errors()->getMessages(), 500);
            }

            $stringLevel = $request->level == 0 ? "children" : "children".str_repeat('.children',$request->level);
            
            $lang = $request->header("language");

            $parentNode = Node::find($parent);
            
            if(!$parentNode){
                return $this->sendError('Node not found','', 400);
            }

            if($request->level == 0){
                $child = $parentNode->where("parent",$parentNode->id)->get();
            }else{
                $child = $parentNode->where("parent",$parentNode->id)->with($stringLevel)->get();
            }
            
            $childrens =$this->mapRecursive($child, $lang);
            

              return $this->sendResponse(NodeResource::collection($childrens), 'Node(s) returned successfully', 200);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e);
        }
        
    }

    public function delete($id){
        try {
            $node = Node::find($id);

            if(!$node){
                return $this->sendError('Node not found','', 400);
            }

            $child = Node::where('parent',$id)->first();

            if($child){
                return $this->sendError("This node is a parent, it cannot be deleted");    
            }else{
                $node->delete();
                return $this->sendResponse([], 'Node deleted successfully', 200);

            }

        } catch (\Exception $e) {
            return $this->sendError("error", $e);
        }
        
    }
    public function mapRecursive($array, $lang) {
        $result = [];
        foreach ($array as $item) {
            $item["language"]= $lang ;
            //dd($item);
            $result[] = $item;
            $result = array_merge($result, $this->mapRecursive($item['children'], $lang));
        }
        return $result;
    }
}
