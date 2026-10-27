CREATE DATABASE IF NOT EXISTS college_event_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE college_event_management;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','organizer') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(50) NOT NULL,
  description TEXT,
  event_date DATE NOT NULL,
  venue VARCHAR(150) NOT NULL,
  capacity INT UNSIGNED NOT NULL DEFAULT 100,
  organizer VARCHAR(120) NOT NULL,
  poster_path VARCHAR(255) DEFAULT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_event_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT NOT NULL,
  participant_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(25) DEFAULT NULL,
  notes TEXT,
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_registration_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Demo user: admin@college.edu / Admin@123
-- Hash is for the demo password above.
INSERT IGNORE INTO users(name,email,password_hash,role) VALUES
('Drusti Bari','admin@college.edu','$2y$12$1j0noVk714SACmNaiYe6ye7.7r7zwPRL..ik5X5eEd2UYc1BVHeOu','admin');

-- Seed events; safely skipped if you re-import by using unique titles only in a fresh database.
INSERT INTO events(title,category,description,event_date,venue,capacity,organizer)
SELECT 'Campus TechFest','Technical','A day of coding, demos and technology activities.',DATE_ADD(CURDATE(), INTERVAL 14 DAY),'Main Auditorium',150,'Drusti Bari'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Campus TechFest');
INSERT INTO events(title,category,description,event_date,venue,capacity,organizer)
SELECT 'Creative Arts Day','Cultural','Showcase music, art, dance and student creativity.',DATE_ADD(CURDATE(), INTERVAL 25 DAY),'College Quadrangle',200,'Cultural Committee'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Creative Arts Day');
INSERT INTO events(title,category,description,event_date,venue,capacity,organizer)
SELECT 'Web Development Workshop','Workshop','Hands-on introduction to modern web development.',DATE_ADD(CURDATE(), INTERVAL 7 DAY),'Computer Lab 204',60,'IT Department'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Web Development Workshop');
