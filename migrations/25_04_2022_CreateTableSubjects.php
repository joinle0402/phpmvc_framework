<?php
namespace application\migrations;

use PDO;

class CreateTableSubjects
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        $statement = "CREATE TABLE subjects (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            image VARCHAR(255)
        )";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }

    public function down()
    {
        $statement = "DROP TABLE subjects;";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }
}
