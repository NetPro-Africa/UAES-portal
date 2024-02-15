<?php

declare(strict_types = 1);

namespace App\Controller;

use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper;
use Cake\Mailer\Email;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;

/**
 * Admins Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(5) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $this->paginate = [
            'contain' => ['Users']
        ];
        $admins = $this->paginate($this->Admins);

        $this->set(compact('admins'));
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for managing transcript requests
    public function managetranscriptorders() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(5) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //ensure this is an admin
        $this->isadmin();
        $trequest_table = TableRegistry::get('Trequests');
        $trequests = $trequest_table->find()
                ->contain(['Students', 'Countries', 'Continents', 'States', 'Couriers', 'Students.Invoices'])
                // ->where(['Invoice.paystatus'=>'success'])
                ->order(['orderdate' => 'DESC']);
        //debug(json_encode( $trequests, JSON_PRETTY_PRINT)); exit;
        $this->set('trequests', $trequests);
        $this->viewBuilder()->setLayout('backend');
    }

    //business inteligence method for admin
    public function businessinteligence() {

        $this->isadmin();
        $students_table = TableRegistry::get('Students');
        
        $query = $students_table->find()->where(['status'=>'Admitted']);
        $query->select([
                    'count' => $query->func()->count('id'),
                    'department_id' => 'department_id',
                    'gender' => 'gender',
                    'lga_id' => 'lga_id'
                ])
                ->group('department_id');
        
        $query1 = $students_table->find()->where(['status'=>'Admitted']);
        $query1->select([
                    'count' => $query->func()->count('id'),
                    'department_id' => 'department_id',
                    'gender' => 'gender',
                    'lga_id' => 'lga_id'
                ])
                ->group('gender');
        //group by state
          $query2 = $students_table->find()->where(['status'=>'Admitted']);
        $query2->select([
                    'count' => $query->func()->count('id'),
                    'department_id' => 'department_id',
                    'state_id' => 'state_id',
                    'lga_id' => 'lga_id'
                ])
                ->group('state_id');
        //group by LGA
        $query3 = $students_table->find()->where(['status'=>'Admitted']);
        $query3->select([
                    'count' => $query->func()->count('id'),
                    'department_id' => 'department_id',
                    'state_id' => 'state_id',
                    'lga_id' => 'lga_id'
                ])
                ->group('lga_id');
              //  ->having(['count >' => 3]);
        // debug(json_encode( $query2, JSON_PRETTY_PRINT)); exit;
         $this->set('students', $query);
          $this->set('bygender', $query1);
         $this->set('bystate', $query2);
         $this->set('bylga', $query3);
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for managing classes
    public function manageclasses() {
        //ensure this is an admin
        $this->isadmin();
        $levels_table = TableRegistry::get('Levels');
        $levels = $levels_table->find()->order(['name' => 'DESC']);
        $this->set('levels', $levels);
        $this->viewBuilder()->setLayout('backend');
    }

//admin method for manage the school books
    public function managebooks() {
        //ensure this is an admin
        $this->isadmin();
        $books_table = TableRegistry::get('Books');

        if ($this->request->is('post')) {
            $isbn = $this->request->getData('isbn');
            $bookauthor = $this->request->getData('bookauthor');
            $booktitle = $this->request->getData('booktitle');
            $conditions = [];
            if (!empty($isbn)) {
                $conditions['isbn'] = $isbn;
            }
            if (!empty($bookauthor)) {
                $conditions['author'] = $bookauthor;
            }
            if (!empty($booktitle)) {
                $conditions['title'] = $booktitle;
            }
            $books = $books_table->find()->where($conditions)->order(['title' => 'DESC']);
            $this->set('books', $books);
        } else {
            $books = $books_table->find()->order(['date_created' => 'DESC']);
            $this->set('books', $books);
        }

        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for generating transcripts
    public function generatetranscript($student_id) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(5) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //ensure this is an admin
        $this->isadmin();
        $stdents_table = TableRegistry::get('Students');
        $trequests_table = TableRegistry::get('Trequests');

        $student = $stdents_table->get($student_id, [
            'contain' => ['Departments.Subjects', 'Users', 'Results.Sessions', 'Results.Semesters', 'Trequests',
                'Results.Subjects', 'Countries', 'States','Faculties']
        ]);
        //get the transcript request
        $trequest = $trequests_table->find()
                ->contain(['Countries', 'Continents', 'Couriers'])
                ->where(['student_id' => $student->id])
                ->order(['orderdate' => 'DESC'])
                ->limit(1);
        $this->set('student', $student);
        $this->set('trequest', $trequest);
        //  debug(json_encode( $student->results, JSON_PRETTY_PRINT)); exit;

        $this->viewBuilder()->setLayout('backend');
    }

//method that ensures this person is an admin
    public function isadmin() {
        $admin = $this->Admins->find()->where(['user_id' => $this->Auth->user('id'), 'status' => 'active'])->first();
        if (!$admin) {
            $this->Flash->error(__('Sorry, unknown admin or disabled account'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        } else {
            return $admin;
        }
    }

    /**
     * View method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $admin = $this->Admins->get($id, [
            'contain' => ['Users', 'Departments']
        ]);

        $this->set('admin', $admin);
        $this->viewBuilder()->setLayout('backend');
    }

    public function newadmin() {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $admin = $this->Admins->newEmptyEntity();
        if ($this->request->is('post')) {
            $email = $this->request->getData('username');
            $fname = $this->request->getData('surname');
            $lname = $this->request->getData('lastname');
            $mname = $this->request->getData('middlename');
             $gender = $this->request->getData('gender');
             $address = $this->request->getData('address');
             $phone = $this->request->getData('phone');
            $user_id = $this->getlogindetails($email, $fname, $lname, $mname, $gender, $address,$phone);
            if (is_numeric($user_id)) {
                //upload passport
                $imagearray = $this->request->getData('passports');
            $admin_photo =  $imagearray->getClientFilename();
            if (!empty($admin_photo)) {
               $studentcontroller = new StudentsController();
                $admin_pix = $studentcontroller->handlefileupload($this->request->getData('passports'), 'img/');
            }
            else{
           $admin_pix = "";   
            }

                $admin = $this->Admins->patchEntity($admin, $this->request->getData());
                $admin->user_id = $user_id;
                $admin->adminphoto = $admin_pix;
                $admin->status = "active";
                if ($this->Admins->save($admin)) {
                    //log activity
                    $usercontroller = new UsersController();

                    $title = "added a new admin " . $admin->surname;
                    $user_id = $this->Auth->user('id');
                    $description = "Created new admin " . $admin->lastname;
                    $ip = $this->request->clientIp();
                    $type = "Add";
                    $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                    $this->Flash->success(__('The admin has been saved.'));

                    return $this->redirect(['controller' => 'Users', 'action' => 'manageadmins']);
                }
                $this->Flash->error(__('The admin could not be saved. Please, try again.'));
            }
        }
        $departments = $this->Admins->Departments->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'departments'));
        $this->viewBuilder()->setLayout('backend');
    }

    //method that creates login details for the new admin
    private function getlogindetails($email, $fname, $lname, $mname,$gender, $address,$phone) {
        $users_Table = TableRegistry::get('Users');
        $user = $users_Table->newEmptyEntity();
        $user->role_id = 1;
        $user->password = "admin123";
        $user->username = $email;
        $user->fname = $fname;
        $user->lname = $lname;
        $user->mname = $mname;
        $user->gender = $gender;
        $user->address =  $address;
        $user->country_id = 160;
        $user->state_id = 2467;
        $user->phone = $phone;
        $user->department_id = 1;
        $user->created_by =1;
        $user->useruniquid = "unique";
        if ($users_Table->save($user)) {
            return $user->id;
        } else {
            return "Failed";
        }
        return;
    }

    //admin method for adding a new book to the library
    public function addnewbook() {
        //ensure this is an admin
        $this->isadmin();
        $books_Table = TableRegistry::get('Books');
        $book = $books_Table->newEmptyEntity();

        if ($this->request->is('post')) {
            $userscontroller = new UsersController();
            //upload cover file
            $imagearray = $this->request->getData('bookimage');
            if (!empty($imagearray['tmp_name'])) {
                $cover_image = $userscontroller->addimage($imagearray);
            } else {
                $cover_image = "";
            }
            $book = $books_Table->patchEntity($book, $this->request->getData());
            $book->coverphoto = $cover_image;
            if ($books_Table->save($book)) {
                //log activity
                $usercontroller = new UsersController();

                $title = "Added a book to the library " . $book->title;
                $user_id = $this->Auth->user('id');
                $description = "Added a book to the library " . $book->title;
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The book has been saved.'));

                return $this->redirect(['action' => 'managebooks']);
            }
            $this->Flash->error(__('The book could not be saved. Please, try again.'));
        }
        // $users = $this->Books->Users->find('list', ['limit' => 200]);
        $this->set(compact('book', 'users'));
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for updating a book
    public function updatebook($id) {

        //ensure this is an admin
        $this->isadmin();
        $books_Table = TableRegistry::get('Books');
        $book = $books_Table->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $userscontroller = new UsersController();
            //upload cover file
            $imagearray = $this->request->getData('bookimage');
            if (!empty($imagearray['tmp_name'])) {
                $cover_image = $userscontroller->addimage($imagearray);
            } else {
                $cover_image = $book->coverphoto;
            }

            $book = $books_Table->patchEntity($book, $this->request->getData());
            $book->coverphoto = $cover_image;
            if ($books_Table->save($book)) {
                //log activity
                $usercontroller = new UsersController();

                $title = "Updated a book in the library " . $book->title;
                $user_id = $this->Auth->user('id');
                $description = "Updated a book in the library " . $book->title;
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The book has been updated.'));

                return $this->redirect(['action' => 'managebooks']);
            }
            $this->Flash->error(__('The book could not be updated. Please, try again.'));
        }
        // $users = $this->Books->Users->find('list', ['limit' => 200]);
        $this->set(compact('book', 'users'));
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method for assigning a book to a student
    public function assignbook($id) {
        $students_Table = TableRegistry::get('Students');
        $books_Table = TableRegistry::get('Books');
        $departments_Table = TableRegistry::get('Departments');
        $borrowedbooks_Table = TableRegistry::get('Borrowedbooks');
        $borrowedbook = $borrowedbooks_Table->newEmptyEntity();
        $book = $books_Table->get($id);
        if ($this->request->is('post')) {
            $borrowedbook = $borrowedbooks_Table->patchEntity($borrowedbook, $this->request->getData());
            $borrowedbook->book_id = $id;
            if ($borrowedbooks_Table->save($borrowedbook)) {
                //mark book as unavailbale
                $book = $books_Table->get($id);
                $book->isavailable = "Unavailable";
                $books_Table->save($book);
                //log activity
                $usercontroller = new UsersController();

                $title = "Issued a book " . $book->title . ' to a student';
                $user_id = $this->Auth->user('id');
                $description = "issued a book " . $book->title;
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The book has been assign to the student.'));

                return $this->redirect(['action' => 'borrowedbooks']);
            }
            $this->Flash->error(__('The borrowedbook could not be saved. Please, try again.'));
        }

        $students = $students_Table->find('list')
                ->where(['status' => 'Admitted'])
                ->order(['fname' => 'DESC']);
        $departments = $departments_Table->find('list')->order(['name' => 'DESC']);
        $this->set(compact('book', 'book', 'students', 'borrowedbook', 'departments'));
        $this->viewBuilder()->setLayout('backend');
    }

    //super admin method for managing and assigning  privileges
    public function assignprivileges($admin_id) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        //ensure this is an admin
        $this->isadmin();
        $admin = $this->Admins->get($admin_id, [
            'contain' => ['Privileges']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('The admin and privileges has been updated.'));

                return $this->redirect(['action' => 'adminprivileges', $admin->id, $admin->surname]);
            }
            $this->Flash->error(__('The admin privileges could not be updated. Please, try again.'));
        }
        $users = $this->Admins->Users->find('list', ['limit' => 200]);
        $departments = $this->Admins->Departments->find('list', ['limit' => 200]);
        $privileges = $this->Admins->Privileges->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'users', 'departments', 'privileges'));
        $this->viewBuilder()->setLayout('backend');
    }

    //method that show an admin with his privileges
    public function adminprivileges($admin_id) {
        $admin = $this->Admins->get($admin_id, [
            'contain' => ['Privileges']
        ]);
        $this->set('admin', $admin);
        $this->viewBuilder()->setLayout('backend');
    }

    //admin method that reuns a book and makes it available for borrowing again
    public function returnbook($id = null) {
        $books_Table = TableRegistry::get('Books');
        $borrowedbooks_Table = TableRegistry::get('Borrowedbooks');
        $book = $books_Table->get($id);
        $book->isavailable = "Available";
        $books_Table->save($book);
        //update borrowed books
        $borrowedbooks = $borrowedbooks_Table->find()->where(['book_id' => $id, 'status' => 'not returned'])->first();
        if ($borrowedbooks) {
            $borrowedbooks->status = "returned";
            $borrowedbooks_Table->save($borrowedbooks);
            $this->Flash->success(__('The book has been returned and now available for borrowing again.'));
            return $this->redirect(['action' => 'managebooks']);
        } else {
            $this->Flash->error(__('Unknown book, unable to return book. Please, try again.'));
            return $this->redirect(['action' => 'borrowedbooks']);
        }
    }

//method that shows the borrowed books
    public function borrowedbooks() {
        $borrowedbooks_Table = TableRegistry::get('Borrowedbooks');
        $borrowedbooks = $borrowedbooks_Table->find()
                ->contain(['Students', 'Books', 'Students.Departments'])
                ->where(['Borrowedbooks.status' => 'not returned'])
                ->order(['date' => 'DESC']);
        $this->set('borrowedbooks', $borrowedbooks);
        // debug(json_encode( $borrowedbooks, JSON_PRETTY_PRINT)); exit;
        $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $admin = $this->Admins->newEmptyEntity();
        if ($this->request->is('post')) {
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('The admin has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin could not be saved. Please, try again.'));
        }
        $users = $this->Admins->Users->find('list', ['limit' => 200]);
        $departments = $this->Admins->Departments->find('list', ['limit' => 200]);
        $privileges = $this->Admins->Privileges->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'users', 'departments', 'privileges'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $admin = $this->Admins->get($id, [
            'contain' => ['Privileges']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('The admin has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin could not be saved. Please, try again.'));
        }
        $users = $this->Admins->Users->find('list', ['limit' => 200]);
        $departments = $this->Admins->Departments->find('list', ['limit' => 200]);
        $privileges = $this->Admins->Privileges->find('list', ['limit' => 200]);
        $this->set(compact('admin', 'users', 'departments', 'privileges'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $admin = $this->Admins->get($id);
        if ($this->Admins->delete($admin)) {
            $this->Flash->success(__('The admin has been deleted.'));
        } else {
            $this->Flash->error(__('The admin could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //test qr-code generateor
    public function getqrcode() {
        $regno = "2001514539";
        $this->set('regno', $regno);
    }

    //admin method for viewing their profile
    public function adminprofile() {
        $admin = $this->Admins->find()->where(['user_id' => $this->Auth->user('id')])
                        ->contain(['Users.Roles', 'Privileges'])->first();
        $this->set('admin', $admin);
        // debug(json_encode( $admin, JSON_PRETTY_PRINT)); exit;
        $this->viewBuilder()->setLayout('backend');
    }

    //shows an admin activity log
    public function viewactivitylogs($admin_id) {
        //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(7) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $admin = $this->Admins->get($admin_id, ['contain' => ['Users.Logs' => function (Query $q) {
                    return $q->order(['Logs.timestamp' => 'DESC'])->limit(500);
                }]]);
                $this->set('admin', $admin);
                $this->viewBuilder()->setLayout('backend');
            }
            
            
        
       //admin method for view students who paid fees to know who did not pay a particular fee
       public function checkwhopaidfee(){
           
             //check privilege
        $privilegescontroller = new PrivilegesController();
        if ($privilegescontroller->hasprivilege(4) == 0) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        } 
         $transactions_Table = TableRegistry::get('Transactions');
           $settings = $this->request->getSession()->read('settings');
          if ($this->request->is('post')) {
            $session_id = $this->request->getData('session_id');
                $condition = [];
            
            if (!empty($session_id)) {
                $condition['Transactions.session_id'] = $session_id;
            }
             $condition['paystatus'] = 'completed';
             $transactions = $transactions_Table->find()
                    ->contain(['Students.Levels', 'Fees', 'Sessions'])
                    ->Where($condition)
                    // ->group(['fee_id'])
                    ->order(['transdate' => 'DESC']);
                    //->limit(5000);
          }else{
           $transactions = $transactions_Table->find()
                    ->contain(['Students.Levels', 'Fees', 'Sessions'])
                    ->Where(['Transactions.session_id'=>$settings->session_id,'paystatus'=>'completed'])
                    // ->group(['fee_id'])
                    ->order(['transdate' => 'DESC']);
                    //->limit(5000);   
          }
            $sessions = $transactions_Table->Sessions->find('list')->order(['name' => 'DESC']);
           
               $this->set(compact( 'sessions','transactions'));
           $this->viewBuilder()->setLayout('backend');
           
       }












       //admin method for retrying a failed transaction
    public function retrypayment($amount, $payref) {
        // $amount_to_pay = round($amount / SERVICE_CHARGE);
           
        $subpdtid = PRODUCT_ID; // your product ID is always constant
        $string_to_hash = PRODUCT_ID . $payref . MACKEY;  // concatenate the strings ("Prod_ID"."txn_ref"."mac") for hash again
        $hash = hash('sha512', $string_to_hash);  //hash to be passed in header

        $query_elements = array(
            "productid" => $subpdtid,
            "transactionreference" => $payref,
            "amount" =>  $amount 
        );
        $link_query_values = http_build_query($query_elements);

        $url = "https://webpay.interswitchng.com/collections/w/pay" . $link_query_values;
        //header details. Put hash here
        $headers = array(
            "GET /HTTP/1.1",
            "Host: webpay.interswitchng.com",
            "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1",
            //"Content-type:  multipart/form-data",
            //"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8", 
            "Accept-Language: en-us,en;q=0.5",
            //"Accept-Encoding: gzip,deflate",
            //"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
            "Keep-Alive: 300",
            "Connection: keep-alive",
            "Hash: $hash" //hash value
        );
      //  print_r($url);
        $ch = curl_init();  //INITIALIZE CURL///////////////////////////////
//               
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, false);
//
        $data = curl_exec($ch);  //EXECUTE CURL STATEMENT///////////////////////////////
        $json = null;
        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch) . "</br></br>";

            $errno = curl_errno($ch);
            $error_message = curl_strerror($errno);
            print $error_message . "</br></br>";

            print_r($headers);
            exit;
        } else {
            //update db
            // Show me the result
            $json = json_decode($data, TRUE);
            curl_close($ch);
          // debug(json_encode( $json , JSON_PRETTY_PRINT)); exit;
            //update db if response code is 00
            if ($json["ResponseCode"] == '00') {
                // debug(json_encode( $json , JSON_PRETTY_PRINT)); exit;
                $transaction = $this->Transactions->find()
                                ->where(['payref' => $payref])->first();
                //update transaction
                $transaction->paystatus = "completed";
                $transaction->gresponse = $json["ResponseDescription"];
                $transaction->paymentlogid = $json["PaymentReference"];
                $transaction->transdate = date('Y-m-d H:i');
                $transaction->pgateway = "InterSwitch";
                // debug(json_encode( $this->request->getData() , JSON_PRETTY_PRINT)); exit; 
                $this->Transactions->save($transaction);
                return $transaction->payref;
                
            } else {
                //the transaction was not succesful
                $this->set('json', $json);
                //print_r(array_values($json));
                 //debug(json_encode( $json , JSON_PRETTY_PRINT)); exit;
                // Display Array Elements///////////////
			echo "Transaction Amount: ".$json["Amount"]."</br>";
			echo "Card Number: ".$json["CardNumber"]."</br>";
			echo "Transaction Reference: ".$json["MerchantReference"]."</br>";
			echo "Payment Reference: ".$json["PaymentReference"]."</br>";
			echo "Retrieval Reference Number: ".$json["RetrievalReferenceNumber"]."</br>";
			echo "Lead Bank CBN Code:".$json["LeadBankCbnCode"]."</br>";
                echo "Lead Bank Name: ".$json["LeadBankName"]."</br>";
                //echo "Split Accounts: ".$json["SplitAccounts"]."</br>";
			echo "Transaction Date: ".$json["TransactionDate"]."</br>";
			echo "Response Code: ".$json["ResponseCode"]."</br>";
			echo "Response Description: ".$json["ResponseDescription"]."</br>";	
                // //////Display Array Elements////////////
                        exit;
            }
        }

       $this->viewBuilder()->setLayout('loginlayout');
    }


            
            

        }
        