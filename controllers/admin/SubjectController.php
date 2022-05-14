<?php
namespace application\controllers\admin;

use application\core\Controller;
use application\core\Request;
use application\core\Response;
use application\core\Session;
use application\models\SubjectModel;

class SubjectController extends Controller
{
    private SubjectModel $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $parameters = [];
        $parameters['subjects'] = $this->subjectModel->all();

        return $this->view('pages.admin.subjects.index', $parameters);
    }
    public function addSubjectForm()
    {
        $parameters = [];
        $parameters['errors'] = Session::flash('errors');
        $parameters['subject'] = Session::flash('subject');

        return $this->view('pages.admin.subjects.addSubjectForm', $parameters);
    }

    public function addSubject(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();
        $dataRequest['image'] = $dataRequest['subjectImages'][0] ?? false;

        $request->setRules(['name' => 'required|min:3|max:40']);

        if (!$request->validate())
        {
            Session::flash('errors', $request->getFirstErrors());
            Session::flash('subject', $dataRequest);
            return $response->redirect('/admin/subjects/addSubjectForm');
        }

        $this->subjectModel = new SubjectModel($dataRequest);
        $this->subjectModel->save();

        return $response->redirect('/admin/subjects');
    }

    public function deleteSubject(Request $request, Response $response)
    {
        $subjectId = intval($request->getRouteParameters()['subjectId']);
        $this->subjectModel->deleteById($subjectId);
        return $response->redirect('/admin/subjects');
    }

    public function updateSubjectForm(Request $request, Response $response)
    {
        $subjectId = intval($request->getRouteParameters()['subjectId']);
        $parameters = [];
        $parameters['subjectId'] = $subjectId;
        $parameters['subject'] = $this->subjectModel->findById($subjectId);

        return $this->view('pages.admin.subjects.updateSubjectForm', $parameters);
    }

    public function updateSubject(Request $request, Response $response)
    {
        $dataRequest = $request->getDataRequest();
        $dataRequest['image'] = $dataRequest['images'][0] ?? false;
        $request->setRules(['name' => 'required|min:3|max:40']);

        if (!$request->validate())
        {
            Session::flash('errors', $request->getFirstErrors());
            Session::flash('account', $request->getDataRequest());
            return $response->redirect('/admin/subjects/update/'.$request->getDataRequest()['id']);
        };

        $this->subjectModel->load($dataRequest);
        $this->subjectModel->update();
        return $response->redirect('/admin/subjects');
    }

}
