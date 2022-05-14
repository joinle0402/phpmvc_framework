<?php
namespace application\models;

use application\core\Model;

class SubjectModel extends Model
{
    protected int $id;
    protected string $name = '';
    protected string $image = '';

    public function __construct(array $dataRequest = [])
    {
        parent::__construct($dataRequest);
    }

	function tableName(): string
    {
        return 'subjects';
	}

	function primaryKey(): string
    {
        return 'id';
	}

	function attributes(): array
    {
        return ['id', 'name', 'image'];
	}
}
