USE app;

Create Table Account
(ID Int PRIMARY KEY AUTO_INCREMENT,
Email varchar(30) UNIQUE,
Password varchar(30),
Full_Name varchar(30),
Created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
Validated_At DATETIME);

Create Table User
(Account_ID Int NOT NULL UNIQUE,
Created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
Is_Banned Boolean NOT NULL);

Create Table Moderator
(Account_ID Int NOT NULL UNIQUE,
Created_At DATETIME,
Can_Edit Boolean,
Can_Delete Boolean);

Create Table Admin
(Account_ID Int NOT NULL UNIQUE,
Created_At DATETIME,
Can_Promote_To_Admin Boolean NOT NULL);

Create Table Category
(ID Int PRIMARY KEY AUTO_INCREMENT UNIQUE,
Name varchar(30),
Slug varchar(30) UNIQUE,
Category_ID Int); -- ASK ABOUT THIS

Create Table Post
(ID Int PRIMARY KEY AUTO_INCREMENT UNIQUE,
Title TEXT,
Description TEXT,
Created_At DATETIME NOT NULL,
Account_ID Int NOT NULL,
Category_ID Int NOT NULL);

Create Table Comment
(ID Int PRIMARY KEY AUTO_INCREMENT UNIQUE,
Text TEXT,
Created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
Account_ID Int NOT NULL,
Post_ID Int NOT NULL);

Alter table User 
ADD FOREIGN KEY (Account_ID) REFERENCES Account(ID);

Alter table Moderator
ADD FOREIGN KEY (Account_ID) REFERENCES Account(ID);

Alter table Admin
ADD FOREIGN KEY (Account_ID) REFERENCES Account(ID);

Alter Table Post
ADD FOREIGN KEY (Category_ID) REFERENCES Category(ID);

Alter Table Post
ADD FOREIGN KEY (Account_ID) REFERENCES Account(ID);

Alter Table Comment
ADD FOREIGN KEY (Account_ID) REFERENCES Account(ID);

Alter Table Comment
ADD FOREIGN KEY (Post_ID) REFERENCES Post(ID);

Alter Table Category
ADD FOREIGN KEY (Category_ID) REFERENCES Category(ID);
