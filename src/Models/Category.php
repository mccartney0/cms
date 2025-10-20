<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';
    protected array $fillable = [
        'name',
        'slug',
        'created_at',
        'updated_at',
    ];

    public function allOrdered(): array
    {
        return $this->all(order: 'name ASC');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->firstWhere('slug', $slug);
    }
}
