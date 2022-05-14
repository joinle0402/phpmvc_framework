<?php
namespace application\migrations;

use PDO;

class CreateTableOptions
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        $statement = "CREATE TABLE options (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            questionId INT NOT NULL,
            content VARCHAR(255) NOT NULL,
            isCorrect TINYINT(1) DEFAULT 0
        )";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }

    public function down()
    {
        $statement = "DROP TABLE options;";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }
}
