-- Drop all SiTIO tables in dependency order.

-- Clear any aborted transaction left by a previous failed run.
ROLLBACK;

BEGIN;

DROP TABLE IF EXISTS user_legal;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS ticket_media;
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS faqs;
DROP TABLE IF EXISTS document_types;
DROP TABLE IF EXISTS programs;
DROP TABLE IF EXISTS study_programs;
DROP TABLE IF EXISTS departments;

COMMIT;
