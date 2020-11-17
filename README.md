To import the database structure to run this site :

Step 1 : Create a new database in mysql
mysql -u root –p
CREATE DATABASE new_database;

Then press CTRL+D to exit mysql shell

Step 2 : Use the dump file to copy tables structure in the new database
sudo mysql -u username -p new_database < dbstructure.sql

Step 3 : Modify the access function
In 'include/manage-db.php' find the line 
$bdd = new PDO('mysql:host=localhost;dbname=mystPlayground;charset=utf8','root', '');
and replace mystPlayground by the name of the new database you created.
