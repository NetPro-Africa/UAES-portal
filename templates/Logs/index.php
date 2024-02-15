<?php 
  $userdata = $this->request->getSession()->read('usersinfo');
  $userrole = $this->request->getSession()->read('usersroles');
?>
<div class="container-fluid">
    <div style="padding-bottom: 10px; margin-bottom: 20px;">
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Activity Logs</h3>
         
    </div>
    <!-- /.box-header -->
       <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activity Logs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                    <thead>
        <tr>
                
                <th scope="col"><?= $this->Paginator->sort('Title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Admin') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Description') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ip') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Type') ?></th>
<!--                <th scope="col" class="actions"><?= __('Actions') ?></th>-->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                
                <td><?= h($log->title) ?></td>
                <td><?= date('D d M, Y h:i',  strtotime($log->timestamp)) ?></td>
                <td><?= $log->has('user') ? $log->user->username: '' ?></td>
                <td><?= h($log->description) ?></td>
                <td><?= h($log->ip) ?></td>
                <td><?= h($log->type) ?></td>
<!--                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $log->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $log->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $log->id], ['confirm' => __('Are you sure you want to delete # {0}?', $log->id)]) ?>
                </td>-->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div></div></div></div></div></div>
   