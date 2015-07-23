CREATE TABLE "Friends" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"user1" VARCHAR(32) NOT NULL,
"user2" VARCHAR(32) NOT NULL,
"accepted" ENUM(0, 1) NOT NULL DEFAULT 0
);