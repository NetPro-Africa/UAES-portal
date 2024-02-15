<?php
declare(strict_types=1);

  namespace App\Controller;
 use Cake\Routing\Router;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 use Cake\Event\EventInterface;
  use PhpOffice\PhpSpreadsheet\Helper;
use Cake\Mailer\Mailer;
  use Cake\ORM\TableRegistry;
  use App\Controller\AppController;

  /**
   * Teachers Controller
   *
   * @property \App\Model\Table\TeachersTable $Teachers
   *
   * @method \App\Model\Entity\Teacher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
   */
  class TeachersController extends AppController {

      /**
       * Index method
       *
       * @return \Cake\Http\Response|void
       */
      public function manageteachers() {
//          $this->paginate = [
//              'contain' => ['Users', 'Countries', 'States']
//          ];
          $teachers = $this->Teachers->find()->contain(['Subjects','Users', 'Countries', 'States']);
          //used for assigning subjects to a teacher in the modal
          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 2000]);
          $users = $this->Teachers->Users->find('list', ['limit' => 200])->where(['role_id' => 3]);

          $this->set(compact('teachers', 'subjects', 'users'));
          $this->viewBuilder()->setLayout('backend');
      }

      /**
       * View method
       *
       * @param string|null $id Teacher id.
       * @return \Cake\Http\Response|void
       * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
       */
      //admin method for viewing a teacher detail
      public function viewteacher($id = null) {
          $teacher = $this->Teachers->get($id, [
              'contain' => ['Users', 'Countries', 'States', 'Subjects', 'Departments']
          ]);

          //used for subject assignment
          $subjectlists = $this->Teachers->Subjects->find('list', ['limit' => 200]);
          $this->set('teacher', $teacher);
          $this->set('subjectlists',  $subjectlists);
          $this->viewBuilder()->setLayout('backend');
      }

      //teachers method for viewing their profile
      public function viewprofile() {
          $teacher = $this->Teachers->find()->where(['user_id' => $this->Auth->user('id')])
                          ->contain(['Users', 'Countries', 'States', 'Subjects', 'Departments'])->first();

          $this->set('teacher', $teacher);
          $this->viewBuilder()->setLayout('teachersbackend');
      }

      //method for downloading a teacher's cv
      public function downloadcv($id) {
          $teacher = $this->Teachers->get($id);
          if (!empty($teacher)) {
              $ext = pathinfo($teacher->cv, PATHINFO_EXTENSION);
              //  debug(json_encode(filesize("cvs/" . $teacher->cv), JSON_PRETTY_PRINT));
              //  exit;
              header('Content-Type: ' . $ext);
              header('Content-Length: ' . filesize("cvs/" . $teacher->cv));
              header('Content-Disposition: attachment;filename="' . $teacher->cv . '"');
              header("Cache-control: private");
          }

          readfile("cvs/" . $teacher->cv);
          return;
      }

      
      
    

      









      /**
       * Add method
       *
       * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
       */
      public function newteacher() {
          $teacher = $this->Teachers->newEmptyEntity();
          if ($this->request->is('post')) {
              
              $email = $this->request->getData('username');
              $fname = $this->request->getData('firstname');
              $lname = $this->request->getData('lastname');
              $mname = $this->request->getData('middlename');
              $user_id = $this->getlogindetails($email, $fname, $lname, $mname);
              if(is_numeric($user_id)){
                     //upload passport
           $passport = $this->request->getData('passports');
            $photo =  $passport->getClientFilename();
            if (!empty($photo)) {
                $studentcontroller = new StudentsController();
               $teacher_photo = $studentcontroller->handlefileupload($this->request->getData('passports'), 'staff_files/');
            }
            else{
            $teacher_photo = "";   
            }
          
            //upload CV
              $cvfile = $this->request->getData('cvv');
              $cv_file = $cvfile->getClientFilename();
            if (!empty($cv_file)) {
                $staff_cv = $this->handlefileupload($this->request->getData('cvv'), 'staff_files/');
            }
            else{
             $staff_cv = "";   
            }
            
              $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
              $teacher->user_id = $user_id;
              $teacher->passport = $teacher_photo;
              $teacher->cv = $staff_cv;
              $teacher->staffgrade_id = 1;
              $teacher->staffdepartment_id = 1;
              if ($this->Teachers->save($teacher)) {
                  //send login details to lecturer
                  $this->newteacheralert($email, $fname, $lname);

                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Added a new Teacher " . $teacher->id;
                  $user_id = $this->Auth->user('id');
                  $description = "Added a new Teacher with user id : " . $teacher->user_id;
                  $ip = $this->request->clientIp();
                  $type = "Add";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                  $this->Flash->success(__('The staff has been saved.'));

                  return $this->redirect(['action' => 'manageteachers']);
          }
          
              }
              $this->Flash->error(__('The staff could not be saved. Please, try again.'));
          }
          $departments = $this->Teachers->Departments->find('list', ['limit' => 200])->order(['name'=>'ASC']);
          $users = $this->Teachers->Users->find('list', ['limit' => 200]);
          $countries = $this->Teachers->Countries->find('list', ['limit' => 200])->order(['name'=>'ASC']);
          $states = $this->Teachers->States->find('list', ['limit' => 200])->order(['name'=>'ASC'])->where(['country_id'=>160]);
          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200])->order(['name'=>'ASC']);
          $this->set(compact('teacher', 'users', 'countries', 'states', 'subjects','departments'));
          $this->viewBuilder()->setLayout('backend');
      }

      
      //method that send username and password to newly added lecturers
        //mail funtion that informs the student that admission has been offered to them
    public function newteacheralert($emailaddress, $fname, $lname) {
            $message = "<br /> Hello " . $fname . ' ' . $lname . ',<br /><br />' . 'An '
                . 'account has ben created for you in the Imo State Polytechnic learning Management System'
                . 'please find your login details below: .<br /><br />'
                . 'Username : '.$emailaddress.'<br />'
                . 'Default Password: teacher123<br />'
                . '<br />Regards,<br /> ICT Unit,<br /><br />Imo State Polytechnic Omuma<br /><br />';

        $email = new Mailer('default');
        $email->setFrom(['supportfess@imopoly.net' => SCHOOL]);
        $email->setTo($emailaddress);
        $email->setBcc(['chukwudi.aniegboka@netpro.africa']);
        $email->setEmailFormat('html');
        $email->setSubject('New Lectruer Account Alert');
        if ($email->deliver($message)) {
            $this->Flash->success('A mail has been sent to the lecturer with login details');
        } else {
            $this->Flash->error('Oh!, sorry, We are unable to send mail.');
        }
        return;
    }




      //the file upload method
    public function handlefileupload($filename, $folder) {
        $attachment = $filename;
        $name = $attachment->getClientFilename();
        $extension = strrchr($name, '.');
        $type = $attachment->getClientMediaType();
       
        $size = $attachment->getSize();
       //  echo $type.' '. $size; exit;
        $tmpName = $attachment->getStream()->getMetadata('uri');
        $error = $attachment->getError();
        //  if(empty($tmpName)){}
        if (empty($filename)) {
            $this->Flash->error(__('There was an error uploading your file. Ensure file is <3mb and of right format(word or pdf).  Please, try again.'));
            return 0;
        }
        if ($error != 0) {
            $this->Flash->error(__('There was an error uploading your file. Ensure file is <3mb and of right format(word or pdf).  Please, try again.'));
            return 0;
        }
        $filenametobd = uniqid(date('d_m_y_h_i_s')).'_' . $name;
        if ((($error == 0) && ($size < 4000000)) && (($type == "application/octet-stream") || ($type == "application/pdf") || ($type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || ($type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"))) {
            $attachment->moveTo($folder . $filenametobd);
            return $filenametobd;
        } else {
            $this->Flash->error(__('There was an error uploading your file. Ensure file is <3mb and of right format(word or pdf).  Please, try again.'));
            return 0;
        }
    }

    //functionn for deleting a file
    public function deletefile($filename, $folder) {
        // $folder_upload = "img/";
        if (file_exists($folder . $filename)) {
            unlink($folder . $filename);
            return;
        }
        return;
    }
      
        //method that creates login details for the new teacher
      private function getlogindetails($email, $fname, $lname, $mname) {
          $users_Table = TableRegistry::get('Users');
          $user = $users_Table->newEmptyEntity();
          $user->role_id = 3;
          $user->password = "teacher123";
          $user->username = $email;
          $user->fname = $fname;
          $user->lname = $lname;
          $user->mname = $mname;
          $user->gender = "gender";
            $user->address = " ";
            $user->phone = " ";
            $user->userstatus = " ";
            $user->country_id = 1;
            $user->state_id = 1;
            $user->department_id = 1;
            $user->created_by = 1;
            $user->useruniquid = " ";
          if ($users_Table->save($user)) {
              return $user->id;
          } else {
              return "Failed";
          }
      }
      
      
      
      //the teachers dashboard
      public function dashboard() {
          $teacher = $this->Teachers->find()
                  ->contain(['Subjects','Departments','Departments.Students','Departments.Subjects'])
                  ->where(['user_id' => $this->Auth->user('id')])->first();
          if (empty($teacher)) {
              $this->Flash->error(__('Sorry, you need to set up your profile first.'));

              return $this->redirect(['action' => 'newprofile']);
          } else {
              $this->Flash->success(__('Welcome!.'));
               $student_Table = TableRegistry::get('Students');
               $students = $student_Table->find()->where(['status'=>'Admitted'])->count();
          }
          $this->set(compact('teacher','students'));
          $this->viewBuilder()->setLayout('teachersbackend');
      }

      //for a first time teacher login
      public function newprofile() {
          $teacher = $this->Teachers->newEmptyEntity();
          if ($this->request->is('post')) {

              $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
              $teacher->user_id = $this->Auth->user('id');
              //upload passport
              $imagearray = $this->request->getData('passports');
              if (!empty($imagearray['tmp_name'])) {
                  $userscontroller = new UsersController();
                  $image_name = $userscontroller->addimage($imagearray);
                  //update passport on users table
                  $usersTable = TableRegistry::get('Users');
                  $user = $usersTable->get($this->Auth->user('id'));
                  $user->passport = $image_name;
                  $usersTable->save($user);
              } else {
                  $image_name = " ";
              }
              $teacher->passport = $image_name;
              //upload CV
              $cvfile = $this->request->getData('ccv');
              if (!empty($cvfile['tmp_name'])) {
                  $cv = $userscontroller->uploadcv($cvfile, "cvs/");
                  $teacher->cv = $cv;
              }
              // debug(json_encode( $teacher, JSON_PRETTY_PRINT)); exit;
              if ($this->Teachers->save($teacher)) {
                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Updated a teacher " . $teacher->id;
                  $user_id = $this->Auth->user('id');
                  $description = "updated teacher with user id : " . $teacher->user_id;
                  $ip = $this->request->clientIp();
                  $type = "Edit";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);

                  $this->Flash->success(__('The teacher has been saved.'));

                  return $this->redirect(['action' => 'dashboard']);
              }
              $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
          }
          // $users = $this->Teachers->Users->find('list', ['limit' => 200]);
          $countries = $this->Teachers->Countries->find('list', ['limit' => 200]);
          $states = $this->Teachers->States->find('list', ['limit' => 200]);
          $this->set(compact('teacher', 'users', 'countries', 'states'));

          $this->viewBuilder()->setLayout('login');
      }

      //teachers method updating their profile
      public function updateprofile() {
          //$teacher = $this->Teachers->get($id, [ 'contain' => []]);
          $teacher = $this->Teachers->find()
                          ->contain(['States', 'Countries'])
                          ->where(['user_id' => $this->Auth->user('id')])->first();
          if ($this->request->is(['patch', 'post', 'put'])) {
              //upload passport
               $studentscontroller = new StudentsController();
              $userscontroller = new UsersController();
              $imagearray = $this->request->getData('passports');
              $photo = $imagearray->getClientFilename();
              if (!empty($photo)) {
               $image_name =     $studentscontroller->handlefileupload($this->request->getData('passports'), 'student_files/');
                
                  //update passport on users table
                  $usersTable = TableRegistry::get('Users');
                  $user = $usersTable->get($this->Auth->user('id'));
                  $user->passport = $image_name;
                  $usersTable->save($user);
              }
              //upload CV
             
            $attachment = $this->request->getData('ccv');
            $name = $attachment->getClientFilename();
            if (!empty($name)) {
               
                $doc1 =  $studentscontroller->handlefileupload($this->request->getData('doc11'), 'cvs/');
            }
            else{
              $doc1   = $teacher->cv;
            }
            

              $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
              $teacher->cv = $doc1;
              $teacher->passport =  $image_name;
              if ($this->Teachers->save($teacher)) {
                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Updated a teacher " . $teacher->id;
                  $user_id = $this->Auth->user('id');
                  $description = "updated teacher with user id : " . $teacher->user_id;
                  $ip = $this->request->clientIp();
                  $type = "Edit";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                  $this->Flash->success(__('Record has been updated.'));

                  return $this->redirect(['action' => 'dashboard']);
              }
              $this->Flash->error(__('The teacher could not be updated. Please, try again.'));
          }
          // $users = $this->Teachers->Users->find('list', ['limit' => 200]);
          $countries = $this->Teachers->Countries->find('list', ['limit' => 200]);
          $states = $this->Teachers->States->find('list', ['limit' => 200])->where(['country_id'=>160])->order(['name'=>'DESC']);
          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200]);
          $this->set(compact('teacher', 'users', 'countries', 'states', 'subjects'));
          $this->viewBuilder()->setLayout('teachersbackend');
      }

      /**
       * Edit method
       *
       * @param string|null $id Teacher id.
       * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
       * @throws \Cake\Network\Exception\NotFoundException When record not found.
       */
      //admin method for updating a teacher
      public function updateteacher($id = null) {
          $teacher = $this->Teachers->get($id, [
             // 'contain' => ['Subjects','Users','Departments','States','Countries']
          ]);
          if ($this->request->is(['patch', 'post', 'put'])) {
              $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
              if ($this->Teachers->save($teacher)) {
                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Updated a teacher " . $teacher->id;
                  $user_id = $this->Auth->user('id');
                  $description = "updated teacher with user id : " . $teacher->user_id;
                  $ip = $this->request->clientIp();
                  $type = "Edit";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                  $this->Flash->success(__('The teacher details has been updated successfully.'));

                  return $this->redirect(['action' => 'manageteachers']);
              }
              $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
          }
          $departments = $this->Teachers->Departments->find('list', ['limit' => 200])->order(['name'=>'ASC']);
          $users = $this->Teachers->Users->find('list', ['limit' => 200]);
          $countries = $this->Teachers->Countries->find('list', ['limit' => 200])->order(['name'=>'ASC']);
          $states = $this->Teachers->States->find('list', ['limit' => 200])->where(['country_id'=>160])->order(['name'=>'ASC']);
          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 5000])->order(['name'=>'ASC']);
          $this->set(compact('teacher', 'users', 'countries', 'states', 'subjects','departments'));
          $this->viewBuilder()->setLayout('backend');
      }

      //assigns subjects to teachers
      public function assignsubjects() {
          $teacher_id = $this->request->getData('teacher_id');
          $teacher_user_id = $this->request->getData('user_id');
          if (!empty($teacher_id)) {
              $teacher = $this->Teachers->get($teacher_id);
          } else { //echo  $teacher_user_id; exit;
              $teacher = $this->Teachers->find()->where(['user_id' => $teacher_user_id])->first();
          }
          // debug(json_encode($teacher, JSON_PRETTY_PRINT)); exit;
          $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
          if ($this->Teachers->save($teacher)) {
              //log activity
              $usercontroller = new UsersController();

              $title = "Updated a teacher " . $teacher->id;
              $user_id = $this->Auth->user('id');
              $description = "Assigned subjects to teacher with user id : " . $teacher->user_id;
              $ip = $this->request->clientIp();
              $type = "Edit";
              $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
              $this->Flash->success(__('The subjects has been assigned successfully.'));

              return $this->redirect(['action' => 'manageteachers']);
          }
          $this->Flash->error(__('Unable to assign subjects. Please try again'));
          return $this->redirect(['action' => 'manageteachers']);
      }

      //assign subjects to teachers
      public function assignsubjectstoteacher() {

          $users_Table = TableRegistry::get('Users');
          $users = $users_Table->find('list')->where(['role_id' => 3]);

          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200]);
          $this->set(compact('users', 'subjects'));
          $this->viewBuilder()->setLayout('backend');
      }

      //method that shows the teacher only her assigned courses
      public function assignedcourses() {
          $teacher = $this->Teachers->find()
                          ->where(['user_id' => $this->Auth->user('id')])
                          ->contain(['Subjects'])->first();

          $this->set('teacher', $teacher);
          $this->viewBuilder()->setLayout('backend');
      }

      //teachers method for addint a topic to a course
      public function addtopic($subject_id) {
          $topics_Table = TableRegistry::get('Topics');
          $subjects_Table = TableRegistry::get('Subjects');
          $subject = $subjects_Table->get($subject_id);
          $topic = $topics_Table->newEmptyEntity();
          if ($this->request->is('post')) {
              $topic = $topics_Table->patchEntity($topic, $this->request->getData());
              $topic->user_id = $this->Auth->user('id');
              $topic->subject_id = $subject_id;

              if ($topics_Table->save($topic)) {
                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Added a Topic " . $topic->title;
                  $user_id = $this->Auth->user('id');
                  $description = "Added a Topic " . $topic->title;
                  $ip = $this->request->clientIp();
                  $type = "Add";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                  $this->Flash->success(__('The topic has been saved.'));

                  return $this->redirect(['action' => 'assignedcourses']);
              }
              $this->Flash->error(__('The topic could not be saved. Please, try again.'));
          }
          $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200]);
          // $admins = $this->Topics->Admins->find('list', ['limit' => 200]);
          $this->set(compact('topic', 'subjects', 'subject'));
          $this->viewBuilder()->setLayout('backend');
      }

      //shows the teacher all her topics
      public function mytopics() {
          $topics_Table = TableRegistry::get('Topics');
          $mytopics = $topics_Table->find()
                  ->where(['Topics.user_id' => $this->Auth->user('id')])
                  ->contain(['Subjects']);
          //     debug(json_encode($mytopics, JSON_PRETTY_PRINT)); exit;
          $this->set('mytopics', $mytopics);
          $this->viewBuilder()->setLayout('backend');
      }

      //teachers method for updating a topic
      public function updatetopic($id) {
          $topics_Table = TableRegistry::get('Topics');
          $topic = $topics_Table->get($id, [
              'contain' => ['Subjects']
          ]);
          if ($this->request->is(['patch', 'post', 'put'])) {
              $topic = $topics_Table->patchEntity($topic, $this->request->getData());
              $topic->updatedon = date('d M Y');
              if ($topics_Table->save($topic)) {
                  //log activity
                  $usercontroller = new UsersController();

                  $title = "Updated a Topic " . $topic->title;
                  $user_id = $this->Auth->user('id');
                  $description = "Updated a Topic " . $topic->title;
                  $ip = $this->request->clientIp();
                  $type = "Edit";
                  $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                  $this->Flash->success(__('The topic has been updated.'));

                  return $this->redirect(['action' => 'mytopics']);
              }
              $this->Flash->error(__('The topic could not be updated. Please, try again.'));
          }
          $subjects = $topics_Table->Subjects->find('list', ['limit' => 200]);
          // $admins = $this->Topics->Admins->find('list', ['limit' => 200]);
          $this->set(compact('topic', 'subjects'));
          $this->viewBuilder()->setLayout('backend');
      }

      //techers method for viewing a topic
      public function viewtopic($id) {
          $topics_Table = TableRegistry::get('Topics');
          $topic = $topics_Table->get($id, [
              'contain' => ['Subjects']
          ]);
          $this->set('topic', $topic);
          $this->viewBuilder()->setLayout('backend');
      }

      /**
       * Delete method
       *
       * @param string|null $id Teacher id.
       * @return \Cake\Http\Response|null Redirects to index.
       * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
       */
      public function delete($id = null) {
          $this->request->allowMethod(['post', 'delete']);
          $teacher = $this->Teachers->get($id);
          if ($this->Teachers->delete($teacher)) {
              //log activity
              $usercontroller = new UsersController();

              $title = "Deleted a teacher " . $teacher->id;
              $user_id = $this->Auth->user('id');
              $description = "Deleted teacher with user id : " . $teacher->user_id;
              $ip = $this->request->clientIp();
              $type = "Delete";
              $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
              $this->Flash->success(__('The teacher has been deleted.'));
          } else {
              $this->Flash->error(__('The teacher could not be deleted. Please, try again.'));
          }

          return $this->redirect(['action' => 'manageteachers']);
      }

      //admin method for sending messages to teachers
      public function newmessagetoteachers() {
          $usersTable = TableRegistry::get('Users');
          if ($this->request->is('post')) {
              $teachers_ids = $this->request->getData('user._ids');

              $subject = $this->request->getData('subject');
              $message = $this->request->getData('message');
              $count = 0;
              //check if a teacher was selected
              if (!empty($teachers_ids)) {
                  //some teachers have been selected, send to them alone
                  foreach ($teachers_ids as $tid) {
                      $teacher_mail = $usersTable->get($tid);
                      $this->messagetoteachers($teacher_mail->username, $subject, $message);
                      $count++;
                  }
              } else { //no employee was selected that means we are sending to all 
                  $employees = $usersTable->find()->where(['role_id' => 3]);
                  foreach ($employees as $employee) {
                      $this->messagetoteachers($employee->username, $subject, $message);
                      $count++;
                  }
              }
              //log activity
              $usercontroller = new UsersController();

              $title = "Sent a mail to employees ";
              $user_id = $this->Auth->user('id');
              $description = "Sent mail to a total of" . $count . " employees ";
              $ip = $this->request->clientIp();
              $type = "Add";
              $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
              $this->Flash->success(__('Message has been sent to ' . $count . ' employees'));
              return $this->redirect(['action' => 'newmessagetoteachers']);
          }

          $teachers = $usersTable->find('list')->where(['role_id' => 3]);
          $this->set(compact('teachers'));
          $this->viewBuilder()->setLayout('backend');
      }

      //admin method that sends a message to selected employees
      private function messagetoteachers($emailaddress, $subject, $message) {

          $message .= '<br /><br />'
                  . 'Kind Regards,<br />'
                  . 'NetPro EMS. <br />';

          $email = new Email('default');
          $email->setFrom(['no-reply@netpro.com.ng' => 'NetPro Int\'l Ltd']);
          $email->setTo($emailaddress);
          // $email->setBcc(['chukwudi@netpro.com.ng']);
          $email->setEmailFormat('html');
          $email->setSubject($subject);
          $email->send($message);
          return;
      }
      
      
      //teachers method for contacting the admin
      public function messagetoadmin(){
          
           if ($this->request->is('post')) {
               $subject = $this->request->getData('subject');
              $message = $this->request->getData('message');
              //get admin email from session
              $settings = $this->request->getSession()->read('settings');
              //call the mailling function
             if($this->messagetoteachers($settings->email, $subject, $message)){
                $this->Flash->success(__('Message has been sent to admin'));
              return $this->redirect(['action' => 'messagetoadmin']);  
             }else{
                $this->Flash->error(__('Sorry, unable to send message, please try again'));
              return $this->redirect(['action' => 'messagetoadmin']);  
             }
               
           }
          $this->viewBuilder()->setLayout('backend');
      }
  
      
      
      //teacher method for sending mails to his students
      public function messagetostudents(){
           $studentsTable = TableRegistry::get('Students');
              $sdepartmentsTable = TableRegistry::get('Departments');
        $teacher =   $this->Teachers->find()
                ->contain(['Departments'])
                ->where(['user_id'=>$this->Auth->user('id')])->first();
         if ($this->request->is('post')) {
               $subject = $this->request->getData('subject');
              $message = $this->request->getData('message');
              $studentids = $this->request->getData('student._ids');
              if(!empty($studentids)){
                  $count = 0;
                  //he selected some students
                  foreach ($studentids as $sid){
                  $student = $studentsTable->get($sid);
                  //call the mailing method
                  $this->messagetoteachers($student->email, $subject, $message);
                  $count++;
                  }
              }else{
                  //no student was selected meaning we are sending to all students in the department
                 $students = $studentsTable->find()->where(['department_id'=> $teacher->department_id]);  
                 foreach ($students as $student){
                  //call the mailing method
                  $this->messagetoteachers($student->email, $subject, $message);
                  $count++;   
                 }
              }
              $this->Flash->success(__('Message has been sent to '.$count.' students'));
              return $this->redirect(['action' => 'messagetostudents']); 
         
         }
          $students = $studentsTable->find('list')->where(['department_id'=> $teacher->department_id]);
          $departments = $sdepartmentsTable->find('list')->where(['id'=>$teacher->department_id]);
          $this->set(compact('students','departments'));
          
            $this->viewBuilder()->setLayout('teachersbackend');
      }
      
      //function that returns the states on the drop down
      public function getstates($country_id) {
          $statestable = TableRegistry::get('States');
          $states = $statestable->find('list')
                  ->where(['country_id' => $country_id]);
          $this->set(compact('states'));
          //debug(json_encode($states , JSON_PRETTY_PRINT)); exit;
      }
      
      
      //teachers method for uploading their results
      public function uploadresults(){
          $teacher = $this->Teachers->find()->contain(['Subjects'])
                  ->where(['user_id'=>$this->Auth->user('id')])->first();
         if(!$teacher){
              $this->Flash->error(__('Wrong access type'));
             return $this->redirect(['controller'=>'Students','action' => 'index']);
         }
          $resultsTable = TableRegistry::get('Results');
           if ($this->request->is(['patch', 'post', 'put'])) {
              $faculty_id = $this->request->getData('faculty_id');
              $department_id = $this->request->getData('department_id');
              $level_id = $this->request->getData('level_id');
              $session_id = $this->request->getData('session_id');
              $semester_id = $this->request->getData('semester_id');
              $course_id = $this->request->getData('subject_id');


               $filename = $this->request->getData('result');
             $name =  $filename->getClientFilename();
             $tmpName =  $filename->getStream()->getMetadata('uri');
            $type = $filename->getClientMediaType();
             $error = $filename->getError();
              $ext = pathinfo($name, PATHINFO_EXTENSION);
              // echo $ext; exit;
              $allowedext = ['csv', 'xlsx'];
              if ( $error!=0) {
                  $this->Flash->error(__('Sorry, there is a problem with the file,only csv or xlsx files can be uploaded. Please check and try again'));

                  return $this->redirect(['action' => 'uploadresults']);
              }
              if (!in_array($ext, $allowedext)) {
                  $this->Flash->error(__('Sorry, only csv or xlsx files can be uploaded.'));

                  return $this->redirect(['action' => 'uploadresults']);
              } else {
                  $helper = new Helper\Sample();
                  debug($helper);
                  $spreadsheet = IOFactory::load( $tmpName );
                  $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                  $count = 0;
                  $inserted = 0;
                  $duplicate_results = 0;
                  $unknown_students = 0;
                  foreach ($sheetData as $data) {

                      $count++;

                      if ($count > 1) {
                          // debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                           $level = $resultsTable->Levels->get($level_id);
                          $department =  $resultsTable->Departments->get($department_id);
                          $semester =  $resultsTable->Semesters->get($semester_id);
                          $course =  $resultsTable->Subjects->get($course_id);
                          $session =  $resultsTable->Sessions->get($session_id);

//               echo strtolower(trim($semester->name)) .' '. strtolower(trim($data['H'])).'<br />';
//              echo strtolower(trim($department->name)) .' '. strtolower(trim($data['G'])).'<br />';
//             echo  strtolower(trim($programe->programname)) .' '. strtolower(trim($data['F'])).'<br />';
//             echo  strtolower(trim($course->name)) .' '. strtolower(trim($data['F'])).'<br />';
//            echo   strtolower(trim($session->name)) .' '. strtolower(trim($data['I'])).'<br />';
//            exit;
                          if (strtolower(trim($department->name)) === strtolower(trim($data['G'])) &&
                                  strtolower(trim($course->name)) === strtolower(trim($data['F'])) &&
                                  strtolower(trim($semester->name)) === strtolower(trim($data['H'])) &&
                                  strtolower(trim($level->name)) === strtolower(trim($data['J'])) &&
                                  strtolower(trim($session->name)) === strtolower(trim($data['I']))) {

                              //get the student and ensure no double result
                              //  debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                              $student =  $resultsTable->Students->find()->where(['regno' => $data['A']])->first();
                              //ensure no result for this course already

                              if ($student) {

                                  $has_result =  $resultsTable->find()->where(['regno' => $data['A'],
                                      'department_id' => $department_id, 'subject_id' => $course_id, 'semester_id' => $semester_id, 'session_id' => $session_id]);
                                  if (empty($has_result->toArray())) {
                                      // debug(json_encode( $data, JSON_PRETTY_PRINT)); exit;
                                      //create a new result for this student
                                      $result =  $resultsTable->newEmptyEntity();
                                      $result->regno = $data['A'];
                                      $result->level_id  = $level->id;
                                      $result->student_id = $student->id;
                                      $result->faculty_id = $faculty_id;
                                      // $result->firstname = $data['C'];
                                      // $result->middlename = $data['D'];
                                      $result->department_id = $department_id;
                                      $result->remark = $data['E'];
                                      $result->semester_id = $semester_id;
                                      $result->subject_id = $course_id;
                                      $result->session_id = $session_id;
                                      $result->creditload = $data['D'];
                                      $result->score = $data['B'];
                                      $result->grade = $data['C'];
                                      $result->user_id = $this->Auth->user('id');
                                      // debug(json_encode($result, JSON_PRETTY_PRINT)); exit;
                                       $resultsTable->save($result);
                                      $inserted ++;
                                  } else {
                                      $duplicate_results++;
                                  }
                              } else {
                                  $unknown_students++;
                              }
                          } else {
                              $this->Flash->error('Total results uploaded : ' . $inserted . ' Some results failed to upload because selected data didnt match provided data. Please ensure the right department,'
                                      . 'course, session and faculty was selected. Duplicate results found : ' . $duplicate_results
                                      . ' Unknown students : ' . $unknown_students);
                              return $this->redirect(['action' => 'uploadresults']);
                          }

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
                  $this->Flash->success(__($inserted . ' Result(s) have been uploaded successfully. Duplicates found : ' . $duplicate_results . ' Unknown students : ' . $unknown_students));

                  return $this->redirect(['action' => 'uploadresults']);
              }
          }
          
          //get teacher subjects
          $teacher_subjects = [];
          foreach ($teacher->subjects as $subject){
              array_push($teacher_subjects, $subject->id);
          }
          $faculties =  $resultsTable->Faculties->find('list', ['limit' => 200])
                  ->order(['name' => 'ASC']);
          $departments =  $resultsTable->Departments->find('list', ['limit' => 200])
                  ->order(['name' => 'ASC']);
          $subjects =  $resultsTable->Subjects->find('list', ['limit' => 200])
                  ->where(['id IN '=>$teacher_subjects])
                  ->order(['name' => 'ASC']);
          $semesters =  $resultsTable->Semesters->find('list', ['limit' => 200]);
          $sessions =  $resultsTable->Sessions->find('list', ['limit' => 200]);
          $levels = $resultsTable->Levels->find('list', ['limit' => 200])->where(['id !=' => 6])->order(['name' => 'ASC']);
          $this->set(compact('faculties', 'departments', 'subjects', 
                  'semesters', 'sessions', 'levels'));
       $this->viewBuilder()->setLayout('teachersbackend');
          }
  
      
      
      
      //teachers method for managing his students results
      public function manageresults(){
           $teacher = $this->Teachers->find()->contain(['Subjects'])->where(['user_id'=>$this->Auth->user('id')])->first();
         if(!$teacher){
              $this->Flash->error(__('Wrong access type'));
             return $this->redirect(['controller'=>'Students','action' => 'index']);
         }
          $resultsTable = TableRegistry::get('Results');
          if ($this->request->is('post')) {
               $session_id = $this->request->getData('session_id');
              $semester_id = $this->request->getData('semester_id');
              $course_id = $this->request->getData('subject_id');
           
              if (!empty($course_id)) {
                  $conditions['Results.subject_id'] = $course_id;
              }
              if (!empty($session_id)) {
                  $conditions['Results.session_id'] = $session_id;
              }
              if (!empty($semester_id)) {
                  $conditions['Results.semester_id'] = $semester_id;
              }

              $results = $resultsTable->find()
                      ->contain(['Students', 'Faculties', 'Departments', 'Subjects', 'Semesters', 'Sessions','Users'])
                      ->where($conditions);
              //debug(json_encode($conditions, JSON_PRETTY_PRINT)); exit;
              $this->set('results', $results);
          }
          
          $teacher_subjects = [];
          foreach ($teacher->subjects as $subject){
              array_push($teacher_subjects, $subject->id);
          } 
          $subjects =  $resultsTable->Subjects->find('list', ['limit' => 200])
                  ->where(['id IN '=>$teacher_subjects])
                  ->order(['name' => 'ASC']);
          $semesters =  $resultsTable->Semesters->find('list', ['limit' => 200]);
          $sessions =  $resultsTable->Sessions->find('list', ['limit' => 200]);
          $this->viewBuilder()->setLayout('teachersbackend');
          $this->set(compact('subjects', 'semesters', 'sessions'));
    
          
      }
  
      
      //lectruers method for viewing students that registered forhis course
    public function viewcoursestudents(){
        $sessions_table = TableRegistry::get('Sessions');
         $subjects_table = TableRegistry::get('Subjects');
        $semesters_table = TableRegistry::get('Semesters');
        $levels_table = TableRegistry::get('Levels');
          if ($this->request->is(['patch', 'post', 'put'])) {
              $course_id = $this->request->getData('subject_id');
              $semester_id = $this->request->getData('semester_id');
              $session_id = $this->request->getData('session_id');
               $level_id = $this->request->getData('level_id');
                $condition = [];
              if (!empty($course_id)) {
                  $condition['subject_id'] = $course_id;
                  //get course for use in the view
                  $subject = $subjects_table->get($course_id);
                  $this->set('subject',$subject);
              }
                if (!empty($semester_id)) {
                  $condition['Courseregistrations.semester_id'] = $semester_id;
              }
              if (!empty($level_id)) {
                  $condition['Courseregistrations.level_id'] = $level_id;
              }
              if (!empty($session_id)) {
                  $condition['Courseregistrations.session_id'] = $session_id;
              }
         $course_subjects_table = TableRegistry::get('CourseregistrationsSubjects');
     $registered =  $course_subjects_table->find()->contain(['Subjects','Courseregistrations.Students.Departments'])
             ->where($condition);  
   //   debug(json_encode( $registered, JSON_PRETTY_PRINT));
      $this->set(compact('registered'));
          }else{
              $subject_ids = $this->getsubjects();
              $subjects =  $subjects_table->find('list')->where([' id IN'=>$subject_ids])->order(['name' => 'DESC']);
         $sessions = $sessions_table->find('list')->order(['name' => 'DESC']);
         $levels = $levels_table->find('list')->order(['name' => 'DESC']);
         $semesters = $semesters_table->find('list')->order(['name' => 'DESC']);
        $this->set(compact('sessions','levels','semesters','subjects'));
          }
        
      $this->viewBuilder()->setLayout('teachersbackend');  
    }
    
    
    
      
    //lecturers method for adding a students ca and exam scores
    public function addca($student_id,$subject_id,$student_name,$course_reg_sub_id){
      $students_table = TableRegistry::get('Students'); 
     
      $course_reg_table = TableRegistry::get('CourseregistrationsSubjects');
      $coursereg_table = TableRegistry::get('Courseregistrations');
      $student =  $students_table->get($student_id);
      $this->set('student',$student);
      $course_reg = $course_reg_table->get($course_reg_sub_id);
      $coursereg = $coursereg_table->get($course_reg->courseregistration_id);
    
       if ($this->request->is(['patch', 'post', 'put'])) {
             $creditload = $this->getcreditunit($subject_id); //$this->request->getData('creditload');
        $ca = $this->request->getData('ca'); 
        $exam = $this->request->getData('exam');
        if($ca>40 || $exam>60){
         $this->Flash->error(__('CA MUST be less than or equal to 30 while Ezam MUST not be more than 70'));    
       return $this->redirect(['action' => 'addca',$student_id,$subject_id,$student->fname,$course_reg->id]);
    
         }
      $course_reg = $course_reg_table->patchEntity($course_reg, $this->request->getData());
      if($course_reg_table->save( $course_reg)){
          //check if result already exists
       $old_result = $this->hasResultAlready($student->regno,$student->department_id, $subject_id,$coursereg->semester_id,$coursereg->session_id);
          
       if($old_result==0){
           //add result new
         $this->adderesult($student->id,$subject_id,$coursereg->session_id,
                $coursereg->semester_id,$student->level_id, $creditload, $course_reg->ca, $course_reg->exam); 
      $this->Flash->success(__('Record has been added'));
       return $this->redirect(['action' => 'registeredstudents',$subject_id,$coursereg->session_id,$coursereg->semester_id]);
      
         }else{
        //call method to update result table
          $this->updateresult($student_id,$subject_id,$coursereg->session_id,
                  $coursereg->semester_id,$coursereg->level_id, $course_reg->ca, $course_reg->exam);
           $this->Flash->success(__('Record has been updated'));
          return $this->redirect(['action' => 'registeredstudents',$subject_id,$coursereg->session_id,$coursereg->semester_id]);
      }
      }
           
           
       }
       
       
         $this->set(compact('student','course_reg'));
     $this->viewBuilder()->setLayout('teachersbackend');    
    }
    
    
    
    //method that return the credit unit of a course
    public function getcreditunit($courseid){
         $subjects_table = TableRegistry::get('Subjects'); 
         $subject =  $subjects_table->get($courseid);
         return  $subject->creditload;
        
    }






    //teacher method for updating ca and exam data
    public function updateca($student_id,$subject_id,$student_name,$course_reg_sub_id){
      $students_table = TableRegistry::get('Students'); 
     
      $course_reg_table = TableRegistry::get('CourseregistrationsSubjects');
      $coursereg_table = TableRegistry::get('Courseregistrations');
      $student =  $students_table->get($student_id);
        $this->set('student',$student);
      $course_reg = $course_reg_table->get($course_reg_sub_id);
      $coursereg = $coursereg_table->get($course_reg->courseregistration_id);
    
       if ($this->request->is(['patch', 'post', 'put'])) {
             $creditload = $this->request->getData('creditload');
      $course_reg = $course_reg_table->patchEntity($course_reg, $this->request->getData());
      if($course_reg_table->save( $course_reg)){
          //check if result already exists
       $old_result = $this->hasResultAlready($student->regno,$student->department_id, $subject_id,$coursereg->semester_id,$coursereg->session_id);
          
       if($old_result==0){
           //add result new
         $this->adderesult($student->id,$subject_id,$coursereg->session_id,
                $coursereg->semester_id,$student->level_id, $creditload, $course_reg->ca, $course_reg->exam); 
      $this->Flash->success(__('Record has been added'));
      return $this->redirect(['action' => 'registeredstudents',$subject_id,$coursereg->session_id,$coursereg->semester_id]);
      
         }else{
          //call method to update result table
          $this->updateresult($student_id,$subject_id,$coursereg->session_id,
                  $coursereg->semester_id,$coursereg->level_id, $course_reg->ca, $course_reg->exam);
           $this->Flash->success(__('Record has been updated'));
          return $this->redirect(['action' => 'registeredstudents',$subject_id,$coursereg->session_id,$coursereg->semester_id]);
      }
      }
           $this->set('course_reg',$course_reg);
           
       }
       
       
         $this->set(compact('student','course_reg'));
     $this->viewBuilder()->setLayout('teachersbackend');    
    }





       
    //where to return after adding or updating ca or exam
    public function registeredstudents($course_id,$session_id,$semester_id){
      $course_reg_sub_table = TableRegistry::get('CourseregistrationsSubjects');
     // $coursereg_table = TableRegistry::get('Courseregistrations');
        $subjects_table = TableRegistry::get('Subjects');
       $condition = [];
        if (!empty($course_id)) {
                  $condition['subject_id'] = $course_id;
                  //get course for use in the view
                  $subject = $subjects_table->get($course_id);
                  $this->set('subject',$subject);
              }
          if (!empty($semester_id)) {
                  $condition['Courseregistrations.semester_id'] = $semester_id;
              }
               if (!empty($session_id)) {
                  $condition['Courseregistrations.session_id'] = $session_id;
              }
     $registered =  $course_reg_sub_table->find()->contain(['Subjects','Courseregistrations.Students.Departments'])
             ->where($condition);
      
     // $students =  $course_reg_sub_table->get($course_reg_sub_id,['contain'=>['Subjects','Courseregistrations.Students.Departments']]);
     
     $this->set(compact('registered'));
     $this->viewBuilder()->setLayout('teachersbackend');       
    }
    

    //method that update result table
    private function adderesult($student_id,$subject_id,$session_id,
                  $semester_id,$level_id, $creditload, $ca, $exam){
      $results_table = TableRegistry::get('Results');  
      //$students_table = TableRegistry::get('Students'); 
      $student = $this->getstudent($student_id);
      $total = $ca+$exam;
      if(empty($exam)){$exam = 0;}
      $grade = "";
     if( $total>=70){$grade ="A";}
     elseif($total>=60){$grade = "B";}
      elseif($total>=50){$grade = "C";}
       elseif($total>=45){$grade = "D";}
       elseif($total>=40){$grade = "E";}
       elseif($total<=39){$grade = "F";}
     $result = $results_table->newEmptyEntity();
     $result->student_id = $student->id;
     $result->faculty_id = $student->faculty_id;
     $result->department_id = $student->department_id;
     $result->subject_id = $subject_id;
     $result->semester_id = $semester_id;
     $result->session_id = $session_id;
     $result->ca = $ca;
     $result->score = $exam;
     $result->total = $ca+$exam;
     $result->level_id = $level_id;
     $result->grade = $grade;
     $result->regno = $student->regno;
     $result->creditload = $creditload;
     $result->user_id = $this->Auth->user('id');
     $results_table->save( $result);
      return;
        
                  }


        ///method that gets a students data
        public function getstudent($student_id){
         $students_table = TableRegistry::get('Students'); 
            $student =    $students_table->get($student_id);
            return $student;
        }




//method that check if student already have this result
    public function hasResultAlready($regno,$department_id, $course_id,$semester_id,$session_id){
        $results_table = TableRegistry::get('Results'); 
        $has_result = $results_table->find()->where(['regno' => $regno,
         'department_id' => $department_id, 'subject_id' => $course_id,
            'semester_id' => $semester_id, 'session_id' => $session_id])->first();
        if(!empty($has_result->id)){
            return $has_result->id;
        }
        else{return 0;}
                                 
    }


//method that gets all subjects assigned to a teacher
    private function getsubjects(){
    $teacher = $this->Teachers->find()
                  ->contain(['Subjects'])
                  ->where(['user_id' => $this->Auth->user('id')])->first();
$ids = [];
foreach ($teacher->subjects as $subject){
    array_push($ids, $subject->id);
}
return $ids;
    }

    
//method for updating an exisiting result
    public function updateresult($student_id,$subject_id,$session_id,
                  $semester_id,$level_id, $ca, $exam){
        $results_table = TableRegistry::get('Results'); 
        $has_result = $results_table->find()
                ->where(['student_id' => $student_id,
         'level_id' => $level_id, 'subject_id' => $subject_id,
            'semester_id' => $semester_id, 'session_id' => $session_id])->first();
        $grade = "";
        $total = $ca+$exam;
       if( $total>=70){$grade ="A";}
     elseif($total>=60){$grade = "B";}
      elseif($total>=50){$grade = "C";}
       elseif($total>=45){$grade = "D";}
       elseif($total>=40){$grade = "E";}
       elseif($total<=39){$grade = "F";}
         $has_result->ca = $ca;
          $has_result->score = $exam;
          $has_result->total = $ca+$exam;
          $has_result->grade = $grade;
         $results_table->save($has_result); 
         return ;
          
        
                  }


    
    
      
      //method that verifies that the person currently loggedin is a teacher
      public function isteacher(){
          $teacher = $this->Teachers->find()->contain(['Subjects','Departments'])->where(['user_id'=>$this->Auth->user('id')])->first();
         if(!$teacher){
              $this->Flash->error(__('Wrong access type'));
             return $this->redirect(['controller'=>'Students','action' => 'index']);
         }else{ //this is a valid teacher so go on
             return $teacher;
         }
          
          
      }
      
      
  
      //teachers method for viewing a student
        public function viewstudent($id = null) {
              $studentstable = TableRegistry::get('Students');
            //ensure this is a teacher
          $teacher = $this->isteacher();
          $student = $studentstable->get($id, [
              'contain' => ['Departments.Subjects', 'States', 'Countries', 'Users', 'Subjects','Invoices.Fees',
                  'Invoices.Sessions','Results.Sessions','Results.Semesters','Results.Subjects']
          ]);

          $this->set('student', $student);
          $this->viewBuilder()->setLayout('teachersbackend');
      }
      
      
         // allow unrestricted pages
    public function beforeFilter(EventInterface $event) {
       // $this->Auth->allow([]);

        $actions = ['newteacher', 'getlogindetails'];
        if (in_array($this->request->getParam('action'), $actions)) {
            // turn form protection 
            $this->FormProtection->setConfig('validate', false);
        }
    }
      
  }
  