<?php
declare(strict_types=1);

namespace App\Controller;
 use Cake\ORM\TableRegistry;
use App\Controller\AppController;

/**
 * Levels Controller
 *
 * @property \App\Model\Table\LevelsTable $Levels
 *
 * @method \App\Model\Entity\Level[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LevelsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $levels = $this->paginate($this->Levels);

        $this->set(compact('levels'));
    }

    /**
     * View method
     *
     * @param string|null $id Level id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $level = $this->Levels->get($id, [
            'contain' => ['Students']
        ]);

        $this->set('level', $level);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addnewclass()
    {
        $level = $this->Levels->newEmptyEntity();
        if ($this->request->is('post')) {
            $level = $this->Levels->patchEntity($level, $this->request->getData());
            if ($this->Levels->save($level)) {
                $this->Flash->success(__('The class has been saved.'));

                return $this->redirect(['controller'=>'Admins','action' => 'manageclasses']);
            }
            $this->Flash->error(__('The class could not be saved. Please, try again.'));
        }
        $this->set(compact('level'));
          $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Edit method
     *
     * @param string|null $id Level id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function updateclass($id = null)
    {
        $level = $this->Levels->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $level = $this->Levels->patchEntity($level, $this->request->getData());
            if ($this->Levels->save($level)) {
                $this->Flash->success(__('The class has been updated.'));

                return $this->redirect(['controller'=>'Admins','action' => 'manageclasses']);
            }
            $this->Flash->error(__('The class could not be saved. Please, try again.'));
        }
        $this->set(compact('level'));
          $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Delete method
     *
     * @param string|null $id Level id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $level = $this->Levels->get($id);
        if ($this->Levels->delete($level)) {
            $this->Flash->success(__('The class has been deleted.'));
        } else {
            $this->Flash->error(__('The class could not be deleted. Please, try again.'));
        }

       return $this->redirect(['controller'=>'Admins','action' => 'manageclasses']);
    }
}
