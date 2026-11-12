<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS tickets (
 *     ticket_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     mail VARCHAR(255) NOT NULL,
 *     question TEXT NOT NULL,
 *     answer TEXT,
 *     deleted_at TIMESTAMPTZ
 * )
 */
final class Ticket
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
                "SELECT ticket_id, mail, question, answer
                FROM tickets
                WHERE deleted_at IS NULL
                ORDER BY ticket_id",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT ticket_id, mail, question, answer
            FROM tickets
            WHERE ticket_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(
        string $mail,
        string $question,
        ?string $answer = null,
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO tickets (mail, question, answer) VALUES (?, ?, ?)",
        );
        $stmt->execute([$mail, $question, $answer]);
        return $stmt->rowCount();
    }

    public function update(
        string $id,
        string $mail,
        string $question,
        ?string $answer,
    ): int {
        $stmt = $this->db->prepare(
            "UPDATE tickets SET mail = ?, question = ?, answer = ?
            WHERE ticket_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$mail, $question, $answer, $id]);
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $this->db->beginTransaction();

        try {
            $this->db
                ->prepare(
                    "UPDATE ticket_media SET deleted_at = NOW()
                    WHERE ticket_id = ? AND deleted_at IS NULL",
                )
                ->execute([$id]);

            $stmt = $this->db->prepare(
                "UPDATE tickets SET deleted_at = NOW()
                WHERE ticket_id = ? AND deleted_at IS NULL",
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
