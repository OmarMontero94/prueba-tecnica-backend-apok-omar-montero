<?php

namespace App\Http\Controllers;

use Exception;
use Validator;

use App\Models\Node;

use Illuminate\Http\Request;

use App\Http\Resources\NodeResource;
use App\Http\Resources\NodeChildResource;

use App\Traits\FormaterTrait;


/**
* @OA\Info(
*           title="Api documentation for -Prueba Practica Backend Apok- by Omar Montero", 
*           version="1.0",
*           description="Documentacion de la pruba tecnica pra el puesto de backend en apok, Hecho por Omar Montero"
* )
*/

class NodeController extends Controller
{
    use FormaterTrait;

    /**
     * @OA\Post(
     *     path="/api/node",
     *     summary="Post a new node",
     *     tags={"Nodes"},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="parents",
     *                  type="array",
     *                  @OA\Items(
     *                      type="number",
     *                      example = "1"
     *                  )
     *               )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Node(s) created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="2"
     *                  ),
     *                  @OA\Property(
     *                         property="parent",
     *                         type="number",
     *                         example="1"
     *                  ),
     *                  @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="two"
     *                  ),
     *                  @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2024-05-10 11:33:25"
     *                  ),
     *                  @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2024-05-10 11:33:25"
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example = "Node(s) created successfully"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="number",
     *                  example = "200"
     *              ),   
     *          )
     *      )
     * )
     */
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

                return $this->sendResponse( new NodeResource($node), 'Node(s) created successfully', 200);

            }else{

                foreach ($request->parents as $parent) {
                    $node =  Node::create(["parent" => $parent]);
                    $node->title = $this->titleTranslation($node->id);
                    $node->save();
                    $nodes[] = $node;
                }

                return $this->sendResponse(NodeResource::collection($nodes), 'Node(s) created successfully', 200);
            }

        } catch (\Exception $e) {
            return $this->sendError("error", $e->getMessage());
        }
        
    }

    /**
     * @OA\Get(
     *     path="/api/node/parents",
     *     summary="Get all parent nodes",
     *     tags={"Nodes"},
     *     
     *     @OA\Response(
     *          response=200,
     *          description="Node(s) returned successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="number",
     *                          example="2"
     *                      ),
     *                      @OA\Property(
     *                          property="parent",
     *                           type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="two"
     *                       ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          example="2024-05-10 11:33:25"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          example="2024-05-10 11:33:25"
     *                      ),
     *                  )
     *                  
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example = "Node(s) returned successfully"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="number",
     *                  example = "200"
     *              ),   
     *          )
     *      )
     * )
     */
    public function indexParents(Request $request){
        try {

            $lang = $request->header("language");
            $parentNodes = Node::has('childrens')->get();
            return $this->sendResponse(NodeResource::collection($parentNodes), 'Node(s) returned successfully', 200);

        } catch (\Exception $e) {
            return $this->sendError("error", $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/node/child/{parent}",
     *     summary="Get child nodes of a parent",
     *     tags={"Nodes"},
     *     @OA\Parameter(
     *         in="path",
     *         name="parent",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="level",
     *                  type="number",
     *                  example = "1"
     *               )
     *          )
     *     ),
     *     
     *     @OA\Response(
     *          response=200,
     *          description="Node(s) returned successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="number",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="parent",
     *                           type="number",
     *                          example="null"
     *                      ),
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                          example="two"
     *                       ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          example="2024-05-10 11:33:25"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          example="2024-05-10 11:33:25"
     *                      ),
     *                      @OA\Property(
     *                          property="childrens",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="number",
     *                                  example="2"
     *                              ),
     *                              @OA\Property(
     *                                  property="parent",
     *                                  type="number",
     *                                  example="1"
     *                              ),
     *                              @OA\Property(
     *                                  property="title",
     *                                  type="string",
     *                                  example="two"
     *                              ),
     *                              @OA\Property(
     *                                  property="created_at",
     *                                  type="string",
     *                                  example="2024-05-10 11:33:25"
     *                              ),
     *                              @OA\Property(
     *                                  property="updated_at",
     *                                  type="string",
     *                                  example="2024-05-10 11:33:25"
     *                              ),
     *                              @OA\Property(
     *                                  property="childrens",
     *                                  type="array",
     *                                  @OA\Items()
     *                              ),
     *                          )
     *                      ),
     *                  )
     *                  
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example = "Node(s) created successfully"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="number",
     *                  example = "200"
     *              ),   
     *          )
     *      )
     * )
     */
    
     public function indexChildByParent($parent, Request $request){
        try {

            $validator = Validator::make($request->all(), [
                 'level'=> 'nullable|integer|gt:0',
             ]);

            if ($validator->fails()) {
                
                return $this->sendError('Validation errors', $validator->errors()->getMessages(), 500);
            }
                                  
            $parentNode = Node::find($parent);
            
            if(!$parentNode){
                return $this->sendError('Node not found','', 400);
            }

            if(!$request->all()){
                $child = $parentNode->where("parent",$parentNode->id)->get();
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

    /**
     * @OA\Delete(
     *     path="/api/node/{id}",
     *     summary="Delete an specific node",
     *     tags={"Nodes"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     
     *     @OA\Response(
     *          response=200,
     *          description="Node deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example = "Node(s) Node deleted successfully"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="number",
     *                  example = "200"
     *              ),   
     *          )
     *      )
     * )
     */
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
            
}
