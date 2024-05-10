<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Validator;
use NumberFormatter;

use App\Models\Node;

use Illuminate\Http\Request;

use App\Http\Resources\NodeResource;
use App\Http\Resources\NodeChildResource;

use App\Traits\FormaterTrait;

class NodeController extends Controller
{
    use FormaterTrait;

    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'parents.*'=> 'nullable|array|exists:App\Models\Node,id',
                'parents.*'=> '',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation errors', $validator->errors()->getMessages(), 500);
            }

            if(!$request->all()){  

                $node =  Node::create([]);
                $node->save(); 
                $node->title = $this->titleTranslation($node->id);
                $node->save();
                $node->timeZone = $request->header("timezone");
                $node->language = $request->header("language");

                return $this->sendResponse( new NodeResource($node), 'Node(s) created successfully', 200);

            }else{

                foreach ($request->parents as $parent) {
                    $node =  Node::create(["parent" => $parent]);
                    $node->title = $this->titleTranslation($node->id);
                    $node->save();
                    $node->language = $request->header("timezone");
                    $node->timeZone = $request->header("languagee");
                    $nodes[] = $node;
                }

                return $this->sendResponse(NodeResource::collection($nodes), 'Node(s) created successfully', 200);
            }

        } catch (\Exception $e) {
            return $this->sendError("error", $e->getMessage());
        }
        
    }

    public function indexParents(Request $request){
        try {
            $lang = $request->header("language");

            $parentNodes = Node::has('childrens')->get();

            return $this->sendResponse(NodeResource::collection($parentNodes), 'Node(s) returned successfully', 200);

        } catch (\Exception $e) {
            return $this->sendError("error", $e);
        }
        
    }

    public function indexChildByParent($parent, Request $request){
        try {

            $validator = Validator::make($request->all(), [
                 'level'=> 'nullable|integer|gt:0',
             ]);

            if ($validator->fails()) {
                
                return $this->sendError('Validation errors', $validator->errors()->getMessages(), 500);
            }
                                  
            $lang = $request->header("language");
            $timezone = $request->header("timezone");

            $parentNode = Node::find($parent);
            
            if(!$parentNode){
                return $this->sendError('Node not found','', 400);
            }

            if(!$request->all()){
                $child = $parentNode->where("parent",$parentNode->id)->get();
                //$childrens =$this->mapRecursive($child, $lang, $timezone);
                return $this->sendResponse(NodeResource::collection($child), 'Node(s) returned successfully', 200);
            }else{
                $stringLevel = $request->level == 1 ? "childrens" : "childrens".str_repeat('.childrens',$request->level - 1);
                $child = Node::where("parent",$parentNode->id)->with($stringLevel)->get()->toArray();
                return $this->sendResponse(NodeChildResource::collection($child), 'Node(s) returned successfully', 200);
            }
            

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
    public function mapRecursive($array, $lang, $timezone) {
        
        foreach ($array as $key => $item) {
            
            $array[$key]["language"] = $lang;
            $array[$key]["timezone"] = $timezone;
            $children = $item["children"];
            //dd($array[$key]["children"]);
            $array[$key]["children"] = $this->mapRecursive($children, $lang, $timezone);
            //$item->put("children", $this->mapRecursive($children, $lang, $timezone));
        } 
        return $array;
    }

    public function mapRecursiveResource($array) {
        $result = [];
        foreach ($array as $item) {
            $result[] = new NodeResource($item);
            $result = array_merge($result, $this->mapRecursiveResource($item['children']));
        }
        return $result;
    }

    // $childArray = $child->toArray();

                // $childArrayResult = $this->mapRecursive($childArray, $lang, $timezone);

                // dd($childArrayResult);

                // $childCollection = new Collection($childArray);
               

                // //dd($childCollection);

                // $childCollection->map($recursive = function($item) use (&$recursive, $lang, $timezone){

                //     //dd($item);

                //     if(!$item["children"]){
                //         return $item;
                //     }
                //     $item["language"] = $lang;
                //     $item["timezone"] = $timezone;
                //     dd($item);
                //     $item["children"] = $recursive($item["children"]);

                //     return $item;
                // });

                // dd($childCollection);

                // $childrens =$this->mapRecursive($childCollection, $lang, $timezone);
                // dd($childrens);
//dd($child);
            //$childrens =$this->mapRecursive($child, $lang, $timezone);
            
            // dd($childrens);
            
}
