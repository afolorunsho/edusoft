<?php
// FOR EVERYBODY signon
Route::get('logon', function () {
    return view('login');
});
Route::get('main', function () {
    return view('index');
});
//Route::get('/', ['as'=> '/', 'uses'=>'LoginController@getMainMenu']);
Route::get('/', ['as'=> '/', 'uses'=>'LoginController@getLogout']);

Route::get('/signon', ['as'=> 'signon', 'uses'=>'LoginController@getLogin']);
Route::get('/system-consult', ['as'=> 'getConsult', 'uses'=>'LoginController@getConsult']);
Route::post('/contactus', ['as'=> 'contactus', 'uses'=>'LoginController@postCaptchaForm']);

Route::post('/login/post', ['as'=> 'loginPost', 'uses'=>'LoginController@postLogin']);
Route::post('/user/signup', ['as'=> 'signup', 'uses'=>'LoginController@signup']);

// FOR ALL THOSE AUTHENTICATED
Route::group(['middleware'=>['authen']], function(){
	Route::get('/logout', ['as'=> 'logout', 'uses'=>'LoginController@getLogout']);
	Route::get('/dashboard', ['as'=> 'dashboard', 'uses'=>'DashboardController@dashboard']);
	Route::get('/dashboard/statistics', ['as'=> 'dashboard-stat', 'uses'=>'DashboardController@dashboard_stat']);
	
	Route::get('/dashboard/logo', ['as'=> 'getInstituteLogo', 'uses'=>'SetController@getInstituteLogo']);
	Route::get('/dashboard/name', ['as'=> 'getInstituteName', 'uses'=>'SetController@getInstituteName']);
	
	Route::post('/user/password/change', ['as'=> 'changePassword', 'uses'=>'LoginController@changePassword']);
});
//administrators setup
Route::group(['middleware' => ['role:Administrator']], function() {
	
	/////////////////SYS
	Route::get('/system/users', ['as'=> 'getUsers' , 'uses' => 'SysController@showUsers' ]);
	Route::get('/system/backups', ['as'=> 'getBackup' , 'uses' => 'SysController@showBackup' ]);
	Route::post('/system/user/register', ['as'=> 'registerUser', 'uses'=>'LoginController@registerUser']);
	Route::get('/system/user/noregister', ['as'=> 'noRegister', 'uses'=>'SysController@noRegister']);
	Route::get('/system/user/edit', ['as'=> 'editUser' , 'uses' => 'SysController@editUser' ]);
	Route::post('/system/user/update', ['as'=> 'updateUser' , 'uses' => 'SysController@updateUser' ]);
	Route::post('/system/user/del', ['as'=> 'delUser' , 'uses' => 'SysController@delUser' ]);
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	Route::get('/backup/view', ['as'=> 'viewBackup' , 'uses' => 'SysController@viewBackup' ]);
	Route::post('/backup/create', ['as'=> 'createBackup' , 'uses' => 'SysController@createBackup' ]);
	//Route::get('/backup/create', ['as'=> 'createBackup' , 'uses' => 'SysController@create_bkup' ]);
	Route::get('/backup/download/sendmail', ['as'=> 'emailBackup' , 'uses' => 'SysController@download' ]);
	Route::post('/backup/download', ['as'=> 'downloadBackup' , 'uses' => 'SysController@downloadBackup' ]);
	Route::get('/backup/restore/internal', ['as'=> 'restoreIntBackup' , 'uses' => 'SysController@restoreIntBackup' ]);
	Route::post('/backup/restore/external', ['as'=> 'restoreExtBackup' , 'uses' => 'SysController@restoreExtBackup' ]);
	Route::post('/backup/download/del', ['as'=> 'delBackup' , 'uses' => 'SysController@delBackup' ]);
	Route::get('/backup/info', ['as'=> 'infoBackup' , 'uses' => 'SysController@infoBackup' ]);
	
});
//confidential information
Route::group(['middleware' => ['role:Administrator,CEO,Manager,Principal']], function() {
	Route::get('/enquiries/students', ['as'=> 'get_enquiryStudent' , 'uses' => 'SearchController@enquiryStudent' ]);
	Route::get('/enquiries/registration', ['as'=> 'get_enquiryRegistration' , 'uses' => 'SearchController@enquiryRegistration' ]);
	Route::get('/enquiries/enrolment', ['as'=> 'get_enquiryEnrolment' , 'uses' => 'SearchController@enquiryEnrolment' ]);
	Route::get('/enquiries/fees', ['as'=> 'get_enquiryFees' , 'uses' => 'SearchController@enquiryFees' ]);
	Route::get('/enquiries/scholarship', ['as'=> 'get_enquiryScholarship' , 'uses' => 'SearchController@enquiryScholarship' ]);
	Route::get('/enquiries/expense', ['as'=> 'get_enquiryExpenses' , 'uses' => 'SearchController@enquiryExpense' ]);
	Route::get('/enquiries/exam', ['as'=> 'get_enquiryExams' , 'uses' => 'SearchController@enquiryExam' ]);
	Route::get('/enquiries/attendance', ['as'=> 'get_enquiryAttend' , 'uses' => 'SearchController@enquiryAttend' ]);
	Route::get('/enquiries/transfer', ['as'=> 'get_enquiryTransfer' , 'uses' => 'SearchController@enquiryTransfer' ]);
	Route::get('/enquiries/bank', ['as'=> 'get_enquiryBank' , 'uses' => 'SearchController@enquiryBank' ]);
	Route::get('/enquiries/performance', ['as'=> 'get_enquiryPerformance' , 'uses' => 'SearchController@enquiryPerformance' ]);
	Route::get('/enquiries/statement', ['as'=> 'get_enquiryStatement' , 'uses' => 'SearchController@enquiryStatement' ]);
	Route::get('/enquiries/journal', ['as'=> 'get_enquiryJounal' , 'uses' => 'SearchController@enquiryJounal']);
	
	Route::get('/search/student', ['as'=> 'searchStudent' , 'uses' => 'SearchController@searchStudent' ]);
	Route::get('/search/enrolment', ['as'=> 'qryEnrolment' , 'uses' => 'SearchController@queryEnrolment' ]);
	Route::get('/search/journal', ['as'=> 'qryJournal' , 'uses' => 'SearchController@queryJounal' ]);
	Route::get('/search/registration', ['as'=> 'qryRegistration' , 'uses' => 'SearchController@queryRegistration' ]);
	Route::get('/search/exam', ['as'=> 'qryExam' , 'uses' => 'SearchController@queryExam' ]);
	Route::get('/search/fees', ['as'=> 'qryFees' , 'uses' => 'SearchController@queryFees' ]);
	Route::get('/search/scholarship', ['as'=> 'qryScholarship' , 'uses' => 'SearchController@queryScholarship' ]);
	Route::get('/search/attend', ['as'=> 'qryAttend' , 'uses' => 'SearchController@queryAttend' ]);
	Route::get('/search/expense', ['as'=> 'qryExpense' , 'uses' => 'SearchController@queryExpense' ]);
	Route::get('/search/ft', ['as'=> 'qryFT' , 'uses' => 'SearchController@queryFT' ]);
	Route::get('/search/bank', ['as'=> 'qryBank' , 'uses' => 'SearchController@queryBank' ]);
	Route::get('/search/performance', ['as'=> 'qryPerformance' , 'uses' => 'SearchController@queryPerformance' ]);
	Route::get('/search/statement', ['as'=> 'qryStatement' , 'uses' => 'SearchController@queryStatement' ]);
	
	/////////////////Charts
	Route::get('/charts/students/fees', ['as'=> 'chartStudentFees' , 'uses' => 'ChartController@chartStudentFees' ]);
	
	////////////Institute
	Route::get('/settings/institute/print', ['as'=> 'printInstitute' , 'uses' => 'SetController@pdfInstitute' ]);
	/////////////////Academic
	Route::get('/settings/academic/pdf', ['as'=> 'pdfAcademic' , 'uses' => 'SetController@pdfAcademic' ]);
	Route::get('/settings/academic/excel', ['as'=> 'excelAcademic' , 'uses' => 'SetController@excelAcademic' ]);
	/////////////////Session
	Route::get('/settings/semester/pdf', ['as'=> 'pdfSemester' , 'uses' => 'SetController@pdfSemester' ]);
	Route::get('/settings/semester/excel', ['as'=> 'excelSemester' , 'uses' => 'SetController@excelSemester' ]);
	/////////////////Events
	Route::get('/settings/events/pdf', ['as'=> 'pdfEvent' , 'uses' => 'SetController@pdfEvents' ]);
	Route::get('/settings/events/excel', ['as'=> 'excelEvent' , 'uses' => 'SetController@excelEvents' ]);
	Route::get('/settings/events/calendar', ['as'=> 'getAcademicCalendar' , 'uses' => 'SetController@getAcademicCalendar' ]);
	//////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////School
	Route::get('/master/school/pdf', ['as'=> 'pdfSchool' , 'uses' => 'MasterController@pdfSchool' ]);
	Route::get('/master/school/excel', ['as'=> 'excelSchool' , 'uses' => 'MasterController@excelSchool' ]);
	/////////////////Schedule
	Route::get('/master/schedule/pdf', ['as'=> 'pdfSchedule' , 'uses' => 'MasterController@pdfSchedule' ]);
	Route::get('/master/schedule/excel', ['as'=> 'excelSchedule' , 'uses' => 'MasterController@excelSchedule' ]);
	/////////////////Class
	Route::get('/master/class/pdf', ['as'=> 'pdfClass' , 'uses' => 'MasterController@pdfSchClass' ]);
	Route::get('/master/class/excel', ['as'=> 'excelClass' , 'uses' => 'MasterController@excelSchClass' ]);
	/////////////////Subject
	Route::get('/master/subject/pdf', ['as'=> 'pdfSubject' , 'uses' => 'MasterController@pdfSubject' ]);
	Route::get('/master/subject/excel', ['as'=> 'excelSubject' , 'uses' => 'MasterController@excelSubject' ]);
	/////////////////Syllabus
	Route::get('/master/syllabus/excel', ['as'=> 'excelSyllabus' , 'uses' => 'MasterController@excelSyllabus' ]);
	/////////////////Student
	Route::get('/student/registration/print',['as'=> 'printRegistration' , 'uses' => 'StudentController@pdfRegistration' ]);
	Route::get('/student/registration/excel', ['as'=> 'excelRegistration' , 'uses' => 'StudentController@excelRegistration' ]);
	
	Route::get('/student/assessment/excel', ['as'=> 'excelAssessment' , 'uses' => 'StudentController@excelAssessment' ]);
	
	Route::get('/fees/payment/print', ['as'=> 'printPayDetails' , 'uses' => 'FeesController@printPayDetails' ]);
	Route::get('/fees/statement', ['as'=> 'printFeeReceipt' , 'uses' => 'FeesController@printFeeReceipt' ]);
	Route::get('/fees/refund/print', ['as'=> 'printRefundDetails' , 'uses' => 'FeesController@printRefundDetails' ]);
	Route::get('/fees/excel', ['as'=> 'excelFees' , 'uses' => 'FeesController@excelFees' ]);
	
	/////////////////Name
	Route::get('/exams/name/excel', ['as'=> 'excelExam' , 'uses' => 'ExamsController@excelExamName' ]);
	Route::get('/exams/name/pdf', ['as'=> 'pdfExam' , 'uses' => 'ExamsController@pdfExamName' ]);
	/////////////////Exam Class
	Route::get('/exams/class/print', ['as'=> 'printExamClass' , 'uses' => 'ExamsController@printExamClass' ]);
	Route::get('/exams/class/excel', ['as'=> 'excelExamClass' , 'uses' => 'ExamsController@excelExamClass' ]);
	Route::get('/exams/class/pdf', ['as'=> 'pdfExamClass' , 'uses' => 'ExamsController@pdfExamClass' ]);
	Route::get('/exams/division/excel', ['as'=> 'excelExamDivScore' , 'uses' => 'ExamsController@excelExamDivScore' ]);
	/////////////////Exam score grade
	Route::get('/exams/grade/excel', ['as'=> 'excelExamGradeScore' , 'uses' => 'ExamsController@excelExamGradeScore' ]);
	Route::get('/exams/semester/score/pdf-result', ['as'=> 'pdfResult' , 'uses' => 'ExamsController@pdfNewResult' ]);
	
	/////////////////Exam scores
	Route::get('/exams/score/excel', ['as'=> 'excelExamScores' , 'uses' => 'ExamsController@excelExamScores' ]);
	Route::get('/exams/score/pdf', ['as'=> 'pdfExamScores' , 'uses' => 'ExamsController@pdfExamScores' ]);
	
	Route::get('/fees/lodgement/excel', ['as'=> 'excelLodgement' , 'uses' => 'FeesController@excelLodgement' ]);
	Route::get('/fees/bank/excel', ['as'=> 'excelBank' , 'uses' => 'FeesController@excelBankPayment']);
	Route::get('/txn/transfer/excel', ['as'=> 'excelTransfer' , 'uses' => 'TxnController@excelTransfer']);
	Route::get('/txn/expense/excel', ['as'=> 'excelExpense' , 'uses' => 'TxnController@excelExpense']);
	
});
//term processing
Route::group(['middleware' => ['role:Administrator,CEO,Manager,Principal,Teacher,Supervisor']], function() {
	Route::get('/exams/semester', ['as'=> 'getTermScore' , 'uses' => 'ExamsController@showTermScore' ]);
	
	Route::get('/exams/semester/score/class', ['as'=> 'getClassScore' , 'uses' => 'ExamsController@getClassScore' ]);
	Route::get('/exams/semester/score/student', ['as'=> 'getStudentScore' , 'uses' => 'ExamsController@getStudentScore' ]);
	Route::get('/exams/semester/score/result', ['as'=> 'getStudentResult' , 'uses' => 'ExamsController@getStudentResult' ]);
	Route::get('/exams/semester/score/email-result', ['as'=> 'emailResult' , 'uses' => 'ExamsController@emailResult' ]);
	Route::get('/exams/semester/score/promoted', ['as'=> 'getPromotedStudents' , 'uses' => 'ExamsController@getPromotedStudents' ]);
	Route::post('/exams/semester/score/generate', ['as'=> 'semesterScore' , 'uses' => 'ExamsController@semesterScore' ]);
	Route::post('/exams/semester/score/promotion', ['as'=> 'semesterPromotion' , 'uses' => 'ExamsController@semesterPromotion' ]);
	Route::post('/exams/semester/score/distribution', ['as'=> 'semesterDistribution' , 'uses' => 'ExamsController@semesterDistribution' ]);
	Route::post('/exams/semester/score/teachers', ['as'=> 'semesterTeacher' , 'uses' => 'ExamsController@semesterTeacher' ]);
	
	
});
//setup transactions
Route::group(['middleware' => ['role:Administrator,CEO,Manager,Principal,Supervisor,Manager,Teacher']], function() {

	Route::post('/settings/institute/del', ['as'=> 'delInstitute' , 'uses' => 'SetController@delInstitute' ]);
	Route::post('/settings/academic/del', ['as'=> 'delAcademic' , 'uses' => 'SetController@delAcademic' ]);
	Route::post('/settings/semester/del', ['as'=> 'delSemester' , 'uses' => 'SetController@delSemester' ]);
	Route::post('/settings/events/del', ['as'=> 'delEvent' , 'uses' => 'SetController@delEvents' ]);
	Route::get('/master/school/excel', ['as'=> 'excelSchool' , 'uses' => 'MasterController@excelSchool' ]);
	Route::post('/master/school/del', ['as'=> 'delSchool' , 'uses' => 'MasterController@delSchool' ]);
	Route::post('/master/schedule/del', ['as'=> 'delSchedule' , 'uses' => 'MasterController@delSchedule' ]);
	Route::post('/master/class/del', ['as'=> 'delClass' , 'uses' => 'MasterController@delSchClass' ]);
	Route::post('/master/subject/del', ['as'=> 'delSubject' , 'uses' => 'MasterController@delSubject' ]);
	Route::post('/master/syllabus/del', ['as'=> 'delSyllabus' , 'uses' => 'MasterController@delSyllabus' ]);
	Route::post('/student/registration/del',['as'=> 'delRegistration' , 'uses' => 'StudentController@delRegistration' ]);
	Route::post('/student/discipline/del',['as'=> 'delDiscipline' , 'uses' => 'StudentController@delDiscipline' ]);
	Route::post('/student/achievement/del',['as'=> 'delAchievement' , 'uses' => 'StudentController@delAchievement' ]);
	Route::post('/student/exit/del', ['as'=> 'delExit' , 'uses' => 'StudentController@delExit' ]);
	Route::post('/fees/head/del', ['as'=> 'delFees' , 'uses' => 'FeesController@delFees' ]);
	Route::post('/fees/payment/del', ['as'=> 'delPayment' , 'uses' => 'FeesController@delPayment' ]);
	Route::post('/exams/name/del', ['as'=> 'delExam' , 'uses' => 'ExamsController@delExamName' ]);
	Route::post('/account/group/del', ['as'=> 'delGroup' , 'uses' => 'AccountController@delGroup' ]);
	Route::post('/account/bank/del', ['as'=> 'delBank' , 'uses' => 'AccountController@delBank' ]);
	Route::post('/account/expense/del', ['as'=> 'delExp' , 'uses' => 'AccountController@delExp' ]);
 
	Route::get('/student/assessment/list', ['as'=> 'getAssessmentList' , 'uses' => 'StudentController@getAssessmentList' ]);
	Route::get('/student/assessment/class', ['as'=> 'getClassAssessment' , 'uses' => 'StudentController@getClassAssessment' ]);
	Route::get('/student/assessment/student', ['as'=> 'getStudentAssessment' , 'uses' => 'StudentController@getStudentAssessment' ]);
	Route::post('/student/assessment/update', ['as'=> 'updateStudentAssessment' , 'uses' =>'StudentController@updateStudentAssessment' ]);
	////////////Institute
	Route::get('/settings/institute/', ['as'=> 'getInstitute' , 'uses' => 'SetController@showInstitute' ]);
	Route::get('/settings/institute/info', ['as'=> 'infoInstitute' , 'uses' => 'SetController@infoInstitute' ]);
	Route::get('/settings/institute/edit', ['as'=> 'editInstitute' , 'uses' => 'SetController@editInstitute' ]);
	Route::post('/settings/institute/update', ['as'=> 'updateInstitute' , 'uses' => 'SetController@updateInstitute' ]);
	Route::post('/settings/institute/image', ['as'=> 'getImage' , 'uses' => 'SetController@getImage' ]);
	/////////////////Academic
	Route::get('/settings/academic/', ['as'=> 'getAcademic' , 'uses' => 'SetController@showAcademic' ]);
	Route::get('/settings/academic/info', ['as'=> 'infoAcademic' , 'uses' => 'SetController@infoAcademic' ]);
	Route::get('/settings/academic/edit', ['as'=> 'editAcademic' , 'uses' => 'SetController@editAcademic' ]);
	Route::post('/settings/academic/update', ['as'=> 'updateAcademic' , 'uses' => 'SetController@updateAcademic' ]);
	Route::get('/settings/academic/dates', ['as'=> 'getAcademicDates' , 'uses' => 'SetController@getAcademicDates' ]);
	/////////////////Session
	Route::get('/settings/semester', ['as'=> 'getSemester' , 'uses' => 'SetController@showSemester' ]);
	Route::get('/settings/semester/info', ['as'=> 'infoSemester' , 'uses' => 'SetController@infoSemester' ]);
	Route::get('/settings/semester/edit', ['as'=> 'editSemester' , 'uses' => 'SetController@editSemester' ]);
	Route::post('/settings/semester/update', ['as'=> 'updateSemester' , 'uses' => 'SetController@updateSemester' ]);
	/////////////////Events
	Route::get('/settings/events', ['as'=> 'getEvents' , 'uses' => 'SetController@showEvents' ]);
	Route::get('/settings/events/info', ['as'=> 'infoEvent' , 'uses' => 'SetController@infoEvents' ]);
	Route::get('/settings/events/edit', ['as'=> 'editEvent' , 'uses' => 'SetController@editEvents' ]);
	Route::post('/settings/events/update', ['as'=> 'updateEvent' , 'uses' => 'SetController@updateEvents' ]);
	Route::post('/settings/events/update-core', ['as'=> 'updateCoreEvent' , 'uses' => 'SetController@updateCoreEvents' ]);
	Route::post('/settings/events/type', ['as'=> 'createEventType' , 'uses' => 'SetController@createEventType' ]);
	/////////////////School
	Route::get('/master/school', ['as'=> 'getSchool' , 'uses' => 'MasterController@showSchool' ]);
	Route::get('/master/school/info', ['as'=> 'infoSchool' , 'uses' => 'MasterController@infoSchool' ]);
	Route::get('/master/school/edit', ['as'=> 'editSchool' , 'uses' => 'MasterController@editSchool' ]);
	Route::post('/master/school/update', ['as'=> 'updateSchool' , 'uses' => 'MasterController@updateSchool' ]);
	/////////////////Schedule
	Route::get('/master/schedule', ['as'=> 'getSchedule' , 'uses' => 'MasterController@showSchedule' ]);
	Route::get('/master/schedule/info', ['as'=> 'infoSchedule' , 'uses' => 'MasterController@infoSchedule' ]);
	Route::get('/master/schedule/edit', ['as'=> 'editSchedule' , 'uses' => 'MasterController@editSchedule' ]);
	Route::post('/master/schedule/update', ['as'=> 'updateSchedule' , 'uses' => 'MasterController@updateSchedule' ]);
	/////////////////Class
	Route::get('/master/class/get', ['as'=> 'getClass' , 'uses' => 'MasterController@showSchClass' ]);
	Route::get('/master/class/list', ['as'=> 'listSchClass' , 'uses' => 'MasterController@listSchClass' ]);
	Route::get('/master/class/info', ['as'=> 'infoClass' , 'uses' => 'MasterController@infoSchClass' ]);
	Route::get('/master/class/edit', ['as'=> 'editClass' , 'uses' => 'MasterController@editSchClass' ]);
	Route::get('/master/class/editDiv', ['as'=> 'editClassDiv' , 'uses' => 'MasterController@editSchClassDiv' ]);
	Route::get('/master/class/section', ['as'=> 'getClassSection' , 'uses' => 'MasterController@getClassSection' ]);
	Route::get('/master/class/sequence', ['as'=> 'getClassSequence' , 'uses' => 'MasterController@getClassSequence' ]);
	Route::post('/master/class/update', ['as'=> 'updateClass' , 'uses' => 'MasterController@updateSchClass' ]);
	
	/////////////////Subject
	Route::get('/master/subject', ['as'=> 'getSubject' , 'uses' => 'MasterController@showSubject' ]);
	Route::get('/master/subject/info', ['as'=> 'infoSubject' , 'uses' => 'MasterController@infoSubject' ]);
	Route::get('/master/subject/edit', ['as'=> 'editSubject' , 'uses' => 'MasterController@editSubject' ]);
	Route::get('/master/subject/class', ['as'=> 'getClassSubject' , 'uses' => 'MasterController@getClassSubject' ]);
	Route::get('/master/subject/section', ['as'=> 'getSectionSubject' , 'uses' => 'MasterController@getSectionSubject' ]);
	Route::post('/master/subject/update', ['as'=> 'updateSubject' , 'uses' => 'MasterController@updateSubject' ]);
	/////////////////Syllabus
	Route::get('/master/syllabus', ['as'=> 'getSyllabus' , 'uses' => 'MasterController@showSyllabus' ]);
	Route::get('/master/syllabus/info', ['as'=> 'infoSyllabus' , 'uses' => 'MasterController@infoSyllabus' ]);
	Route::get('/master/syllabus/edit', ['as'=> 'editSyllabus' , 'uses' => 'MasterController@editSyllabus' ]);
	Route::get('/master/syllabus/get', ['as'=> 'getClassSyllabus' , 'uses' => 'MasterController@getClassSyllabus' ]);
	
	Route::post('/master/syllabus/update', ['as'=> 'updateSyllabus' , 'uses' => 'MasterController@updateSyllabus' ]);
	Route::post('/master/syllabus/import', ['as'=> 'syllabusImport' , 'uses' => 'MasterController@syllabusImport' ]);
	Route::post('/master/syllabus/import-update', ['as'=> 'updateSyllabusImport' , 'uses' => 'MasterController@updateSyllabusImport' ]);
	///////////////////EXAMS
	/////////////////Name
	Route::get('/exams/name', ['as'=> 'getExamName' , 'uses' => 'ExamsController@showExamName' ]);
	Route::get('/exams/name/print', ['as'=> 'printExam' , 'uses' => 'ExamsController@printExamName' ]);
	Route::get('/exams/name/info', ['as'=> 'infoExam' , 'uses' => 'ExamsController@infoExamName' ]);
	Route::get('/exams/name/edit', ['as'=> 'editExam' , 'uses' => 'ExamsController@editExamName' ]);
	Route::get('/exams/name/list', ['as'=> 'listExams' , 'uses' => 'ExamsController@listExams' ]);
	Route::post('/exams/name/update', ['as'=> 'updateExam' , 'uses' => 'ExamsController@updateExamName' ]);
	/////////////////Exam Class
	Route::get('/exams/class', ['as'=> 'getExamClass' , 'uses' => 'ExamsController@showExamClass' ]);
	Route::get('/exams/class/print', ['as'=> 'printExamClass' , 'uses' => 'ExamsController@printExamClass' ]);
	Route::get('/exams/class/info', ['as'=> 'infoExamClass' , 'uses' => 'ExamsController@infoExamClass' ]);
	Route::post('/exams/class/update', ['as'=> 'updateExamClass' , 'uses' => 'ExamsController@updateExamClass' ]);
	/////////////////Exam score division
	Route::get('/exams/division', ['as'=> 'getExamDiv' , 'uses' => 'ExamsController@showExamDiv' ]);
	Route::get('/exams/division/get-div', ['as'=> 'getScoreDiv' , 'uses' => 'ExamsController@getScoreDiv' ]);
	Route::get('/exams/division/list', ['as'=> 'getScoreDivList' , 'uses' => 'ExamsController@getScoreDivList' ]);
	Route::post('/exams/division/post', ['as'=> 'createScoreDiv' , 'uses' => 'ExamsController@createScoreDiv' ]);
	Route::post('/exams/division/update', ['as'=> 'updateClassScoreDiv' , 'uses' => 'ExamsController@updateClassScoreDiv' ]);
	/////////////////Exam score grade
	Route::get('/exams/grade', ['as'=> 'getExamGrade' , 'uses' => 'ExamsController@showExamGrade' ]);
	Route::get('/exams/grade/get-div', ['as'=> 'getScoreGrade' , 'uses' => 'ExamsController@getScoreGrade' ]);
	Route::get('/exams/grade/list', ['as'=> 'getScoreGradeList' , 'uses' => 'ExamsController@getScoreGradeList' ]);
	Route::get('/exams/grade/record', ['as'=> 'getGradeRecord' , 'uses' => 'ExamsController@getGradeRecord' ]);
	Route::post('/exams/grade/post', ['as'=> 'createScoreGrade' , 'uses' => 'ExamsController@createScoreGrade' ]);
	Route::post('/exams/grade/update', ['as'=> 'updateClassScoreGrade' , 'uses' => 'ExamsController@updateClassScoreGrade' ]);
	/////////////////FEES HEAD
	Route::get('/fees/head', ['as'=> 'getFeeHead' , 'uses' => 'FeesController@showFeeHead' ]);
	Route::get('/fees/head/info', ['as'=> 'infoFees' , 'uses' => 'FeesController@infoFees' ]);
	Route::get('/fees/head/list', ['as'=> 'listFees' , 'uses' => 'FeesController@getFeesList' ]);
	Route::get('/fees/head/edit', ['as'=> 'editFees' , 'uses' => 'FeesController@editFees' ]);
	Route::post('/fees/head/update', ['as'=> 'updateFees' , 'uses' => 'FeesController@updateFees' ]);
	/////////////////////FEES STRUCTURE
	Route::get('/fees/structure', ['as'=> 'getFeeStruct' , 'uses' => 'FeesController@showFeeStruct' ]);
	Route::get('/fees/structure/info', ['as'=> 'infoFeeStruct' , 'uses' => 'FeesController@infoFeeStruct' ]);
	Route::post('/fees/structure/update', ['as'=> 'updateFeeStruct' , 'uses' => 'FeesController@updateFeeStruct' ]);
	
	Route::get('/fees/instruction', ['as'=> 'getFeeInstruct' , 'uses' => 'FeesController@showFeeInstruct' ]);
	Route::get('/fees/instruction/info', ['as'=> 'infoFeeInstruct' , 'uses' => 'FeesController@infoFeeInstruct' ]);
	Route::post('/fees/instruction/update', ['as'=> 'updateFeeInstruct' , 'uses' => 'FeesController@updateFeeInstruct' ]);
	
	/////////////assessment
	Route::get('/student/assess', ['as'=> 'getAssessSetup' , 'uses' => 'StudentController@showAssessSetup' ]);
	Route::get('/student/assess/get', ['as'=> 'getAssessmentHead' , 'uses' => 'StudentController@getAssessmentHead' ]);
	Route::get('/student/assess/edit', ['as'=> 'editClassAssessment' , 'uses' => 'StudentController@editClassAssessment' ]);
	Route::post('/student/assess/head', ['as'=> 'updateAssessHead' , 'uses' => 'StudentController@updateAssessHead' ]);
	Route::post('/student/assess/para', ['as'=> 'updateAssessPara' , 'uses' => 'StudentController@updateAssessPara' ]);
	//fess due
	Route::get('/fees/due', ['as'=> 'showFeesDue' , 'uses' => 'FeesController@showFeesDue' ]);
	Route::get('/fees/due/all_due_fees', ['as'=> 'getAllFeesDue' , 'uses' => 'FeesController@getAllFeesDue' ]);
	Route::get('/fees/payment/yearly_fees', ['as'=> 'getYearlyFees' , 'uses' => 'FeesController@getYearlyFees' ]);
	Route::get('/fees/due/due_fees', ['as'=> 'getFeesDue' , 'uses' => 'FeesController@getFeesDue' ]);
	Route::get('/fees/due/print_fees', ['as'=> 'printStudentFees' , 'uses' => 'FeesController@printStudentFees' ]);
	Route::get('/fees/due/print_all_fees', ['as'=> 'printAllFees' , 'uses' => 'FeesController@printAllFees' ]);
	Route::get('/fees/due/print_bills', ['as'=> 'printStudentBill' , 'uses' => 'FeesController@printStudentBill' ]);
	Route::get('/fees/due/due_fees/email', ['as'=> 'emailStudentDues' , 'uses' => 'FeesController@emailStudentDues' ]);
	Route::get('/fees/due/bills/email', ['as'=> 'emailStudentBills' , 'uses' => 'FeesController@emailStudentBills' ]);
	
});
//basic transactions
Route::group(['middleware' => ['role:Administrator,CEO,Manager,Principal,Supervisor,Operator,Manager,Teacher']], function() {

	Route::get('/student/registration', 
		['as'=> 'getRegistration' , 'uses' => 'StudentController@showRegistration' ]);
	Route::get('/student/registration/info', 
		['as'=> 'infoRegistration' , 'uses' => 'StudentController@infoRegistration' ]);
	Route::get('/student/registration/edit', 
		['as'=> 'editRegistration' , 'uses' => 'StudentController@editRegistration' ]);
	Route::get('/student/registration/active', 
		['as'=> 'getActiveStudent' , 'uses' => 'StudentController@getActiveStudent' ]);
	Route::get('/student/registration/student', 
		['as'=> 'getStudent' , 'uses' => 'StudentController@getStudent' ]);
	Route::get('/student/registration/student_id', 
		['as'=> 'getStudentID' , 'uses' => 'StudentController@getViewStudentID' ]);
		
	Route::get('/student/registration/full-name', 
		['as'=> 'getFullName' , 'uses' => 'StudentController@getFullName' ]);
	Route::post('/student/registration/imageupdate', 
		['as'=> 'updateStudentImage' , 'uses' => 'StudentController@updateStudentImage' ]);
	Route::post('/student/registration/update', 
		['as'=> 'updateRegistration' , 'uses' => 'StudentController@updateRegistration' ]);
	Route::post('/student/registration/image', 
		['as'=> 'getImage' , 'uses' => 'StudentController@getImage' ]);
	Route::post('/student/registration/multiple', 
		['as'=> 'updateMultiple' , 'uses' => 'StudentController@updateMultiple' ]);
	Route::post('/student/registration/import', 
		['as'=> 'studentImport' , 'uses' => 'StudentController@registration_import' ]);
	Route::post('/student/registration/updateimport', 
		['as'=> 'updateImport' , 'uses' => 'StudentController@importUpdate' ]);
	
	/////////////////Enrolment
	Route::get('/student/enrolment', ['as'=> 'getEnrolment' , 'uses' => 'StudentController@showEnrolment' ]);
	Route::get('/student/enrolment/forms', ['as'=> 'classForms' , 'uses' => 'StudentController@classForms' ]);
	Route::get('/student/enrolment/students', ['as'=> 'getDivStudents' , 'uses' => 'StudentController@getDivStudents' ]);
	Route::get('/student/enrolment/class', ['as'=> 'getStudentClass' , 'uses' => 'StudentController@getStudentClassForm' ]);
	Route::get('/student/enrolment/active', ['as'=> 'listActiveStudents' , 'uses' => 'StudentController@listActiveStudents' ]);
	Route::get('/student/enrolment/list', ['as'=> 'listEnrolStudents' , 'uses' => 'StudentController@listEnrolStudents' ]);
	Route::get('/student/class/list', ['as'=> 'listClassStudents' , 'uses' => 'StudentController@listClassStudents' ]);
	Route::post('/student/enrolment/update', ['as'=> 'updateEnrolment' , 'uses' => 'StudentController@updateEnrolment' ]);
	Route::get('/student/enrolment/excel', ['as'=> 'excelEnrolment' , 'uses' => 'StudentController@excelEnrolment' ]);
	Route::get('/student/enrolment/occupancy', ['as'=> 'classOccupancy' , 'uses' => 'StudentController@classOccupancy' ]);
	
	/////////////////Attendance
	Route::get('/student/attendance', ['as'=> 'getAttendance' , 'uses' => 'StudentController@showAttendance' ]);
	Route::get('/student/attendance/student', ['as'=> 'getStudentAttendance' , 'uses' => 'StudentController@getStudentAttendance' ]);
	Route::get('/student/attendance/class', ['as'=> 'getClassAttendance' , 'uses' => 'StudentController@getClassAttendance' ]);
	Route::post('/student/attendance/update', 
		['as'=> 'updateAttendance' , 'uses' => 'StudentController@updateAttendance' ]);
	/////////////////promotion
	Route::get('/student/promotion', ['as'=> 'getPromotion' , 'uses' => 'StudentController@showPromotion' ]);
	Route::get('/student/promotion/search', ['as'=> 'searchPromotion' , 'uses' => 'StudentController@searchPromotion' ]);
	Route::post('/student/promotion/update', 
		['as'=> 'updatePromotion' , 'uses' => 'StudentController@updatePromotion' ]);
	/////////////////transfer
	Route::get('/student/transfer', ['as'=> 'getTransfer' , 'uses' => 'StudentController@showTransfer' ]);
	Route::get('/student/transfer/search', ['as'=> 'searchTransfer' , 'uses' => 'StudentController@searchTransfer' ]);
	Route::post('/student/transfer/update', 
		['as'=> 'updateTransfer' , 'uses' => 'StudentController@updateTransfer' ]);
	/////////////////termination
	Route::get('/student/termination', ['as'=> 'getTermination' , 'uses' => 'StudentController@showTermination' ]);
	Route::get('/student/termination/search', ['as'=> 'searchTermination' , 'uses' => 'StudentController@searchTermination' ]);
	Route::post('/student/termination/update', 
		['as'=> 'updateTermination' , 'uses' => 'StudentController@updateTermination' ]);
	/////////////////discipline
	Route::get('/student/discipline', ['as'=> 'getDiscipline' , 'uses' => 'StudentController@showDiscipline' ]);
	Route::get('/student/discipline/search', ['as'=> 'searchDiscipline' , 'uses' => 'StudentController@searchDiscipline' ]);
	Route::post('/student/discipline/update', 
		['as'=> 'updateDiscipline' , 'uses' => 'StudentController@updateDiscipline' ]);
	/////////////////achievement
	Route::get('/student/achievement', ['as'=> 'getAchievement' , 'uses' => 'StudentController@showAchievement' ]);
	Route::get('/student/achievement/search', ['as'=> 'searchAchievement' , 'uses' => 'StudentController@searchAchievement' ]);
	Route::post('/student/achievement/update', 
		['as'=> 'updateAchievement' , 'uses' => 'StudentController@updateAchievement' ]);
	/////////////////exit
	Route::get('/student/exit', ['as'=> 'getExit' , 'uses' => 'StudentController@showExit' ]);
	Route::get('/student/exit/search', ['as'=> 'searchExit' , 'uses' => 'StudentController@searchExit' ]);
	Route::post('/student/exit/update', ['as'=> 'updateExit' , 'uses' => 'StudentController@updateExit' ]);
	
	//Student based fees
	Route::get('/fees/service', ['as'=> 'getStudentService' , 'uses' => 'FeesController@showStudentService' ]);
	Route::get('/fees/service/div', ['as'=> 'getDivServices' , 'uses' => 'FeesController@getDivServices' ]);
	Route::get('/fees/service/student', ['as'=> 'getStudentFees' , 'uses' => 'FeesController@getStudentFees' ]);
	Route::post('/fees/service/update', ['as'=> 'updateStudentService' , 'uses' => 'FeesController@updateStudentService' ]);
	
	Route::get('/fees/payment', ['as'=> 'getFeePay' , 'uses' => 'FeesController@showFeePay' ]);
	Route::get('/fees/payment/fees', ['as'=> 'searchFeesPmt' , 'uses' => 'FeesController@searchFeesPmt' ]);
	Route::get('/fees/payment/bank', ['as'=> 'searchBankPmt' , 'uses' => 'FeesController@searchBankPmt' ]);
	Route::get('/fees/payment/lodge', ['as'=> 'searchLodge' , 'uses' => 'FeesController@searchLodge' ]);
	
	Route::get('/fees/payment/details', ['as'=> 'getPayDetails' , 'uses' => 'FeesController@getPayDetails' ]);
	Route::get('/fees/payment/edit', ['as'=> 'editPayment' , 'uses' => 'FeesController@editPayment' ]);
	
	Route::post('/fees/payment/update', ['as'=> 'updateFeesPayment' , 'uses' => 'FeesController@updateFeesPayment' ]);
	Route::post('/fees/payment/import-update', ['as'=> 'updatePaymentImport' , 'uses' => 'FeesController@updatePaymentImport' ]);
	Route::post('/fees/payment/import', ['as'=> 'paymentImport' , 'uses' => 'FeesController@importPayment' ]);
	Route::get('/fees/payment/delete_batch', ['as'=> 'delFeesBatch' , 'uses' => 'FeesController@delFeesBatch' ]);
	
	Route::post('/txn/transfer/delete', ['as'=> 'delTransfer' , 'uses' => 'TxnController@delTransfer' ]);
	Route::post('/txn/expense/delete', ['as'=> 'delExpense' , 'uses' => 'TxnController@delExpense' ]);
	Route::get('/txn/expense/query', ['as'=> 'queryExpense' , 'uses' => 'TxnController@queryExpense']);
	Route::get('/txn/transfer/query', ['as'=> 'queryFT' , 'uses' => 'TxnController@queryFT']);
	
	Route::get('/fees/discount', ['as'=> 'getDiscount' , 'uses' => 'FeesController@showDiscount' ]);
	Route::get('/fees/discount/record', ['as'=> 'searchDiscount' , 'uses' => 'FeesController@searchDiscount' ]);
	Route::post('/fees/discount/update', ['as'=> 'updateDiscount' , 'uses' => 'FeesController@updateDiscount' ]);
	
	Route::get('/fees/refund', ['as'=> 'getFeeRefund' , 'uses' => 'FeesController@showFeeRefund' ]);
	Route::get('/fees/refund/fees', ['as'=> 'searchFeesRefund' , 'uses' => 'FeesController@searchFeesRefund' ]);
	Route::get('/fees/refund/bank', ['as'=> 'searchBankRefund' , 'uses' => 'FeesController@searchBankRefund' ]);
	Route::get('/fees/refund/details', ['as'=> 'getRefundDetails' , 'uses' => 'FeesController@getRefundDetails' ]);
	Route::post('/fees/refund/update', ['as'=> 'updateFeesRefund' , 'uses' => 'FeesController@updateFeesRefund' ]);
	
	////////////////////////////////////////////////////////////////////////////////////////////////EXAMS
	/////////////////Exam scores
	Route::get('/exams/score', ['as'=> 'getExamScore' , 'uses' => 'ExamsController@showExamScore' ]);
	Route::get('/exams/score/max', ['as'=> 'getExamMaxScore' , 'uses' => 'ExamsController@getExamMaxScore' ]);
	Route::get('/exams/score/list', ['as'=> 'listExamScore' , 'uses' => 'ExamsController@listExamScore' ]);
	Route::post('/exams/score/update', ['as'=> 'updateExamScore' , 'uses' => 'ExamsController@updateExamScore' ]);
	Route::get('/exams/score/remarks', ['as'=> 'getExamRemarks' , 'uses' => 'ExamsController@getExamRemarks' ]);
	Route::get('/exams/score/slips', ['as'=> 'getExamSlips' , 'uses' => 'ExamsController@getExamSlips' ]);
	Route::post('/exams/score/import', ['as'=> 'scoreImport' , 'uses' => 'ExamsController@scoreImport' ]);
	Route::post('/exams/score/import-update', ['as'=> 'updateScoreImport' , 'uses' => 'ExamsController@updateScoreImport' ]);
	Route::post('/exams/score/class-update', ['as'=> 'updateClassScore' , 'uses' => 'ExamsController@updateClassScore' ]);
	Route::get('/exams/score/edit', ['as'=> 'editStudentScore' , 'uses' => 'ExamsController@editStudentScore' ]);
	Route::get('/exams/score/print', ['as'=> 'prtStudentScore' , 'uses' => 'ExamsController@prtStudentScore' ]);
	Route::get('/exams/class/score', ['as'=> 'getStudentClassExam' , 'uses' => 'ExamsController@studentClassExam' ]);
	
	
});
//accounts transaction
Route::group(['middleware' => ['role:Administrator,CEO,Manager,Principal,Supervisor,Operator,Manager,Teacher,Admin']], function() {
	////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////Accounts
	Route::get('/account/group', ['as'=> 'getGroup' , 'uses' => 'AccountController@showGroup' ]);
	Route::get('/account/group/info', ['as'=> 'infoGroup' , 'uses' => 'AccountController@infoGroup' ]);
	Route::get('/account/group/edit', ['as'=> 'editGroup' , 'uses' => 'AccountController@editGroup' ]);
	Route::post('/account/group/update', ['as'=> 'updateGroup' , 'uses' => 'AccountController@updateGroup' ]);
	
	Route::get('/account/bank', ['as'=> 'getBank' , 'uses' => 'AccountController@showBank' ]);
	Route::get('/account/bank/info', ['as'=> 'infoBank' , 'uses' => 'AccountController@infoBank' ]);
	Route::get('/account/bank/edit', ['as'=> 'editBank' , 'uses' => 'AccountController@editBank' ]);
	Route::get('/account/bank/list', ['as'=> 'getBankList' , 'uses' => 'AccountController@getBankList' ]);
	Route::post('/account/bank/update', ['as'=> 'updateBank' , 'uses' => 'AccountController@updateBank' ]);
	
	Route::get('/account/expenses', ['as'=> 'getExp' , 'uses' => 'AccountController@showExp' ]);
	Route::get('/account/expense/info', ['as'=> 'infoExp' , 'uses' => 'AccountController@infoExp' ]);
	Route::get('/account/expense/list', ['as'=> 'listExp' , 'uses' => 'AccountController@getExpList' ]);
	Route::get('/account/expense/edit', ['as'=> 'editExp' , 'uses' => 'AccountController@editExp' ]);
	Route::post('/account/expense/update', ['as'=> 'updateExp' , 'uses' => 'AccountController@updateExp' ]);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////TXN
	Route::get('/txn/expense', ['as'=> 'getTxnExp' , 'uses' => 'TxnController@showTxnExp' ]);
	Route::get('/txn/expense/search', ['as'=> 'searchExp' , 'uses' => 'TxnController@searchExp' ]);
	Route::post('/txn/expense/update', ['as'=> 'updateTxnExp' , 'uses' => 'TxnController@updateTxnExp' ]);
	
	Route::post('/txn/expense/import-update', ['as'=> 'updateExpenseImport' , 'uses' => 'TxnController@updateExpenseImport' ]);
	Route::post('/txn/expense/import', ['as'=> 'expenseImport' , 'uses' => 'TxnController@importExpense' ]);
	Route::get('/txn/expense/delete_batch', ['as'=> 'delExpBatch' , 'uses' => 'TxnController@delExpBatch' ]);
	
	Route::get('/txn/ft', ['as'=> 'getTxnFT' , 'uses' => 'TxnController@showTxnFT' ]);
	Route::get('/txn/ft/search', ['as'=> 'searchFT' , 'uses' => 'TxnController@searchFT' ]);
	Route::post('/txn/ft/update', ['as'=> 'updateTxnFT' , 'uses' => 'TxnController@updateTxnFT' ]);
	
	Route::post('/txn/ft/import-update', ['as'=> 'updateImportLodgement' , 'uses' => 'TxnController@updateImportLodgement' ]);
	Route::post('/txn/ft/import', ['as'=> 'lodgementImport' , 'uses' => 'TxnController@importLodgement' ]);
	
	Route::get('/txn/journal', ['as'=> 'getTxnJrnl' , 'uses' => 'TxnController@showTxnJrnl' ]);
	
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| 	php artisan cache:clear
  	php artisan config:cache
  	composer dump-autoload
  	
  	php artisan migrate
	php artisan migrate:refresh
	php artisan db:seed --class=RolesTableSeeder
	php artisan db:seed --class=UsersTableSeeder
	
  	If you are using MAMP and you are getting a “mysqldump command not found” message using the Terminal this is what you have to do:
 
	Locate the “mysqldump file inside MAMP. It is usually located in /Applications/MAMP/Library/bin/mysqldump
	Right click on the file and hit “Make Alias”.  This will create a file named “mysqldump alias 2”.
 
	Move the file named “mysqldump alias 2” inside "/Users/MYUSERNAME/bin".
 
	Restart the Terminal and your are good to go.

	$var = '20/04/2012';
	$date = str_replace('/', '-', $var);
	echo date('Y-m-d', strtotime($date))
	
	
	Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| contains the "web" middleware group. Now create something great!
	|
	Route::group(['middleware'=>['authen', 'roles']], function(){
		Route::get('logout', ['as'=> 'logout', 'uses'=>'LoginController@getLogout']);
		Route::get('dashboard', ['as'=> 'dashboard', 'uses'=>'DashboardController@dashboard']);
	});
	///////////////////////////SELECT
	Don't call all. Pass an array of columns to the get method:
	$selected_votes = users_details::get(['selected_vote']);
	
	If you just want a simple list for that column, you can use the lists method:
	$selected_votes = users_details::pluck('selected_vote');
	
	If you only want a single value from the first row, use this:
	$selected_vote = users_details::value('selected_vote');
	
	///////////////////////////////MIGRATE
	php artisan migrate
	composer diagnose
	composer update
	php artisan vendor:publish
	
	^([01]\d|2[0-3]):?([0-5]\d)$
	The expression reads:
	
	^        Start of string (anchor)
	(        begin capturing group
	  [01]   a "0" or "1"
	  \d     any digit
	 |       or
	  2[0-3] "2" followed by a character between 0 and 3 inclusive
	)        end capturing group
	:?       optional colon
	(        start capturing
	  [0-5]  character between 0 and 5
	  \d     digit
	)        end group
	$        end of string anchor
	
	//display the title with a border around it
	$pdf->SetXY(50,20);
	$pdf->SetDrawColor(50,60,100);
	$pdf->Cell(100,10,'FPDF Tutorial',1,0,'C',0);
	Cell

	Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
	Description
	
	Prints a cell (rectangular area) with optional borders, background color and character string. The upper-left corner of the cell corresponds to the current position. The text can be aligned or centered. After the call, the current position moves to the right or to the next line. It is possible to put a link on the text. 
	If automatic page breaking is enabled and the cell goes beyond the limit, a page break is done before outputting.
	Parameters
	
	w
	Cell width. If 0, the cell extends up to the right margin.
	h
	Cell height. Default value: 0.
	txt
	String to print. Default value: empty string.
	border
	Indicates if borders must be drawn around the cell. The value can be either a number:
	0: no border
	1: frame
	or a string containing some or all of the following characters (in any order):
	L: left
	T: top
	R: right
	B: bottom
	Default value: 0.
	ln
	Indicates where the current position should go after the call. Possible values are:
	0: to the right
	1: to the beginning of the next line
	2: below
	Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
	align
	Allows to center or align the text. Possible values are:
	L or empty string: left align (default value)
	C: center
	R: right align
	fill
	Indicates if the cell background must be painted (true) or transparent (false). Default value: false.
	link
	URL or identifier returned by AddLink().
	Example
	
	// Set font
	$pdf->SetFont('Arial','B',16);
	// Move to 8 cm to the right
	$pdf->Cell(80);
	// Centered text in a framed 20*10 mm cell and line break
	$pdf->Cell(20,10,'Title',1,1,'C');
*/
/* 
	Generating a PDF based on a view template can be easily done by using a package such as DOMPDF made by Barryvdh

	Generating a PDF would look something like this
	
	$view = View::make('any.view', compact('variable'));
	$contents = $view->render();
	$pdf = App::make('dompdf.wrapper');
	$pdf->loadHTML($contents);
	$output = $pdf->output();
	
	Storage::put('/folder/your-file.pdf', $output);
	Attaching a document to a mail is pretty simple in Laravel (5.4) [docs]
	
	// file location
	$file = storage_path('app/folder/your-file.pdf');
	
	// return mail with an attachment
	return $this->view('emails.confirm')
		->from('me@stackoverflow.com', 'From')->subject('New mail')
		->with([
			'name' => $this->data['name'],
		])->attach($file, [
			'as' => 'File name',
			'mime' => 'application/pdf',
		]);
		There are three commands available db:backup, db:restore and db:list.
		
		write able chmod a+w /PATH/TO/DIR
		I added a .my.cnf to my home directory containing
		[mysqld_safe]
		[mysqld]
		
		secure_file_priv=""
		BACKUP: 	$cmd = "mysqldump -h <host> -u <user> -p<password> <database_name> > backup.sql";
				exec($cmd, $output, $return_value);

		STORAGE PATH 
		The best approach is to create a symbolic link like @SlateEntropy very well pointed out in the answer below. 
		To help with this, since version 5.3, Laravel includes a command which makes this incredibly easy to do:
		
		php artisan storage:link
		That creates a symlink from public/storage to storage/app/public for you and that's all there is to it. 
		Now any file in /storage/app/public can be accessed via a link like:
		
		If you have tables set up something like this:

		users
			id
			...
		
		friends
			id
			user_id
			friend_id
			...
		
		votes, comments and status_updates (3 tables)
			id
			user_id
			....
		In your User model:
		
		class User extends Eloquent {
			public function friends()
			{
				return $this->hasMany('Friend');
			}
		}
		In your Friend model:
		
		class Friend extends Eloquent {
			public function user()
			{
				return $this->belongsTo('User');
			}
		}
		Then, to gather all the votes for the friends of the user with the id of 1, you could run this query:
		
		$user = User::find(1);
		$friends_votes = $user->friends()
			->with('user') // bring along details of the friend
			->join('votes', 'votes.user_id', '=', 'friends.friend_id')
			->get(['votes.*']); // exclude extra details from friends table
		Run the same join for the comments and status_updates tables. If you would like votes, comments, 
		and status_updates to be in one chronological list, you can merge the resulting three collections 
		into one and then sort the merged collection.
		
		Edit
		
		To get votes, comments, and status updates in one query, you could build up each query and then union the results.
		 Unfortunately, this doesn't seem to work if we use the Eloquent hasMany relationship (see comments for this question 
		 for a discussion of that problem) so we have to modify to queries to use where instead:
		
		$friends_votes = 
			DB::table('friends')->where('friends.user_id','1')
			->join('votes', 'votes.user_id', '=', 'friends.friend_id');
		
		$friends_comments = 
			DB::table('friends')->where('friends.user_id','1')
			->join('comments', 'comments.user_id', '=', 'friends.friend_id');
		
		$friends_status_updates = 
			DB::table('status_updates')->where('status_updates.user_id','1')
			->join('friends', 'status_updates.user_id', '=', 'friends.friend_id');
		
		$friends_events = 
			$friends_votes
			->union($friends_comments)
			->union($friends_status_updates)
			->get();*/
			
	/* //////////////////
	
	The Storage facade may be used to interact with any of your configured disks. Alternatively, 
	you may type-hint the Illuminate\Contracts\Filesystem\Factory contract on any class that is resolved via the Laravel service container.
	
	Retrieving A Particular Disk
	
	$disk = Storage::disk('s3');
	
	$disk = Storage::disk('local');
	Determining If A File Exists
	
	$exists = Storage::disk('s3')->exists('file.jpg');
	Calling Methods On The Default Disk
	
	if (Storage::exists('file.jpg'))
	{
		//
	}
	Retrieving A File's Contents
	
	$contents = Storage::get('file.jpg');
	Setting A File's Contents
	
	Storage::put('file.jpg', $contents);
	Prepend To A File
	
	Storage::prepend('file.log', 'Prepended Text');
	Append To A File
	
	Storage::append('file.log', 'Appended Text');
	Delete A File
	
	Storage::delete('file.jpg');
	
	Storage::delete(['file1.jpg', 'file2.jpg']);
	Copy A File To A New Location
	
	Storage::copy('old/file1.jpg', 'new/file1.jpg');
	Move A File To A New Location
	
	Storage::move('old/file1.jpg', 'new/file1.jpg');
	Get File Size
	
	$size = Storage::size('file1.jpg');
	Get The Last Modification Time (UNIX)
	
	$time = Storage::lastModified('file1.jpg');
	Get All Files Within A Directory
	
	$files = Storage::files($directory);
	
	// Recursive...
	$files = Storage::allFiles($directory);
	Get All Directories Within A Directory
	
	$directories = Storage::directories($directory);
	
	// Recursive...
	$directories = Storage::allDirectories($directory);
	Create A Directory
	
	Storage::makeDirectory($directory);
	Delete A Directory
	
	Storage::deleteDirectory($directory);
	
	// Path to the project's root folder    
	echo base_path();
	
	// Path to the 'app' folder    
	echo app_path();        
	
	// Path to the 'public' folder    
	echo public_path();
	
	//Path to the 'app/storage' folder    
	echo storage_path();
	
	*/
	////////////////////////////////////////////
	/*
	php artisan cache:clear
	// and
	php artisan config:cache
	How to get public folder path in laravel ?
	public_path(); // Path of public/
	
	How to get base path(Project Root) in laravel ?
	base_path(); // Path of application root
	
	How to get storage folder path in laravel ?
	storage_path(); // Path of storage/
	
	How to get app folder path in laravel ?
	app_path(); // Path of app/
	
	By default Laravel puts files in the storage/app/public directory, 
	which is not accessible from the outside web. So you have to create a symbolic link between that directory and your public one:
	
	storage/app/public -> public/storage
	
	You can do that by executing the php artisan storage:link command (docs).
	
	To create a model and migration at the same time: php artisan make:model Student -m
	
	Controller

	$path=Storage::get('pdf/a.jpg');
	return view('test.view', compact('path'));
	View
	
	<img src="{{ $path }}" >
	
	The best approach is to create a symbolic link like @SlateEntropy very well pointed out in the answer below. 
	To help with this, since version 5.3, Laravel includes a command which makes this incredibly easy to do:

	php artisan storage:link
	That creates a symlink from public/storage to storage/app/public for you and that's all there is to it.
	 Now any file in /storage/app/public can be accessed via a link like:

	http://somedomain.com/storage/image.jpg
	If, for any reason, your can't create symbolic links (maybe you're on shared hosting, etc.) or you want to protect some files behind some access control logic, there is the alternative of having a special route that reads and serves the image. For example a simple closure route like this:

	Route::get('storage/{filename}', function ($filename)
	{
		$path = storage_path('public/' . $filename);

		if (!File::exists($path)) {
			abort(404);
		}

		$file = File::get($path);
		$type = File::mimeType($path);

		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		return $response;
	});
	You can now access your files just as you would if you had a symlink:

	http://somedomain.com/storage/image.jpg
	If you're using the Intervention Image Library you can use its built in response method to make things more succinct:

	Route::get('storage/{filename}', function ($filename)
	{
		return Image::make(storage_path('public/' . $filename))->response();
	});
Hi @WebKenth and @ejdelmonico my endless efforts finally have perfect result. My problem is i use window 7 and i use vagrant and virtualbox on it. Since Windows doesn't have native support for Linux-style symlinks, this would fail and break provisioning.. My steps for fixing it.

Add permission to create symbolic link http://superuser.com/questions/104845/permission-to-make-symbolic-links-in-windows-7
I user Git bash for my command line. And i need Run As Administrator. I access
vagrant ssh
cd "path_root_my_project"
php artisan storage:link
Check folder public, i saw a unknown file call storage, not directory http://prntscr.com/dexd43 . But it works now.
Under Windows you create a symlink with mklink /d. Attention under Windows its target and then source.

mklink /d "D:\wamp\www\magento\app\design\adminhtml\default\default\template\
company\name\" "D:\wamp\www\plugin-magento\app\design\adminhtml\default\default\template\company\name\"
http://www.sevenforums.com/tutorials/278262-mklink-create-use-links-windows.html
Your problem probably is that you have to start your vagrant box as a system administrator.

So hit start type "cmd", right click it choose "Run as administrator". Navigate to your project, type "vagrant up". Retry the command.
ln -sr storage/app/public public/storage

	php artisan migrate
	php artisan migrate:refresh
	php artisan db:seed --class=RolesTableSeeder
	php artisan db:seed --class=UsersTableSeeder
	php artisan cache:clear
	php artisan config:cache
	php artisan view:clear
	
	composer diagnose
	composer update
	Switch to root user by typing the following command 
	:sudo su - in order to run composer self-update
	php artisan vendor:publish
	
	Now access config/tcpdf.php to customize.
	php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
	
	n order to avoid the parsing of Blade files on each reload, Laravel caches the views after Blade is processed. 
	I've experienced some situations where the source (view file) is updated but the cache file is not "reloaded". 
	In these cases, all you need to do is to delete the cached views and reload the page.

	The cached view files are stored in storage/framework/views.
	
	ALTER TABLE Orders
		ADD FOREIGN KEY (PersonID) REFERENCES Persons(PersonID);

	*/
	/////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	/*
		
	// get images containing the string '_thumb'  
	foreach (glob("*_thumb.*") as $filename) {  
		echo $filename."<br />";  
	}  
	view plaincopy to clipboardprint?
	// get all text files with the extension .txt  
	foreach (glob("*.txt") as $filename) {  
		echo $filename."<br />";  
	} 
	////////////////////////////
	<!DOCTYPE html>
	<html>
	<body>
	<script>
	var pages = ["P1.pdf", "P2.pdf", "P3.pdf"];
	var oWindow=new Array();
	function PrintAll(){
		for (var i = 0; i < pages.length; i++) {
			oWindow[i].print();
			oWindow[i].close();
	
		}
	}
		function OpenAll() {
		for (var i = 0; i < pages.length; i++) {
			oWindow[i] = window.open(pages[i]);
		}
		setTimeout("PrintAll()", 5000);
		}
		OpenAll();
	</script>
	</body>
	</html>
	/////////////////////////////
	If you want the user to preview the text file, you could use an iframe instead:
	I recommend keeping JS out of HTML, this is just for example
	
	<iframe id="textfile" src="text.txt"></iframe>
	<button onclick="print()">Print</button>
	<script type="text/javascript">
	function print() {
		var iframe = document.getElementById('textfile');
		iframe.contentWindow.print();
	}
	</script>

	w = window.open('text.txt');
	w.print();
	////////////////////////////////////
	<body>
	
		<div id="txtdiv"></div>
	
		<script type="text/javascript">
		  $('#txtdiv').load('trial.txt', function()
		  {
			window.print(); //prints when text is loaded
		  });
	
		</script>
	 </body>
	////////////////////////////
	var pre = document.querySelector("pre");
	
	document.querySelector("input[type=file]")
	.addEventListener("change", function(event) {
	  var files = event.target.files;
	  for (var i = 0; i < files.length; i++) {
		(function(file) {
		  var reader = new FileReader();
		  reader.addEventListener("load", function(e) {
			 pre.textContent += "\n" + e.target.result;
		  });
		  reader.readAsText(file)
		}(files[i]))
	  }
	})
	<input type="file" accept="text/plain" multiple />
	<pre>
	
	</pre>
	/////////////////
	$file = $_GET["file"];
	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename=' . urlencode(basename($file)));
		// header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
	}
	/////////////
	<a href="pdf_server.php?file=pdffilename">Download my eBook</a>
	which outputs a custom header, opens the PDF (binary safe) and prints the data to the user's browser, 
	then they can choose to save the PDF despite their browser settings. The pdf_server.php should look like this:
	
	header("Content-Type: application/octet-stream");
	
	$file = $_GET["file"] .".pdf";
	header("Content-Disposition: attachment; filename=" . urlencode($file));   
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");            
	header("Content-Length: " . filesize($file));
	flush(); // this doesn't really matter.
	$fp = fopen($file, "r");
	while (!feof($fp))
	{
		echo fread($fp, 65536);
		flush(); // this is essential for large downloads
	} 
	fclose($fp); 
	<a href="./directory/yourfile.pdf" download>Download the pdf</a>
	//////////////////////////////////////////
	$file = $_GET["file"];
	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename=' . urlencode(basename($file)));
		// header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
	}
	/////////////////////////
	var folder = "images/";
	
	$.ajax({
		url : folder,
		success: function (data) {
			$(data).find("a").attr("href", function (i, val) {
				if( val.match(/\.(jpe?g|png|gif)$/) ) { 
					$("body").append( "<img src='"+ folder + val +"'>" );
				} 
			});
		}
	});
	///////////////////////////
	<html>
	<body>
		<div id='fileNames'></div>
	</body>
	<script src="js/jquery.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () 
		{
			//$.get("/content/images/", function(data) {});
			$.get(".", function(data) 
			{
				$("#fileNames").append(data);
			});
		})
	</script>

	var pdfFilesDirectory = '/uploads/pdf/';
	
	// get auto-generated page 
	$.ajax({url: pdfFilesDirectory}).then(function(html) {
		// create temporary DOM element
		var document = $(html);
	
		// find all links ending with .pdf 
		document.find('a[href$=.pdf]').each(function() {
			var pdfName = $(this).text();
			var pdfUrl = $(this).attr('href');
	
			// do what you want here 
		})
	});
	$directory = "/directory/path";
	$pdffiles = glob($directory . "*.pdf");
	
	$files = array();
	
	foreach($pdffiles as $pdffile)
	{
	   $files[] = "<a href=$pdffile>".basename($pdffile)."</a>";
	}
	
	echo json_encode($files);
	Now you just need to loop through the Json object to list the Url's.
	
	Something like:
	
	$.getJSON( "file.php", function( data ) {
	  var items = [];
	  $.each( data, function(val ) {
		items.push(val);
	  });
	
	  $( "body" ).append( items );
	});
	You may use the following PHP code for view the PDF files:
	
	<a href="view.php">View File</a>
	And the view.php page has the following code:
	
	$path="uploads/overviews/myfile.pdf"; header('content-type:application/pdf'); echo file_get_contents($path);
	///////////////////////////////////////////////////////////////////
	
	
	This bit of code should list all entries in a certain directory:
	if ($handle = opendir('.')) {
	
		while (false !== ($entry = readdir($handle))) {
	
			if ($entry != "." && $entry != "..") {
	
				echo "$entry\n";
			}
		}
	
		closedir($handle);
	}
	//////////////////////////////////////////////////////
	$path    = '/tmp';
	$files = scandir($path);
	Following code will remove . and .. from the returned array from scandir:
	
	$files = array_diff(scandir($path), array('.', '..'));
	//////////////////////////////////
	$dir = opendir('files/'); 
	echo '<ul>'; 
	while ($read = readdir($dir)) 
	{ 
	
	if ($read!='.' && $read!='..') 
	{ 
	echo '<li><a href="files/'.$read.'">'.$read.'</a></li>'; 
	} 
	
	} 
	
	echo '</ul>'; 
	
	closedir($dir); 
	
	/////////////////////////
	
	//path to directory to scan
	$directory = "../data/team/";
	
	//get all text files with a .txt extension.
	$texts = glob($directory . "*.txt");
	
	//print each file name
	foreach($texts as $text)
	{
		echo $text;
	}
	
	////////////////////////////////////
	
	foreach (glob("/location/for/public/images/*.png") as $filename) {
		echo "$filename size " . filesize($filename) . "\n";
	}
	///////////////////////EXCEL
	public downloadItemsExcel() {
		$items = Item::all();
		Excel::create('items', function($excel) use($items) {
			$excel->sheet('ExportFile', function($sheet) use($items) {
				$sheet->fromArray($items);
			});
		})->export('xls');
	}
	->store($type, 'public/reports/', true);
	*/