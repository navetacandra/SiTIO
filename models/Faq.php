<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS faqs (
 *     faq_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     question VARCHAR(255),
 *     answer TEXT
 * )
 */
final class Faq
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
                "SELECT faq_id, question, answer FROM faqs ORDER BY question",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT faq_id, question, answer FROM faqs WHERE faq_id = ?",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $question, string $answer): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO faqs (question, answer) VALUES (?, ?)",
        );
        $stmt->execute([$question, $answer]);
        return $stmt->rowCount();
    }

    public function update(string $id, string $question, string $answer): int
    {
        $stmt = $this->db->prepare(
            "UPDATE faqs SET question=?, answer=? WHERE faq_id=?",
        );
        $stmt->execute([$question, $answer, $id]);
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $stmt = $this->db->prepare("DELETE FROM faqs WHERE faq_id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
