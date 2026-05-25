INSERT INTO users (id, name, email, password_hash, role, status, email_verified_at, created_at, updated_at)
VALUES
  (1, 'Administrador Permutare', 'admin@permutare.local', '$2y$10$PuqplSgzBHwmZlc1JmORSeUgj4lE8FQQ.IaXEmIfYKoeQWkjyNf/u', 'admin', 'active', NOW(), NOW(), NOW())
ON CONFLICT (id) DO UPDATE SET
  name = EXCLUDED.name,
  role = 'admin',
  status = 'active',
  updated_at = NOW();

SELECT setval(pg_get_serial_sequence('users', 'id'), GREATEST((SELECT MAX(id) FROM users), 1), true);

INSERT INTO user_profiles (user_id, institution, course, campus, bio, avatar_path, created_at, updated_at)
VALUES
  (1, 'Permutare', 'Administracao da plataforma', 'Online', 'Conta administrativa inicial.', NULL, NOW(), NOW())
ON CONFLICT (user_id) DO UPDATE SET
  institution = EXCLUDED.institution,
  course = EXCLUDED.course,
  campus = EXCLUDED.campus,
  updated_at = NOW();

INSERT INTO user_scores (user_id, average_rating, total_reviews, reputation_points, reputation_level, updated_at)
VALUES (1, 0, 0, 0, 'beginner', NOW())
ON CONFLICT (user_id) DO UPDATE SET updated_at = NOW();

-- Login inicial:
-- E-mail: admin@permutare.local
-- Senha: Admin@123456
