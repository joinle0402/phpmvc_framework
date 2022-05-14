<?php
namespace application\routers;

use application\core\Router;
use application\controllers\client\SiteController;
use application\controllers\client\AuthController;
use application\controllers\admin\AdminController;
use application\controllers\admin\AccountController;
use application\controllers\admin\SubjectController;
use application\controllers\admin\ExaminationController;
use application\controllers\client\CrawlerController;

Router::get('/', [SiteController::class , 'index']);
Router::get('/crawler', [CrawlerController::class , 'index']);

Router::get('/login', [AuthController::class , 'loginForm']);
Router::post('/login', [AuthController::class , 'login']);
Router::get('/register', [AuthController::class , 'registerForm']);
Router::post('/register', [AuthController::class , 'register']);
Router::get('/logout', [AuthController::class , 'logout']);

Router::get("/admin", [AdminController::class , 'index']);

Router::get("/admin/accounts", [AccountController::class , 'index']);
Router::get("/admin/accounts/addAccountForm", [AccountController::class , 'addAccountForm']);
Router::post("/admin/accounts/add", [AccountController::class , 'addAccount']);
Router::get("/admin/accounts/delete/{accountId}", [AccountController::class , 'deleteAccount']);
Router::get("/admin/accounts/update/{accountId}", [AccountController::class , 'updateAccountForm']);
Router::post("/admin/accounts/update", [AccountController::class , 'updateAccount']);

Router::get("/admin/subjects", [SubjectController::class , 'index']);
Router::get("/admin/subjects/addSubjectForm", [SubjectController::class , 'addSubjectForm']);
Router::post("/admin/subjects/add", [SubjectController::class , 'addSubject']);
Router::get("/admin/subjects/delete/{subjectId}", [SubjectController::class , 'deleteSubject']);
Router::get("/admin/subjects/update/{subjectId}", [SubjectController::class , 'updateSubjectForm']);
Router::post("/admin/subjects/update", [SubjectController::class , 'updateSubject']);

Router::get("/admin/examinations", [ExaminationController::class , 'index']);
Router::get("/admin/examinations/addExaminationForm", [ExaminationController::class , 'addExaminationForm']);
Router::post("/admin/examinations/add", [ExaminationController::class , 'addExamination']);
Router::get("/admin/examinations/delete/{examinationId}", [ExaminationController::class , 'deleteExamination']);
Router::get("/admin/examinations/update/{examinationId}", [ExaminationController::class , 'updateExaminationForm']);
Router::post("/admin/examinations/update", [ExaminationController::class , 'updateExamination']);

Router::post("/admin/examinations/{examinationId}/addQuestion", [ExaminationController::class , 'addQuestion']);
Router::get("/admin/examinations/{examinationId}/addQuestionForm", [ExaminationController::class , 'addQuestionForm']);
Router::get("/admin/examinations/{examinationId}", [ExaminationController::class , 'detailsExamination']);

Router::get("/admin/examinations/{examinationId}/questions/delete/{questionId}", [ExaminationController::class , 'deleteQuestion']);
Router::get("/admin/examinations/{examinationId}/questions/update/{questionId}", [ExaminationController::class , 'updateQuestionForm']);
Router::post("/admin/examinations/{examinationId}/questions/update/{questionId}", [ExaminationController::class , 'updateQuestion']);
