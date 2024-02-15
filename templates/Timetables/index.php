<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
     <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Time Table Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                  <thead>
            <tr>
              
                    <th><?= $this->Paginator->sort('Session') ?></th>
                    <th><?= $this->Paginator->sort('Department') ?></th>
                    <th><?= $this->Paginator->sort('Level') ?></th>
                    <th><?= $this->Paginator->sort('Semester') ?></th>
                  
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                  </thead>
      
     
        <tbody>
                <?php foreach ($timetables as $timetable): ?>
                <tr>
                
                    <td><?= $timetable->has('session') ? $timetable->session->name : '' ?></td>
                    <td><?= $timetable->has('department') ? $timetable->department->name : '' ?></td>
                    <td><?= $timetable->has('level') ? $timetable->level->name : '' ?></td>
                    <td><?= $timetable->has('semester') ? $timetable->semester->name : '' ?></td>
                  
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $timetable->id],['title' => 'view time table', 'class' => 'btn btn-success']) ?>
                        <?= $this->Html->link(__('Update'), ['action' => 'update', $timetable->id],['title' => 'Update time table', 'class' => 'btn btn-primary']) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $timetable->id], 
                                ['confirm' => __('Are you sure you want to delete this time table # {0}?', $timetable->id),'class' => 'btn btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>           
            
            
            
            
            
            
            
            
            
<div class="timetables index content">
    <?= $this->Html->link(__('New Timetable'), ['action' => 'addtimetable'], ['class' => 'button float-right']) ?>
   
   
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
