<?php

namespace application\core;

use PDO;

class Database
{
    protected PDO $connection;
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getLastInsertedId() {
        return $this->connection->lastInsertId();
    }

    public function removeMigrations()
    {
        
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = scandir(ROOT_DIRECTORY."/migrations");
        $toMigrations = array_diff($migrationFiles, $appliedMigrations);
        $newAppliedMigrations = [];
        foreach ($toMigrations as $key => $migration)
        {
            if ($migration !== "." && $migration !== "..")
            {
                preg_match('~[a-zA-Z]~i', $migration, $firstCharacter);
                $position = strpos($migration, $firstCharacter[0]);
                $migrationClassName = substr($migration, $position);
                $position = strpos($migrationClassName, '.');
                $migrationClassName = substr($migrationClassName, 0, $position);
                $migrationClassName = "\application\migrations\\$migrationClassName";
                $migrationPath = ROOT_DIRECTORY."/migrations/".$migration;
                $migrationPath = str_replace("/", '\\', $migrationPath);

                if (file_exists($migrationPath))
                {
                    require_once $migrationPath;
                    $migrationInstance = new $migrationClassName($this->connection);
                    $migrationInstance->up();

                    $newAppliedMigrations[] = $migration;
                }
            }
        }

        if (!empty($newAppliedMigrations))
        {
            $this->saveNewAppliedMigrations($newAppliedMigrations);
            echo "Save New Applied Migrations successfully!!!!!";
        }
    }

    protected function createMigrationsTable()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                migration VARCHAR(255) NOT NULL,
                create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    protected function getAppliedMigrations()
    {
        $statement = $this->connection->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function saveNewAppliedMigrations(array $migrations)
    {
        $migration = implode(', ', array_map(fn ($migration) => "('$migration')", $migrations));
        $statement = $this->connection->prepare("INSERT INTO migrations(migration) VALUES $migration ");
        $statement->execute();
    }

}
