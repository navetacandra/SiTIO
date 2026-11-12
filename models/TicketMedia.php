<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS ticket_media (
 *     ticket_id UUID NOT NULL
 *         REFERENCES tickets(ticket_id) ON DELETE CASCADE,
 *     type VARCHAR(16) NOT NULL CHECK (type IN ('image', 'pdf')),
 *     path TEXT NOT NULL,
 *     deleted_at TIMESTAMPTZ,
 *     PRIMARY KEY (ticket_id, path)
 * )
 */
final class TicketMedia
{
    private PDO $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function all(string $ticketId): array
    {
        $stmt = $this->db->prepare(
            "SELECT ticket_id, type, path
            FROM ticket_media
            WHERE ticket_id = ? AND deleted_at IS NULL
            ORDER BY path",
        );
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }

    public function find(string $ticketId, string $path): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT ticket_id, type, path
            FROM ticket_media
            WHERE ticket_id = ? AND path = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$ticketId, $path]);
        return $stmt->fetch();
    }

    public function create(string $ticketId, string $type, string $path): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO ticket_media (ticket_id, type, path) VALUES (?, ?, ?)",
        );
        $stmt->execute([$ticketId, $type, $path]);
        return $stmt->rowCount();
    }

    public function update(
        string $ticketId,
        string $path,
        string $type,
        string $newPath,
    ): int {
        $stmt = $this->db->prepare(
            "UPDATE ticket_media SET type = ?, path = ?
            WHERE ticket_id = ? AND path = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$type, $newPath, $ticketId, $path]);
        return $stmt->rowCount();
    }

    public function delete(string $ticketId, string $path): int
    {
        $stmt = $this->db->prepare(
            "UPDATE ticket_media SET deleted_at = NOW()
            WHERE ticket_id = ? AND path = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$ticketId, $path]);
        return $stmt->rowCount();
    }
}
