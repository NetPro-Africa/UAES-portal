<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CourseassignmentsSubject $courseassignmentsSubject
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Courseassignments Subjects'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="courseassignmentsSubjects form content">
            <?= $this->Form->create($courseassignmentsSubject) ?>
            <fieldset>
                <legend><?= __('Add Courseassignments Subject') ?></legend>
                <?php
                    echo $this->Form->control('courseassignment_id', ['options' => $courseassignments]);
                    echo $this->Form->control('subject_id', ['options' => $subjects]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
