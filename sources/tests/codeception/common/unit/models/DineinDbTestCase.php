<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 2/25/2015
 * Time: 9:32 PM
 */

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\DbTestCase;
use Codeception\Specify;


class DineinDbTestCase extends DbTestCase {

    use Specify;

    /**
     * test create update delete
     * @param $className
     * @param $properties
     * @param array $propertiesUpdate
     */
    protected function testCreateDelete($className, $properties, $propertiesUpdate = [])
    {
        /** @var \yii\db\ActiveRecord $model */
        $model = new $className();

        foreach ($properties as $key => $value) {
            $model->$key = $value;
        }

        $this->specify('record should be created', function () use ($model) {
            $result = $model->save();
            if (!$result) {
                $errors = $model->errors;
                codecept_debug($errors);
            }
            expect('record should be saved', $result)->true();
            expect('no errors should exist', $model->errors)->isEmpty();
        });

        $this->specify('record should retrieved', function () use ($className, $properties, $model) {
            $result = $className::findOne(['id' => $model->id, 'record_type' => 'Active']);
            expect('record should be loaded', $result)->notNull();
            foreach ($properties as $key => $value) {
                expect('record should have correct ' . $key, $result->$key)->equals($model->$key);
            }
        });

        foreach ($propertiesUpdate as $key => $value) {
            $model->$key = $value;
        }

        $this->specify('record should be updated', function () use ($model) {
            $result = $model->save();
            expect('record should be saved', $result)->true();
            expect('no errors should exist', $model->errors)->isEmpty();
        });

        $this->specify('updated record should be retrieved', function () use ($className, $properties, $propertiesUpdate, $model) {
            $result = $className::findOne(['id' => $model->id, 'record_type' => 'Active']);

            expect('record should be loaded', $result)->notNull();

            foreach ($properties as $key => $value) {
                expect('record should have correct ' . $key, $result->$key)->equals($model->$key);
            }
            foreach ($propertiesUpdate as $key => $value) {
                expect('record should have correct ' . $key, $result->$key)->equals($model->$key);
            }
        });

        $this->specify('record should be deleted', function () use ($model) {
            $model->record_type = 'Deleted';
            $result = $model->save();
            if($model->hasErrors()){
                codecept_debug($model->getErrors());
            }
            expect('record should be deleted', $result)->true();
            expect('no errors should exist', $model->errors)->isEmpty();
        });

        $this->specify('record should not be retrieved', function () use ($className, $model) {
            $result = $className::findOne(['id' => $model->id, 'record_type' => 'Active']);
            expect('record should be loaded', $result)->null();
        });
    }
} 