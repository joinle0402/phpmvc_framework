<?php
namespace application\migrations;

use PDO;

class CreateTableQuestions
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        $statement = "CREATE TABLE questions (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            examinationId INT NOT NULL,
            content VARCHAR(255) NOT NULL,
            description VARCHAR(255),
            paragraph VARCHAR(255)
        )";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }

    public function down()
    {
        $statement = "DROP TABLE questions;";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }
}
