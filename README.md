# CSCE310Project
CSCE 310 TAMCC Project

# Setting up the server
- First xampp must be downloaded on your computer
- Go into the xampp folder wherever you downloaded it and git clone this repo into the htdocs folder
- On the xampp control panel you want to start the Apache port
- Now you can go to localhost/(folder name goes here) and it should bring you to our project

# Setting up the SQL Database
- Make sure your github repo has a file called db_setup.sql
- On the xampp control panel start the MySQL port and click on "Admin"
- Go to "Databases" and where it says "Create database" enter in the name of the database. Mine is "csce310db"
- Click on the database on the left side of the screen and go to the tab that says "Import"
- Upload the db_setup.sql file and press "Import" at the bottom
- You should see a bunch of success messages and see that all of the databases have been created

# Roles on the Project

## Anthony
- **Admin:** Program Information Management
- **Student:** Application Information Management

---

## Truett
- **Admin:** User Authentication and Roles
- **Student:** User Authentication and Roles

---

## Pranav
- **Admin:** Program Progress Tracking
- **Student:** Program Progress Tracking

---

## Abdullah
- **Admin:** Event Management
- **Student:** Document Upload and Management
