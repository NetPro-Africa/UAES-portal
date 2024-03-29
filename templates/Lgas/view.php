<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lga $lga
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Lga'), ['action' => 'edit', $lga->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Lga'), ['action' => 'delete', $lga->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lga->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Lgas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Lga'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="lgas view content">
            <h3><?= h($lga->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($lga->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('State') ?></th>
                    <td><?= $lga->has('state') ? $this->Html->link($lga->state->name, ['controller' => 'States', 'action' => 'view', $lga->state->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($lga->id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Students') ?></h4>
                <?php if (!empty($lga->students)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Fname') ?></th>
                            <th><?= __('Lname') ?></th>
                            <th><?= __('Mname') ?></th>
                            <th><?= __('Dob') ?></th>
                            <th><?= __('Joindate') ?></th>
                            <th><?= __('Department Id') ?></th>
                            <th><?= __('Olevelresulturl') ?></th>
                            <th><?= __('Jamb') ?></th>
                            <th><?= __('Birthcerturl') ?></th>
                            <th><?= __('Othercerts') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('State Id') ?></th>
                            <th><?= __('Country Id') ?></th>
                            <th><?= __('Address') ?></th>
                            <th><?= __('Phone') ?></th>
                            <th><?= __('Fathersname') ?></th>
                            <th><?= __('Mothersname') ?></th>
                            <th><?= __('Fatherphone') ?></th>
                            <th><?= __('Motherphone') ?></th>
                            <th><?= __('Lga Id') ?></th>
                            <th><?= __('Community') ?></th>
                            <th><?= __('Passporturl') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Regno') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Admissiondate') ?></th>
                            <th><?= __('Gender') ?></th>
                            <th><?= __('Application No') ?></th>
                            <th><?= __('Level Id') ?></th>
                            <th><?= __('Faculty Id') ?></th>
                            <th><?= __('Jambregno') ?></th>
                            <th><?= __('Previousschool') ?></th>
                            <th><?= __('Programe Id') ?></th>
                            <th><?= __('Fathersjob') ?></th>
                            <th><?= __('Mothersjob') ?></th>
                            <th><?= __('Studentstatus') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($lga->students as $students) : ?>
                        <tr>
                            <td><?= h($students->id) ?></td>
                            <td><?= h($students->fname) ?></td>
                            <td><?= h($students->lname) ?></td>
                            <td><?= h($students->mname) ?></td>
                            <td><?= h($students->dob) ?></td>
                            <td><?= h($students->joindate) ?></td>
                            <td><?= h($students->department_id) ?></td>
                            <td><?= h($students->olevelresulturl) ?></td>
                            <td><?= h($students->jamb) ?></td>
                            <td><?= h($students->birthcerturl) ?></td>
                            <td><?= h($students->othercerts) ?></td>
                            <td><?= h($students->email) ?></td>
                            <td><?= h($students->state_id) ?></td>
                            <td><?= h($students->country_id) ?></td>
                            <td><?= h($students->address) ?></td>
                            <td><?= h($students->phone) ?></td>
                            <td><?= h($students->fathersname) ?></td>
                            <td><?= h($students->mothersname) ?></td>
                            <td><?= h($students->fatherphone) ?></td>
                            <td><?= h($students->motherphone) ?></td>
                            <td><?= h($students->lga_id) ?></td>
                            <td><?= h($students->community) ?></td>
                            <td><?= h($students->passporturl) ?></td>
                            <td><?= h($students->user_id) ?></td>
                            <td><?= h($students->regno) ?></td>
                            <td><?= h($students->status) ?></td>
                            <td><?= h($students->admissiondate) ?></td>
                            <td><?= h($students->gender) ?></td>
                            <td><?= h($students->application_no) ?></td>
                            <td><?= h($students->level_id) ?></td>
                            <td><?= h($students->faculty_id) ?></td>
                            <td><?= h($students->jambregno) ?></td>
                            <td><?= h($students->previousschool) ?></td>
                            <td><?= h($students->programe_id) ?></td>
                            <td><?= h($students->fathersjob) ?></td>
                            <td><?= h($students->mothersjob) ?></td>
                            <td><?= h($students->studentstatus) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
