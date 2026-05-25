CREATE TABLE IF NOT EXISTS users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user' CHECK (role IN ('user', 'admin')),
  status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'blocked', 'deleted')),
  email_verified_at TIMESTAMPTZ NULL,
  created_at TIMESTAMPTZ NOT NULL,
  updated_at TIMESTAMPTZ NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_users_status ON users (status);
CREATE INDEX IF NOT EXISTS idx_users_role ON users (role);

CREATE TABLE IF NOT EXISTS user_profiles (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
  institution VARCHAR(190) NOT NULL,
  course VARCHAR(190) NOT NULL,
  campus VARCHAR(190) NULL,
  bio TEXT NULL,
  avatar_path VARCHAR(255) NULL,
  created_at TIMESTAMPTZ NOT NULL,
  updated_at TIMESTAMPTZ NOT NULL
);

CREATE TABLE IF NOT EXISTS anuncios (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  title VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(40) NOT NULL CHECK (category IN ('books', 'electronics', 'school_supplies', 'clothing', 'furniture', 'services', 'other')),
  item_condition VARCHAR(40) NOT NULL CHECK (item_condition IN ('new', 'like_new', 'used_good', 'used_regular', 'needs_repair')),
  desired_item VARCHAR(255) NOT NULL,
  image_path VARCHAR(255) NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'paused', 'completed', 'removed')),
  views_count INTEGER NOT NULL DEFAULT 0 CHECK (views_count >= 0),
  created_at TIMESTAMPTZ NOT NULL,
  updated_at TIMESTAMPTZ NOT NULL,
  completed_at TIMESTAMPTZ NULL
);

CREATE INDEX IF NOT EXISTS idx_anuncios_user ON anuncios (user_id);
CREATE INDEX IF NOT EXISTS idx_anuncios_status_created ON anuncios (status, created_at);
CREATE INDEX IF NOT EXISTS idx_anuncios_category_condition ON anuncios (category, item_condition);
CREATE INDEX IF NOT EXISTS idx_anuncios_search ON anuncios USING GIN (
  to_tsvector('portuguese', coalesce(title, '') || ' ' || coalesce(description, '') || ' ' || coalesce(desired_item, ''))
);

CREATE TABLE IF NOT EXISTS propostas (
  id BIGSERIAL PRIMARY KEY,
  anuncio_id BIGINT NOT NULL REFERENCES anuncios(id) ON DELETE RESTRICT,
  owner_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  proposer_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  message TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected', 'cancelled', 'completed')),
  created_at TIMESTAMPTZ NOT NULL,
  updated_at TIMESTAMPTZ NOT NULL,
  accepted_at TIMESTAMPTZ NULL,
  cancelled_at TIMESTAMPTZ NULL
);

CREATE INDEX IF NOT EXISTS idx_propostas_anuncio_status ON propostas (anuncio_id, status);
CREATE INDEX IF NOT EXISTS idx_propostas_owner ON propostas (owner_id);
CREATE INDEX IF NOT EXISTS idx_propostas_proposer ON propostas (proposer_id);

CREATE TABLE IF NOT EXISTS mensagens (
  id BIGSERIAL PRIMARY KEY,
  proposta_id BIGINT NOT NULL REFERENCES propostas(id) ON DELETE CASCADE,
  sender_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  message TEXT NOT NULL,
  read_at TIMESTAMPTZ NULL,
  created_at TIMESTAMPTZ NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_mensagens_proposta_created ON mensagens (proposta_id, created_at);
CREATE INDEX IF NOT EXISTS idx_mensagens_sender ON mensagens (sender_id);

CREATE TABLE IF NOT EXISTS avaliacoes (
  id BIGSERIAL PRIMARY KEY,
  proposta_id BIGINT NOT NULL REFERENCES propostas(id) ON DELETE RESTRICT,
  reviewer_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  reviewed_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  rating SMALLINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment TEXT NULL,
  created_at TIMESTAMPTZ NOT NULL,
  updated_at TIMESTAMPTZ NOT NULL,
  CONSTRAINT uq_avaliacao_por_proposta UNIQUE (proposta_id, reviewer_id)
);

CREATE INDEX IF NOT EXISTS idx_avaliacoes_reviewed ON avaliacoes (reviewed_id);

CREATE TABLE IF NOT EXISTS user_scores (
  user_id BIGINT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
  average_rating NUMERIC(3,2) NOT NULL DEFAULT 0,
  total_reviews INTEGER NOT NULL DEFAULT 0 CHECK (total_reviews >= 0),
  reputation_points INTEGER NOT NULL DEFAULT 0 CHECK (reputation_points >= 0),
  reputation_level VARCHAR(20) NOT NULL DEFAULT 'beginner' CHECK (reputation_level IN ('beginner', 'bronze', 'silver', 'gold')),
  updated_at TIMESTAMPTZ NOT NULL
);

CREATE TABLE IF NOT EXISTS denuncias (
  id BIGSERIAL PRIMARY KEY,
  reporter_id BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  reported_user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
  anuncio_id BIGINT NULL REFERENCES anuncios(id) ON DELETE SET NULL,
  proposta_id BIGINT NULL REFERENCES propostas(id) ON DELETE SET NULL,
  reason VARCHAR(40) NOT NULL CHECK (reason IN ('inappropriate_behavior', 'fraud_suspicion', 'offensive_content', 'fake_item', 'spam', 'other')),
  description TEXT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'reviewing', 'resolved', 'archived')),
  admin_notes TEXT NULL,
  created_at TIMESTAMPTZ NOT NULL,
  resolved_at TIMESTAMPTZ NULL
);

CREATE INDEX IF NOT EXISTS idx_denuncias_status ON denuncias (status);
CREATE INDEX IF NOT EXISTS idx_denuncias_reporter ON denuncias (reporter_id);

CREATE TABLE IF NOT EXISTS login_attempts (
  id BIGSERIAL PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  attempted_at TIMESTAMPTZ NOT NULL,
  success BOOLEAN NOT NULL DEFAULT false
);

CREATE INDEX IF NOT EXISTS idx_login_attempts_email_time ON login_attempts (email, attempted_at);
CREATE INDEX IF NOT EXISTS idx_login_attempts_ip_time ON login_attempts (ip_address, attempted_at);

CREATE TABLE IF NOT EXISTS email_verifications (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  token CHAR(64) NOT NULL UNIQUE,
  expires_at TIMESTAMPTZ NOT NULL,
  used_at TIMESTAMPTZ NULL,
  created_at TIMESTAMPTZ NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_email_verifications_user ON email_verifications (user_id);
