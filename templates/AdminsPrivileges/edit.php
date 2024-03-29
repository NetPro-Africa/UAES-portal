<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AdminsPrivilege $adminsPrivilege
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $adminsPrivilege->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $adminsPrivilege->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Admins Privileges'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="adminsPrivileges form content">
            <?= $this->Form->create($adminsPrivilege) ?>
            <fieldset>
                <legend><?= __('Edit Admins Privilege') ?></legend>
                <?php
                    echo $this->Form->control('admin_id', ['options' => $admins]);
                    echo $this->Form->control('privilege_id', ['options' => $privileges]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
