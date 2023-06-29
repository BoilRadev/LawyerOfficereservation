Create table clients(
id integer auto_increment,
first_name varchar(45),
last_name varchar(45),
email varchar(100),
password varchar(100),
primary key(id)
);

Create table lawyers(
id integer auto_increment,
first_name varchar(45),
last_name varchar(45),
email varchar(100),
password varchar(100),
fee DECIMAL(10,2),
address VARCHAR(45),
primary key(id)
);

Create table appintments(
id integer auto_increment,
startTime datetime,
endTime datetime,
client_id integer,
lawyer_id integer,
primary key(id),
foreign key(clientId) references clients(id),
foreign key (lawyerId) references lawyers(id)
);