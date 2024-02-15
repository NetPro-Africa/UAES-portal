<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sparent $sparent
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sparents'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sparents form content">
            <?= $this->Form->create($sparent) ?>
            <fieldset>
                <legend><?= __('Add Sparent') ?></legend>
                <?php
                    echo $this->Form->control('fathersname');
                    echo $this->Form->control('mothersname');
                    echo $this->Form->control('fatherphone');
                    echo $this->Form->control('motherphone');
                    echo $this->Form->control('fathersjob');
                    echo $this->Form->control('mothersjob');
                    echo $this->Form->control('pemailaddress');
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('address');
                    echo $this->Form->control('status');
                    echo $this->Form->control('students._ids', ['options' => $students]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
