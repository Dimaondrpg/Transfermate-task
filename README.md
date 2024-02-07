1.	As I understood, it was necessary to read data from a directory tree with XML. I implemented this through the Database, XMLProcessor classes, and the XMLprocess script itself.
1.1 I created the tables in the database in the following way:

CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    author_id INT,
    title VARCHAR(255) NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);

CREATE INDEX idx_authors_name ON authors USING btree (name);

1.2 You can see the check for the existence of a record in the database in the Database class in the addOrUpdateBook method.

2.	I added authors and books in the specified languages, these characters are processed correctly.

3.	I created a page that displays everything as specified in the task, and to optimize search and sorting operations, I indexed the fields that are searched.

4.	Since I used a local server (via XAMPP) and the operating system is Windows, to set up a Cron Job, I used Task Scheduler, where I created a Trigger to execute on a schedule, and in Actions, I specified the path to php.exe and as an argument the path to the XMLprocess.php script. If I were using a LAMP server, it could simply be executed in the terminal by setting up a crontab in the following way (to run every 10 minutes):

crontab -e */10 * * * * C:/xampp/php/php.exe C:/xampp/htdocs/project1/XMLprocess.php

Additional task: Unfortunately, I didn’t have enough time to implement it (I don’t mean the 5 days you gave, just at the moment I still haven’t left my previous job, there was a lot of work and I only started on the task on Saturday evening), but in the interview, I’m ready to discuss how this task could have been implemented.
