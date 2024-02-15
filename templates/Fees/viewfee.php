
<div class="fees view large-9 medium-8 columns content">
    <h3><?= h($fee->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($fee->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= $fee->has('user') ? $this->Html->link($fee->user->username, ['controller' => 'Users', 'action' => 'view', $fee->user->id]) : '' ?></td>
        </tr>
        
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($fee->amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($fee->status) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Assigned Departments') ?></h4>
        <?php if (!empty($fee->departments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
               
                <th style="padding: 10px">Name</th>
             
                <th style="padding: 10px">Dept code </th>
<!--                <th style="padding: 10px">Actions</th>-->
            </tr>
            <?php foreach ($fee->departments as $departments): ?>
            <tr>
              
                <td style="padding: 10px"><?= h($departments->name) ?></td>
                
                <td style="padding: 10px"><?= h($departments->deptcode) ?></td>
<!--                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Departments', 'action' => 'view', $departments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Departments', 'action' => 'edit', $departments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Departments', 'action' => 'delete', $departments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $departments->id)]) ?>
                </td>-->
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
<!--    <div class="related">
        <h4><?= __(' Fee Allocations') ?></h4>
        <?php if (!empty($fee->feeallocations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
               
                <th style="padding: 10px;"><?= __('Fee') ?></th>
                <th style="padding: 10px;"><?= __('Department') ?></th>
                <th style="padding: 10px;"><?= __('Startdate') ?></th>
                <th style="padding: 10px;"><?= __('Enddate') ?></th>
                <th style="padding: 10px;"><?= __('Allocated By') ?></th>
                <th style="padding: 10px;" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($fee->feeallocations as $feeallocations): ?>
            <tr>
               
                <td style="padding: 10px;"><?= h($feeallocations->fee) ?></td>
                <td style="padding: 10px;"><?= h($feeallocations->department->name) ?></td>
                <td style="padding: 10px;"><?= h($feeallocations->startdate) ?></td>
                <td style="padding: 10px;"><?= h($feeallocations->enddate) ?></td>
                <td style="padding: 10px;"><?= h($feeallocations->user->username) ?></td>
                <td style="padding: 10px;" class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Feeallocations', 'action' => 'view', $feeallocations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Feeallocations', 'action' => 'edit', $feeallocations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Feeallocations', 'action' => 'delete', $feeallocations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $feeallocations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>-->
</div>
