<?php
require_once __DIR__ . '/../app.php';

$conn = \sql\conn();

$hashword = sha1("password");
$conn->exec("
    INSERT INTO account(email, password, full_name, is_admin, is_moderator, created_at, updated_at) VALUES
        ('boulderingFan5428@hotmail.com', '$hashword', 'Mr. Climber', 1, 1, '2019-10-01 00:00:00', '2019-10-01 00:00:00'),
        ('nachos991122@gmail.com', '$hashword', 'Jake McRocks', 0, 1, '2019-10-02 14:50:55', '2019-10-02 14:53:19'),
        ('IhateClimbing@gmail.com', '$hashword', 'Troll', 0, 0, '2019-10-14 01:50:22', '2019-10-14 1:51:10'),
        ('admin@example.com', '$hashword', 'Ferguson the Admin', 1, 0, '2019-10-14 1:51:10', NULL),
        ('moderator@example.com', '$hashword', 'Billy the Moderator', 0, 1, '2019-10-14 1:51:10', NULL),
        ('user@example.com', '$hashword', 'Dwight the User', 0, 0, '2019-10-14 1:51:10', NULL);
");

$conn->exec("
    INSERT INTO category (id, name, slug, category_id) VALUES
        (1, 'Community', 'community', NULL),
        (2, 'Boise Bouldering', 'boise-bouldering', NULL),
        (3, 'Other', 'other', NULL),
        (4, 'News and Announcements', 'news-and-announcements', 1),
        (5, 'General Discussion', 'general-discussion', 1),
        (6, 'Bug Reports', 'bug-reports', 1),
        (7, 'Find a Group', 'find-a-group', 2),
        (8, 'Projects', 'projects', 2),
        (9, 'Gear', 'gear', 2),
        (10, 'Off-Topic Discussion', 'off-topic-discussion', 3),
        (11, 'Hobbies-and-Interests', 'hobbies-and-interests', 3),
        (12, 'Entertainment', 'entertainment', 3);
");

$conn->exec("

    INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Website Open', 'Welcome to the Website! Im glad to have you!', '2019-10-01 00:00:00', 1, 4);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('First!', '2019-10-02 16:00:00', 2, 1);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Haha. Glad to have you!', '2019-10-02 17:00:00', 1, 1);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('This place sucks.', '2019-10-17 12:00:00', 3, 1);

    INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Rules', 'Here are the site rules...', '2019-10-01 00:00:00', 1, 4);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Hey theres a typo on the first line!', '2019-10-02 12:00:00', 2, 2);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Whoops. Fixed.', '2019-10-2 13:00:00', 1, 2);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Ill let you know if I see any more.', '2019-10-2 13:15:00', 1, 2);
    
    INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Notice: Rule Changes', 'Due to a negative and unruly former-member of the community, I had to update a few rules', '2019-10-18 8:00:00', 1, 4);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Thank god. He was such a jerk', '2019-10-18 9:00:00', 2, 3);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah he was pretty over-the-top', '2019-10-18 9:01:00', 1, 3);
    INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Hopefully we dont get more people like him', '2019-10-18 9:02:00', 2, 3);

-- Inserts into category 5, (General Discussion)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Give your suggestions here', 'post  your suggestions for improving the forums here', '2019-10-01 17:00:00', 1, 5);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Could you implement a private message system?', '2019-10-15 4:00:00', 2, 4);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Sure! Ill put it on my work list', '2019-10-15 5:00:00', 1, 4);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Make the forums not suck.', '2019-10-16', 3, 4);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Expansion', 'How do you think we should attract more people?', '2019-10-09 11:00:00', 2, 5);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('How about we try and advertize to the climbing gyms in the area?', '2019-10-10 15:00:00', 1, 5);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I was thinking the same thing!', '2019-10-10 16:00:00', 2, 5);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('This place is terrible and wont ever get more people if I can help it.', '2019-10-17 14:00:00', 3, 5);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('This community sucks', 'Like seriously you guys all suck.', '2019-10-16 11:00:00', 3, 5);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Why are you like this?', '2019-10-16 12:00:00', 2, 6);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah man why are you such a jerk? Stop making posts like these.', '2019-10-16 13:00:00', 1, 6);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('lel', '2019-10-17 00:24:55', 3, 6);

-- Inserts into category 6, (Bug Reports)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Button Doesnt work', 'Check my screenshot, the button doesnt do anything. Hope you can fix it!', '2019-10-02 5:00:00', 2, 6);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Oh this was a stupid mistake on my part. Give me a second', '2019-10-2 5:40:00', 1, 7);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Its fixed.', '2019-10-02 6:00:00', 1, 7);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Sweet!', '2019-10-02 6:40:00', 2, 7);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Weird Color Clipping Issue', 'Theres a clipping issue on the login page when you scroll in a lot.', '2019-10-02 7:00:00', 2, 6);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Im having problems figuring this out right away. Let me investigate for a bit.', '2019-10-02 7:10:00', 1, 8);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('It seems to be getting worse since your last update.', '2019-10-02 8:00:00', 2, 8);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Aaaaaaaa', '2019-10-02 8:00:00', 1, 8);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Site crashes on mobile devices', 'Just wanted to bring attention to it. Here are my phone specs: ...', '2019-10-09 12:00:00', 2, 6);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('False alarm! My phone just died. Hahaha', '2019-10-09 12:10:00', 2, 9);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wow youre so stupid. How do you even do that?', '2019-10-16 7:00:00', 3, 9);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I will warn you again: Stop being mean to other users.', '2019-10-16 9:00:00', 1, 9);

-- Inserts into category 7, (Find a Group
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Black Cliffs this weekend?', 'Looking for climbing buddies to go out to the black cliffs this weekend.', '2019-10-05 16:00:00', 1, 7);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Cant this weekend, how about next?', '2019-10-05 16:10:00', 2, 10);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Sure!', '2019-10-05 16:11:00', 1, 10);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Who would want to climb with you lol', '2019-10-14 18:00:00', 3, 10);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Want to schedule a meet-up at Asana?', 'Looking to meet up with people at Asana for climbing.', '2019-10-15 17:00:00', 2, 7);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I go every Tuesday and Thursday! Lets meet then!', '2019-10-15 17:10:00', 1, 11);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('What time? 5PM?', '2019-10-15 17:10:50', 2, 11);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('5 PM works for me!', '2019-10-15 18:00:00', 1, 11);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Looking for a Group... Not!', 'Lmao I trolled you guys so hard.', '2019-10-17 14:00:00', 3, 7);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Stop making these posts.', '2019-10-17 14:50:00', 2, 12);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('You stop.', '2019-10-17 15:00:00', 3, 12);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Dont you have anything better to do?', '2019-10-17 17:00:00', 1, 12);

-- Inserts into category 8, (Projects)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('My current Project', 'Heres an album of images, what does everyone think?', '2019-10-4 12:00:00', 1, 8);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Looks fun! How is the grip on that first hold?', '2019-10-04 16:00:00', 2, 13);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Better than it looks!', '2019-10-04 16:30:00', 1, 13);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('In that case, I would love to try it myself next time Im out there', '2019-10-04 17:00:00', 2, 13);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Beginner Routes at Black Cliffs', 'Heres a compilation of routes for beginners over at the black cliffs that I made', '2019-10-4 16:00:00', 2, 8);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wow! How long did this take you to make?', '2019-10-05 7:00:00', 1 , 14);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Not long! I made it as I cleared them!', '2019-10-05 14:00:00', 2, 14);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wow you are not very good', '2019-10-14 2:00:12', 3, 14);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Success!', 'Heres a video of me completing a route today that my friend took!', '2019-10-17 12:00:00', 2, 8);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('You move really well! Great Vid!', '2019-10-17 13:00:00', 1, 15);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Is that you? How ugly.', '2019-10-18 1:22:55', 3, 15);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('You are getting banned for that one. You were warned enough', '2019-10-18 7:00:00', 1, 15);

-- Inserts into category 9, (Gear)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Gear shops in the area.', 'Heres my list of places that sell gear in the Boise area: (list)', '2019-10-1 19:00:00', 1, 9);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('You forgot about (location)! Otherwise great list!', '2019-10-02 19:00:00', 2, 16);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Really? Ive never been there. Ill add it now.', '2019-10-03 8:00:00', 1, 16);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Theyre great! You should check them out!', '2019-10-03 9:00:00', 2, 16);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('What shoes do you use?', 'What shoes do you use? I use BRAND shoes!', '2019-10-5 12:00:00', 2, 9);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I used to use BRAND shoes, but then I took an arrow to the knee.', '2019-10-6 4:00:00', 1, 17);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Lol. Old meme. What shoes do you use now though?', '2019-10-6 6:00:00', 2, 17);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I use OTHER BRAND now. lmao', '2019-10-6 15:00:00', 1, 17);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Climbing gear is for stupid people', 'New science states that you are stupid if you use climbing gear', '2019-10-16 0:22:44', 3, 9);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('What science states this? It seems fake.', '2019-10-16 7:00:00', 2, 18);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('The science of your mom lol XD haha.', '2019-10-16 8:00:00', 3, 18);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Please post something more relevant next time.', '2019-10-16 14:00:00', 1, 18);

-- Inserts into category 10, (Off-Topic Discussion)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('Look at my dog!', 'A picture of my doggo','2019-10-03 10:00:00', 2, 10);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Cute Doggo.', '2019-10-04 9:45:02', 1, 19);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah I love him.', '2019-10-04', 2, 19);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Im adding your dog to the list of things I want to punch.', '2019-10-18 3:00:00', 3, 19);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('This is what I look like', 'A picture of me','2019-10-01 9:00:00', 1, 10);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I think Ive seen you around! Do you go to LOCATION often?', '2019-10-03 15:00:00', 2, 20);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah all the time! Do you?', '2019-10-03 16:00:00', 1, 20);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah I work there! Ill say hi next time I see you!', '2019-10-03 18:00:00', 2, 20);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('Politics aaa!', 'I hate political person A, they make me so mad!','2019-10-04 17:22:00', 2, 10);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I dont like talking poltics.', '2019-10-04 18:00:00', 1, 21);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I love person A, this is why youre stupid.', '2019-10-17 15:00:00', 3, 21);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Why are you so rude in every single post you write?', '2019-10-17 16:00:00', 2, 21);

-- Inserts into category 11, (Hobbies and Interests)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('What do you like?', 'I know we all like climbing, but what else do you like to do?','2019-10-04 10:00:00', 2, 11);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I like to wakeboard. Ill post some images later.', '2019-10-04 21:00:00', 1, 22);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I like to punch young animals, Ill post some images later.', '2019-10-14 17:00:00', 3, 22);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wtf?', '2019-10-14 17:03:22', 2, 22);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('Sick Wakeboarding Pictures', 'Here are some pictures of me wakeboarding last summer in XXX.','2019-10-05 9:00:00', 1, 11);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wow! That looks real fun!', '2019-10-06 9:00:00', 2, 23);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah I love doing it during the summer.', '2019-10-06 10:00:00', 1, 23);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Yeah Id love to try someday.', '2019-10-06 11:00:00', 2, 23);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES('Punching Young Animals', 'Here is my imgur gallery of all the young Animals I punch: (link removed)','2019-10-14 17:22:00', 3, 11);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wtf. This is terrible.', '2019-10-14 18:00:00', 2, 24);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Im editing your link out of your post. Dont do this again or Ill ban you.', '2019-10-14 23:00:00', 1, 24);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('=P', '2019-10-14 23:22:11', 3, 24);

-- Inserts into category 12, (Entertainment)
INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Interesting YouTube Video!', 'I just found this cool gaming video, check it out: (video link)', '2019-10-8 9:00:00', 2, 12);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Whoops I fixed the link.', '2019-10-08 10:00:00', 2, 25);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Ahaha. I love that guy. This is a classic vid.', '2019-10-17 22:00:00', 3, 25);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Wow. This guy actually likes something.', '2019-10-17 23:00:00', 1, 25);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('Upcoming Concert', 'XXX is playing at XXX, anyone else going?', '2019-10-9 10:00:00', 2, 12);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I cant sadly :c', '2019-10-9 14:00:00', 1, 26);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('That band sucks anyway', '2019-10-16 4:00:00', 3, 26);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('To each their own.', '2019-10-16 18:00:00', 2, 26);

INSERT INTO post (title, description, created_at, account_id, category_id) VALUES ('This song is great.', 'I was listening to this song and it rocks! Check it out: (Song link)',  '2019-10-10 10:00:00', 2, 12);

INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Not really my thing.', '2019-10-10 10:00:04', 1, 27);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('Thats understandable.', '2019-10-10 10:10:10', 2, 27);
INSERT INTO comment(text, created_at, account_id, post_id) VALUES ('I listened to it anyway. Its not bad I guess.', '2019-10-10 11:00:00', 1, 27);

");
