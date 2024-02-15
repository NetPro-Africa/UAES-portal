<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 *
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettingsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $settings = $this->paginate($this->Settings);

        $this->set(compact('settings'));
        $this->viewBuilder()->setLayout('backend');
    }

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);

        $this->set('setting', $setting);
          $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $setting = $this->Settings->newEmptyEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $this->set(compact('setting'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function editsettings($id = null) {
        //check privilege
          $privilegescontroller = new PrivilegesController();
         if($privilegescontroller->hasprivilege(6)==0){
               return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
         }
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            //upload passport
           // debug(json_encode($this->request->getData(), JSON_PRETTY_PRINT)); exit;
            $imagearray = $this->request->getUploadedFile('logos');
             $name = $imagearray->getClientFilename();
            if (!empty($name)) {
                $studentcontroller = new StudentsController();
                $school_logo =  $studentcontroller->handlefileupload($this->request->getData('logos'), 'img/');
            }
            else{
            $school_logo =  $setting->logo;  
            }
           
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            $setting->logo =    $school_logo;
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $sessions = $this->Settings->Sessions->find('list',['limit' => 200])->order(['name'=>'ASC']);
        $semesters = $this->Settings->Semesters->find('list',['limit' => 200])->order(['name'=>'ASC']);
        $this->set(compact('setting','sessions','semesters'));
      $this->viewBuilder()->setLayout('backend');
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        //check privilege
          $privilegescontroller = new PrivilegesController();
         if($privilegescontroller->hasprivilege(6)==0){
               return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
         }
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__('The setting has been deleted.'));
        } else {
            $this->Flash->error(__('The setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
