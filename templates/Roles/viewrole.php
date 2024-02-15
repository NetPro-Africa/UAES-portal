<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>

<div class="container-fluid">
<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newrole'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'create new role']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Users with <?=h($role->role_name)?> Roles</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Roles Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
               
                <th scope="col"><?= __('Username') ?></th>
               <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Middle Name') ?></th>
               
                <th scope="col"><?= __('Useruniquid') ?></th>
                <th scope="col"><?= __('Userstatus') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
                  </thead>
                     <tfoot>
            <tr>
               
                <th scope="col"><?= __('Username') ?></th>
               <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Middle Name') ?></th>
               
                <th scope="col"><?= __('Useruniquid') ?></th>
                <th scope="col"><?= __('Userstatus') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
                  </tfoot>
                  <tbody>
            <?php foreach ($role->users as $users): ?>
            <tr>
               
                <td><?= h($users->username) ?></td>
                
                <td><?= h($users->fname) ?></td>
                <td><?= h($users->lname) ?></td>
                <td><?= h($users->mname) ?></td>
                
                <td><?= h($users->useruniquid) ?></td>
                <td><?= h($users->userstatus) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
                  </tbody>
        </table>
      
    </div>
            </div>
          </div>

        </div>
</div>

