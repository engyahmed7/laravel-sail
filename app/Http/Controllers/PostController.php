<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints of Posts"
 * )
 */
class PostController extends Controller
{
    protected $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    use ResponseTrait;
    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="Get list of posts",
     *     description="Retrieve a list of posts with pagination",
     *     tags={"Posts"},
     * security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     * @OA\Parameter(
 *         name="Accept-Language",
 *         in="header",
 *         required=false,
 *         description="Specify the language for the response (e.g., 'ar' for Arabic, 'en' for English)",
 *         @OA\Schema(
 *             type="string",
 *             example="ar"
 *         )
 *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Posts not found"
     *     )
     * )
     */
    
     public function index()
    {
        // $posts = Post::orderBy("created_at", "desc")
        //              ->with(["categories", "comments"])
        //              ->get();

        // if ($posts->isEmpty()) {
        //     return $this->errorResponse('Posts not found', 404);
        // }

        // // Return paginated posts with PostResource collection
        // return $this->successResponse(PostResource::collection($posts));

        //firebase
        $posts = $this->database->getReference('posts')->getValue();

        $posts = collect($posts)->map(function ($postData) {
            return new Post($postData);
        });

        if ($posts->isEmpty()) {
            return $this->errorResponse(__('messages.post_not_found'), 404);
        }

        return $this->successResponse(PostResource::collection($posts), __('messages.posts_retrieved_successfully'));
    }

    public function create() {}

    /**
     * @OA\Post(
     *    path="/posts",
     *    tags={"Posts"},
     *     security={{"bearerAuth": {}}},
     *    summary="Store a new post",
     *    description="Returns post data",
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="title",
     *                    type="string",
     *                    description="Title of the post",
     *                    example="Sample Post Title"
     *                ),
     *                @OA\Property(
     *                    property="content",
     *                    type="string",
     *                    description="Content of the post",
     *                    example="Sample content of the post."
     *                )
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=201,
     *        description="Post created successfully",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="status",
     *                type="string",
     *                example="success"
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Post created successfully"
     *            ),
     *            @OA\Property(
     *                property="data",
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Bad request",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="status",
     *                type="string",
     *                example="error"
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Invalid data provided"
     *            )
     *        )
     *    )
     * )
     */
    /**
     * @group Post Management
     *
     * Create a new post.
     *
     * @authenticated
     *
     * @bodyParam title string required The title of the post. Example: My First Post
     * @bodyParam content string required The content of the post. Example: This is the content of my first post.
     *
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "title": "My First Post",
     *     "content": "This is the content of my first post.",
     *     "user_id": 1
     *   },
     *   "message": "Post created successfully"
     * }
     *
     * @response 401 {
     *   "error": "Unauthorized"
     * }
     *
     * @responseField $.data.id integer The ID of the created post.
     * @responseField $.data.title string The title of the created post.
     * @responseField $.data.content string The content of the created post.
     * @responseField $.data.user_id integer The ID of the user who created the post.
     */
    public function store(PostRequest $request)
    {
        // $data = $request->validated();
        // $data['user_id'] = auth()->user()->id;

        // $post = new PostResource(Post::create($data));

        // // return response()->json($post, 201);
        // return $this->successResponse($post, 'Post created successfully', 201);


        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $newPostKey = $this->database->getReference('posts')->push($data);
        return $this->successResponse(PostResource::collection($newPostKey), __('messages.post_created_successfully'),201);
    }


    
    // /**
    //  * @group Post Management
    //  *
    //  * Get the details of a specific post.
    //  *
    //  * @authenticated
    //  *
    //  * @urlParam id string required The ID of the post. Example: 1
    //  *
    //  * @response 200 {
    //  *   "data": {
    //  *     "id": 1,
    //  *     "title": "My First Post",
    //  *     "content": "This is the content of my first post.",
    //  *     "user_id": 1
    //  *   }
    //  * }
    //  *
    //  * @response 404 {
    //  *   "error": "Post not found"
    //  * }
    //  *
    //  * @responseField $.data.id integer The ID of the post.
    //  * @responseField $.data.title string The title of the post.
    //  * @responseField $.data.content string The content of the post.
    //  * @responseField $.data.user_id integer The ID of the user who created the post.
    //  */
    /**
     * @OA\Get(
     *    path="/posts/{id}",
     *   summary="Get post by id",
     *  description="Retrieve a post by id",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     *    name="id",
     *   in="path",
     * required=true,
     * description="ID of the post",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     *   response=200,
     * description="Successful operation",
     * ),
     * @OA\Response(
     *  response=404,
     * description="Post not found"
     * )
     * )
     */

    public function show(string $id)
    {
        $post = new PostResource(Post::find($id));
        if (!$post) {
            // return response()->json(['message' => 'Post not found'], 404);
            return $this->errorResponse(__('messages.post_not_found'), 404);
        }
        // return response()->json($post);
        return $this->successResponse($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *    path="/posts/{id}",
     *    summary="Update post by id",
     *    description="Update a post by id",
     *    tags={"Posts"},
     * security={{"bearerAuth": {}}},
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        description="ID of the post",
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="title",
     *                    type="string",
     *                    description="Title of the post",
     *                    example="Updated Post Title"
     *                ),
     *                @OA\Property(
     *                    property="content",
     *                    type="string",
     *                    description="Content of the post",
     *                    example="Updated content of the post."
     *                )
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Post updated successfully",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="status",
     *                type="string",
     *                example="success"
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Post Updated Successfully"
     *            ),
     *            @OA\Property(
     *                property="data",
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Post not found",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="status",
     *                type="string",
     *                example="error"
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Post not found"
     *            )
     *        )
     *    )
     * )
     */
    public function update(PostRequest $request, string $id)
    {
        // $post = new PostResource(Post::find($id));
        // if (!$post) {
        //     // return response()->json(['message' => 'Post not found'], 404);
        //     return $this->errorResponse('Post not found', 404);
        // }
        // $post->update($request->validated());
        // // return [
        // //     'message' => 'Post Updated Successfully',
        // //     'data' => response()->json($post)
        // // ];
        // return $this->successResponse($post, 'Post Updated Successfully');

        $data = $request->validated();

        $postReference = $this->database->getReference('posts/' . $id);
        $postReference->update($data);

        $updatedData = $postReference->getValue();

        $post = $updatedData;

        return $this->successResponse(new PostResource($post), __('messages.post_updated_successfully'));
    }

    
    // /**
    //  * @group Post Management
    //  *
    //  * Delete a specific post.
    //  *
    //  * @authenticated
    //  *
    //  * @urlParam id integer required The ID of the post to delete. Example: 1

    //  *
    //  * @response 200 {
    //  *   "message": "Post deleted successfully",
    //  *   "data": {
    //  *     "id": 1,
    //  *     "title": "My First Post",
    //  *     "content": "This is the content of my first post.",
    //  *     "user_id": 1
    //  *   }
    //  * }
    //  *
    //  * @response 404 {
    //  *   "error": "Post not found"
    //  * }
    //  *
    //  * @responseField $.message string A message indicating the result of the deletion.
    //  * @responseField $.data.id integer The ID of the deleted post.
    //  * @responseField $.data.title string The title of the deleted post.
    //  * @responseField $.data.content string The content of the deleted post.
    //  * @responseField $.data.user_id integer The ID of the user who created the deleted post.
    //  */
    /**
     * @OA\Delete(
     *   path="/posts/{id}",
     *  summary="Delete post by id",
     * description="Delete a post by id",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     *   name="id",
     * in="path",
     * required=true,
     * description="ID of the post",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Post deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="status",
     * type="string",
     * example="success"
     * ),
     * @OA\Property(
     * property="message",
     * type="string",
     * example="Post deleted successfully"
     * ),
     * @OA\Property(
     * property="data",
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Post not found",
     * @OA\JsonContent(
     * @OA\Property(
     * property="status",
     * type="string",
     * example="error"
     * ),
     * @OA\Property(
     * property="message",
     * type="string",
     * example="Post not found"
     * )
     * )
     * )
     * )
     */
    public function destroy(string $id)
    {
        // $post = Post::find($id);
        // //  new PostResource();
        // if (!$post) {
        //     // return response()->json(['message' => 'Post not found'], 404);
        //     return $this->errorResponse('Post not found', 404);
        // }
        // $post->delete();
        // // return [
        // //     'message' => 'Post deleted successfully',
        // //     'data' => response()->json($post)
        // // ];
        // return $this->successResponse(new PostResource($post), 'Post deleted successfully');

        $postKey = $this->database->getReference('posts/'.$id)->remove();
        return $this->successResponse(['id' => $postKey->getKey()], __('messages.post_deleted_successfully'));
    }
}
