<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SubjectsStudent $subjectsStudent
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $subjectsStudent->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $subjectsStudent->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Subjects Students'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="subjectsStudents form content">
            <?= $this->Form->create($subjectsStudent) ?>
            <fieldset>
                <legend><?= __('Edit Subjects Student') ?></legend>
                <?php
                    echo $this->Form->control('subject_id', ['options' => $subjects]);
                    echo $this->Form->control('student_id', ['options' => $students]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
