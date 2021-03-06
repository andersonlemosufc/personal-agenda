CREATE TABLE address (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    street VARCHAR(100),
    number INTEGER,
    complement VARCHAR(50),
    neighborhood VARCHAR(50),
    postal_code VARCHAR(10),
    city VARCHAR(50),
    state VARCHAR(50),
    country VARCHAR(50)
);

CREATE TABLE owner (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    date_of_birth DATETIME,
    phone VARCHAR(15),
    email VARCHAR(100),
    photo LONGBLOB,
    address_id INTEGER,
    FOREIGN KEY (address_id) REFERENCES address(id) ON DELETE SET NULL ON UPDATE CASCADE,
    password VARCHAR(300)
);

CREATE TABLE contact (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    date_of_birth DATETIME,
    phone VARCHAR(15),
    email VARCHAR(100),
    photo LONGBLOB,
    address_id INTEGER,
    FOREIGN KEY (address_id) REFERENCES address(id) ON DELETE SET NULL ON UPDATE CASCADE,
    comments VARCHAR(300),
    favorite BOOLEAN,
    owner_id INTEGER,
    FOREIGN KEY (owner_id) REFERENCES owner(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE appointment (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    start DATETIME,
    end DATETIME,
    description VARCHAR(300),
    repeat_type INTEGER,
    place_id INTEGER,
    owner_id INTEGER,
    FOREIGN KEY (place_id) REFERENCES address(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (owner_id) REFERENCES owner(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE contact_appointment (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    contact_id INTEGER,
    appointment_id INTEGER,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointment(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO owner (name, email, password) VALUES ('admin', 'admin@test.com', sha1('123'));
