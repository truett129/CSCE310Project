-- Users Table
CREATE TABLE Users (
    UIN INT PRIMARY KEY,
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
    UIN INT AUTO_INCREMENT PRIMARY KEY,
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
    FOREIGN KEY (UIN) REFERENCES Users(UIN) ON DELETE CASCADE
);

-- Certification Table
CREATE TABLE Certification (
    Cert_ID INT AUTO_INCREMENT PRIMARY KEY,
    Level VARCHAR(255),
    Name VARCHAR(255),
    Description VARCHAR(255)
);

-- Internship Table
CREATE TABLE Internship (
    Intern_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    Description VARCHAR(255),
    Is_Gov BOOLEAN
);

-- Programs Table
CREATE TABLE Programs (
    Program_Num INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    Description VARCHAR(255),
    Is_Active BOOLEAN DEFAULT TRUE
);

-- Track Table
CREATE TABLE Track (
    Program_Num INT,
    Student_Num INT,
    Tracking_Num INT AUTO_INCREMENT PRIMARY KEY,
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num) ON DELETE CASCADE,
    FOREIGN KEY (Student_Num) REFERENCES College_Student(UIN) ON DELETE CASCADE
);

-- Classes Table
CREATE TABLE Classes (
    Class_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    Description VARCHAR(255),
    Type VARCHAR(255)
);

-- Class Enrollment Table
CREATE TABLE Class_Enrollment (
    CE_NUM INT AUTO_INCREMENT PRIMARY KEY,
    UIN INT,
    Class_ID INT,
    Status VARCHAR(255),
    Semester VARCHAR(255),
    Year YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN) ON DELETE CASCADE,
    FOREIGN KEY (Class_ID) REFERENCES Classes(Class_ID) ON DELETE CASCADE
);

-- Cert Enrollment Table
CREATE TABLE Cert_Enrollment (
    CertE_Num INT AUTO_INCREMENT PRIMARY KEY,
    UIN INT,
    Cert_ID INT,
    Status VARCHAR(255),
    Training_Status VARCHAR(255),
    Program_Num INT,
    Semester VARCHAR(255),
    YEAR YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN) ON DELETE CASCADE,
    FOREIGN KEY (Cert_ID) REFERENCES Certification(Cert_ID) ON DELETE CASCADE,
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num) ON DELETE CASCADE
);

-- Intern App Table
CREATE TABLE Intern_App (
    IA_Num INT AUTO_INCREMENT PRIMARY KEY,
    UIN INT,
    Intern_ID INT,
    Status VARCHAR(255),
    Year YEAR,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN) ON DELETE CASCADE,
    FOREIGN KEY (Intern_ID) REFERENCES Internship(Intern_ID) ON DELETE CASCADE
);

-- Applications Table
CREATE TABLE Applications (
    App_Num INT AUTO_INCREMENT PRIMARY KEY,
    Program_Num INT,
    UIN INT,
    Uncom_Cert VARCHAR(255),
    Com_Cert VARCHAR(255),
    Purpose_Statement LONGTEXT,
    FOREIGN KEY (UIN) REFERENCES College_Student(UIN) ON DELETE CASCADE,
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num) ON DELETE CASCADE
);

-- Document Table
CREATE TABLE Document (
    Doc_Num INT AUTO_INCREMENT PRIMARY KEY,
    App_Num INT,
    Link VARCHAR(255),
    Doc_Type VARCHAR(255),
    FOREIGN KEY (App_Num) REFERENCES Applications(App_Num) ON DELETE CASCADE
);

-- Event Table
CREATE TABLE Event (
    Event_ID INT AUTO_INCREMENT PRIMARY KEY,
    UIN INT,
    Program_Num INT,
    Start_Date DATE,
    Time TIME,
    Location VARCHAR(255),
    End_Date DATE,
    Event_Type VARCHAR(255),
    FOREIGN KEY (UIN) REFERENCES Users(UIN) ON DELETE CASCADE,
    FOREIGN KEY (Program_Num) REFERENCES Programs(Program_Num) ON DELETE CASCADE
);

-- Event Tracking Table
CREATE TABLE Event_Tracking (
    ET_Num INT AUTO_INCREMENT PRIMARY KEY,
    Event_ID INT,
    UIN INT,
    FOREIGN KEY (Event_ID) REFERENCES Event(Event_ID) ON DELETE CASCADE,
    FOREIGN KEY (UIN) REFERENCES Users(UIN) ON DELETE CASCADE
);

