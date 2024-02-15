<?php

declare(strict_types = 1);

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function login() {
        //get the logo on the login page
        $settings_Table = TableRegistry::get('Settings');
        $settings = $settings_Table->get(1, ['contain' => ['Sessions', 'Semesters']]);
        if ($this->request->is('post')) {
         //$id_token =  $this->request->getData('id_token'); 
       //  echo '$id_token'; exit;
            //   debug(json_encode($this->request->getData(), JSON_PRETTY_PRINT)); exit;
            $user = $this->Auth->identify();
            // Create user binding. This is optional as binding is not required to send SMS notifications.
            // $this->Users->bindUser($user);
            // Send welcome message. if binding is disabled, do not add the last arg `true`.
            // $this->Users->notifyUser($user, 'Welcome to Cake Notifier', true);
            //handle sign in
           // require_once 'vendor/autoload.php';

// Get $id_token via HTTPS POST.

//$client = new Google_Client(['client_id' => CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
//$payload = $client->verifyIdToken($id_token);
//if ($payload) {
//  $userid = $payload['sub'];
//  // If request specified a G Suite domain:
//  //$domain = $payload['hd'];
//} else {
//  // Invalid ID token
//}
          

            if ($user && $user['userstatus'] != 'Disabled') {
                $this->Auth->setUser($user);
                $RolesTable = TableRegistry::get('Roles');
                $roles = $RolesTable->get($user['role_id']);
                $this->updateLogout($user['id']);
                $this->createLogin($user['id']);
                //get the system settings and put it in session
                // $settings = $settings_Table->get(1);
                $this->request->getSession()->write('settings', $settings);
                $this->request->getSession()->write('usersinfo', $user);
                $this->request->getSession()->write('usersroles', $roles);
                if ($user['role_id'] == 2) {
                    //get the student and put it in session
                    $studentsTable = TableRegistry::get('Students');
                    $student = $studentsTable->find()->where(['user_id' => $user['id'], 'status' => 'Admitted'])->first();
                    if (!$student) {//not yet admitted
                        $this->Flash->error('Invalid access. Student not admitted yet');

                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    } elseif (empty($student->passporturl)) {
                        //yet to update profile
                        $this->Flash->error(__('Sorry, you must update your profile to continue. Please ensure you select your state '
                                        . 'of origin, current class/level, program and uplaod a passport(less than 1mb jpg, jpeg or png).'));
                        //hide the navigations
                        $is_owing = 'is_owing';
                        $this->request->getSession()->write('is_owing', $is_owing);
                        return $this->redirect(['controller' => 'Students', 'action' => 'updateprofile']);
                    }
                     elseif ($student->studentstatus == "Suspended") {
                        //yet to update profile
                        $this->Flash->error(__('Sorry, your account has been suspended. Please contact admin or visit the ICT unit for assistance'));
                      
                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    }
                    $this->request->getSession()->write('student', $student);
                    return $this->redirect(['controller' => 'Students', 'action' => 'dashboard']);
                } elseif ($user['role_id'] == 3) {
                    //get the lecturer or employee details and put them in session
                    $teachersTable = TableRegistry::get('Teachers');
                    $teacher = $teachersTable->find()->where(['user_id' => $user['id']])->first();
                    $this->request->getSession()->write('teacher', $teacher);
                    return $this->redirect(['controller' => 'Teachers', 'action' => 'dashboard']);
                } elseif (($user['role_id'] == 1) || ($user['role_id'] == 5) || $user['role_id'] == 7) {
                    //get the admin and put it in session
                    $adminsTable = TableRegistry::get('Admins');
                    $admin = $adminsTable->find()->contain(['Privileges'])->where(['user_id' => $user['id']])->first();
                    // debug(json_encode(   $admin , JSON_PRETTY_PRINT)); exit;
                    $this->request->getSession()->write('admin', $admin);

                    return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
                }
            } else {
                $this->Flash->error('Bad Credentials or account disabled. Please check your credentials or contact admin for assistance');
            }
        }
        $this->set('logo', $settings);
        $this->viewBuilder()->setLayout('loginlayout');
    }

    //method that creates a cookie and redirects the admin with HR privilege to the HR system
    public function ishr($privilegid) {
        $hradmin = $this->request->getSession()->read('admin');
        $privileges = [];
        // debug(json_encode($admindata->privileges, JSON_PRETTY_PRINT)); exit;
        foreach ($hradmin->privileges as $privilege) {
            array_push($privileges, $privilege->id);
        }
        if (in_array($privilegid, $privileges)) {
            $cookie = (new Cookie('HRUSERCUN'))
                    ->withValue('1')
                    ->withExpiry(new DateTime('+1 year'))
                    ->withPath('/')
                    ->withDomain('example.com')
                    ->withSecure(false)
                    ->withHttpOnly(true);
            // Create a new collection
            $cookies = new CookieCollection([$cookie]);

// Add to an existing collection
            $cookies = $cookies->add($cookie);
// Check if a cookie exists
            $cookies->has('remember_me');
            return 1;
        }
    }

    //the admin  user dashboard
    public function dashboard() {
        $settings = $this->request->getSession()->read('settings');
        $admin = $this->Users->get($this->Auth->user('id'));
        $students_Table = TableRegistry::get('Students');
        $departments_Table = TableRegistry::get('Departments');
        $courseregistrations_Table = TableRegistry::get('Courseregistrations');
        $teachers_Table = TableRegistry::get('Teachers');
        $fees_Table = TableRegistry::get('Fees');
        $admins_Table = TableRegistry::get('Admins');
        $hostels_Table = TableRegistry::get('Hostels');
        $subjects_Table = TableRegistry::get('Subjects');
        $trequests_Table = TableRegistry::get('Trequests');
        $trequests = $trequests_Table->find()->where(['deliverystatus !=' => 'Delivered'])->count();
        $transactions_table = TableRegistry::get('Transactions');
        $departments = $departments_Table->find()->count();
        $fees = $fees_Table->find()->count();
        $hostels = $hostels_Table->find()->count();
        $admins = $admins_Table->find()->count();
        $years = [date('Y'), date('Y') - 1, date('Y') - 2,date('Y')-3];
        $years_conditions['admissiondate IN'] = $years;
      
        $condition = array(DATE('admissiondate') . ' BETWEEN NOW() AND ' . (date('Y') - 4));
        $current_students = $students_Table->find()->where($years_conditions)->count();
        $trsnactions_graph = $transactions_table->find()
                ->where(['DATE(transdate) > DATE(DATE_SUB(NOW(), INTERVAL 180 DAY))','paystatus' => 'completed']);
       $trsnactions_graph->select([
            'totalvalue' => $trsnactions_graph->func()->sum('amount'),
           'count' => $trsnactions_graph->func()->count('id'),
            'duration' => 'DATE(transdate)'
        ])
        ->group('MONTH(transdate)');
        // debug(json_encode( $trsnactions_graph, JSON_PRETTY_PRINT)); exit;
        //get alumnai
        $alumni = $students_Table->find()->where(['level_id' => 6])->count();
        $course_regs = $courseregistrations_Table->find()->where(['session_id' => $settings->session_id,
                    'semester_id' => $settings->semester_id])->count();
        $payments = $transactions_table->find()->where(['session_id' => $settings->session_id]);

        $payments->select([
            'amount' => $payments->func()->sum('amount'),
            'txdate' => 'DATE(transdate)'
        ]);
        $graph = $this->transactionviewsgraph();
        // debug(json_encode($payments, JSON_PRETTY_PRINT)); exit;
        $subjects = $subjects_Table->find()->count();
        $teachers = $teachers_Table->find()->count();
        $students = $students_Table->find()->where(['status' => 'Admitted', 'level_id !=' => 6])->count();
        $applied = $students_Table->find()->where(['status' => 'Applied','YEAR(joindate)'=>date('Y')])->count();
        // $pending_students = $students_Table->find()->where(['status'=>'Selected'])->count();
        $this->set('admin', $admin);
        $this->set(compact('students', 'teachers', 'subjects', 'applied', 'graph', 'payments', 
                'current_students', 'course_regs', 'departments', 'fees', 'hostels', 'admins', 
                'trequests', 'alumni','trsnactions_graph'));
        $this->viewBuilder()->setLayout('backend');
    }

    //the graph that shows our view counts
    private function transactionviewsgraph() {

        // debug(json_encode($from, JSON_PRETTY_PRINT)); exit;
        $transactions_Table = TableRegistry::get('Transactions');
        $views_graph = $transactions_Table->find();

        $views_graph->select([
                    'amount' => $views_graph->func()->sum('amount'),
                    'txdate' => 'DATE(transdate)'
                ])
                ->group('MONTH(txdate)');

        return $views_graph;
    }

    //admin method for managing admins
    public function manageadmins() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //ensure admin is loggeding
        $this->isloggedin();
        $admins_Table = TableRegistry::get('Admins');

        $alldmins = $admins_Table->find()->contain(['Users.Roles', 'Departments']);
        $this->set('alldmins', $alldmins);
        // debug(json_encode(   $alldmins, JSON_PRETTY_PRINT)); exit;
        $this->viewBuilder()->setLayout('backend');
    }

//ensure admin is loggedin
    public function isloggedin() {
        $logged_admin = $this->Users->get($this->Auth->user('id'));
        if ($logged_admin) {
            $this->set('logged_admin', $logged_admin);
            $this->request->getSession()->write('logged_admin', $logged_admin);
        } else {
            $this->Flash->error('Please login to continue');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
        return;
    }

    //the log otu function
    public function logout($user_id) {
        $UserLoginTable1 = TableRegistry::get('Userlogins');
        $userLogin = $UserLoginTable1->find()
                ->where(['logouttime' => '0000-00-00 00:00:00', 'user_id' => $user_id])
                ->first();
        if ($userLogin) {
            $userLogin->logouttime = date('Y-m-d H:i:s');
            $UserLoginTable1->save($userLogin);
            //debug(json_encode( $userLogin, JSON_PRETTY_PRINT)); exit;
            $this->request->getSession()->destroy();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        } else {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function updateLogout($user_id) {
        $UserLoginTable1 = TableRegistry::get('Userlogins');
        $userLogin = $UserLoginTable1->find()
                ->where(['logouttime' => '0000-00-00 00:00:00', 'user_id' => $user_id])
                ->first();
        if ($userLogin) {
            $userLogin->logouttime = date('Y-m-d H:i:s');
            $UserLoginTable1->save($userLogin);
            //debug(json_encode( $userLogin, JSON_PRETTY_PRINT)); exit;
        } else {
            return;
        }
    }

    public function createLogin($user_id) {
        $UserLoginTable = TableRegistry::get('Userlogins');
        $newUserLogin0 = $UserLoginTable->newEmptyEntity();
        $newUserLogin0->user_id = $user_id;
        $UserLoginTable->save($newUserLogin0);
        return;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        //ensure admin is loggeding
        $this->isloggedin();
//        $this->paginate = [
//           'contain' => ['Roles', 'Countries', 'States', 'Departments']
//        ];
        $users = $this->Users->find()->order(['created_date' => 'DESC']);

        $this->set(compact('users'));
        $this->viewBuilder()->setLayout('backend');
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Countries', 'States', 'Departments', 'Logs']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function newadmin() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {

            //upload passport
            /* $imagearray = $this->request->getData('passports');
              if (!empty($imagearray['tmp_name'])) {
              $image_name = $this->addimage($imagearray);
              } else {
              $image_name = '';
              } */

            $user = $this->Users->patchEntity($user, $this->request->getData());
            // $user->passport = $image_name;
            $user->created_by = $this->Auth->user('id');
            //  debug(json_encode( $user, JSON_PRETTY_PRINT)); exit;
            if ($this->Users->save($user)) {
                //generate uniqu id
                $this->createadminid($user->id);
                $this->Flash->success(__('The admin has been saved.'));

                return $this->redirect(['action' => 'manageadmins']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        /* $countries = $this->Users->Countries->find('list', ['limit' => 200]);
          $states = $this->Users->States->find('list', ['limit' => 200]); */
        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles', 'departments'));
        $this->viewBuilder()->setLayout('backend');
    }

    //function that generates a unique ID for each
    private function createadminid($id) {
        //get invoice prefix from session
        $settings = $this->request->getSession()->read('settings');
        $user = $this->Users->get($id);
        $user->useruniquid = $settings->adminprefix . date('y/m') . '/' . $id;
        $this->Users->save($user);
        return;
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    //method that updates an admin profile
    public function updateprofile() {
        //ensure admin is loggeding
        $this->isloggedin();
        $adminsTable = TableRegistry::get('Admins');
        $admin = $adminsTable->find()->where(['user_id' => $this->Auth->user('id')])
                        ->contain(['Users.Roles', 'Departments'])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {

            //upload passport
            $imagearray = $this->request->getData('passport');
             $passport_filename =   $imagearray->getClientFilename();
            if (!empty($passport_filename)) {
               $studentcontroller = new StudentsController();
                $image_name =   $studentcontroller->handlefileupload($this->request->getData('passport'), 'img/');
            
            } else {
                $image_name = $admin->adminphoto;
            }

            $admin = $adminsTable->patchEntity($admin, $this->request->getData());
            $admin->adminphoto = $image_name;
            if ($adminsTable->save($admin)) {

                //log activity
                $usercontroller = new UsersController();

                $title = "Admin updated his profil" . $admin->surname;
                $user_id = $this->Auth->user('id');
                $description = "profile update " . $admin->surname;
                $ip = $this->request->clientIp();
                $type = "Edit";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The admin has been updated successfuly.'));

                return $this->redirect(['action' => 'myprofile']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);

//        $countries = $this->Users->Countries->find('list', ['limit' => 200]);
//        $states = $this->Users->States->find('list', ['limit' => 200]);
        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'roles', 'departments'));
        $this->viewBuilder()->setLayout('backend');
    }

    public function updateadmin($id = null) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //ensure admin is loggeding
        $this->isloggedin();
        $adminsTable = TableRegistry::get('Admins');
        $admin = $adminsTable->get($id, [
            'contain' => ['Users.Roles', 'Departments']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $role_id = $this->request->getData('role_id');
            //upload passport
            $imagearray = $this->request->getData('passport');
            $pixname = $imagearray->getClientFilename();
            if (!empty($pixname)) {
                $studentcontroller = new StudentsController();
                $adminphoto = $studentcontroller->handlefileupload($this->request->getData('passport'), 'img/');
            } else {
                $adminphoto = $admin->adminphoto;
            }
            $admin = $adminsTable->patchEntity($admin, $this->request->getData());
            $admin->adminphoto = $adminphoto;
            if ($adminsTable->save($admin)) {
                //update role if necessary
                if (!empty($role_id)) {
                    $this->updaterole($admin->user_id, $role_id);
                }
                //log activity
                $usercontroller = new UsersController();

                $title = "Updated an Admin" . $admin->surname;
                $user_id = $this->Auth->user('id');
                $description = "Updated an Admin data " . $admin->surname;
                $ip = $this->request->clientIp();
                $type = "Edit";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The admin has been updated successfuly.'));

                return $this->redirect(['action' => 'manageadmins']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);

//        $countries = $this->Users->Countries->find('list', ['limit' => 200]);
//        $states = $this->Users->States->find('list', ['limit' => 200]);
        $departments = $this->Users->Departments->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'roles', 'departments'));
        $this->viewBuilder()->setLayout('backend');
    }

    //method that updates admin role after an update on their profile
    private function updaterole($user_id, $role_id) {
        $user = $this->Users->get($user_id);
        $user->role_id = $role_id;
        $this->Users->save($user);
        return;
    }

    //function for adding a staff image
    public function addimage($imagearray) {
        $folder_upload = "img/";
        $extension = array("jpeg", "jpg", "png", "gif");
        if (empty($imagearray['tmp_name'])) {
            return;
        }
        //$message = " ";
        $size = \getimagesize($imagearray['tmp_name']);
        // $mimetype = stripslashes($size['mime']); 
        if ((empty($size) || ($size[0] === 0) || ($size[1] === 0))) {
            throw new \Exception('This is unacceptable!. image must be of type : gif, jpeg, png or jpg and less than 2mb.');
        }

        //ensure image is less than 1 mb
        if ($imagearray['size'] > 1000000) {
            //  debug(json_encode( $imagearray, JSON_PRETTY_PRINT)); exit;  
            $this->Flash->error(__('Unable to upload Image. Image must be less than 1mb '));
            return;
        }


        $finfo = new \finfo(FILEINFO_MIME_TYPE);
//     //$filename = "company_staff_ids/".$staff_id;
        $file_type = $finfo->file(h($imagearray['tmp_name']), FILEINFO_MIME_TYPE);
//    
//    echo $file_type; exit;
        if (!(($file_type == "image/gif") || ($file_type == "image/png") || ($file_type == "image/jpeg") ||
                ($file_type == "image/pjpeg") || ($file_type == "image/x-png"))) {
            throw new \Exception('This is unacceptable!. image must be of type : gif, jpeg, png or jpg and less than 2mb .');
        }

        $file_name = $imagearray['name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array($ext, $extension)) {
            $file_name = md5(uniqid($imagearray['name'], true)) . time();

            if (!file_exists($folder_upload . $file_name . '.' . $ext)) {
                $file_name = $file_name . '.' . $ext;
                move_uploaded_file($imagearray["tmp_name"], $folder_upload . $file_name);

                chmod($folder_upload . $file_name, 0644);
                return $message = $file_name;
            } else {
                $filename = basename($file_name, $ext);
                $newFileName = crypt($filename . time()) . "." . $ext;
                // echo $file_name; exit;
                move_uploaded_file($imagearray["tmp_name"], $folder_upload . $newFileName);
                chmod($folder_upload . $newFileName, 0644);
                //delete old file
                unlink($folder_upload . $file_name);
                return $message = $newFileName;
            }
        } else {
            return $message = 'Unable to upload image, please ensure you are uploading a jpg,png,gif or Jpeg file. ';
            // debug(json_encode( $error, JSON_PRETTY_PRINT)); exit;
        }


        return $message = "images upload successful";
    }

    //functionn for deleting a file
    public function deletefile($filename) {
        $folder_upload = "img/";
        if (file_exists($folder_upload . $filename)) {
            unlink($folder_upload . $filename);
            return;
        }
        return;
    }

    //method that keeps track of all user activities on the app

    public function makeLog($title, $user_id, $description, $ip, $type) {
        //trust proxy
        $this->request->trustProxy = true;
        $LogsTable = TableRegistry::get('Logs');
        $logs = $LogsTable->newEmptyEntity();
        $logs->title = $title;
        $logs->user_id = $user_id;
        $logs->description = $description;
        $logs->ip = $ip;
        $logs->type = $type;
        // debug(json_encode( $logs, JSON_PRETTY_PRINT)); exit;
        $LogsTable->save($logs);
        return;
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'checkandremoveemail']);
    }

//forgot password method
    public function forgotpassword() {
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            $user = $this->Users->find()->where(['username' => $username])->first();
            if ($user) {
                //send a mail with the verification link
                $user->verification_key = md5($username);
                $this->Users->save($user);
                if ($this->emailverification($user->username, $user->verification_key)) {
                    // $this->Flash->success(__('A verification mail has been sent to you. Please check your inbox/spam folder and click on the link'));
                } else {
                    //$this->Flash->error(__('Sorry, unable to send mail. Please try again'));
                }
                return $this->redirect(['controller' => 'Users', 'action' => 'forgotpassword']);
            }
            $this->Flash->success(__('Sorry, user not found'));
            return $this->redirect(['controller' => 'Users', 'action' => 'forgotpassword']);
        }

        $this->viewBuilder()->setLayout('loginlayout');
    }

    public function changeuserstatus($user_id, $status) {
        $user = $this->Users->get($user_id);
        $user->userstatus = $status;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('Admin status has been changed to ' . $status));
        } else {
            $this->Flash->error(__('Unable to change admin status. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'manageadmins']);
    }

    public function viewadmin($admin_id) {
        $adminsTable = TableRegistry::get('Admins');

        $admin = $adminsTable->get($admin_id, ['contain' => ['Users.Roles', 'Privileges']]);
        $this->set('admin', $admin);
        // debug(json_encode( $admin, JSON_PRETTY_PRINT)); exit;
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for viewing her profile
    public function myprofile() {
        $adminsTable = TableRegistry::get('Admins');
        $admin = $adminsTable->get($this->Auth->user('id'), [
            'contain' => ['Users.Roles', 'Departments']
        ]);
        $this->set('admin', $admin);

        $this->viewBuilder()->setLayout('backend');
    }

    //method for uploading cvs
    public function uploadcv($file, $folder) {
        $extension = ['.docx', '.doc', '.pdf', '.txt'];
        //  $finfo = new \finfo(FILEINFO_MIME_TYPE);
        // $file_type = $finfo->file(h($file['tmp_name']), FILEINFO_MIME_TYPE);
        // $ext = pathinfo($file_type, PATHINFO_EXTENSION);
        $ext = strrchr($file['name'], '.');
        // echo $ext; exit;
        if (in_array($ext, $extension)) {
            $file_name = md5(uniqid($file['name'], true)) . time();

            if (!file_exists($folder . $file_name . $ext)) {
                $file_name = $file_name . $ext;

                move_uploaded_file($file["tmp_name"], $folder . $file_name);

                chmod($folder . $file_name, 0644);
                return $message = $file_name;
            } else {
                $filename = basename($file_name, $ext);
                $newFileName = crypt($filename . time()) . "." . $ext;
                // echo $file_name; exit;
                move_uploaded_file($file["tmp_name"], $folder . $newFileName);
                chmod($folder . $newFileName, 0644);
                return $message = $newFileName;
            }
        } else {
            return $message = 'Unable to upload image, please ensure you are uploading a jpg,png,gif or Jpeg file. ';
            // debug(json_encode( $error, JSON_PRETTY_PRINT)); exit;
        }


        // return $message = "images upload successful";
//          if (!(($file_type == ".doc") || ($file_type == ".docx") || ($file_type == ".pdf") ||
//                  ($file_type == ".txt"))) {
//              throw new \Exception('This is unacceptable!. image must be of type : gif, jpeg, png or jpg and less than 2mb .');
//          }
    }

    //method that send an email verification link
    public function emailverification($username, $key) {
        //base url
        $baseUrl = Router::url('/', true);
        $message = "Hello, you have requested to reset your password, please click the below link to reset your password<br />.";

        $message .= "  <a href='https://portal.uaes.education/users/changepassword/" . $key . "'>Change Password </a> or copy the link below and paste on your browser,then click  : ";

        $message .= "https://portal.uaes.education/users/changepassword/" . $key;

        $message .= '<br /><br />'
                . 'Kind Regards,<br />'
                . SCHOOL . ' <br />';


        // $statusmsg = "";
        $email = new Mailer('default');
        $email->setFrom(['info@uaes.education' => SCHOOL]);
        $email->setTo($username);
        $email->setBcc(['chukwudi.aniegboka@netpro.africa']);
        $email->setEmailFormat('html');
        $email->setSubject('Password Reset');
        if ($email->deliver($message)) {
            $this->Flash->success(__('A verification mail has been sent to ' . $username . ' Please check your inbox/spam folder and click on the link.'));
        } else {
            $this->Flash->error(__('Sorry, unable to send mail. Please try again.'));
        }
        return;
    }

    
    
    //method that sends mail to the foundation domain, parameters are passed from the foundation website to here
    public function myserver($name, $emailaddress,$message){
//        $post_data = $this->request->getData();
//        $dname = $post_data['name'];
//        $dmail = $post_data['mail'];
//        $dmessage = $post_data['message'];
        $the_message = $message.'<br />';
        $the_message .= 'Sender name: '.$name;
        $the_message .= 'Sender email: '.$emailaddress;
        $the_message .= '<br /><br />'
                . 'Kind Regards,<br />';
                
        // $statusmsg = "";
        $email = new Mailer('default');
        $email->setFrom(['info@uaes.education' => 'UAES Umuagwo']);
        $email->setTo('info@claretianeducation.org');
        $email->setBcc(['chukwudi.aniegboka@netpro.africa']);
        $email->setEmailFormat('html');
        $email->setSubject('Contact @ CUN Foundation');
        if ($email->deliver($the_message)) {
            exit;
        } else {
            exit;
        }
        return; 
    }
    
    
    
    //admin method for deactivating a user account
    public function deactivateaccount($user_id) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $user = $this->Users->get($user_id);
        $user->userstatus = "Deactivated";
        $this->Users->save($user);
        $this->Flash->success(__('The User account has been Deactivated '));
        return $this->redirect(['controller' => 'Users', 'action' => 'manageusers']);
    }

    //admin method for activating a user account
    public function activateaccount($user_id) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $user = $this->Users->get($user_id);
        $user->userstatus = "Activated";
        $this->Users->save($user);
        $this->Flash->success(__('The User account has been Aactivated '));
        return $this->redirect(['controller' => 'Users', 'action' => 'manageusers']);
    }

    //method that changes the password ead2c29088db4ffe4b7069146716157a
    public function changepassword($key) {
        if ($this->request->is('post')) {

            $user = $this->Users->find()->where(['verification_key' => $key])->first();
            if ($user) {
                $user->password = $this->request->getData('password');
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Your password has been updated'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                } else {
                    $this->Flash->error(__('Unable to change password. Please, try again.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            } else {
                $this->Flash->error(__('Unknown User.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }
        $this->viewBuilder()->setLayout('loginlayout');
    }

    //dashboard for demo purposes
    public function demo() {

        $this->viewBuilder()->setLayout('backend');
    }

//admin method for removing email addresses that have incomplete data
    //only those interested in helping organize the event were asked to comment, you are now muted for 3 days
    public function checkandremoveemail() {
        $users_Table = TableRegistry::get('Users');
        $students_Table = TableRegistry::get('Students');
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $user = $users_Table->find()->where(['username' => $email])->first();

            if (!empty($user->id)) {
                $student = $students_Table->find()->where(['user_id' => $user->id])->first();
                if (!empty($student->id)) {
                    //email belongs to another student
                    $this->Flash->error(__('The email address is already in use by another student'));
                    return $this->redirect(['action' => 'checkandremoveemail']);
                } else { //no student account attached to it so delete
                    $this->delete($user->id);
                    $this->Flash->success(__('The email address has been deleted and can now be '
                                    . 'used for application by another candidate'));
                    return $this->redirect(['action' => 'checkandremoveemail']);
                }
            } else { //email not found
                $this->Flash->error(__('The email address is not found on our system'));
                return $this->redirect(['action' => 'checkandremoveemail']);
            }
        }


        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for downloadoing applicayion files
    public function downloadfiles($name) {

        $ext = pathinfo($name, PATHINFO_EXTENSION);
        if (!file_exists("student_files/" . $name . '.' . $ext)) {


            //  debug(json_encode(filesize("cvs/" . $teacher->cv), JSON_PRETTY_PRINT));
            //  exit;
            header('Content-Type: ' . $ext);
            header('Content-Length: ' . filesize("student_files/" . $name));
            header('Content-Disposition: attachment;filename="' . $name . '"');
            header("Cache-control: private");


            readfile("student_files/" . $name);
            return;
        } else {
            $this->Flash->error(__('File not found'));
            return $this->redirect(['controller' => 'Students', 'action' => 'manageapplicants']);
        }
    }
    
    
    
    //method i used to update passwords for lecturers after migration
    public function updatelecturerspasswords(){
    $teachers = $this->Users->find()->where(['role_id'=>3]);
    $count = 0;
    foreach( $teachers as $teacher){
      $teacher->password = "lecturer123";
      $this->Users->save($teacher);
       $count++;
      
    }
     $this->Flash->success(__( $count.' passwords updated'));
     return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);   
        
    }
    
    //method i used to update passwords for students after migration
    public function updatestudentspasswords(){
        //  $students_Table = TableRegistry::get('Students');
    $students = $this->Users->find()->where(['role_id'=>2]);
    $count = 0;
    foreach( $students as $student){
      $student->password = "student123";
      $this->Users->save($student);
       $count++;
      
    }
     $this->Flash->success(__( $count.' passwords updated'));
     return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);   
        
    } 
    
    
    

    // allow unrestricted pages
    public function beforeFilter(EventInterface $event) {
        $this->Auth->allow(['forgotpassword','myserver', 'emailverification', 'changepaasword', 'changepassword',
            'login', 'applicationguide']);
        if (!$this->Auth->user()) {
            $this->Auth->setConfig('authError', false);
        }
//          $actions = ['login'];
//        if (in_array($this->request->getParam('action'), $actions)) {
//
//            // turn form protection 
//
//            $this->FormProtection->setConfig('validate', false);
//        }
    }

}
