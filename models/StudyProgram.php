<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS study_programs (
 *     study_program_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     department_id UUID REFERENCES departments(department_id) ON DELETE CASCADE,
 *     study_program_name VARCHAR(128),
 *     deleted_at TIMESTAMPTZ
 * );
 *
 * CREATE UNIQUE INDEX IF NOT EXISTS study_programs_name_department_unique
 *     ON study_programs (study_program_name, department_id)
 *     WHERE deleted_at IS NULL
 */
final class StudyProgram
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
                "SELECT study_program_id, department_name, study_program_name
                FROM study_programs
                JOIN departments USING (department_id)
                WHERE study_programs.deleted_at IS NULL
                    AND departments.deleted_at IS NULL
                ORDER BY study_program_name",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT study_program_id, department_name, study_program_name
            FROM study_programs
            JOIN departments USING (department_id)
            WHERE study_program_id = ? AND study_programs.deleted_at IS NULL
                AND departments.deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $department_id, string $name): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO study_programs (department_id, study_program_name) VALUES (?, ?)",
        );
        $stmt->execute([$department_id, $name]);
        return $stmt->rowCount();
    }

    public function update(string $id, string $department_id, string $name): int
    {
        $stmt = $this->db->prepare(
            "UPDATE study_programs SET department_id=?, study_program_name=?
            WHERE study_program_id=? AND deleted_at IS NULL",
        );
        $stmt->execute([$department_id, $name, $id]);
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $stmt = $this->db->prepare(
            "UPDATE study_programs SET deleted_at = NOW()
            WHERE study_program_id=? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
