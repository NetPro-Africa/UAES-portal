<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Result Entity
 *
 * @property int $id
 * @property int $student_id
 * @property int $faculty_id
 * @property int $department_id
 * @property int $subject_id
 * @property int $semester_id
 * @property int $session_id
 * @property string $score
 * @property string $grade
 * @property string|null $remark
 * @property \Cake\I18n\FrozenTime $uploaddate
 * @property int $user_id
 * @property string $regno
 * @property int|null $creditload
 * @property int $level_id
 *
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\Faculty $faculty
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Subject $subject
 * @property \App\Model\Entity\Semester $semester
 * @property \App\Model\Entity\Session $session
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Level $level
 */
class Result extends Entity
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
        'student_id' => true,
        'faculty_id' => true,
        'department_id' => true,
        'subject_id' => true,
        'semester_id' => true,
        'session_id' => true,
        'score' => true,
        'grade' => true,
        'remark' => true,
        'uploaddate' => true,
        'user_id' => true,
        'regno' => true,
        'creditload' => true,
        'level_id' => true,
        'student' => true,
        'faculty' => true,
        'department' => true,
        'subject' => true,
        'semester' => true,
        'session' => true,
        'user' => true,
        'level' => true,
    ];
}
