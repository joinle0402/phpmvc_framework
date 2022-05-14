<?php
namespace application\migrations;

use PDO;

class CreateTableAccounts
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        $statement = "CREATE TABLE accounts (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            role ENUM('USER', 'ADMINISTRATOR') NOT NULL DEFAULT 'USER'
        )";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }

    public function down()
    {
        $statement = "DROP TABLE accounts;";
        $this->connection->exec($statement);
        echo $statement.PHP_EOL;
    }
}
