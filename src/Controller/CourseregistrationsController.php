<?php
declare(strict_types=1);

  namespace App\Controller;

  use Cake\Mailer\Email;
  use Cake\Event\Event;
  use Cake\ORM\TableRegistry;
  use App\Controller\AppController;

  /**
   * Courseregistrations Controller
   *
   * @property \App\Model\Table\CourseregistrationsTable $Courseregistrations
   *
   * @method \App\Model\Entity\Courseregistration[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
   */
  class CourseregistrationsController extends AppController {

      /**
       * Index method
       *
       * @return \Cake\Http\Response|void
       */
      public function index() {
          $this->paginate = [
              'contain' => ['Students', 'Sessions', 'Semesters', 'Levels']
          ];
          $courseregistrations = $this->paginate($this->Courseregistrations);

          $this->set(compact('courseregistrations'));
      }

      /**
       * View method
       *
       * @param string|null $id Courseregistration id.
       * @return \Cake\Http\Response|void
       * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
       */
      public function view($id = null) {
          $courseregistration = $this->Courseregistrations->get($id, [
              'contain' => ['Students.Departments', 'Sessions', 'Semesters', 'Levels', 'Subjects']
          ]);

          $this->set('courseregistration', $courseregistration);
           $this->viewBuilder()->setLayout('backend');
      }

      /**
       * Add method
       *
       * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
       */
      public function register() {
          //get the current session
          $sesion = $this->request->getSession()->read('settings');
          //get the course registration_subjects table
          $subject_Table = TableRegistry::get('Subjects');
          $course_assignments = TableRegistry::get('Courseassignments'); 
          $departments_table = TableRegistry::get('Departments'); 
          //ensure this is a valid student
          $student = $this->isstudent();
           //check if this guy has already registered
              $this->checkcourseregistraion($student->id,$student->level_id); 
         
          $courseregistration = $this->Courseregistrations->newEmptyEntity();
          if ($this->request->is('post')) {
             
              //check if there is a carry over course
              // $selected_courses = $this->request->getData('subjects._ids');

      
              $courseregistration = $this->Courseregistrations->patchEntity($courseregistration, $this->request->getData());
              $courseregistration->student_id = $student->id;
              $courseregistration->session_id = $sesion->session_id;
              $courseregistration->level_id = $student->level_id;
             $courseregistration->semester_id = $sesion->semester_id;
             // debug(json_encode($courseregistration, JSON_PRETTY_PRINT)); exit;
              if ($this->Courseregistrations->save($courseregistration)) {
                  //register assigned courses
                  $this->registerassignedcourses($courseregistration->id,$student->level_id,$student->department_id,$sesion->semester_id);

                  $this->Flash->success(__('Your course registration was succesful.'));

                  return $this->redirect(['action' => 'registeredcourses']);
              }
          
              $this->Flash->error(__('The course registration could not be saved. Please, try again.'));
         // }
          }
           $semesters = $this->Courseregistrations->Semesters->find('list', ['limit' => 2]);
          $levels = $this->Courseregistrations->Levels->find('list', ['limit' => 4])->order(['id'=>'ASC']);
          $subjects =  $course_assignments->Subjects->find('list', ['limit' => 2000])
                  ->where(['department_id'=> $student->department_id,'level_id'=> $student->level_id,'semester_id'=>$sesion->semester_id]);
         $departments =  $departments_table->find('list')->order(['name'=>'DESC']);
         $assigned_courses =  $subject_Table->find('all', ['limit' => 30])->where(['department_id' => $student->department_id,
             'level_id'=> $student->level_id,'semester_id'=>$sesion->semester_id]);
          $this->set(compact('courseregistration', 'assigned_courses','student', 'departments', 'semesters', 'levels', 'subjects'));
        // $this->set('session_id', $sesion->session_id);
          $this->viewBuilder()->setLayout('studentsbackend');
      }

      
      //student method for chosing a semester to register its courses
      public function choosesemestercourses(){
          
            if ($this->request->is('post')) {
                $level_id = $this->request->getData('level_id');
                $semester_id = $this->request->getData('semester_id');
                $session_id = $this->request->getData('session_id');
                 //ensure this is a valid student
          $student = $this->isstudent();
          //check previouse registration
       $reg_status =   $this->checkpastcourseregistraion($student->id,$student->level_id,$session_id,$semester_id);
         if( $reg_status==0){
       //create new course registration entity
          $courseregistration = $this->Courseregistrations->newEmptyEntity();
          $courseregistration->level_id =  $level_id;
          $courseregistration->student_id = $student->id;
          $courseregistration->semester_id = $semester_id;
          $courseregistration->session_id = $session_id;
        if($this->Courseregistrations->save($courseregistration)){
  //get the assigned courses 
                $course_assignments = TableRegistry::get('Courseassignments'); 
               //get the course registration_subjects table
          $coursereg_students_Table = TableRegistry::get('CourseregistrationsSubjects');
                   $assigned_courses =  $course_assignments->find()
                       ->contain(['Subjects'])
                      ->where(['department_id'=> $student->department_id,'level_id'=> $level_id ,'semester_id'=> $semester_id])
                      ->first();
                    //save the main courses asign to his dept
                  foreach ($assigned_courses->subjects as $subject) {
                      $coursereg = $coursereg_students_Table->newEmptyEntity();
                      $coursereg->courseregistration_id = $courseregistration->id;
                      $coursereg->subject_id = $subject->id;

                      $coursereg_students_Table->save($coursereg);
                  }
                  
                   $this->Flash->success(__('The course registration was succesful.'));

                  return $this->redirect(['action' => 'registeredcourses']);
        }else{
            //unable to register the courses for a past semester
           $this->Flash->error(__('Sorry, unable to register courses. Please, try again.')); 
           return $this->redirect(['action' => 'registeredcourses']);
        }
                
            }
            else{
                //unable to register the courses for a past semester
              $this->Flash->error(__('You have already registered your courses for the chosen semester. View and print below or contact admin for further assistance'));  
              return $this->redirect(['action' => 'registeredcourses']);
              
            }
            }
          
          $sessions = $this->Courseregistrations->Sessions->find('list', ['limit' => 200]);
          $semesters = $this->Courseregistrations->Semesters->find('list', ['limit' => 200]);
          $levels = $this->Courseregistrations->Levels->find('list', ['limit' => 200]);
         
          $this->set(compact('sessions', 'semesters', 'levels'));
           $this->viewBuilder()->setLayout('studentsbackend');
      }


           
          //check that max unit is not exceeded
          private function checkMaxUnit($selectedcourses,$dept_id){
           $reg_unit =   $this->request->getSession()->read('creditload_registered');
          $subject_table =  TableRegistry::get('Subjects'); 
           $departments_table =  TableRegistry::get('Departments');
          $chosen_units = 0;
           foreach ($selectedcourses as $course_id) {
                    if (is_numeric($course_id)) {
                        
                      $course =  $subject_table->get($course_id);
                      $chosen_units +=$course->creditload;    
                    }
          
          }
          $department = $departments_table->get($dept_id);
           $max_unit =  $department->maxunit;
          $total_unit = $reg_unit+$chosen_units;
          if( $total_unit > $max_unit){ return 0;}else{return 1;}
          
         // return $reg_unit+$chosen_units;
          }

          
                
          //method that adds courses chosen by the student
          private function addcourses($courseids,$coursereg_id){
             $coursereg_subject_table =  TableRegistry::get('CourseregistrationsSubjects'); 
              $count = 0;
            if (!empty($courseids)) {
                foreach ($courseids as $course_id) {
                    if (is_numeric($course_id)) {
                        //check that course has not been registered before
                        If($this->checkCourse($coursereg_id,$course_id)==1){
                        $coursereg =  $coursereg_subject_table->newEmptyEntity();
                      $coursereg->courseregistration_id = $coursereg_id;
                      $coursereg->subject_id = $course_id;
                      $coursereg_subject_table->save($coursereg);

                        $count++;
                        //echo "value : " . $value . '<br/>';    
                    }
                    }
                }
            }
            return $count;
           
          }
          
          
          
          //method that registers all assigned courses for the student
          private function registerassignedcourses($coursereg_id,$level_id,$dept_id,$semester_id){
            $coursereg_subject_table =  TableRegistry::get('CourseregistrationsSubjects'); 
             $subject_Table =  TableRegistry::get('Subjects');
              $assigned_courses = $subject_Table->find()->where(['department_id' => $dept_id,
             'level_id'=> $level_id,'semester_id'=>$semester_id]);
            // debug(json_encode( $assigned_courses, JSON_PRETTY_PRINT)); exit; 
               foreach ($assigned_courses as $course) {
                        //check that course has not been registered before
                        If($this->checkCourse($coursereg_id,$course->id)==1){
                        $coursereg =  $coursereg_subject_table->newEmptyEntity();
                      $coursereg->courseregistration_id = $coursereg_id;
                      $coursereg->subject_id = $course->id;
                      $coursereg_subject_table->save($coursereg);

                      //  $count++;
                        //echo "value : " . $value . '<br/>';    
                    }
                
                    
                }
                return;
              
              
          }
          
          
          
              
          //check that a particular course has not been registred by this student
          private function checkCourse($coursereg_id,$course_id){
                $coursereg_subject_table =  TableRegistry::get('CourseregistrationsSubjects'); 
                $registered_course = $coursereg_subject_table->find()
                        ->where(['courseregistration_id'=>$coursereg_id,'subject_id'=>$course_id])->first();
                if(!empty($registered_course->subject_id)){
                    return 0; //already registered, do not register the course again
                }else{ //register course
                    return 1;
                    
                }
              
          }
            
      //student method for deleting registered courses
        public function deletecourses($id = null) {
          $this->request->allowMethod(['post', 'delete']);
          $courseregistration = $this->Courseregistrations->get($id);
          if ($this->Courseregistrations->delete($courseregistration)) {
              $this->Flash->success(__('The course registration data has been deleted.'));
             // $this->courseregdeletealert( $courseregistration->student_id);
          } else {
              $this->Flash->error(__('The course registration data could not be deleted. Please, try again.'));
          }

          return $this->redirect(['controller'=>'Courseregistrations','action' => 'register']);
      }
      

      //method that ensures the student has not registered courses already for the current semester
      private function checkcourseregistraion($student_id,$level_id){
           //get the current session
          $sesion = $this->request->getSession()->read('settings');
          $coursereg =  $this->Courseregistrations->find()
                  ->where(['student_id'=>$student_id,'level_id'=>$level_id,'session_id'=>$sesion->session_id,
                      'semester_id'=>$sesion->semester_id])->first();
            // debug(json_encode( $coursereg, JSON_PRETTY_PRINT)); exit;
          if(!empty($coursereg)){
              $this->Flash->error(__('You have already registered your courses for this semester. View and print below or contact admin for further assistance'));
           return $this->redirect(['action' => 'registeredcourses']);
          }
          return;
          
      }
      
        //method that ensures the student has not registered courses already for the current semester
      private function checkpastcourseregistraion($student_id,$level_id,$session_id,$semester_id){
           //get the current session
         // $sesion = $this->request->getSession()->read('settings');
          $coursereg =  $this->Courseregistrations->find()
                  ->where(['student_id'=>$student_id,'level_id'=>$level_id,'session_id'=>$session_id,
                      'semester_id'=>$semester_id])->first();
          if(!empty($coursereg)){
              $this->Flash->error(__('You have already registered your courses for the chosen semester. View and print below or contact admin for further assistance'));
           return 1;
          }
          return 0;
          
      }


    //student method for registering courses without carry over
      private function registerfreshcourses($student_id, $department, $level_id) {
          //get the current session
          $sesion = $this->request->getSession()->read('settings');
          $coursereg_students_Table = TableRegistry::get('CourseregistrationsSubjects');
          $courseregistration = $this->Courseregistrations->newEmptyEntity();
          $courseregistration->student_id = $student_id;
          $courseregistration->session_id = $sesion->session_id;
          $courseregistration->semester_id = $sesion->semester_id;
          $courseregistration->level_id = $level_id;
         //  debug(json_encode($department, JSON_PRETTY_PRINT)); exit;
          $this->Courseregistrations->save($courseregistration);
          //save the main courses asign to his dept
          foreach ($department->subjects as $subject) {
              $coursereg = $coursereg_students_Table->newEmptyEntity();
              $coursereg->courseregistration_id = $courseregistration->id;
              $coursereg->subject_id = $subject->id;

              $coursereg_students_Table->save($coursereg);
             
          }
           $this->Flash->success(__('Your course registration was succesful.'));
           return $this->redirect(['action' => 'registeredcourses']);
      }

      //method that dispalys all courses registered by this student
      public function registeredcourses() {
          //ensure this is a valid student
          $student = $this->isstudent();
          $registeredcourses = $this->Courseregistrations->find()
                  ->contain(['Sessions', 'Levels', 'Semesters'])
                  ->where(['student_id' => $student->id]);
          $this->set(compact('registeredcourses', 'student'));
          $this->viewBuilder()->setLayout('studentsbackend');
      }

      //method that shows a teacher the students that registered for his course
      public function coursestudents() {
          $course_subjects_table = TableRegistry::get('CourseregistrationsSubjects');
             //get the current session
          $sesion = $this->request->getSession()->read('settings');
          //get this teacher
         // $teacherscontroller = new TeachersController();
         // $teacher = $teacherscontroller->isteacher();
//          $teacher_subjects = [];
//          foreach ($teacher->subjects as $subject) {
//              array_push($teacher_subjects, $subject->id);
//          }
 $courseregistrations = $course_subjects_table->find()->distinct(['subject_id'])
         ->contain(['Subjects']);
         
//          $courseregistrations = $course_subjects_table->find()
//                  ->contain(['Courseregistrations.Sessions', 'Courseregistrations.Semesters',
//                      'Courseregistrations.Subjects'])
//                 // ->where(['subject_id IN ' => $teacher_subjects])
//                  ->where(['session_id'=>$sesion->session_id,'semester_id'=>$sesion->semester_id])
//                  ->distinct(['subject_id', 'Courseregistration_id'])
//                  ->order(['Courseregistrations.date_created' => 'ASC']);
          // debug(json_encode($courseregistrations, JSON_PRETTY_PRINT)); exit;
         // $this->set('teacher_subjects', $teacher_subjects);
          $this->set('courseregistrations', $courseregistrations);
          $this->viewBuilder()->setLayout('backend');
      }

      //show the student that registered for this course
      public function viewregisteredstudents($id) {
          //get the current session
          $sesion = $this->request->getSession()->read('settings');
          $coursereg_students_Table = TableRegistry::get('CourseregistrationsSubjects');
          $students_Table = TableRegistry::get('Subjects');
          $subject =  $students_Table->get($id);
          $registrations = $coursereg_students_Table->find()
                  ->contain(['Courseregistrations.Students.Departments', 'Courseregistrations.Students.Programmes',
                      'Courseregistrations.Levels','Courseregistrations.Students.Levels','Courseregistrations.Students.Users',
                      'Courseregistrations.Sessions', 'Courseregistrations.Semesters'])
                  ->where(['subject_id' => $id, 'Courseregistrations.session_id' => $sesion->session_id,
              'Courseregistrations.semester_id' => $sesion->semester_id]);
//        $courseregistration = $this->Courseregistrations->get($id, [
//            'contain' => ['Students', 'Sessions', 'Semesters', 'Levels']
//        ]);
          // debug(json_encode( $registrations, JSON_PRETTY_PRINT)); exit;
          $this->set('courseregistration', $registrations);
          $this->set('subject', $subject);
          $this->viewBuilder()->setLayout('backend');
      }

      //shows the student his registered courses for the semester https://www.youtube.com/watch?v=j957SvQHd1c
      public function viewcourses($id) {
          //ensure this is a valid student
          $student = $this->isstudent();
          $courseregistration = $this->Courseregistrations->get($id, [
              'contain' => ['Sessions', 'Semesters', 'Levels', 'Subjects']
          ]);
         // debug(json_encode( $courseregistration, JSON_PRETTY_PRINT)); exit;
          $this->set('courseregistration', $courseregistration);
          $this->set('student', $student);

          $this->viewBuilder()->setLayout('studentsbackend');
      }

      //method that ensure this person is a student
      private function isstudent() {
          $students_Table = TableRegistry::get('Students');
          $student = $students_Table->find()
                  ->contain(['Departments', 'Levels'])
                  ->where(['user_id' => $this->Auth->user('id')])
                  ->first();
          if (!$student) { //this is not a valid student
              $this->Flash->error(__('Sorry, invalid access'));

              return $this->redirect(['action' => 'index']);
          } else {
              return $student;
          }
      }

      /**
       * Edit method
       *
       * @param string|null $id Courseregistration id.
       * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
       * @throws \Cake\Network\Exception\NotFoundException When record not found.
       */
       public function edit($id = null) {
          $courseregistration = $this->Courseregistrations->get($id, [
              'contain' => ['Subjects.Departments','Subjects.Teachers','Subjects.Semesters']
          ]);
            $student = $this->isstudent();
        // debug(json_encode($courseregistration, JSON_PRETTY_PRINT)); exit;
            //ensure this course registration belongs to this student
            if($courseregistration->student_id != $student->id){
             $this->Flash->error(__('Invalid access.'));

                  return $this->redirect(['controller'=>'Users','action' => 'login']);   
            }
          
           $departments_table = TableRegistry::get('Departments');
              $course_assignments = TableRegistry::get('Courseassignments'); 
              //get the current session
          $sesion = $this->request->getSession()->read('settings');
          if ($this->request->is(['patch', 'post', 'put'])) {
              $chosen_courses = $this->request->getData('subjects._ids');
              if(!empty( $chosen_courses) && $this->checkMaxUnit($chosen_courses, $student->department_id)!=0){
           $couses_added =   $this->addcourses($chosen_courses,$id); 
           $this->Flash->success(__('The course registration has been updated, '. $couses_added.' Courses added'));
            return $this->redirect(['action' => 'edit',$courseregistration->id,$courseregistration->session_id]);
              }
              else{ //the student has selected more credit unit than 35, so dont register
                $this->Flash->error(__('Sorry, you have selected more courses than your credit unit for '
                        . 'the semester allows, kindly remove some courses and try again.'));

                 return $this->redirect(['action' => 'edit',$courseregistration->id,$courseregistration->session_id]);   
                  
              }
              
        
             // $courseregistration = $this->Courseregistrations->patchEntity($courseregistration, $this->request->getData());
//              if ($this->Courseregistrations->save($courseregistration)) {
//                  $this->Flash->success(__('The course registration has been updated.'));
//
//                  return $this->redirect(['action' => 'edit',$courseregistration->id,$courseregistration->session_id]);
//              }
              $this->Flash->error(__('The course registration could not be updated. Please, try again.'));
          }
         $sessions = $this->Courseregistrations->Sessions->find('list', ['limit' => 10])->order(['name'=>'DESC']);
          $semesters = $this->Courseregistrations->Semesters->find('list', ['limit' => 2]);
          $levels = $this->Courseregistrations->Levels->find('list', ['limit' => 4])->order(['id'=>'ASC']);
          $subjects =  $course_assignments->Subjects->find('list', ['limit' => 200])->order(['name'=>'ASC']);
         $departments =  $departments_table->find('list')->order(['name'=>'ASC'])->order(['name'=>'ASC']);
          // $subjects = $this->Courseregistrations->Subjects->find('list', ['limit' => 90])->where(['department_id' => $student->department_id]);
          $this->set(compact('courseregistration','sessions', 'student', 'departments', 'semesters', 'levels', 'subjects'));
         $this->set('session_id', $sesion->session_id);
           $this->viewBuilder()->setLayout('studentsbackend');
          }

          
          
             
      //method that populates courses based on students department
      public function getdeptcourses($semester_id,$deptid,$levelid){
          //$student = $this->isstudent();
        $course_table = TableRegistry::get('Subjects');
      // $course_assignments = TableRegistry::get('CourseassignmentsSubjects');
        $subjects =  $course_table->find('list')
                ->where(['department_id' => $deptid,'level_id'=>$levelid,
                    'semester_id'=>$semester_id])
                ->order(['name'=>'DESC']);
       
        $this->set(compact('subjects'));
          
      } 
      
        
    //method that populates courses based on department
    public function getcoursesindept($deptid){
        $course_table = TableRegistry::get('Subjects');
        $subjects =  $course_table->find('list')
                ->where(['department_id' => $deptid])
                ->order(['name'=>'DESC']);
        $this->set(compact('subjects'));
        
    }
    
    //method that gets courses based on department and level
    public function getdeptcoursesindeptandlevel($deptid,$levelid){
      $course_table = TableRegistry::get('Subjects');
        $subjects =  $course_table->find('list')
                ->where(['department_id' => $deptid,'level_id'=>$levelid])
                ->order(['name'=>'DESC']);
        $this->set(compact('subjects'));  
    }
          
          
      /**
       * Delete method
       *
       * @param string|null $id Courseregistration id.
       * @return \Cake\Http\Response|null Redirects to index.
       * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
       */
      public function delete($id = null) {
          $this->request->allowMethod(['post', 'delete']);
          $courseregistration = $this->Courseregistrations->get($id);
          if ($this->Courseregistrations->delete($courseregistration)) {
              $this->Flash->success(__('The courseregistration has been deleted.'));
          } else {
              $this->Flash->error(__('The courseregistration could not be deleted. Please, try again.'));
          }

          return $this->redirect(['action' => 'index']);
      }

  }
  