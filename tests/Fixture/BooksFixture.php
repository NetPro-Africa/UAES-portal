<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BooksFixture
 */
class BooksFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 188, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'author' => ['type' => 'string', 'length' => 188, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'pubdate' => ['type' => 'string', 'length' => 44, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'isavailable' => ['type' => 'string', 'length' => 18, 'null' => true, 'default' => 'Available', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'date_created' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'isbn' => ['type' => 'string', 'length' => 60, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'coverphoto' => ['type' => 'string', 'length' => 188, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'author' => 'Lorem ipsum dolor sit amet',
                'pubdate' => 'Lorem ipsum dolor sit amet',
                'isavailable' => 'Lorem ipsum dolo',
                'date_created' => 1591171068,
                'user_id' => 1,
                'isbn' => 'Lorem ipsum dolor sit amet',
                'coverphoto' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
