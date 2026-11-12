<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS document_types (
 *     document_type_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     document_type_name VARCHAR(128) NOT NULL,
 *     document_type_code VARCHAR(128) NOT NULL,
 *     is_required BOOLEAN NOT NULL DEFAULT FALSE,
 *     max_size INTEGER NOT NULL,
 *     deleted_at TIMESTAMPTZ
 * );
 *
 * CREATE UNIQUE INDEX IF NOT EXISTS document_types_code_unique
 *     ON document_types (document_type_code)
 *     WHERE deleted_at IS NULL
 */
final class DocumentType
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
                "SELECT document_type_id, document_type_name, document_type_code,
                    is_required, max_size
                FROM document_types
                WHERE deleted_at IS NULL
                ORDER BY document_type_name",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT document_type_id, document_type_name, document_type_code,
                is_required, max_size
            FROM document_types
            WHERE document_type_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $name, bool $isRequired, int $maxSize): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO document_types
                (document_type_name, document_type_code, is_required, max_size)
            VALUES (?, ?, ?, ?)",
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $this->generateCode($name));
        $stmt->bindValue(3, $isRequired, PDO::PARAM_BOOL);
        $stmt->bindValue(4, $maxSize, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function update(
        string $id,
        string $name,
        bool $isRequired,
        int $maxSize,
    ): int {
        $stmt = $this->db->prepare(
            "UPDATE document_types
            SET document_type_name = ?, document_type_code = ?, is_required = ?,
                max_size = ?
            WHERE document_type_id = ? AND deleted_at IS NULL",
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $this->generateCode($name));
        $stmt->bindValue(3, $isRequired, PDO::PARAM_BOOL);
        $stmt->bindValue(4, $maxSize, PDO::PARAM_INT);
        $stmt->bindValue(5, $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $stmt = $this->db->prepare(
            "UPDATE document_types SET deleted_at = NOW()
            WHERE document_type_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    private function generateCode(string $name): string
    {
        $code = strtolower(trim($name));
        $code = preg_replace("/[^a-z0-9]+/", "_", $code) ?? "";
        return trim($code, "_");
    }
}
