<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS departments (
 *     department_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     department_name VARCHAR(128),
 *     deleted_at TIMESTAMPTZ
 * );
 *
 * CREATE UNIQUE INDEX IF NOT EXISTS departments_name_unique
 *     ON departments (department_name)
 *     WHERE deleted_at IS NULL
 */
final class Department
{
    private PDO $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function all(): array
    {
        return $this->db
            ->query(
                "SELECT department_id, department_name FROM departments
                WHERE deleted_at IS NULL
                ORDER BY department_name",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT department_id, department_name FROM departments
            WHERE department_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO departments (department_name) VALUES (?)",
        );
        $stmt->execute([$name]);
        return $stmt->rowCount();
    }

    public function update(string $id, string $name): int
    {
        $stmt = $this->db->prepare(
            "UPDATE departments SET department_name=?
            WHERE department_id=? AND deleted_at IS NULL",
        );
        $stmt->execute([$name, $id]);
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $this->db->beginTransaction();

        try {
            $this->db
                ->prepare(
                    "UPDATE study_programs SET deleted_at = NOW()
                    WHERE department_id = ? AND deleted_at IS NULL",
                )
                ->execute([$id]);

            $stmt = $this->db->prepare(
                "UPDATE departments SET deleted_at = NOW()
                WHERE department_id = ? AND deleted_at IS NULL",
            );
            $stmt->execute([$id]);
            $affected = $stmt->rowCount();

            $this->db->commit();
            return $affected;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
