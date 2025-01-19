<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FeedRepository;

class FeedController extends Controller
{
    protected $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function index()
    {
        $feeds = $this->feedRepository->listAll();

        return response()->json($feeds, 200);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'source' => 'required|string|in:ElPais,ElMundo,Manual',
        ]);

        $feed = $this->feedRepository->store($validation);

        return response()->json([
            'message' => 'Article created',
            'data' => $feed,
        ], 201);
    }

    public function show($id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($feed, 200);
    }

    public function update(Request $request, $id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $validation = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'source' => 'string',
        ]);

        $updatedFeed = $this->feedRepository->update($feed, $validation);

        return response()->json([
            'message' => 'Article updated',
            'data' => $updatedFeed,
        ], 200);
    }

    public function destroy($id)
    {
        $feed = $this->feedRepository->findById($id);

        if (!$feed) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $this->feedRepository->delete($feed);

        return response()->json(['message' => 'Articlke deleted'], 200);
    }
}
