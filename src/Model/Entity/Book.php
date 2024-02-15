<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Book Entity
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $pubdate
 * @property string|null $isavailable
 * @property \Cake\I18n\FrozenTime $date_created
 * @property int $user_id
 * @property string $isbn
 * @property string|null $coverphoto
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Borrowedbook[] $borrowedbooks
 */
class Book extends Entity
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
        'title' => true,
        'author' => true,
        'pubdate' => true,
        'isavailable' => true,
        'date_created' => true,
        'user_id' => true,
        'isbn' => true,
        'coverphoto' => true,
        'user' => true,
        'borrowedbooks' => true,
    ];
}
