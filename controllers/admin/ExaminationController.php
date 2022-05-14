<?php
namespace application\controllers\admin;

use application\core\Controller;
use application\core\Request;
use application\core\Response;
use application\core\Session;
use application\models\SubjectModel;
use application\models\ExaminationModel;
use application\models\OptionModel;
use application\models\QuestionModel;

class ExaminationController extends Controller
{
    private SubjectModel $subjectModel;
    private ExaminationModel $examinationModel;
    protected QuestionModel $questionModel;
    protected OptionModel $optionModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
        $this->examinationModel = new ExaminationModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
    }

    public function index()
    {
        $parameters = [];
        $parameters['examinations'] = $this->examinationModel->all();

        foreach ($parameters['examinations'] as $key => $examination)
        {
            $subjectId = $examination['subjectId'];
            $parameters['examinations'][$key]['subject'] = $this->subjectModel->findById($subjectId)['name'];
        }

        return $this->view('pages.admin.examinations.index', $parameters);
    }

    public function addExaminationForm()
    {
        $parameters = [];
        $parameters['subjects'] = $this->subjectModel->all();
        $parameters['totalQuestions'] = range(5, 100, 5);
        $parameters['defaultTotalQuestion'] = 40;
        $parameters['totalTimes'] = range(5, 120, 5);
        $parameters['defaultTotalTime'] = 60;
        $parameters['errors'] = Session::flash('errors');
        $parameters['examination'] = Session::flash('examination');

        return $this->view('pages.admin.examinations.addExaminationForm', $parameters);
    }

    public function addExamination(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();

        $request->setDataRequest($dataRequest);

        $request->setRules([ 'name' => 'required' ]);

        if (!$request->validate())
        {
            Session::flash('errors', $request->getFirstErrors());
            Session::flash('examination', $request->getDataRequest());

            return $response->redirect('/admin/examinations/addExaminationForm');
        }

        $this->examinationModel = new ExaminationModel($dataRequest);
        $this->examinationModel->save();

        return $response->redirect('/admin/examinations');
    }

    public function updateExaminationForm(Request $request, Response $response)
    {
        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $parameters = [];
        $parameters['examinationId'] = $examinationId;
        $parameters['examination'] = $this->examinationModel->findById($examinationId);
        $parameters['subjects'] = $this->subjectModel->all();
        $parameters['totalQuestions'] = range(5, 100, 5);
        $parameters['totalTimes'] = range(5, 120, 5);

        return $this->view('pages.admin.examinations.updateExaminationForm', $parameters);
    }

    public function updateExamination(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();
        $dataRequest['id'] = intval($dataRequest['id']);

        $request->setDataRequest($dataRequest);

        $request->setRules([ 'name' => 'required' ]);

        if (!$request->validate())
        {
            Session::flash('errors', $request->getFirstErrors());
            Session::flash('examination', $request->getDataRequest());

            return $response->redirect('/admin/examinations/updateExaminationForm');
        }

        $this->examinationModel->load($request->getDataRequest());
        $this->examinationModel->update();
        return $response->redirect('/admin/examinations');
    }

    public function deleteExamination(Request $request, Response $response)
    {
        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $this->examinationModel->deleteById($examinationId);
        return $response->redirect('/admin/examinations');
    }

    public function detailsExamination(Request $request, Response $response)
    {
        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $parameters = [];
        $parameters['examinationId'] = $examinationId;
        $parameters['questions'] = $this->questionModel->find([
            'examinationId' => $examinationId
        ]);

        foreach ($parameters['questions'] as $key => $question)
        {
            $parameters['questions'][$key]['options'] = $this->optionModel->find([
                'questionId' => $question['id']
            ]);
        }

        return $this->view('pages.admin.examinations.detailsExamination', $parameters);
    }

    public function addQuestionForm(Request $request, Response $response)
    {
        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $parameters = [];
        $parameters['examinationId'] = $examinationId;

        return $this->view('pages.admin.examinations.addQuestionForm', $parameters);
    }

    public function addQuestion(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();
        print_r($dataRequest);
        echo '<hr/>';
        echo str_replace("<p>", "",$dataRequest['content']);
        echo '<hr/>';
        die;

        // foreach ($dataRequest['options'] as $key => $option)
        // {
        //     $dataRequest['options'][$key] = getTextBetweenTags($dataRequest['options'][$key], 'p');
        // }
        echo '<pre>';
        print_r($dataRequest);
        echo '</pre>';
        echo '<hr/>';
        die;

        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $this->questionModel->load([
            "examinationId" => $examinationId,
            "content" => $dataRequest['content']
        ]);
        $this->questionModel->save();

        $questionId = intval($this->questionModel->getLastInsertedId());
        foreach ($dataRequest['options'] as $optionContent)
        {
            $this->optionModel->load([
                'questionId' => $questionId,
                'content' => $optionContent
            ]);
            $this->optionModel->save();
        }

        $lastInsertedId = intval($this->optionModel->getLastInsertedId());
        $numberOfOptions = count($dataRequest['options']);

        foreach ($dataRequest['isCorrect'] as $isCorrectIndex)
        {
            $optionId = $lastInsertedId - ($numberOfOptions - intval($isCorrectIndex));
            $this->optionModel->load([
                'id' => "$optionId",
                'isCorrect' => 1
            ]);
            $this->optionModel->update();
        }

        return $response->redirect('/admin/examinations/'.$examinationId);
    }

    public function deleteQuestion(Request $request, Response $response)
    {
        $questionId = intval($request->getRouteParameters()['questionId']);
        $examinationId = intval($request->getRouteParameters()['examinationId']);
        $this->questionModel->deleteById($questionId);
        $this->optionModel->delete([ 'questionId' => $questionId ]);
        return $response->redirect('/admin/examinations/'.$examinationId);
    }

    public function updateQuestionForm(Request $request, Response $response)
    {
        $questionId = intval($request->getRouteParameters()['questionId']);
        $examinationId = intval($request->getRouteParameters()['examinationId']);

        $parameters = [];
        $parameters['examinationId'] = $examinationId;
        $parameters['subjects'] = $this->subjectModel->all();
        $parameters['question'] = $this->questionModel->findById($questionId);
        $parameters['question']['options'] = $this->optionModel->find([
            'questionId' => $questionId
        ]);

        return $this->view('pages.admin.examinations.updateQuestionForm', $parameters);
    }

    public function updateQuestion(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();
        $questionId = intval($request->getRouteParameters()['questionId']);
        $examinationId = intval($request->getRouteParameters()['examinationId']);

        $this->questionModel->load([
            'id' => $questionId,
            'content' => $dataRequest['content']
        ]);
        $this->questionModel->update();

        $numberOfOptions = count($dataRequest['options']);
        $options = $this->optionModel->find(['questionId' => $questionId]);
        $lastOptionId = end($options)['id'];

        foreach ($dataRequest['options'] as $key => $optionContent)
        {
            $optionId = $lastOptionId - ($numberOfOptions - $key);

            $this->optionModel->load([
                'id' => $optionId,
                'content' => $optionContent
            ]);
            $this->optionModel->update();
        }

        foreach ($dataRequest['isCorrect'] as $isCorrectIndex)
        {
            $optionId = $lastOptionId - ($numberOfOptions - intval($isCorrectIndex));
            $this->optionModel->load([
                'id' => $optionId,
                'isCorrect' => 1
            ]);
            $this->optionModel->update();
        }

        return $response->redirect('/admin/examinations/'.$examinationId);
    }

}
