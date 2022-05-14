<?php
namespace application\models;

use application\core\Model;
use DateTime;

class ExaminationModel extends Model
{
    protected int $id;
    protected int $subjectId;
    protected string $name;
    protected string $description;
    protected int $totalQuestion;
    protected int $totalTime;
    protected DateTime $createdDate;

	function tableName(): string
    {
        return 'examinations';
	}

	function attributes(): array
    {
        return ['id', 'subjectId', 'name', 'description', 'totalQuestion', 'totalTime', 'createdDate'];
	}

	function primaryKey(): string
    {
        return 'id';
	}

    public function __construct(array $dataRequest = [])
    {
        parent::__construct($dataRequest);
    }
}
