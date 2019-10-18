USE app;

-- REQUIRED QUERY 1 
SELECT * FROM Account a LEFT JOIN User u ON a.ID = u.Account_ID WHERE a.Validated_At IS NOT NULL;

-- REQUIRED QUERY 2
SELECT * FROM Account a RIGHT JOIN Moderator m ON a.ID = m.Account_ID LEFT JOIN Admin ad ON a.ID = ad.Account_ID WHERE a.Validated_At IS NOT NULL;

-- REQUIRED QUERY 3

SELECT * FROM Category c WHERE c.Category_ID = 1;

SELECT * FROM Category c WHERE c.Category_ID = 2;

SELECT * FROM Category c WHERE c.Category_ID = 3;

-- REQUIRED QUERY 4

SELECT * FROM Post p WHERE p.Category_ID = 4 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 5 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 6 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 7 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 8 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 9 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 10 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 11 ORDER BY Created_At;

SELECT * FROM Post p WHERE p.Category_ID = 12 ORDER BY Created_At;

-- REQURIED QUERY 5

SELECT COUNT(*) FROM Category c LEFT JOIN Post p ON p.Category_ID = c.ID WHERE c.Category_ID = 1;
SELECT COUNT(*) FROM Category c LEFT JOIN Post p ON p.Category_ID = c.ID WHERE c.Category_ID = 2;
SELECT COUNT(*) FROM Category c LEFT JOIN Post p ON p.Category_ID = c.ID WHERE c.Category_ID = 3;
