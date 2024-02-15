<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Controller\AppController;

/**
 * Subjects Controller
 *
 * @property \App\Model\Table\SubjectsTable $Subjects
 *
 * @method \App\Model\Entity\Subject[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubjectsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $subjects = $this->paginate($this->Subjects);

        $this->set(compact('subjects'));
    }

    /**
     * View method
     *
     * @param string|null $id Subject id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $subject = $this->Subjects->get($id, [
            'contain' => ['Users', 'Departments', 'Semesters', 'Levels', 'Courseregistrations', 'Students', 'Teachers', 'Coursematerials', 'Results', 'Topics']
        ]);
       //  debug(json_encode( $subject, JSON_PRETTY_PRINT)); exit;
        $this->set('subject', $subject);
    }

    public function viewsubject($id = null) {
        $subject = $this->Subjects->get($id, [
            'contain' => ['Departments', 'Teachers']
        ]);

        $this->set('subject', $subject);
        $this->viewBuilder()->setLayout('backend');
    }

    //method i used to pull courses from old impoly to the new one
    public function getcoures() {
        $courses_table = TableRegistry::get('Courses');
        $courses = $courses_table->find();
        $count = 1;
        foreach ($courses as $course) {
            $subject = $this->Subjects->newEntity();
            $subject->name = $course->description;
            $subject->subjectcode = $course->coursename;
            $subject->creditload = $course->units;
            $subject->user_id = 1;
            $subject->status = 1;
            $subject->department_id = $count;
            $this->Subjects->save($subject);
            $count++;
            if ($count == 30) {
                $count = 1;
            }
        }
    }

    public function newsubject() {
        $subject = $this->Subjects->newEmptyEntity();
        if ($this->request->is('post')) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            $subject->user_id = $this->Auth->user('id');
            if ($this->Subjects->save($subject)) {
                //log activity
                $usercontroller = new UsersController();

                $title = "Added a new subject " . $subject->name;
                $user_id = $this->Auth->user('id');
                $description = "Created a new Subject " . $subject->name;
                $ip = $this->request->clientIp();
                $type = "Add";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The course has been saved.'));

                return $this->redirect(['action' => 'managesubjects']);
            }
            $this->Flash->error(__('The subject could not be saved. Please, try again.'));
        }
        $departments = $this->Subjects->Departments->find('list', ['limit' => 200])->order(['name'=>'ASC']);
        $levels = $this->Subjects->Levels->find('list', ['limit' => 200]);
        $semesters = $this->Subjects->Semesters->find('list', ['limit' => 200]);
        $students = $this->Subjects->Students->find('list', ['limit' => 200]);
        $teachers = $this->Subjects->Teachers->find('list', ['limit' => 200]);
        $this->set(compact('subject', 'departments', 'levels', 'semesters', 'students', 'teachers'));
        $this->viewBuilder()->setLayout('backend');
    }

    public function updatesubject($id = null) {
        $subject = $this->Subjects->get($id, [
            'contain' => ['Departments', 'Teachers']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            if ($this->Subjects->save($subject)) {
                //log activity
                $usercontroller = new UsersController();

                $title = "Updated a subject " . $subject->name;
                $user_id = $this->Auth->user('id');
                $description = "Updated a Subject " . $subject->name;
                $ip = $this->request->clientIp();
                $type = "Edit";
                $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
                $this->Flash->success(__('The course has been updated.'));

                return $this->redirect(['action' => 'managesubjects']);
            }
            $this->Flash->error(__('The subject could not be saved. Please, try again.'));
        }
        $departments = $this->Subjects->Departments->find('list', ['limit' => 200])->order(['name'=>'ASC']);
        $levels = $this->Subjects->Levels->find('list', ['limit' => 200])->order(['name'=>'ASC']);
        $semesters = $this->Subjects->Semesters->find('list', ['limit' => 200]);
       // $students = $this->Subjects->Students->find('list', ['limit' => 200]);
        $teachers = $this->Subjects->Teachers->find('list', ['limit' => 200])->order(['firstname'=>'ASC']);
        $this->set(compact('subject', 'departments', 'levels', 'semesters', 'teachers'));

        $this->viewBuilder()->setLayout('backend');
    }

    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $subject = $this->Subjects->get($id);
        if ($this->Subjects->delete($subject)) {
            //log activity
            $usercontroller = new UsersController();

            $title = "Deleted a subject " . $subject->name;
            $user_id = $this->Auth->user('id');
            $description = "Deleted a Subject " . $subject->name;
            $ip = $this->request->clientIp();
            $type = "Delete";
            $usercontroller->makeLog($title, $user_id, $description, $ip, $type);
            $this->Flash->success(__('The subject has been deleted.'));
        } else {
            $this->Flash->error(__('The subject could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'managesubjects']);
    }

    public function managesubjects() {

        $subjects = $this->Subjects->find()
                ->contain(['Departments', 'Semesters', 'Levels','Teachers'])
                ->order(['Subjects.name' => 'ASC']);

        $this->set(compact('subjects'));

        $this->viewBuilder()->setLayout('backend');
    }

    public function changesubjectstatus($id, $status) {
        $subjects = $this->Subjects->get($id);
        $subjects->status = $status;

        if ($this->Subjects->save($subjects)) {
            $this->Flash->success(__('Subject status has been changed'));
        } else {
            $this->Flash->error(__('Unable to change Subjects status. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Subjects', 'action' => 'managesubjects']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $subject = $this->Subjects->newEntity();
        if ($this->request->is('post')) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('The subject has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The subject could not be saved. Please, try again.'));
        }
        $users = $this->Subjects->Users->find('list', ['limit' => 200]);
        $departments = $this->Subjects->Departments->find('list', ['limit' => 200]);
        $semesters = $this->Subjects->Semesters->find('list', ['limit' => 200]);
        $levels = $this->Subjects->Levels->find('list', ['limit' => 200]);
        $courseregistrations = $this->Subjects->Courseregistrations->find('list', ['limit' => 200]);
        $students = $this->Subjects->Students->find('list', ['limit' => 200]);
        $teachers = $this->Subjects->Teachers->find('list', ['limit' => 200]);
        $this->set(compact('subject', 'users', 'departments', 'semesters', 'levels', 'courseregistrations', 'students', 'teachers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Subject id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $subject = $this->Subjects->get($id, [
            'contain' => ['Departments', 'Semesters', 'Levels', 'Courseregistrations', 'Students', 'Teachers']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('The subject has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The subject could not be saved. Please, try again.'));
        }
        $users = $this->Subjects->Users->find('list', ['limit' => 200]);
        $departments = $this->Subjects->Departments->find('list', ['limit' => 200]);
        $semesters = $this->Subjects->Semesters->find('list', ['limit' => 200]);
        $levels = $this->Subjects->Levels->find('list', ['limit' => 200]);
        $courseregistrations = $this->Subjects->Courseregistrations->find('list', ['limit' => 200]);
        $students = $this->Subjects->Students->find('list', ['limit' => 200]);
        $teachers = $this->Subjects->Teachers->find('list', ['limit' => 200]);
        $this->set(compact('subject', 'users', 'departments', 'semesters', 'levels', 'courseregistrations', 'students', 'teachers'));
    }

}
