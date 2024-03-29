<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LevelsSubject Entity
 *
 * @property int $id
 * @property int $subject_id
 * @property int $level_id
 *
 * @property \App\Model\Entity\Subject $subject
 * @property \App\Model\Entity\Level $level
 */
class LevelsSubject extends Entity
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
        'subject_id' => true,
        'level_id' => true,
        'subject' => true,
        'level' => true,
    ];
}
