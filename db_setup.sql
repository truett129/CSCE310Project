-- Users Table
CREATE TABLE Users (
<<<<<<< HEAD
    UIN INT AUTO_INCREMENT PRIMARY KEY,
=======
    UIN INT PRIMARY KEY,
>>>>>>> main
    First_Name VARCHAR(255),
    M_Initial CHAR(1),
    Last_Name VARCHAR(255),
    Username VARCHAR(255),
    Passwords VARCHAR(255),
    User_Type VARCHAR(255),
    Email VARCHAR(255),
    Discord_Name VARCHAR(255)
);

-- College Student Table
CREATE TABLE College_Student (
<<<<<<< HEAD
    UIN INT AUTO_INCREMENT PRIMARY KEY,
=======
    UIN INT PRIMARY KEY,
>>>>>>> main
    Gender VARCHAR(255),
    Hispanic_Latino BOOLEAN,
    Race VARCHAR(255),
    US_Citizen BOOLEAN,
    First_Generation BOOLEAN,
    DoB DATE,
    GPA FLOAT,
    Major VARCHAR(255),
    Minor_1 VARCHAR(255),
    Minor_2 VARCHAR(255),
    Expected_Graduation SMALLINT,
    School VARCHAR(255),
    Classification VARCHAR(255),
    Phone BIGINT,
    Student_Type VARCHAR(255),
    FOREIGN KEY (UIN) REFERENCES Users(UIN)
);

-- Certification Table
CREATE TABLE Certification (
<<<<<<< HEAD
    Cert_ID INT AUTO_INCREMENT PRIMARY KEY,
=======
    Cert_ID INT PRIMARY KEY,
>>>>>>> main
    Level VARCHAR(255),
    Name VARCHAR(255),
    Description VARCHAR(255)
);

-- Internship Table
CREATE TABLE Internship (
<<<<<<< HEAD
    Intern_ID INT AUTO_INCREMENT PRIMARY KEY,
=======
    Intern_ID INT PRIMARY KEY,
>>>>>>> main
    Name VARCHAR(255),
    Description VARCHAR(255),
    Is_Gov BOOLEAN
);

-- Programs Table
CREATE TABLE Programs (
<<<<<<< HEAD
    Program_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    Program_Num INT PRIMARY KEY,
>>>>>>> main
    Name VARCHAR(255),
    Description VARCHAR(255)
);

-- Track Table
CREATE TABLE Track (
    Program_Num INT,
    Student_Num INT,
<<<<<<< HEAD
    Tracking_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    Tracking_Num INT PRIMARY KEY,
>>>>>>> main
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num),
    FOREIGN KEY (Student_Num) REFERENCES College_Student(UIN)
);

-- Classes Table
CREATE TABLE Classes (
<<<<<<< HEAD
    Class_ID INT AUTO_INCREMENT PRIMARY KEY,
=======
    Class_ID INT PRIMARY KEY,
>>>>>>> main
    Name VARCHAR(255),
    Description VARCHAR(255),
    Type VARCHAR(255)
);

-- Class Enrollment Table
CREATE TABLE Class_Enrollment (
<<<<<<< HEAD
    CE_NUM INT AUTO_INCREMENT PRIMARY KEY,
=======
    CE_NUM INT PRIMARY KEY,
>>>>>>> main
    UIN INT,
    Class_ID INT,
    Status VARCHAR(255),
    Semester VARCHAR(255),
    Year YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN),
    FOREIGN KEY (Class_ID) REFERENCES Classes(Class_ID)
);

-- Cert Enrollment Table
CREATE TABLE Cert_Enrollment (
<<<<<<< HEAD
    CertE_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    CertE_Num INT PRIMARY KEY,
>>>>>>> main
    UIN INT,
    Cert_ID INT,
    Status VARCHAR(255),
    Training_Status VARCHAR(255),
    Program_Num INT,
    Semester VARCHAR(255),
    YEAR YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN),
    FOREIGN KEY (Cert_ID) REFERENCES Certification(Cert_ID),
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num)
);

-- Intern App Table
CREATE TABLE Intern_App (
<<<<<<< HEAD
    IA_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    IA_Num INT PRIMARY KEY,
>>>>>>> main
    UIN INT,
    Intern_ID INT,
    Status VARCHAR(255),
    Year YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN),
    FOREIGN KEY (Intern_ID) REFERENCES Internship(Intern_ID)
);

-- Applications Table
CREATE TABLE Applications (
<<<<<<< HEAD
    App_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    App_Num INT PRIMARY KEY,
>>>>>>> main
    Program_Num INT,
    UIN INT,
    Uncom_Cert VARCHAR(255),
    Com_Cert VARCHAR(255),
    Purpose_Statement LONGTEXT,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN),
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num)
);

-- Document Table
CREATE TABLE Document (
<<<<<<< HEAD
    Doc_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    Doc_Num INT PRIMARY KEY,
>>>>>>> main
    App_Num INT,
    Link VARCHAR(255),
    Doc_Type VARCHAR(255),
    FOREIGN KEY (App_Num) REFERENCES Applications(App_Num)
);

-- Event Table
CREATE TABLE Event (
<<<<<<< HEAD
    Event_ID INT AUTO_INCREMENT PRIMARY KEY,
=======
    Event_ID INT PRIMARY KEY,
>>>>>>> main
    UIN INT,
    Program_Num INT,
    Start_Date DATE,
    Time TIME,
    Location VARCHAR(255),
    End_Date DATE,
    Event_Type VARCHAR(255),
    FOREIGN KEY (UIN) REFERENCES Users(UIN),
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num)
);

-- Event Tracking Table
CREATE TABLE Event_Tracking (
<<<<<<< HEAD
    ET_Num INT AUTO_INCREMENT PRIMARY KEY,
=======
    ET_Num INT PRIMARY KEY,
>>>>>>> main
    Event_ID INT,
    UIN INT,
    FOREIGN KEY (Event_ID) REFERENCES Event(Event_ID),
    FOREIGN KEY (UIN) REFERENCES Users(UIN)
<<<<<<< HEAD
);
=======
);
>>>>>>> main
