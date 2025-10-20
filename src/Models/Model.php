<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected string $table;
    protected array $fillable = [];

    protected function connection(): PDO
    {
        return Database::connection();
    }

    public function all(string $where = '', array $params = [], string $order = 'created_at DESC'): array
    {
        $sql = sprintf('SELECT * FROM %s', $this->table);

        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }

        $stmt = $this->connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->connection()->prepare(sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', $this->table));
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function firstWhere(string $column, mixed $value): ?array
    {
        $stmt = $this->connection()->prepare(sprintf('SELECT * FROM %s WHERE %s = :value LIMIT 1', $this->table, $column));
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(array $attributes): int
    {
        $data = $this->filterFillable($attributes);
        $columns = array_keys($data);
        $placeholders = array_map(fn($column) => ':' . $column, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->connection()->prepare($sql);
        $stmt->execute($data);

        return (int) $this->connection()->lastInsertId();
    }

    public function update(int $id, array $attributes): void
    {
        $data = $this->filterFillable($attributes);
        $columns = array_map(fn($column) => $column . ' = :' . $column, array_keys($data));
        $sql = sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            $this->table,
            implode(', ', $columns)
        );

        $data['id'] = $id;

        $stmt = $this->connection()->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection()->prepare(sprintf('DELETE FROM %s WHERE id = :id', $this->table));
        $stmt->execute(['id' => $id]);
    }

    protected function filterFillable(array $attributes): array
    {
        if (empty($this->fillable)) {
            return $attributes;
        }

        return array_intersect_key($attributes, array_flip($this->fillable));
    }
}
