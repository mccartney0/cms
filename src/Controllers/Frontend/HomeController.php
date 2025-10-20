<?php

namespace App\Controllers\Frontend;

use App\Core\Config;
use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;

class HomeController extends Controller
{
    public function index(): void
    {
        $postModel = new Post();
        $categoryModel = new Category();

        $posts = $postModel->published(6);
        $categories = $categoryModel->allOrdered();

        $this->view('home', [
            'posts' => $posts,
            'categories' => $categories,
            'meta' => [
                'title' => Config::get('meta.title'),
                'description' => Config::get('meta.description'),
            ],
        ]);
    }
}
