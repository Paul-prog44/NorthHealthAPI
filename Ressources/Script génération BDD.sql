CREATE TABLE center (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, center_id INT DEFAULT NULL, gender VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, INDEX IDX_1FC0F36A5932F377 (center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE hospitalisation (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, vegetarian TINYINT(1) NOT NULL, single_room TINYINT(1) NOT NULL, television TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE medical_file (id INT AUTO_INCREMENT NOT NULL, allergies VARCHAR(255) DEFAULT NULL, documents VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, medical_file_id INT DEFAULT NULL, gender VARCHAR(20) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, email_address VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, social_security VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1ADAD7EBD5C999A2 (medical_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, medical_file_id INT NOT NULL, hospitalisation_id INT DEFAULT NULL, doctor_id INT NOT NULL, center_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_42C84955D5C999A2 (medical_file_id), UNIQUE INDEX UNIQ_42C84955F531F4C5 (hospitalisation_id), INDEX IDX_42C8495587F4FB17 (doctor_id), INDEX IDX_42C849555932F377 (center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE specialty_doctor (specialty_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_2DBBE9AC9A353316 (specialty_id), INDEX IDX_2DBBE9AC87F4FB17 (doctor_id), PRIMARY KEY(specialty_id, doctor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE specialty_center (specialty_id INT NOT NULL, center_id INT NOT NULL, INDEX IDX_728BF1E29A353316 (specialty_id), INDEX IDX_728BF1E25932F377 (center_id), PRIMARY KEY(specialty_id, center_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A5932F377 FOREIGN KEY (center_id) REFERENCES center (id);
ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBD5C999A2 FOREIGN KEY (medical_file_id) REFERENCES medical_file (id);
ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D5C999A2 FOREIGN KEY (medical_file_id) REFERENCES medical_file (id);
ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F531F4C5 FOREIGN KEY (hospitalisation_id) REFERENCES hospitalisation (id);
ALTER TABLE reservation ADD CONSTRAINT FK_42C8495587F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id);
ALTER TABLE reservation ADD CONSTRAINT FK_42C849555932F377 FOREIGN KEY (center_id) REFERENCES center (id);
ALTER TABLE specialty_doctor ADD CONSTRAINT FK_2DBBE9AC9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) ON DELETE CASCADE;
ALTER TABLE specialty_doctor ADD CONSTRAINT FK_2DBBE9AC87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE;
ALTER TABLE specialty_center ADD CONSTRAINT FK_728BF1E29A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) ON DELETE CASCADE;
ALTER TABLE specialty_center ADD CONSTRAINT FK_728BF1E25932F377 FOREIGN KEY (center_id) REFERENCES center (id) ON DELETE CASCADE;
