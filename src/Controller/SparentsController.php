<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Sparents Controller
 *
 * @property \App\Model\Table\SparentsTable $Sparents
 * @method \App\Model\Entity\Sparent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SparentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $sparents = $this->paginate($this->Sparents);

        $this->set(compact('sparents'));
    }

    /**
     * View method
     *
     * @param string|null $id Sparent id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sparent = $this->Sparents->get($id, [
            'contain' => ['Users', 'Students'],
        ]);

        $this->set(compact('sparent'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sparent = $this->Sparents->newEmptyEntity();
        if ($this->request->is('post')) {
            $sparent = $this->Sparents->patchEntity($sparent, $this->request->getData());
            if ($this->Sparents->save($sparent)) {
                $this->Flash->success(__('The sparent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sparent could not be saved. Please, try again.'));
        }
        $users = $this->Sparents->Users->find('list', ['limit' => 200]);
        $students = $this->Sparents->Students->find('list', ['limit' => 200]);
        $this->set(compact('sparent', 'users', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sparent id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sparent = $this->Sparents->get($id, [
            'contain' => ['Students'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sparent = $this->Sparents->patchEntity($sparent, $this->request->getData());
            if ($this->Sparents->save($sparent)) {
                $this->Flash->success(__('The sparent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sparent could not be saved. Please, try again.'));
        }
        $users = $this->Sparents->Users->find('list', ['limit' => 200]);
        $students = $this->Sparents->Students->find('list', ['limit' => 200]);
        $this->set(compact('sparent', 'users', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sparent id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sparent = $this->Sparents->get($id);
        if ($this->Sparents->delete($sparent)) {
            $this->Flash->success(__('The sparent has been deleted.'));
        } else {
            $this->Flash->error(__('The sparent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
