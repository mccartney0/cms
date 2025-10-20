<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Str;
use App\Models\Category;
use App\Models\Post;

class PostController extends Controller
{
    private Post $posts;
    private Category $categories;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->posts = new Post();
        $this->categories = new Category();
    }

    public function index(): void
    {
        $posts = $this->posts->all('', [], 'created_at DESC');
        $this->view('admin/posts/index', [
            'posts' => $posts,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/posts/form', [
            'post' => null,
            'categories' => $this->categories->allOrdered(),
            'selectedCategories' => [],
        ]);
    }

    public function store(): void
    {
        $data = $this->sanitizeInput();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('admin/posts/form', [
                'errors' => $errors,
                'post' => $data,
                'categories' => $this->categories->allOrdered(),
                'selectedCategories' => $this->request->input('categories', []),
            ]);
            return;
        }

        $data['slug'] = $this->uniqueSlug($data['slug']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = $data['created_at'];
        $data['published_at'] = $this->publishedAt($data['status'], $this->request->input('published_at'));

        $postId = $this->posts->create($data);
        $this->posts->syncCategories($postId, (array) $this->request->input('categories', []));

        $this->redirect('/admin/posts');
    }

    public function edit(string $id): void
    {
        $post = $this->posts->find((int) $id);

        if (!$post) {
            $this->view('errors.404', ['title' => 'Post não encontrado'], 404);
            return;
        }

        $this->view('admin/posts/form', [
            'post' => $post,
            'categories' => $this->categories->allOrdered(),
            'selectedCategories' => array_column($this->posts->categories((int) $id), 'id'),
        ]);
    }

    public function update(string $id): void
    {
        $post = $this->posts->find((int) $id);

        if (!$post) {
            $this->view('errors.404', ['title' => 'Post não encontrado'], 404);
            return;
        }

        $data = $this->sanitizeInput();
        $errors = $this->validate($data, (int) $id);

        if ($errors) {
            $this->view('admin/posts/form', [
                'errors' => $errors,
                'post' => array_merge($post, $data),
                'categories' => $this->categories->allOrdered(),
                'selectedCategories' => $this->request->input('categories', []),
            ]);
            return;
        }

        $data['slug'] = $this->uniqueSlug($data['slug'], (int) $id);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['published_at'] = $this->publishedAt($data['status'], $this->request->input('published_at'), $post['published_at']);

        $this->posts->update((int) $id, $data);
        $this->posts->syncCategories((int) $id, (array) $this->request->input('categories', []));

        $this->redirect('/admin/posts');
    }

    public function destroy(string $id): void
    {
        $this->posts->delete((int) $id);
        $this->redirect('/admin/posts');
    }

    private function sanitizeInput(): array
    {
        $title = trim((string) $this->request->input('title', ''));
        $slug = trim((string) $this->request->input('slug', ''));

        return [
            'title' => $title,
            'slug' => $slug ?: Str::slug($title),
            'excerpt' => trim((string) $this->request->input('excerpt', '')),
            'body' => trim((string) $this->request->input('body', '')),
            'meta_title' => trim((string) $this->request->input('meta_title', '')),
            'meta_description' => trim((string) $this->request->input('meta_description', '')),
            'status' => in_array($this->request->input('status'), ['draft', 'published'], true) ? $this->request->input('status') : 'draft',
        ];
    }

    private function validate(array $data, ?int $ignoreId = null): array
    {
        $errors = [];

        if (!$data['title']) {
            $errors[] = 'O título é obrigatório.';
        }

        if (!$data['body']) {
            $errors[] = 'O conteúdo é obrigatório.';
        }

        if ($existing = $this->posts->firstWhere('slug', $data['slug'])) {
            if ($ignoreId === null || (int) $existing['id'] !== $ignoreId) {
                $errors[] = 'Este slug já está em uso. Informe outro valor.';
            }
        }

        return $errors;
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $counter = 1;

        while ($existing = $this->posts->firstWhere('slug', $slug)) {
            if ($ignoreId !== null && (int) $existing['id'] === $ignoreId) {
                break;
            }

            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function publishedAt(string $status, ?string $input, ?string $current = null): ?string
    {
        if ($status !== 'published') {
            return null;
        }

        $value = $input ? $this->formatDateTime($input) : ($current ?: date('Y-m-d H:i:s'));

        return $value ?: date('Y-m-d H:i:s');
    }

    private function formatDateTime(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $value = str_replace('T', ' ', $value);

        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $value)) {
            $value .= ':00';
        }

        return $value;
    }
}
