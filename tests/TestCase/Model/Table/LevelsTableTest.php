<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LevelsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LevelsTable Test Case
 */
class LevelsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LevelsTable
     */
    protected $Levels;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Levels',
        'app.Courseassignments',
        'app.Courseregistrations',
        'app.Results',
        'app.Students',
        'app.Subjects',
        'app.Departments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Levels') ? [] : ['className' => LevelsTable::class];
        $this->Levels = TableRegistry::getTableLocator()->get('Levels', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Levels);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
