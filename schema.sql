-- SiTIO database schema
-- Extracted from models/*.php

BEGIN;

CREATE TABLE IF NOT EXISTS departments (
    department_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    department_name VARCHAR(128),
    deleted_at TIMESTAMPTZ
);

CREATE UNIQUE INDEX IF NOT EXISTS departments_name_unique
    ON departments (department_name)
    WHERE deleted_at IS NULL;

CREATE TABLE IF NOT EXISTS study_programs (
    study_program_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    department_id UUID
        REFERENCES departments(department_id) ON DELETE CASCADE,
    study_program_name VARCHAR(128),
    deleted_at TIMESTAMPTZ
);

CREATE UNIQUE INDEX IF NOT EXISTS study_programs_name_department_unique
    ON study_programs (study_program_name, department_id)
    WHERE deleted_at IS NULL;

CREATE TABLE IF NOT EXISTS programs (
    program_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    program_type VARCHAR(16) NOT NULL
        CHECK (program_type IN ('inbound', 'outbound')),
    program_name VARCHAR(255) NOT NULL,
    start_at TIMESTAMPTZ NOT NULL,
    end_at TIMESTAMPTZ NOT NULL,
    deleted_at TIMESTAMPTZ
);

CREATE TABLE IF NOT EXISTS document_types (
    document_type_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    document_type_name VARCHAR(128) NOT NULL,
    document_type_code VARCHAR(128) NOT NULL,
    is_required BOOLEAN NOT NULL DEFAULT FALSE,
    max_size INTEGER NOT NULL,
    deleted_at TIMESTAMPTZ
);

CREATE UNIQUE INDEX IF NOT EXISTS document_types_code_unique
    ON document_types (document_type_code)
    WHERE deleted_at IS NULL;

CREATE TABLE IF NOT EXISTS faqs (
    faq_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    question VARCHAR(255),
    answer TEXT
);

CREATE TABLE IF NOT EXISTS tickets (
    ticket_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    mail VARCHAR(255) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT,
    deleted_at TIMESTAMPTZ
);

CREATE TABLE IF NOT EXISTS ticket_media (
    ticket_id UUID NOT NULL
        REFERENCES tickets(ticket_id) ON DELETE CASCADE,
    type VARCHAR(16) NOT NULL
        CHECK (type IN ('image', 'pdf')),
    path TEXT NOT NULL,
    deleted_at TIMESTAMPTZ,
    PRIMARY KEY (ticket_id, path)
);

CREATE TABLE IF NOT EXISTS users (
    user_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(32) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(16) NOT NULL
        CHECK (role IN ('admin', 'struktural', 'dosen', 'mahasiswa')),
    address TEXT NOT NULL,
    birth_date DATE NOT NULL,
    birth_location VARCHAR(128) NOT NULL,
    position VARCHAR(128),
    department_id UUID
        REFERENCES departments(department_id) ON DELETE CASCADE,
    study_program_id UUID
        REFERENCES study_programs(study_program_id) ON DELETE CASCADE,
    admission_date DATE,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    deleted_at TIMESTAMPTZ,
    CONSTRAINT users_role_fields_check CHECK (
        (role = 'admin'
            AND position IS NULL
            AND department_id IS NULL
            AND study_program_id IS NULL
            AND admission_date IS NULL)
        OR (role = 'struktural'
            AND position IS NOT NULL
            AND department_id IS NULL
            AND study_program_id IS NULL
            AND admission_date IS NULL)
        OR (role = 'dosen'
            AND position IS NULL
            AND department_id IS NOT NULL
            AND study_program_id IS NULL
            AND admission_date IS NULL)
        OR (role = 'mahasiswa'
            AND position IS NULL
            AND department_id IS NOT NULL
            AND study_program_id IS NOT NULL
            AND admission_date IS NOT NULL)
    )
);

CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique
    ON users (email)
    WHERE deleted_at IS NULL;

CREATE UNIQUE INDEX IF NOT EXISTS users_phone_unique
    ON users (phone)
    WHERE deleted_at IS NULL;

CREATE TABLE IF NOT EXISTS user_legal (
    user_id UUID PRIMARY KEY
        REFERENCES users(user_id) ON DELETE CASCADE,
    nik VARCHAR(16),
    nim VARCHAR(32),
    nip VARCHAR(32),
    paspor_num VARCHAR(32),
    ktp TEXT,
    paspor TEXT,
    deleted_at TIMESTAMPTZ
);

COMMIT;
