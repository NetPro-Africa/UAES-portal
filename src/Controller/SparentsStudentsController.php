<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SparentsStudents Controller
 *
 * @property \App\Model\Table\SparentsStudentsTable $SparentsStudents
 * @method \App\Model\Entity\SparentsStudent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SparentsStudentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Students', 'ParentSparentsStudents'],
        ];
        $sparentsStudents = $this->paginate($this->SparentsStudents);

        $this->set(compact('sparentsStudents'));
    }

    /**
     * View method
     *
     * @param string|null $id Sparents Student id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sparentsStudent = $this->SparentsStudents->get($id, [
            'contain' => ['Students', 'ParentSparentsStudents', 'ChildSparentsStudents'],
        ]);

        $this->set(compact('sparentsStudent'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sparentsStudent = $this->SparentsStudents->newEmptyEntity();
        if ($this->request->is('post')) {
            $sparentsStudent = $this->SparentsStudents->patchEntity($sparentsStudent, $this->request->getData());
            if ($this->SparentsStudents->save($sparentsStudent)) {
                $this->Flash->success(__('The sparents student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sparents student could not be saved. Please, try again.'));
        }
        $students = $this->SparentsStudents->Students->find('list', ['limit' => 200]);
        $parentSparentsStudents = $this->SparentsStudents->ParentSparentsStudents->find('list', ['limit' => 200]);
        $this->set(compact('sparentsStudent', 'students', 'parentSparentsStudents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sparents Student id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sparentsStudent = $this->SparentsStudents->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sparentsStudent = $this->SparentsStudents->patchEntity($sparentsStudent, $this->request->getData());
            if ($this->SparentsStudents->save($sparentsStudent)) {
                $this->Flash->success(__('The sparents student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sparents student could not be saved. Please, try again.'));
        }
        $students = $this->SparentsStudents->Students->find('list', ['limit' => 200]);
        $parentSparentsStudents = $this->SparentsStudents->ParentSparentsStudents->find('list', ['limit' => 200]);
        $this->set(compact('sparentsStudent', 'students', 'parentSparentsStudents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sparents Student id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sparentsStudent = $this->SparentsStudents->get($id);
        if ($this->SparentsStudents->delete($sparentsStudent)) {
            $this->Flash->success(__('The sparents student has been deleted.'));
        } else {
            $this->Flash->error(__('The sparents student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
