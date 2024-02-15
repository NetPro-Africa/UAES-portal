<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DepartmentsFeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DepartmentsFeesTable Test Case
 */
class DepartmentsFeesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DepartmentsFeesTable
     */
    protected $DepartmentsFees;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DepartmentsFees',
        'app.Fees',
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
        $config = TableRegistry::getTableLocator()->exists('DepartmentsFees') ? [] : ['className' => DepartmentsFeesTable::class];
        $this->DepartmentsFees = TableRegistry::getTableLocator()->get('DepartmentsFees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DepartmentsFees);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
