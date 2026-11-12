<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS programs (
 *     program_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     program_type VARCHAR(16) NOT NULL
 *         CHECK (program_type IN ('inbound', 'outbound')),
 *     program_name VARCHAR(255) NOT NULL,
 *     start_at TIMESTAMPTZ NOT NULL,
 *     end_at TIMESTAMPTZ NOT NULL,
 *     deleted_at TIMESTAMPTZ
 * )
 */
final class Program
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
                "SELECT program_id, program_type, program_name, start_at, end_at
                FROM programs
                WHERE deleted_at IS NULL
                ORDER BY start_at",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT program_id, program_type, program_name, start_at, end_at
            FROM programs
            WHERE program_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(
        string $type,
        string $name,
        string $startAt,
        string $endAt,
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO programs (program_type, program_name, start_at, end_at)
            VALUES (?, ?, ?, ?)",
        );
        $stmt->execute([$type, $name, $startAt, $endAt]);
        return $stmt->rowCount();
    }

    public function update(
        string $id,
        string $type,
        string $name,
        string $startAt,
        string $endAt,
    ): int {
        $stmt = $this->db->prepare(
            "UPDATE programs
            SET program_type = ?, program_name = ?, start_at = ?, end_at = ?
            WHERE program_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$type, $name, $startAt, $endAt, $id]);
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $stmt = $this->db->prepare(
            "UPDATE programs SET deleted_at = NOW()
            WHERE program_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
