<?php
namespace application\migrations;

use PDO;

class CreateTableExaminations
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        $statement = "CREATE TABLE examinations (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            subjectId INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255),
            totalQuestion INT NOT NULL,
            totalTime INT NOT NULL,
            createdDate DATETIME DEFAULT CURRENT_TIMESTAMP,
            updatedDate DATETIME ON UPDATE CURRENT_TIMESTAMP
        )";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }

    public function down()
    {
        $statement = "DROP TABLE examinations;";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }
}
