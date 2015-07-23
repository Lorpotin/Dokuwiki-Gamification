CREATE TABLE "User" (
"uid" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"username" VARCHAR(32) NOT NULL,
"password" VARCHAR(32) NOT NULL,
"points" INTEGER,
"badgesEarned" INTEGER,
"loginCount" INTEGER,
"currentlyLoggedIn" BOOLEAN,
"profilePicture" VARCHAR(64),
"firstname" VARCHAR(32),
"lastname" VARCHAR(32),
"email" VARCHAR(32),
"honenumber" VARCHAR(32),
"description" VARCHAR(512)
);

CREATE TABLE "Achievement" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"username" VARCHAR(32) NOT NULL,
"achievementID" INTEGER NOT NULL
);

CREATE TABLE "Friends" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"user1" VARCHAR(32) NOT NULL,
"user2" VARCHAR(32) NOT NULL,
"accepted" ENUM('0', '1') NOT NULL DEFAULT '0'
);
