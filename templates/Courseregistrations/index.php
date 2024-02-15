<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Courseregistration[]|\Cake\Collection\CollectionInterface $courseregistrations
 */
?>
<div class="courseregistrations index content">
    <?= $this->Html->link(__('New Courseregistration'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Courseregistrations') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('student_id') ?></th>
                    <th><?= $this->Paginator->sort('session_id') ?></th>
                    <th><?= $this->Paginator->sort('semester_id') ?></th>
                    <th><?= $this->Paginator->sort('level_id') ?></th>
                    <th><?= $this->Paginator->sort('date_created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courseregistrations as $courseregistration): ?>
                <tr>
                    <td><?= $this->Number->format($courseregistration->id) ?></td>
                    <td><?= $courseregistration->has('student') ? $this->Html->link($courseregistration->student->id, ['controller' => 'Students', 'action' => 'view', $courseregistration->student->id]) : '' ?></td>
                    <td><?= $courseregistration->has('session') ? $this->Html->link($courseregistration->session->name, ['controller' => 'Sessions', 'action' => 'view', $courseregistration->session->id]) : '' ?></td>
                    <td><?= $courseregistration->has('semester') ? $this->Html->link($courseregistration->semester->name, ['controller' => 'Semesters', 'action' => 'view', $courseregistration->semester->id]) : '' ?></td>
                    <td><?= $courseregistration->has('level') ? $this->Html->link($courseregistration->level->name, ['controller' => 'Levels', 'action' => 'view', $courseregistration->level->id]) : '' ?></td>
                    <td><?= h($courseregistration->date_created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $courseregistration->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $courseregistration->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $courseregistration->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courseregistration->id)]) ?>
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
