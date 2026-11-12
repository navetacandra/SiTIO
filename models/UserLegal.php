<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS user_legal (
 *     user_id UUID PRIMARY KEY
 *         REFERENCES users(user_id) ON DELETE CASCADE,
 *     nik VARCHAR(16),
 *     nim VARCHAR(32),
 *     nip VARCHAR(32),
 *     paspor_num VARCHAR(32),
 *     ktp TEXT,
 *     paspor TEXT,
 *     deleted_at TIMESTAMPTZ
 * )
 */
final class UserLegal
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
                "SELECT user_id, nik, nim, nip, paspor_num, ktp, paspor
                FROM user_legal
                WHERE deleted_at IS NULL
                ORDER BY user_id",
            )
            ->fetchAll();
    }

    public function find(string $userId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT user_id, nik, nim, nip, paspor_num, ktp, paspor
            FROM user_legal
            WHERE user_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function update(
        string $userId,
        ?string $nik = null,
        ?string $nim = null,
        ?string $nip = null,
        ?string $pasporNum = null,
        ?string $ktp = null,
        ?string $paspor = null,
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO user_legal
                (user_id, nik, nim, nip, paspor_num, ktp, paspor)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON CONFLICT (user_id) DO UPDATE SET
                nik = EXCLUDED.nik,
                nim = EXCLUDED.nim,
                nip = EXCLUDED.nip,
                paspor_num = EXCLUDED.paspor_num,
                ktp = EXCLUDED.ktp,
                paspor = EXCLUDED.paspor,
                deleted_at = NULL",
        );

        $stmt->bindValue(1, $userId);

        foreach (
            [$nik, $nim, $nip, $pasporNum, $ktp, $paspor]
            as $i => $value
        ) {
            $stmt->bindValue(
                $i + 2,
                $value,
                $value === null ? PDO::PARAM_NULL : PDO::PARAM_STR,
            );
        }

        $stmt->execute();
        return $stmt->rowCount();
    }
}
