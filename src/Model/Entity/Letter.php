<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Letter Entity
 *
 * @property int $id
 * @property int $mode_id
 * @property string $letterbody
 * @property string $title
 * @property \Cake\I18n\FrozenTime $datecreated
 *
 * @property \App\Model\Entity\Mode $mode
 */
class Letter extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'mode_id' => true,
        'letterbody' => true,
        'title' => true,
        'datecreated' => true,
        'mode' => true,
    ];
}
