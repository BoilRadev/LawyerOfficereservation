ALTER TABLE appintments
ADD appointmentDate DATE,
ADD title VARCHAR(45);

ALTER TABLE appintments
DROP FOREIGN KEY appintments_ibfk_1,
DROP FOREIGN KEY appintments_ibfk_2;

ALTER TABLE appintments
DROP COLUMN clientId,
DROP COLUMN lawyerId;

CREATE TABLE review(
appintment_id INT,
client_id INT,
lawyer_id INT,
foreign key(appintment_id) references appintments(id),
foreign key(client_id) references clients(id),
foreign key(lawyer_id) references lawyers(id));

ALTER TABLE appintments RENAME appointments;

ALTER TABLE appointments
CHANGE startTime start_time TIME,
CHANGE endTime end_time TIME,
CHANGE appointmentDate appointment_date DATE;
