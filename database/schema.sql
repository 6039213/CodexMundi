-- Schema for Codex Mundi
CREATE DATABASE IF NOT EXISTS `codex_mundi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `codex_mundi`;

CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(190) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	role ENUM('visitor','researcher','editor','archivist','admin') NOT NULL DEFAULT 'visitor',
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	INDEX(role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wonders (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	slug VARCHAR(190) NOT NULL UNIQUE,
	title VARCHAR(190) NOT NULL,
	country VARCHAR(120) NOT NULL,
	summary TEXT,
	description LONGTEXT,
	myth LONGTEXT,
	category ENUM('classic','modern','natural') NOT NULL,
	continent ENUM('africa','asia','europe','north_america','south_america','oceania','antarctica') NOT NULL,
	year_built INT NULL,
	exists_now TINYINT(1) NOT NULL DEFAULT 1,
	status ENUM('draft','pending','approved') NOT NULL DEFAULT 'approved',
	lat DECIMAL(9,6) NULL,
	lng DECIMAL(9,6) NULL,
	created_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	INDEX(slug),
	INDEX(continent),
	INDEX(category),
	INDEX(status),
	CONSTRAINT fk_wonders_users FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS media (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	wonder_id INT UNSIGNED NOT NULL,
	type ENUM('image','document') NOT NULL,
	url VARCHAR(255) NOT NULL,
	mime VARCHAR(100) NOT NULL,
	size INT UNSIGNED NOT NULL DEFAULT 0,
	status ENUM('pending','approved') NOT NULL DEFAULT 'approved',
	created_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	INDEX(wonder_id),
	CONSTRAINT fk_media_wonder FOREIGN KEY (wonder_id) REFERENCES wonders(id) ON DELETE CASCADE,
	CONSTRAINT fk_media_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tags (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wonder_tags (
	wonder_id INT UNSIGNED NOT NULL,
	tag_id INT UNSIGNED NOT NULL,
	added_by INT UNSIGNED NULL,
	PRIMARY KEY(wonder_id, tag_id),
	CONSTRAINT fk_wt_wonder FOREIGN KEY (wonder_id) REFERENCES wonders(id) ON DELETE CASCADE,
	CONSTRAINT fk_wt_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
	CONSTRAINT fk_wt_user FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_log (
	id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	actor_id INT UNSIGNED NULL,
	entity VARCHAR(50) NOT NULL,
	entity_id INT UNSIGNED NOT NULL,
	action VARCHAR(50) NOT NULL,
	changes JSON NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	INDEX(entity),
	CONSTRAINT fk_audit_user FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
