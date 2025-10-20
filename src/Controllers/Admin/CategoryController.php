<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    private Category $categories;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->categories = new Category();
    }

    public function index(): void
    {
        $this->view('admin/categories/index', [
            'categories' => $this->categories->allOrdered(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/categories/form', [
            'category' => null,
        ]);
    }

    public function store(): void
    {
        $data = $this->sanitize();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('admin/categories/form', [
                'errors' => $errors,
                'category' => $data,
            ]);
            return;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = $data['created_at'];

        $this->categories->create($data);

        $this->redirect('/admin/categories');
    }

    public function edit(string $id): void
    {
        $category = $this->categories->find((int) $id);

        if (!$category) {
            $this->view('errors.404', ['title' => 'Categoria não encontrada'], 404);
            return;
        }

        $this->view('admin/categories/form', [
            'category' => $category,
        ]);
    }

    public function update(string $id): void
    {
        $category = $this->categories->find((int) $id);

        if (!$category) {
            $this->view('errors.404', ['title' => 'Categoria não encontrada'], 404);
            return;
        }

        $data = $this->sanitize();
        $errors = $this->validate($data, (int) $id);

        if ($errors) {
            $this->view('admin/categories/form', [
                'errors' => $errors,
                'category' => array_merge($category, $data),
            ]);
            return;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->categories->update((int) $id, $data);

        $this->redirect('/admin/categories');
    }

    public function destroy(string $id): void
    {
        $this->categories->delete((int) $id);
        $this->redirect('/admin/categories');
    }

    private function sanitize(): array
    {
        $name = trim((string) $this->request->input('name', ''));
        $slug = trim((string) $this->request->input('slug', ''));

        return [
            'name' => $name,
            'slug' => $slug ?: Str::slug($name),
        ];
    }

    private function validate(array $data, ?int $ignoreId = null): array
    {
        $errors = [];

        if (!$data['name']) {
            $errors[] = 'O nome é obrigatório.';
        }

        if ($existing = $this->categories->firstWhere('slug', $data['slug'])) {
            if ($ignoreId === null || (int) $existing['id'] !== $ignoreId) {
                $errors[] = 'Este slug já está em uso.';
            }
        }

        return $errors;
    }
}
