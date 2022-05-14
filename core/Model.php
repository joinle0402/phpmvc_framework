<?php
namespace application\core;

use PDO;

abstract class Model extends Database
{
    abstract public function tableName(): string;
    abstract public function primaryKey(): string;
    abstract public function attributes(): array;

    private array $modelAttributes = [];

    private bool $isDebugging = true;

    protected function __construct(array $dataRequest = [])
    {
        if (!empty($dataRequest))
        {
            $this->load($dataRequest);
        }
        $this->connection = Application::$application->database->connection;
    }

    public function load(array $dataRequest = [])
    {
        if (!empty($dataRequest))
        {
            $this->modelAttributes = [];

            foreach ($dataRequest as $attribute => $value)
            {
                if (property_exists($this, $attribute))
                {
                    $this->{$attribute} = $value;
                    $this->modelAttributes[$attribute] = $value;
                }
            }
        }
    }

    public function getModelAttribute()
    {
        return $this->modelAttributes;
    }

    public function save()
    {
        $attributes = array_filter($this->getModelAttribute());
        $attributesNames = array_keys($attributes);
        $attributesNamesQuery = implode(', ', $attributesNames);
        $attributesNamesParameterQuery = array_map(fn ($attributeName) => ":$attributeName", $attributesNames);
        $attributesNamesParameterQuery = implode( ', ', $attributesNamesParameterQuery);

        $query = "INSERT INTO ".$this->tableName()." ($attributesNamesQuery) VALUES ($attributesNamesParameterQuery)";

        if ($this->isDebugging)
        {
            echo '<pre>';
            var_dump($query);
            echo '</pre>';
            foreach ($attributes as $attributesName => $attributesValue)
            {
                echo '<pre>';
                var_dump(":$attributesName", $attributesValue);
                echo '</pre>';
            }
        }
        else
        {
            $statement = $this->connection->prepare($query);
            foreach ($attributes as $attributesName => $attributesValue)
            {
                $statement->bindValue(":$attributesName", $attributesValue);
            }
            return $statement->execute();
        }
    }

    public function update()
    {
        $modelAttributes = array_filter($this->getModelAttribute());
        $modelAttributeNames = array_keys($modelAttributes);

        array_shift($modelAttributes);

        $modelAttributesQuery = array_map(fn ($attributeNames) => "$attributeNames = :$attributeNames", array_keys($modelAttributes));
        $modelAttributesQuery = implode(", ", $modelAttributesQuery);

        $query = "UPDATE {$this->tableName()} SET $modelAttributesQuery WHERE id = :id";

        if ($this->isDebugging)
        {
            echo '<pre>';
            var_dump($query);
            echo '</pre>';
            echo '<hr/>';
            foreach ($modelAttributeNames as $attributeName)
            {
                echo '<pre>';
                var_dump(":$attributeName", $this->{$attributeName});
                echo '</pre>';
                echo '<hr/>';
            }
        }
        else
        {
            $statement = $this->connection->prepare($query);
            foreach ($modelAttributeNames as $attributeName)
            {
                $statement->bindValue(":$attributeName", $this->{$attributeName});
            }

            return $statement->execute();
        }
    }

    public function find(array $attributes)
    {
        $attributesQuery = array_map(fn ($attributeField) => "$attributeField = :$attributeField" , array_keys($attributes));
        $attributesQuery = implode('AND ', $attributesQuery);

        $query = "SELECT * FROM ".$this->tableName()." WHERE $attributesQuery";

        // echo '<pre>';
        // var_dump($query);
        // echo '</pre>';
        // foreach ($attributes as $attributeField => $attributeValue)
        // {
        //     echo '<pre>';
        //     var_dump(":$attributeField", $attributeValue);
        //     echo '</pre>';
        // }

        $statement = $this->connection->prepare($query);
        foreach ($attributes as $attributeField => $attributeValue)
        {
            $statement->bindValue(":$attributeField", $attributeValue);
        }
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $query = "SELECT * FROM ".$this->tableName()." WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $query = "SELECT * FROM ".$this->tableName();
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(array $attributes)
    {
        $attributeNames = array_keys($attributes);
        $attributeNamesQuery = array_map(fn ($attributeName) => "$attributeName = :$attributeName", $attributeNames);
        $attributeNamesQuery = implode(" AND ", $attributeNamesQuery);
        $query = "DELETE FROM ".$this->tableName()." WHERE $attributeNamesQuery";

        // echo '<pre>';
        // var_dump($query);
        // echo '</pre>';
        // foreach ($attributes as $attributeName => $attributeValue)
        // {
        //     echo '<pre>';
        //     var_dump(":$attributeName", $attributeValue);
        //     echo '</pre>';
        // }

        $statement = $this->connection->prepare($query);
        foreach ($attributes as $attributeName => $attributeValue)
        {
            $statement->bindValue(":$attributeName", $attributeValue);
        }
        $statement->execute();
    }

    public function deleteById(int $id)
    {
        $query = "DELETE FROM ".$this->tableName()." WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }

}


?>

