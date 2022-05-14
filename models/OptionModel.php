<?php
namespace application\models;

use application\core\Model;

class OptionModel extends Model
{
    protected int $id;
    protected int $questionId;
    protected string $content = '';
    protected int $isCorrect;

    public function __construct(array $dataRequest = [])
    {
        parent::__construct($dataRequest);
    }

	function tableName(): string
    {
        return 'options';
	}

	function primaryKey(): string
    {
        return 'id';
	}

	function attributes(): array
    {
        return ['id', 'questionId', 'content', 'isCorrect'];
	}
}
