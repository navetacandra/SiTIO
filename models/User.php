<?php declare(strict_types=1);

/**
 * -- Schema
 * CREATE TABLE IF NOT EXISTS users (
 *     user_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
 *     email VARCHAR(255) NOT NULL,
 *     phone VARCHAR(32) NOT NULL,
 *     password VARCHAR(255) NOT NULL,
 *     role VARCHAR(16) NOT NULL
 *         CHECK (role IN ('admin', 'struktural', 'dosen', 'mahasiswa')),
 *     address TEXT NOT NULL,
 *     birth_date DATE NOT NULL,
 *     birth_location VARCHAR(128) NOT NULL,
 *     position VARCHAR(128),
 *     department_id UUID
 *         REFERENCES departments(department_id) ON DELETE CASCADE,
 *     study_program_id UUID
 *         REFERENCES study_programs(study_program_id) ON DELETE CASCADE,
 *     admission_date DATE,
 *     is_active BOOLEAN NOT NULL DEFAULT FALSE,
 *     deleted_at TIMESTAMPTZ,
 *     CONSTRAINT users_role_fields_check CHECK (
 *         (role = 'admin'
 *             AND position IS NULL
 *             AND department_id IS NULL
 *             AND study_program_id IS NULL
 *             AND admission_date IS NULL)
 *         OR (role = 'struktural'
 *             AND position IS NOT NULL
 *             AND department_id IS NULL
 *             AND study_program_id IS NULL
 *             AND admission_date IS NULL)
 *         OR (role = 'dosen'
 *             AND position IS NULL
 *             AND department_id IS NOT NULL
 *             AND study_program_id IS NULL
 *             AND admission_date IS NULL)
 *         OR (role = 'mahasiswa'
 *             AND position IS NULL
 *             AND department_id IS NOT NULL
 *             AND study_program_id IS NOT NULL
 *             AND admission_date IS NOT NULL)
 *     )
 * );
 *
 * CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique
 *     ON users (email)
 *     WHERE deleted_at IS NULL;
 *
 * CREATE UNIQUE INDEX IF NOT EXISTS users_phone_unique
 *     ON users (phone)
 *     WHERE deleted_at IS NULL
 */
final class User
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
                "SELECT user_id, email, phone, role, address, birth_date,
                    birth_location, position, department_id, study_program_id,
                    admission_date, is_active
                FROM users
                WHERE deleted_at IS NULL
                ORDER BY COALESCE(email, phone)",
            )
            ->fetchAll();
    }

    public function find(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT user_id, email, phone, role, address, birth_date,
                birth_location, position, department_id, study_program_id,
                admission_date, is_active
            FROM users
            WHERE user_id = ? AND deleted_at IS NULL",
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update(
        string $id,
        string $email,
        string $phone,
        string $address,
        string $birthDate,
        string $birthLocation,
        ?string $position,
        ?string $departmentId,
        ?string $studyProgramId,
        ?string $admissionDate,
        bool $isActive,
        ?string $password = null,
    ): int {
        $email = $this->normalizeEmail($email);
        $phone = $this->normalizePhone($phone);
        $address = trim($address);
        $birthDate = trim($birthDate);
        $birthLocation = trim($birthLocation);

        if ($address === "" || $birthDate === "" || $birthLocation === "") {
            throw new InvalidArgumentException(
                "Address, birth date, and birth location are required.",
            );
        }

        if ($password !== null && $password === "") {
            $password = null;
        }

        $stmt = $this->db->prepare(
            "UPDATE users
            SET email = ?, phone = ?, address = ?, birth_date = ?,
                birth_location = ?, position = ?, department_id = ?,
                study_program_id = ?, admission_date = ?, is_active = ?,
                password = COALESCE(?, password)
            WHERE user_id = ? AND deleted_at IS NULL",
        );

        $stmt->bindValue(1, $email);
        $stmt->bindValue(2, $phone);
        $stmt->bindValue(3, $address);
        $stmt->bindValue(4, $birthDate);
        $stmt->bindValue(5, $birthLocation);
        $this->bindNullable($stmt, 6, $position);
        $this->bindNullable($stmt, 7, $departmentId);
        $this->bindNullable($stmt, 8, $studyProgramId);
        $this->bindNullable($stmt, 9, $admissionDate);
        $stmt->bindValue(10, $isActive, PDO::PARAM_BOOL);
        $this->bindNullable(
            $stmt,
            11,
            $password === null
                ? null
                : password_hash($password, PASSWORD_BCRYPT),
        );
        $stmt->bindValue(12, $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function createStruktural(
        string $email,
        string $phone,
        string $password,
        string $position,
        string $address,
        string $birthDate,
        string $birthLocation,
    ): string {
        return $this->createBase(
            "struktural",
            $email,
            $phone,
            $password,
            $address,
            $birthDate,
            $birthLocation,
            $position,
            null,
            null,
            null,
        );
    }

    public function createDosen(
        string $email,
        string $phone,
        string $password,
        string $departmentId,
        string $address,
        string $birthDate,
        string $birthLocation,
    ): string {
        return $this->createBase(
            "dosen",
            $email,
            $phone,
            $password,
            $address,
            $birthDate,
            $birthLocation,
            null,
            $departmentId,
            null,
            null,
        );
    }

    public function createMahasiswa(
        string $email,
        string $phone,
        string $password,
        string $departmentId,
        string $studyProgramId,
        string $admissionDate,
        string $address,
        string $birthDate,
        string $birthLocation,
    ): string {
        return $this->createBase(
            "mahasiswa",
            $email,
            $phone,
            $password,
            $address,
            $birthDate,
            $birthLocation,
            null,
            $departmentId,
            $studyProgramId,
            $admissionDate,
        );
    }

    public function login(string $emailOrPhone, string $password): array|false
    {
        $identifier = trim($emailOrPhone);
        $stmt = $this->db->prepare(
            "SELECT user_id, password FROM users
            WHERE (LOWER(email) = ? OR phone = ?)
                AND deleted_at IS NULL AND is_active = TRUE
            LIMIT 1",
        );
        $stmt->execute([strtolower($identifier), $identifier]);
        $user = $stmt->fetch();

        if ($user === false || !password_verify($password, $user["password"])) {
            return false;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["user_id"];

        return $this->find($user["user_id"]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION["user_id"]);
        session_regenerate_id(true);
    }

    public function setActive(string $id, bool $isActive): int
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET is_active = ?
            WHERE user_id = ? AND deleted_at IS NULL",
        );
        $stmt->bindValue(1, $isActive, PDO::PARAM_BOOL);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $this->db->beginTransaction();

        try {
            $this->db
                ->prepare(
                    "UPDATE user_legal SET deleted_at = NOW()
                    WHERE user_id = ? AND deleted_at IS NULL",
                )
                ->execute([$id]);

            $stmt = $this->db->prepare(
                "UPDATE users SET deleted_at = NOW()
                WHERE user_id = ? AND deleted_at IS NULL",
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

    private function createBase(
        string $role,
        string $email,
        string $phone,
        string $password,
        string $address,
        string $birthDate,
        string $birthLocation,
        ?string $position,
        ?string $departmentId,
        ?string $studyProgramId,
        ?string $admissionDate,
    ): string {
        $email = $this->normalizeEmail($email);
        $phone = $this->normalizePhone($phone);
        $address = trim($address);
        $birthDate = trim($birthDate);
        $birthLocation = trim($birthLocation);

        if ($address === "" || $birthDate === "" || $birthLocation === "") {
            throw new InvalidArgumentException(
                "Address, birth date, and birth location are required.",
            );
        }

        if ($password === "") {
            throw new InvalidArgumentException("Password is required.");
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO users
                    (email, phone, password, role, address, birth_date,
                        birth_location, position, department_id,
                        study_program_id, admission_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                RETURNING user_id",
            );
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $phone);
            $stmt->bindValue(3, password_hash($password, PASSWORD_BCRYPT));
            $stmt->bindValue(4, $role);
            $stmt->bindValue(5, $address);
            $stmt->bindValue(6, $birthDate);
            $stmt->bindValue(7, $birthLocation);
            $this->bindNullable($stmt, 8, $position);
            $this->bindNullable($stmt, 9, $departmentId);
            $this->bindNullable($stmt, 10, $studyProgramId);
            $this->bindNullable($stmt, 11, $admissionDate);
            $stmt->execute();

            $userId = (string) $stmt->fetchColumn();
            $this->db
                ->prepare("INSERT INTO user_legal (user_id) VALUES (?)")
                ->execute([$userId]);

            $this->db->commit();
            return $userId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function normalizeEmail(string $email): string
    {
        $email = strtolower(trim($email));

        if ($email === "") {
            throw new InvalidArgumentException("Email is required.");
        }

        return $email;
    }

    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        if ($phone === "") {
            throw new InvalidArgumentException("Phone is required.");
        }

        return $phone;
    }

    private function bindNullable(
        PDOStatement $stmt,
        int $parameter,
        ?string $value,
    ): void {
        $stmt->bindValue(
            $parameter,
            $value,
            $value === null ? PDO::PARAM_NULL : PDO::PARAM_STR,
        );
    }
}
