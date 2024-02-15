<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper;
use App\Controller\AppController;
use \Cake\Database\Expression\QueryExpression;
use Cake\Event\EventInterface;

/**
 * Results Controller
 *
 * @property \App\Model\Table\ResultsTable $Results
 *
 * @method \App\Model\Entity\Result[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResultsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function manageresults() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //if this was a search
        if ($this->request->is('post')) {
            $departments_table = TableRegistry::get('Departments');
            $department_id = $this->request->getData('department_id');
            $faculty_id = $this->request->getData('faculty_id');
            $session_id = $this->request->getData('session_id');
            $semester_id = $this->request->getData('semester_id');
            $course_id = $this->request->getData('subject_id');
            $student_id = $this->request->getData('student_id');
            $level_id = $this->request->getData('level_id');

            $conditions = [];
            if (!empty($department_id)) {
                $conditions['Results.department_id'] = $department_id;
                $deptmt = $departments_table->get($department_id);
                $this->set('deptmt', $deptmt);
            }
            if (!empty($faculty_id)) {
                $conditions['Results.faculty_id'] = $faculty_id;
            }
            if (!empty($course_id)) {
                $conditions['Results.subject_id'] = $course_id;
            }
            if (!empty($student_id)) {
                $conditions['Results.student_id'] = $student_id;
            }
            if (!empty($session_id)) {
                $conditions['Results.session_id'] = $session_id;
            }
            if (!empty($semester_id)) {
                $conditions['Results.semester_id'] = $semester_id;
            }
            if (!empty($level_id)) {
                $conditions['Results.level_id'] = $level_id;
                $this->request->getSession()->write('dlevelid', $level_id);
            }

            $courses = $this->Results->find()
                    ->distinct(['subject_id'])
                    ->contain(['Subjects', 'Departments', 'Levels', 'Faculties', 'Sessions', 'Semesters'])
                    ->where($conditions)
                    ->order(['Results.subject_id' => 'DESC']);

            // $students_Table = TableRegistry::get('Students');
            $dstudents = $this->Results->find()->contain(['Students'])->distinct(['Students.id'])
                    ->where(['Results.level_id' => $level_id, 'Results.department_id' => $department_id,
                        'Students.status' => 'Admitted', 'Results.session_id' => $session_id])
                    ->order(['Students.fname' => 'DESC']);

//            $dstudents = $students_Table->find()
//                    //->distinct(['Students.id'])
//                   // ->contain(['Results'])
//                    ->where(['level_id'=>$level_id,'department_id'=>$department_id,'status'=>'Admitted'])
//                    ->order(['Students.fname'=>'DESC']);
            $this->set('dstudents', $dstudents);
            //  debug(json_encode( $students, JSON_PRETTY_PRINT)); exit;


            $results = $this->Results->find()
                    ->contain(['Students', 'Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions', 'Users', 'Levels'])
                    ->where($conditions);

            $this->set('results', $results);
            // $this->set('dresults', $dresults);
            $this->set('courses', $courses);
        } else { //if this was not a search
            $results = $this->Results->find()
                    ->contain(['Students', 'Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions', 'Users', 'Levels'])
                    ->limit(50);

            // $results = $this->paginate($this->Results);

            $this->set(compact('results'));
        }

        $faculties = $this->Results->Faculties->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $departments = $this->Results->Departments->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $levels = $this->Results->Levels->find('list', ['limit' => 9])
                ->order(['name' => 'ASC']);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 200]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $students = $this->Results->Students->find('list', ['limit' => 2000]);
        $this->set(compact('results', 'levels', 'students', 'faculties', 'departments', 'subjects', 'semesters', 'sessions'));
        $this->viewBuilder()->setLayout('backend');
    }

    /**
     * View method
     *
     * @param string|null $id Result id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $result = $this->Results->get($id, [
            'contain' => ['Students', 'Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions', 'Users', 'Levels']
        ]);

        $this->set('result', $result);
        $this->viewBuilder()->setLayout('backend');
    }

    //admin and teacher method for result bulk upload
    public function uploadresults() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $faculty_id = $this->request->getData('faculty_id');
            $department_id = $this->request->getData('department_id');
            $level_id = $this->request->getData('level_id');
            $session_id = $this->request->getData('session_id');
            $semester_id = $this->request->getData('semester_id');
            $course_id = $this->request->getData('subject_id');

            $filename = $this->request->getData('result');
            $name = $filename->getClientFilename();
            $tmpName = $filename->getStream()->getMetadata('uri');
            $type = $filename->getClientMediaType();
            $error = $filename->getError();
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // echo $ext; exit;
            $allowedext = ['csv', 'xlsx'];
            if ($error != 0) {
                $this->Flash->error(__('Sorry, there is a problem with the file,only csv or xlsx files can be uploaded. Please check and try again'));

                return $this->redirect(['action' => 'uploadresults']);
            }
            if (!in_array($ext, $allowedext)) {
                $this->Flash->error(__('Sorry, only csv or xlsx files can be uploaded.'));

                return $this->redirect(['action' => 'uploadresults']);
            } else {
                $helper = new Helper\Sample();
                //  debug($helper);
                $spreadsheet = IOFactory::load($tmpName);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $count = 0;
                $inserted = 0;
                $duplicate_results = 0;
                $unknown_students = 0;
                //note students with old results
                $regnos = "";
                foreach ($sheetData as $data) {

                    $count++;

                    if ($count > 1) {
                        // debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                        $level = $this->Results->Levels->get($level_id);
                        $department = $this->Results->Departments->get($department_id);
                        $semester = $this->Results->Semesters->get($semester_id);
                        $course = $this->Results->Subjects->get($course_id);
                        $session = $this->Results->Sessions->get($session_id);

//               echo strtolower(trim($semester->name)) .' '. strtolower(trim($data['H'])).'<br />';
//              echo strtolower(trim($department->name)) .' '. strtolower(trim($data['G'])).'<br />';
//             echo  strtolower(trim($level->name)).' '. strtolower(trim($data['J'])).'<br />';
//             echo  strtolower(trim($course->name)) .' '. strtolower(trim($data['F'])).'<br />';
//            echo   strtolower(trim($session->name)) .' '. strtolower(trim($data['I'])).'<br />';
//            exit;
                       // if (strtolower(trim($department->name)) === strtolower(trim($data['G'])) &&
                               // strtolower(trim($course->name)) === strtolower(trim($data['F'])) &&
                               // strtolower(trim($semester->name)) === strtolower(trim($data['H'])) &&
                                // strtolower(trim($level->name)) === strtolower(trim($data['J'])) &&
                              //  strtolower(trim($session->name)) === strtolower(trim($data['I']))) {
//ensure all needed fields are provided to avoid throwing error
//                                if(empty($data['A'])|| empty($data['B'])|| empty($data['C']) || empty($data['F'])
//                                        || empty($data['I'])){
//                                    $this->Flash->error('Incomplete data provided. Please check after item  : ' . $inserted);
//                            return $this->redirect(['action' => 'uploadresults']);
//                                        }
                            //get the student and ensure no double result
                            //  debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                       
                            $student = $this->Results->Students->find()->where(['regno' => (string)$data['A']])->first();
                            //ensure no result for this course already
                           // debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;

                            if ($student) {
                                //note students with old results
                                $regnos = "";
                                $has_result = $this->Results->find()->where(['regno' => (string)$data['A'],
                                            'department_id' => $department_id, 'subject_id' => $course_id, 'semester_id' => $semester_id, 'session_id' => $session_id])->first();
                                   
                                if (empty($has_result) && !empty($data['A'])) {
                                    //create a new result for this student
                                    $result = $this->Results->newEmptyEntity();
                                    $result->regno = $data['A'];
                                    $result->level_id = $level_id;
                                    $result->student_id = $student->id;
                                    $result->faculty_id = $faculty_id;
                                    // $result->ca = $data['C'];
                                    $result->total = $data['B'] + $data['C'];
                                    $result->department_id = $department_id;
                                    $result->ca = $data['C'];
                                    $result->semester_id = $semester_id;
                                    $result->subject_id = $course_id;
                                    $result->session_id = $session_id;
                                    $result->creditload = $this->getcreditunit($course_id);
                                    $result->score = $data['B'];
                                    $result->grade = $this->getgrade($data['B'] + $data['C']);
                                    $result->user_id = $this->Auth->user('id');
                                    //  debug(json_encode($result, JSON_PRETTY_PRINT)); exit;
                                    $this->Results->save($result);
                                    $inserted++;
                                } else {
                                    $duplicate_results++;
                                  //  $regnos .= '-' . (string)$has_result->regno;
                                    $this->Flash->success('Total results uploaded : ' . $inserted 
                                            . ' Duplicate results found : ' . $duplicate_results-1 
                                            . ' Unknown students : ' . $unknown_students);
                                    return $this->redirect(['action' => 'uploadresults']);
                                }
                            } else {
                                $unknown_students++;
                            }
//                        } else {
//                            $this->Flash->error('Total results uploaded : ' . $inserted . ' Some results failed to upload because selected data didnt match provided data. Please ensure the right department,'
//                                    . 'course, session and faculty was selected. Duplicate results found : ' . $duplicate_results . ' (' . $regnos . ')'
//                                    . ' Unknown students : ' . $unknown_students);
//                            return $this->redirect(['action' => 'uploadresults']);
//                        }

                        // debug(json_encode($data['F'], JSON_PRETTY_PRINT)); exit;
                    }
                }
                //log activity
                $usercontroller = new UsersController();

                $title = "Result Bulk Upload ";
                $user_id = $this->Auth->user('id');
                $description = "Uploaded " . $inserted . ' results';
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__($inserted . ' Result(s) have been uploaded successfully. Duplicates found : ' . $duplicate_results . ' (' . $regnos . ')' . ' Unknown students : ' . $unknown_students));

                return $this->redirect(['action' => 'uploadresults']);
            }
        }

        $faculties = $this->Results->Faculties->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $departments = $this->Results->Departments->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 900])
                ->order(['name' => 'ASC']);

        $levels = $this->Results->Levels->find('list', ['limit' => 200])->where(['id !=' => 6])->order(['name' => 'ASC']);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 20]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $this->viewBuilder()->setLayout('backend');
        $this->set(compact('faculties', 'departments', 'subjects', 'semesters', 'sessions', 'levels'));
    }

    //admin result upload based on courses
    public function uploadcourseresults() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $faculty_id = $this->request->getData('faculty_id');
            //  $department_id = $this->request->getData('department_id');
            $level_id = $this->request->getData('level_id');
            $session_id = $this->request->getData('session_id');
            $semester_id = $this->request->getData('semester_id');
            $course_id = $this->request->getData('subject_id');

            $filename = $this->request->getData('result');
            $name = $filename->getClientFilename();
            $tmpName = $filename->getStream()->getMetadata('uri');
            $type = $filename->getClientMediaType();
            $error = $filename->getError();
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // echo $ext; exit;
            $allowedext = ['csv', 'xlsx'];
            if ($error != 0) {
                $this->Flash->error(__('Sorry, there is a problem with the file,only csv or xlsx files can be uploaded. Please check and try again'));

                return $this->redirect(['action' => 'uploadcourseresults']);
            }
            if (!in_array($ext, $allowedext)) {
                $this->Flash->error(__('Sorry, only csv or xlsx files can be uploaded.'));

                return $this->redirect(['action' => 'uploadcourseresults']);
            } else {
                $helper = new Helper\Sample();
                //  debug($helper);
                $spreadsheet = IOFactory::load($tmpName);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $count = 0;
                $inserted = 0;
                $duplicate_results = 0;
                $unknown_students = 0;
                //note students with old results
                $regnos = "";
                foreach ($sheetData as $data) {

                    $count++;

                    if ($count > 1) {
                        
                        //   $level = $this->Results->Levels->get($level_id);
                        //   $department = $this->Results->Departments->get($department_id);
                        //   $semester = $this->Results->Semesters->get($semester_id);
                        //  $course = $this->Results->Subjects->get($course_id);
                        //  $session = $this->Results->Sessions->get($session_id);
                        $dept_name = trim($data['G']);
                        $student_dept = $this->Results->Departments->find()->where(['name' => $dept_name])->first();
                        
                        $subjectname = trim($data['F']);
                        $student_subject = $subjects = $this->Results->Subjects->find()
                                        ->where(['name' => $subjectname, 'department_id' => $student_dept->id,
                                            'semester_id' => $semester_id, 'level_id' => $level_id])->first();
                         //debug(json_encode( $student_subject, JSON_PRETTY_PRINT)); exit;

//               echo strtolower(trim($semester->name)) .' '. strtolower(trim($data['H'])).'<br />';
//              echo strtolower(trim($department->name)) .' '. strtolower(trim($data['G'])).'<br />';
//             echo  strtolower(trim($level->name)).' '. strtolower(trim($data['J'])).'<br />';
//             echo  strtolower(trim($course->name)) .' '. strtolower(trim($data['F'])).'<br />';
//            echo   strtolower(trim($session->name)) .' '. strtolower(trim($data['I'])).'<br />';
//            exit;
                        if ((!empty($student_dept->id)) && (!empty($student_subject->id))) {

                            //get the student and ensure no double result
                            //  debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                            $student = $this->Results->Students->find()->where(['regno' => $data['A']])->first();
                            //ensure no result for this course already

                            if ($student) {
                                //note students with old results
                                $has_result = $this->Results->find()->where(['regno' => $data['A'],
                                            'department_id' => $student_dept->id, 'subject_id' =>  $student_subject->id, 'semester_id' => $semester_id, 'session_id' => $session_id])->first();

                                if (empty($has_result) && !empty($data['A'])) {

                                    //create a new result for this student
                                    $result = $this->Results->newEmptyEntity();
                                    $result->regno = $data['A'];
                                    $result->level_id = $level_id;
                                    $result->student_id = $student->id;
                                    $result->faculty_id = $student->faculty_id;
                                    // $result->ca = $data['C'];
                                    $result->total = $data['B'] + $data['C'];
                                    $result->department_id = $student_dept->id;
                                    $result->ca = $data['C'];
                                    $result->semester_id = $semester_id;
                                    $result->subject_id = $student_subject->id;
                                    $result->session_id = $session_id;
                                    $result->creditload = $student_subject->creditload;
                                    $result->score = $data['B'];
                                    $result->grade = $this->getgrade($data['B'] + $data['C']);
                                    $result->user_id = $this->Auth->user('id');
                                    //  debug(json_encode($result, JSON_PRETTY_PRINT)); exit;
                                    $this->Results->save($result);
                                    $inserted++;
                                } else {
                                    $duplicate_results++;
                                    $regnos .= '-' . $has_result->regno;
                                    $this->Flash->error('Total results uploaded : ' . $inserted . ' Some results failed to upload because selected data didnt match provided data. Please ensure the right department,'
                                            . 'course, session and faculty was selected. Duplicate results found : ' . $duplicate_results . ' (' . $regnos . ')'
                                            . ' Unknown students : ' . $unknown_students);
                                    return $this->redirect(['action' => 'uploadcourseresults']);
                                }
                            } else {
                                $unknown_students++;
                            }
                        } else {
                            $this->Flash->error('Total results uploaded : ' . $inserted . ' Some results failed to upload because selected data didnt match provided data. Please ensure the right department,'
                                    . 'course, session and faculty was selected. Duplicate results found : ' . $duplicate_results . ' (' . $regnos . ')'
                                    . ' Unknown students : ' . $unknown_students);
                            return $this->redirect(['action' => 'uploadcourseresults']);
                        }
                    }
                }
                //log activity
                $usercontroller = new UsersController();

                $title = "Result Bulk Upload ";
                $user_id = $this->Auth->user('id');
                $description = "Uploaded " . $inserted . ' results';
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__($inserted . ' Result(s) have been uploaded successfully. Duplicates found : ' . $duplicate_results . ' (' . $regnos . ')' . ' Unknown students : ' . $unknown_students));

                return $this->redirect(['action' => 'uploadcourseresults']);
            }
        }

        $faculties = $this->Results->Faculties->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $departments = $this->Results->Departments->find('list', ['limit' => 200])
                ->order(['name' => 'ASC']);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 700])
                ->order(['id' => 'ASC']);

        $levels = $this->Results->Levels->find('list', ['limit' => 200])->where(['id !=' => 6])->order(['name' => 'ASC']);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 20]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $this->viewBuilder()->setLayout('backend');
        $this->set(compact('faculties', 'departments', 'subjects', 'semesters', 'sessions', 'levels'));
    }

    //method that gets a subject and return the credit unit for result upload
    public function getcreditunit($subjectid) {
        $subjects_table = TableRegistry::get('Subjects');
        $subject = $subjects_table->get($subjectid);
        return $subject->creditload;
    }

    //method that returns grade based on the score
    public function getgrade($total) {
        $grade = "";
        if ($total >= 70) {
            $grade = "A";
        } elseif ($total >= 60) {
            $grade = "B";
        } elseif ($total >= 50) {
            $grade = "C";
        } elseif ($total >= 45) {
            $grade = "D";
        } elseif ($total >= 40) {
            $grade = "E";
        } elseif ($total <= 39) {
            $grade = "F";
        }

        return $grade;
    }

    //method that downloads the result file format 
    public function downloadformat() {
        // $url = Router::url('/', true);
        $ext = pathinfo("result_sample.xlsx", PATHINFO_EXTENSION);
        // echo  basename($pathtofile."cvs/students_format.xlsx"); exit;
        $filename = "result_sample.xlsx";
        header('Content-Type: ' . $ext);
        header('Content-Length: ' . filesize("cvs/result_sample.xlsx"));
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header("Cache-control: private");
        readfile("cvs/result_sample.xlsx");
        return;
    }

    //admin method for generating transcript
    public function gettranscript($student_id) {
        //check for any pending carry over
        $check_standing = $this->checkstanding($student_id);
        //get last result session for graduation year
        $graduation_years = $this->getlastresultsession($student_id);
        $students_table = TableRegistry::get('Students');
        $student = $students_table->get($student_id, ['contain' => ['Departments', 'Programmes', 'Users', 'Countries', 'States', 'Faculties']]);
        // debug(json_encode(  $student, JSON_PRETTY_PRINT)); exit; 


        $year1 = $this->Results->find()->contain(['Subjects', 'Sessions'])->where(['student_id' => $student_id, 'Results.level_id' => 1]);
        $year2 = $this->Results->find()->contain(['Subjects', 'Sessions'])->where(['student_id' => $student_id, 'Results.level_id' => 2]);
        $year3 = $this->Results->find()->contain(['Subjects', 'Sessions'])->where(['student_id' => $student_id, 'Results.level_id' => 3]);
        $year4 = $this->Results->find()->contain(['Subjects', 'Sessions'])->where(['student_id' => $student_id, 'Results.level_id' => 4]);
        // debug(json_encode( $year3, JSON_PRETTY_PRINT)); exit; 
        $this->set(compact('year1', 'year2', 'year3', 'year4', 'student', 'graduation_years'));
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method that check if a student is cleared for transcript
    public function checkstanding($student_id) {
        $check_standing = $this->Results->find()->where(['student_id' => $student_id, 'grade' => "F"]);

        if (!empty($check_standing)) {

            //check if carryover has been written
            foreach ($check_standing as $result) {
                $this->verifycarryover($result->student_id, $result->subject_id, $result->session_id);
            }
        }
    }

    //admin method that checks if a carryover has been passed by the student
    private function verifycarryover($student_id, $subject_id, $failed_session_id) {
        $passed_carryover = $this->Results->find()->where(['student_id' => $student_id,
            'grade !=' => "F", 'subject_id' => $subject_id, 'session_id !=' => $failed_session_id]);

        if (empty($passed_carryover->toArray())) {
            //debug(json_encode(  $passed_carryover, JSON_PRETTY_PRINT)); exit;
            //has some uncleared carryover
            $this->Flash->error(__('This student is not on clear standings. Has some CARRYOVER COURSES UNCLEARED.'));
            return $this->redirect(['controller' => 'Admins', 'action' => 'managetranscriptorders']);
        } else {
            return;
        }
    }

    //admin method that returns the graduation year based on last entered result session
    public function getlastresultsession($student_id) {
        $last_result = $this->Results->find()->contain(['Sessions'])
                        ->where(['student_id' => $student_id])
                        ->order(['session_id' => 'DESC'])
                        ->limit(1)->last();

        $last_session = $last_result->session->name;
        $syears = explode("/", $last_session);
        return $syears[0];
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $result = $this->Results->newEmptyEntity();
        if ($this->request->is('post')) {
            $result = $this->Results->patchEntity($result, $this->request->getData());
            if ($this->Results->save($result)) {
                $this->Flash->success(__('The result has been saved.'));

                return $this->redirect(['action' => 'manageresults']);
            }
            $this->Flash->error(__('The result could not be saved. Please, try again.'));
        }
        $students = $this->Results->Students->find('list', ['limit' => 200]);
        $faculties = $this->Results->Faculties->find('list', ['limit' => 200]);
        $departments = $this->Results->Departments->find('list', ['limit' => 200]);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 200]);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 200]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $users = $this->Results->Users->find('list', ['limit' => 200]);
        $this->set(compact('result', 'students', 'faculties', 'departments', 'subjects', 'semesters', 'sessions', 'users'));
        $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Edit method
     *
     * @param string|null $id Result id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function updateresult($id = null) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $result = $this->Results->get($id, [
            'contain' => ['Students', 'Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ca = $this->request->getData('ca');
            $score = $this->request->getData('score');
            $total = $ca + $score;
            if ($total >= 70) {
                $grade = "A";
            } elseif ($total >= 60) {
                $grade = "B";
            } elseif ($total >= 50) {
                $grade = "C";
            } elseif ($total >= 45) {
                $grade = "D";
            } elseif ($total >= 40) {
                $grade = "E";
            } elseif ($total <= 39) {
                $grade = "F";
            }
            $result = $this->Results->patchEntity($result, $this->request->getData());
            $result->total = $total;
            $result->grade = $grade;
            if ($this->Results->save($result)) {
                //log activity
                $usercontroller = new UsersController();

                $title = "Updated a Result ";
                $user_id = $this->Auth->user('id');
                $description = "Updated a result " . $result->id;
                $ip = $this->request->clientIp();
                $type = "Update";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The result has been updated.'));

                return $this->redirect(['action' => 'manageresults']);
            }
            $this->Flash->error(__('The result could not be saved. Please, try again.'));
        }
        $students = $this->Results->Students->find('list', ['limit' => 200]);
        $faculties = $this->Results->Faculties->find('list', ['limit' => 200]);
        $departments = $this->Results->Departments->find('list', ['limit' => 200]);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 200]);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 200]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $users = $this->Results->Users->find('list', ['limit' => 200]);
        $this->set(compact('result', 'students', 'faculties', 'departments', 'subjects', 'semesters', 'sessions', 'users'));
        $this->viewBuilder()->setLayout('backend');
    }

    //method that gets a list of all departments in a faculty and puts them in a dropdown
    public function getdepartments($faculty_id) {
        $departments = $this->Results->Departments->find('list', ['limit' => 200])
                ->where(['faculty_id' => $faculty_id]);
        $this->set(compact('departments'));
    }

    //student method for checking their results
    public function myresults() {
        //check fee payment
        $this->checkfeepaymentbeforeresult();
        $student = $this->Results->Students->find()->contain(['Departments'])
                        ->where(['user_id' => $this->Auth->user('id')])->first();
        if ($this->request->is('post')) {

            $session_id = $this->request->getData('session_id');
            $semester_id = $this->request->getData('semester_id');
            $course_id = $this->request->getData('subject_id');
            $level_id = $this->request->getData('level_id');
            $conditions = [];
            if (!empty($semester_id)) {
                $conditions['Results.semester_id'] = $semester_id;
            }
            if (!empty($course_id)) {
                $conditions['Results.subject_id'] = $course_id;
            }
            if (!empty($session_id)) {
                $conditions['Results.session_id'] = $session_id;
            }
            if (!empty($level_id)) {
                $conditions['Results.level_id'] = $level_id;
            }
            $results = $this->Results->find()
                    ->contain(['Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions', 'Levels'])
                    ->where(['Results.regno' => $student->regno])
                    ->where($conditions);
            //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
            $this->set('results', $results);
        } else {
            $results = $this->Results->find()
                    ->contain(['Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions', 'Levels'])
                    ->where(['student_id' => $student->id]);

            //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
            $this->set('results', $results);
        }

        $subjects = $this->Results->Subjects->find('list', ['limit' => 200]);
        $levels = $this->Results->Levels->find('list', ['limit' => 200])->where(['id !=' => 5])->order(['name' => 'ASC']);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 200]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $this->set(compact('subjects', 'semesters', 'sessions', 'student', 'levels'));

        $this->viewBuilder()->setLayout('studentsbackend');
    }

    
    //this method was used to correct a result given to the wrong student by using the wrong regno
    public function correctresult(){
     
        //get all the result for the session 2022/2023
        $results = $this->Results->find()
                    ->where(['student_id' => 33,'session_id'=>6]);
        
        foreach($results as $result){
            //echo $result->regno.'<br />'.$result->department_id; exit;
         $result->faculty_id = 3;
         $result->department_id = 10;
         $result->regno = "CUN2021/0033";
         $result->student_id = 89;
         $this->Results->save($result);
            
        }
       exit; 
    }
    
    
    
    //ENsure student has paid at least 3 different fees for the session before they can see their results
    public function checkfeepaymentbeforeresult(){
        //disable results viewing
        $this->Flash->error(__('Sorry, result viewing is temporarily disabled. Please check back in few days time'));
       return $this->redirect(['controller'=>'Students','action' => 'dashboard']);
        
        $studentscontroller = New StudentsController();
     $student =   $studentscontroller->isstudent();
              $transactions_table = TableRegistry::get('Transactions');
              $session = $this->request->getSession()->read('settings');
              $past_session_id = $session->session_id - 1;
     //check payment
     $payment =  $transactions_table->find()
             ->where(['student_id'=>$student->id,'session_id'=>$past_session_id])
             ->count();
     if(  $payment>=3){
         return;
     }
     else{
         $this->Flash->error(__('Sorry, you need to pay some of your fees before you can access your results.'));
       return $this->redirect(['controller'=>'Students','action' => 'dashboard']);  
     }
        
    }
    
    
    //method that checks that a result is approved before students can see them
    public function checkifapproved($sessionid,$semesterid){
        $approvedresults_table = TableRegistry::get('Approvedresults'); 
        $status = $approvedresults_table->find(['session_id'=>$sessionid,
            'semester_id'=>$semesterid,'status'=>'Approved'])->first();
        if(!empty($status->id)){
            return;
        }
        else{
        $this->Flash->error(__('Sorry, the result you are looking for is currently unavailable.'));
       return $this->redirect(['controller'=>'Students','action' => 'dashboard']);  
     }
    }
    
    
    //calculate CGPA
    public function calculateCGPA($regnumb) {
        //$results_table = TableRegistry::get('Results');
        $courses_table = TableRegistry::get('Subjects');
        $constants_table = TableRegistry::get('Constants');
        $total = 0;
        $totalUnits = 0;
        $results = $this->Results->find()->where(['regnumb' => $regnumb]);
        $l = 0;

        //  debug(json_encode( $results, JSON_PRETTY_PRINT)); exit;
        foreach ($results as $result) {
            $credit_unit = $courses_table->get($result->course_id);
            $grade_point_quality = $constants_table->find()->where(['name' => $result->grade])->first();
            $course_point = $grade_point_quality->value * $credit_unit->creditload;
            $total += $course_point;
            $totalUnits += $credit_unit->creditload;
            $l++;
        }
        return number_format($total / $totalUnits, 2);
    }

    /**
     * Delete method
     *
     * @param string|null $id Result id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $result = $this->Results->get($id);
        if ($this->Results->delete($result)) {
            //log activity
            $usercontroller = new UsersController();

            $title = "Deleted a Result ";
            $user_id = $this->Auth->user('id');
            $description = "Deleted a result " . $result->id;
            $ip = $this->request->clientIp();
            $type = "Delete";
            $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
            $this->Flash->success(__('The result has been deleted.'));
        } else {
            $this->Flash->error(__('The result could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'manageresults']);
    }
    
    
    //admin method for removing results uploaded by mistake
    public function removeresult(){
           //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(3) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
       if ($this->request->is('post')) {
           $count = 0;
     $session_id = $this->request->getData('session_id');
            $semester_id = $this->request->getData('semester_id');
            $course_id = $this->request->getData('subject_id');
            $level_id = $this->request->getData('level_id');
      $dept_id = $this->request->getData('department_id');
      $results = $this->Results->find()->where(['semester_id'=>$semester_id,
          'subject_id'=>$course_id,'level_id'=>$level_id,'department_id'=>$dept_id,'session_id'=>$session_id]);
    // debug(json_encode( $results, JSON_PRETTY_PRINT)); exit;
      foreach($results as $result){
       $this->Results->delete($result); 
                $count++;
      }
      
      $this->Flash->success(__($count .' results have been deleted'));
       }
       
        $departments = $this->Results->Departments->find('list', ['limit' => 200])->order(['name'=>'ASC']);
        $subjects = $this->Results->Subjects->find('list', ['limit' => 4000]);
        $semesters = $this->Results->Semesters->find('list', ['limit' => 200]);
        $sessions = $this->Results->Sessions->find('list', ['limit' => 200]);
        $levels = $this->Results->Levels->find('list', ['limit' => 200]);
        $this->set(compact('departments', 'subjects', 'semesters', 'sessions', 'levels'));
        $this->viewBuilder()->setLayout('backend');
        
       // return $this->redirect(['action' => 'removeresult']);
    }
    
    

    //method that gets the students in a given department
    public function getdaepts($faculty_id) {
        $departments_table = TableRegistry::get('Departments');
        $departments = $departments_table->find('list')
                ->where(['faculty_id' => $faculty_id])
                ->order(['name' => 'ASC']);
        $this->set('departments', $departments);
    }

    //returns the students in a selected department during result search
    public function studentsindept($dept_id) {
        $students_table = TableRegistry::get('Students');
        $students = $students_table->find('list')
                ->where(['department_id' => $dept_id])
                ->order(['fname' => 'ASC']);
        $this->set('students', $students);
    }

    //admin method for managing and generating transcripts
    public function managetranscript() {

        $students_table = TableRegistry::get('Students');
        $departments_table = TableRegistry::get('Levels');

        if ($this->request->is('post')) {

            $dept_id = $this->request->getData('department_id');
            $level_id = $this->request->getData('level_id');
            $conditions = [];
            if (!empty($dept_id)) {
                $conditions['Students.department_id'] = $dept_id;
            }
            if (!empty($level_id)) {
                $conditions['Students.level_id'] = $level_id;
            }
            $students = $students_table->find()
                    ->contain(['Faculties', 'Departments', 'Levels'])
                    ->where($conditions);
            //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
            $this->set('students', $students);
        } else {
            $students = $students_table->find()
                    ->contain(['Faculties', 'Departments', 'Levels'])
                    ->where(['Students.level_id' => 4]);

            //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
            $this->set('students', $students);
        }


        $levels = $students_table->Levels->find('list', ['limit' => 20])->order(['name' => 'ASC']);
        $departments = $students_table->Departments->find('list', ['limit' => 200]);

        $this->set(compact('departments', 'levels'));

        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for getting all the results of a student
    public function getallresults($student_id, $name) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(5) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $students_table = TableRegistry::get('Students');
        $student = $students_table->get($student_id, ['contain' => ['Faculties', 'Departments', 'Levels']]);
        $results = $this->Results->find()
                ->contain(['Subjects', 'Semesters', 'Sessions', 'Levels'])
                ->where(['student_id' => $student->id])
                ->order(['Results.session_id' => 'ASC']);
        //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
        $this->set(compact('results', 'student'));
        $this->viewBuilder()->setLayout('backend');
    }
    
    
    
    
      // allow unrestricted pages
    public function beforeFilter(EventInterface $event) {
     

        $actions = ['uploadcourseresults', 'uploadresults'];
        if (in_array($this->request->getParam('action'), $actions)) {
            // turn form protection 
            $this->FormProtection->setConfig('validate', false);
        }
    }

}
