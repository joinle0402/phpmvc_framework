<?php
namespace application\models;

use application\core\Model;

class QuestionModel extends Model
{
    protected int $id;
    protected int $examinationId;
    protected string $content = '';
    protected string $paragraph = '';
    protected string $questionType = '';

    public function __construct(array $dataRequest = [])
    {
        parent::__construct($dataRequest);
    }

	function tableName(): string
    {
        return 'questions';
	}

	function primaryKey(): string
    {
        return 'id';
	}

	function attributes(): array
    {
        return ['id', 'examinationId', 'content', 'paragraph', 'questionType'];
	}
}
