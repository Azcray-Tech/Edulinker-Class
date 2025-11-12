INSERT INTO roles (nombre) VALUES ('Administrador'), ('Profesor'), ('Usuario');

INSERT INTO users (username, email, password, imagen, biografia, rol_id) VALUES
  ('admin', 'admin@example.com', '', NULL, 'Administrador del sitio', 1),
  ('prof', 'prof@example.com', '', NULL, 'Profesor de prueba', 2),
  ('alumno', 'student@example.com', '', NULL, 'Estudiante de prueba', 3);

INSERT INTO category (nombre) VALUES ('Matemáticas'), ('Lengua'), ('Ciencias');

INSERT INTO articles (title, article, image, category, user, date) VALUES
  ('Primer artículo de ejemplo', 'Este es el contenido del artículo de prueba. Aquí aparece texto de ejemplo para verificar la visualización.', '', 'Matemáticas', 1, NOW()),
  ('Segundo artículo', 'Contenido del segundo artículo', '', 'Lengua', 2, NOW());