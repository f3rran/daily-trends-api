<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FeedRepository;

/**
 * @OA\Tag(
 *     name="Feeds",
 *     description="Feed model controller"
 * )
 */
class FeedController extends Controller
{
    protected $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/feeds",
     *     summary="Get all the articles",
     *     tags={"Feeds"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de feeds obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Article"),
     *                 @OA\Property(property="content", type="string", example="Content of the article"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-19T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-19T12:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $feeds = $this->feedRepository->listAll();

        return response()->json($feeds, 200);
    }

     /**
     * @OA\Post(
     *     path="/api/feeds",
     *     summary="Store article",
     *     tags={"Feeds"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", example="title"),
     *             @OA\Property(property="content", type="string", example="body of the article")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Nuevo artículo"),
     *             @OA\Property(property="content", type="string", example="Contenido del artículo"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-19T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-19T12:00:00Z")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|min:10',
            'source' => 'required|string|in:ElPais,ElMundo,Manual',
        ]);

        $feed = $this->feedRepository->store($validation);

        return response()->json([
            'message' => 'Article created',
            'data' => $feed,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/feeds/{id}",
     *     summary="Get an article by id",
     *     tags={"Feeds"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the feed",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Feed encontrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Feed example"),
     *             @OA\Property(property="content", type="string", example="Content of the feed"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-19T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-19T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function show($id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($feed, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/feeds/{id}",
     *     summary="Update article",
     *     tags={"Feeds"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the feed",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", example="Updated article title"),
     *             @OA\Property(property="content", type="string", example="Contenido")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Article updated title"),
     *             @OA\Property(property="content", type="string", example="Contenido updated"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-19T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-19T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $validation = $request->validate([
            'title' => 'string|max:255|min:3',
            'content' => 'string|min:10',
            'source' => 'string',
        ]);

        $updatedFeed = $this->feedRepository->update($feed, $validation);

        return response()->json([
            'message' => 'Article updated',
            'data' => $updatedFeed,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/feeds/{id}",
     *     summary="Delete article",
     *     tags={"Feeds"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the feed",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $this->feedRepository->delete($feed);

        return response()->json(['message' => 'Article deleted'], 200);
    }
}
