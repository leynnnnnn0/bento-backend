<?php

class PostController
{
    public function index()
    {
        $db = Application::$app->db;

        // Fetch posts
        $postsQuery = "SELECT 
                      p.id,
                      p.user_id,
                      p.body,
                      p.created_at,                                                                          
                      u.username
                  FROM posts p
                  JOIN users u ON p.user_id = u.id
                  ORDER BY p.created_at DESC";

        $postsStatement = $db->query($postsQuery);
        $posts = $postsStatement->fetchAll(PDO::FETCH_ASSOC);

        // Fetch votes
        $votesQuery = "SELECT 
                      v.post_id,
                      v.likedBy_id,
                      v.vote
                  FROM votes v";

        $votesStatement = $db->query($votesQuery);
        $votes = $votesStatement->fetchAll(PDO::FETCH_ASSOC);

        // Aggregate votes for each post
        $postsWithVotes = [];
        foreach ($posts as $post) {
            $postId = $post['id'];
            $post['votes'] = array_filter($votes, function($vote) use ($postId) {
                return $vote['post_id'] == $postId;
            });
            $postsWithVotes[] = $post;
        }

        return json_encode($postsWithVotes);
    }


    public function store()
    {
        $db = Application::$app->db;
        // Get the raw POST data from the input stream
        $rawData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($rawData, true);
        $query = "INSERT INTO posts (user_id, body) VALUES (:user_id, :body)";
        $statement = $db->query($query, ['user_id' => $data['user_id'], 'body' => $data['body']]);
        return json_encode(['success' => $statement]);
    }
}