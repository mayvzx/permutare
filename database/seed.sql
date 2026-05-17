INSERT INTO users (id, name, email, password_hash, role, status, email_verified_at, created_at, updated_at)
VALUES
  (1, 'Administrador Permutare', 'admin@permutare.local', '$2y$10$PuqplSgzBHwmZlc1JmORSeUgj4lE8FQQ.IaXEmIfYKoeQWkjyNf/u', 'admin', 'active', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  role = 'admin',
  status = 'active',
  updated_at = NOW();

INSERT INTO user_profiles (user_id, institution, course, campus, bio, avatar_path, created_at, updated_at)
VALUES
  (1, 'Permutare', 'Administracao da plataforma', 'Online', 'Conta administrativa inicial.', NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  institution = VALUES(institution),
  course = VALUES(course),
  campus = VALUES(campus),
  updated_at = NOW();

INSERT INTO user_scores (user_id, average_rating, total_reviews, reputation_points, reputation_level, updated_at)
VALUES (1, 0, 0, 0, 'beginner', NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Login inicial:
-- E-mail: admin@permutare.local
-- Senha: Admin@123456
-- Troque a senha depois de criar seu proprio fluxo de edicao de senha.
