<?php

namespace App\Controllers\Frontend;

use App\Core\Config;
use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;

class BlogController extends Controller
{
    public function index(): void
    {
        $postModel = new Post();
        $categoryModel = new Category();

        $posts = $postModel->all('status = :status', ['status' => 'published'], 'published_at DESC');
        $categories = $categoryModel->allOrdered();

        $this->view('blog/index', [
            'posts' => $posts,
            'categories' => $categories,
            'meta' => [
                'title' => 'Blog | ' . Config::get('meta.title'),
                'description' => 'Conteúdo otimizado para SEO com as últimas publicações do blog.',
            ],
        ]);
    }

    public function show(string $slug): void
    {
        $postModel = new Post();
        $categoryModel = new Category();

        $post = $postModel->findBySlug($slug);

        if (!$post || $post['status'] !== 'published') {
            $this->view('errors.404', ['title' => 'Conteúdo não encontrado'], 404);
            return;
        }

        $categories = $postModel->categories((int) $post['id']);
        $allCategories = $categoryModel->allOrdered();

        $this->view('blog/show', [
            'post' => $post,
            'categories' => $categories,
            'allCategories' => $allCategories,
            'meta' => [
                'title' => $post['meta_title'] ?: $post['title'],
                'description' => $post['meta_description'] ?: $post['excerpt'],
            ],
        ]);
    }

    public function category(string $slug): void
    {
        $postModel = new Post();
        $categoryModel = new Category();

        $category = $categoryModel->findBySlug($slug);

        if (!$category) {
            $this->view('errors.404', ['title' => 'Categoria não encontrada'], 404);
            return;
        }

        $posts = $postModel->publishedByCategory((int) $category['id']);

        $this->view('blog/index', [
            'posts' => $posts,
            'categories' => $categoryModel->allOrdered(),
            'selectedCategory' => $category,
            'meta' => [
                'title' => sprintf('Categoria %s | %s', $category['name'], Config::get('meta.title')),
                'description' => sprintf('Artigos publicados na categoria %s.', $category['name']),
            ],
        ]);
    }
}
