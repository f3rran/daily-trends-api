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
        //Validation
        if (strlen($data['title']) < 3 || strlen($data['content']) < 10) {
            return null;
        }

        return Feed::create($data);
    }

    public function update(Feed $feed, array $data)
    {
        //Validation
        if (strlen($data['title']) < 3 || strlen($data['content']) < 10) {
            return null;
        }

        $feed->update($data);
        return $feed;
    }

    public function delete(Feed $feed)
    {
        return $feed->delete();
    }
}
