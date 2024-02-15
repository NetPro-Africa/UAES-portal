<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admisioncondition[]|\Cake\Collection\CollectionInterface $admisionconditions
 */
?>
<div class="admisionconditions index content">
    <?= $this->Html->link(__('New Admisioncondition'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Admisionconditions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('lastupdate') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admisionconditions as $admisioncondition): ?>
                <tr>
                    <td><?= $this->Number->format($admisioncondition->id) ?></td>
                    <td><?= $admisioncondition->has('user') ? $this->Html->link($admisioncondition->user->id, ['controller' => 'Users', 'action' => 'view', $admisioncondition->user->id]) : '' ?></td>
                    <td><?= h($admisioncondition->lastupdate) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $admisioncondition->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $admisioncondition->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $admisioncondition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $admisioncondition->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
