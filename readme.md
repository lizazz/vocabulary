Code Care
Laravel test task

We have mysql database with table Vocabulary. Table has column word varchar(255).

Create page where user can select several items from Vocabulary table and get hash of them with one or several selected algorithms, e.g. md5, sha1, etc. Please provide at least 5 different algorithms. Result for each selected hash algorithm has to be displayed independently.
User should be able to save hashes to the database and access saved hashes in json format via http requests.
Provide cli task and schedule it to run each 11 minutes. Task should create xml files with information about user, their saved hashes, origin words and similar words from database. User information should include ip, browser, cookie and country of the user.
