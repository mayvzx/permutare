CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  status ENUM('active', 'blocked', 'deleted') NOT NULL DEFAULT 'active',
  email_verified_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX idx_users_status (status),
  INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  institution VARCHAR(190) NOT NULL,
  course VARCHAR(190) NOT NULL,
  campus VARCHAR(190) NULL,
  bio TEXT NULL,
  avatar_path VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anuncios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  category ENUM('books', 'electronics', 'school_supplies', 'clothing', 'furniture', 'services', 'other') NOT NULL,
  item_condition ENUM('new', 'like_new', 'used_good', 'used_regular', 'needs_repair') NOT NULL,
  desired_item VARCHAR(255) NOT NULL,
  image_path VARCHAR(255) NULL,
  status ENUM('active', 'paused', 'completed', 'removed') NOT NULL DEFAULT 'active',
  views_count INT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  completed_at DATETIME NULL,
  INDEX idx_anuncios_user (user_id),
  INDEX idx_anuncios_status_created (status, created_at),
  INDEX idx_anuncios_category_condition (category, item_condition),
  FULLTEXT INDEX ft_anuncios_search (title, description, desired_item),
  CONSTRAINT fk_anuncios_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS propostas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  anuncio_id BIGINT UNSIGNED NOT NULL,
  owner_id BIGINT UNSIGNED NOT NULL,
  proposer_id BIGINT UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  status ENUM('pending', 'accepted', 'rejected', 'cancelled', 'completed') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  accepted_at DATETIME NULL,
  cancelled_at DATETIME NULL,
  INDEX idx_propostas_anuncio_status (anuncio_id, status),
  INDEX idx_propostas_owner (owner_id),
  INDEX idx_propostas_proposer (proposer_id),
  CONSTRAINT fk_propostas_anuncio FOREIGN KEY (anuncio_id) REFERENCES anuncios(id) ON DELETE RESTRICT,
  CONSTRAINT fk_propostas_owner FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT fk_propostas_proposer FOREIGN KEY (proposer_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mensagens (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proposta_id BIGINT UNSIGNED NOT NULL,
  sender_id BIGINT UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  read_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_mensagens_proposta_created (proposta_id, created_at),
  INDEX idx_mensagens_sender (sender_id),
  CONSTRAINT fk_mensagens_proposta FOREIGN KEY (proposta_id) REFERENCES propostas(id) ON DELETE CASCADE,
  CONSTRAINT fk_mensagens_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS avaliacoes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proposta_id BIGINT UNSIGNED NOT NULL,
  reviewer_id BIGINT UNSIGNED NOT NULL,
  reviewed_id BIGINT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  comment TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  UNIQUE KEY uq_avaliacao_por_proposta (proposta_id, reviewer_id),
  INDEX idx_avaliacoes_reviewed (reviewed_id),
  CONSTRAINT fk_avaliacoes_proposta FOREIGN KEY (proposta_id) REFERENCES propostas(id) ON DELETE RESTRICT,
  CONSTRAINT fk_avaliacoes_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT fk_avaliacoes_reviewed FOREIGN KEY (reviewed_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT chk_rating_range CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_scores (
  user_id BIGINT UNSIGNED PRIMARY KEY,
  average_rating DECIMAL(3,2) NOT NULL DEFAULT 0,
  total_reviews INT UNSIGNED NOT NULL DEFAULT 0,
  reputation_points INT UNSIGNED NOT NULL DEFAULT 0,
  reputation_level ENUM('beginner', 'bronze', 'silver', 'gold') NOT NULL DEFAULT 'beginner',
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_scores_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS denuncias (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reporter_id BIGINT UNSIGNED NOT NULL,
  reported_user_id BIGINT UNSIGNED NULL,
  anuncio_id BIGINT UNSIGNED NULL,
  proposta_id BIGINT UNSIGNED NULL,
  reason ENUM('inappropriate_behavior', 'fraud_suspicion', 'offensive_content', 'fake_item', 'spam', 'other') NOT NULL,
  description TEXT NULL,
  status ENUM('pending', 'reviewing', 'resolved', 'archived') NOT NULL DEFAULT 'pending',
  admin_notes TEXT NULL,
  created_at DATETIME NOT NULL,
  resolved_at DATETIME NULL,
  INDEX idx_denuncias_status (status),
  INDEX idx_denuncias_reporter (reporter_id),
  CONSTRAINT fk_denuncias_reporter FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT fk_denuncias_reported FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_denuncias_anuncio FOREIGN KEY (anuncio_id) REFERENCES anuncios(id) ON DELETE SET NULL,
  CONSTRAINT fk_denuncias_proposta FOREIGN KEY (proposta_id) REFERENCES propostas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  attempted_at DATETIME NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  INDEX idx_login_attempts_email_time (email, attempted_at),
  INDEX idx_login_attempts_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS email_verifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  token CHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_email_verifications_user (user_id),
  CONSTRAINT fk_email_verifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
