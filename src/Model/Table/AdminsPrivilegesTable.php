<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminsPrivileges Model
 *
 * @property \App\Model\Table\AdminsTable&\Cake\ORM\Association\BelongsTo $Admins
 * @property \App\Model\Table\PrivilegesTable&\Cake\ORM\Association\BelongsTo $Privileges
 *
 * @method \App\Model\Entity\AdminsPrivilege newEmptyEntity()
 * @method \App\Model\Entity\AdminsPrivilege newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdminsPrivilege get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdminsPrivilege|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminsPrivilege[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AdminsPrivilegesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('admins_privileges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Admins', [
            'foreignKey' => 'admin_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Privileges', [
            'foreignKey' => 'privilege_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['admin_id'], 'Admins'));
        $rules->add($rules->existsIn(['privilege_id'], 'Privileges'));

        return $rules;
    }
}
