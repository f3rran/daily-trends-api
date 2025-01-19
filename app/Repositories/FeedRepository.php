<?php

namespace App\Repositories;

use App\Models\Feed;

class FeedRepository
{
    public function listAll()
    {
        return Feed::all();
    }

    public function findById($id)
    {
        return Feed::find($id);
    }

    public function store(array $data)
    {
        return Feed::create($data);
    }

    public function update(Feed $feed, array $data)
    {
        $feed->update($data);
        return $feed;
    }

    public function delete(Feed $feed)
    {
        return $feed->delete();
    }
}
