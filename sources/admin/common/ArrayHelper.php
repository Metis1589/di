<?php

namespace admin\common;

use Yii;

class ArrayHelper {
    
    public static function translateList($list) {
        
        foreach ($list as $value) {
            $value->name_key = Yii::$app->globalCache->getLabel($value->name_key);
        }
        
        return $list;
    }


    /**
     * Converting a Yii model with all relations to a an array.
     * @param mixed $models A single model or an array of models for converting to array.
     * @param array $filterAttributes should be like array('table name'=>'column names','user'=>'id,firstname,lastname'
     * 'comment'=>'*') to filter attributes. Also can use alias for column names by using AS with the column name just
     * like in SQL.
     * @param array $ignoreRelations an array contains the model names in relations that will not be converted to array
     * @return array array of converted model with all related relations.
     */
    public static function convertArToArray($models, array $filterAttributes = null,array $ignoreRelations=array())
    {
        if((!is_array($models))&&(is_null($models))) return null;

        if (is_array($models))
            $arrayMode = TRUE;
        else {
            $models = array($models);
            $arrayMode = FALSE;
        }

        $result = array();
        foreach ($models as $model) {
            $attributes = $model->getAttributes();
            if ($model->hasErrors()) {
                $attributes['errors'] = [];
                foreach($model->errors as $error) {
                    array_push($attributes['errors'], $error);
                }
            }
            if (isset($filterAttributes) && is_array($filterAttributes)) {
                foreach ($filterAttributes as $key => $value) {

                    if (strtolower($key) == strtolower($model->tableName())) {
                        $arrColumn = explode(",", $value);

                        if (strpos($value, '*') === FALSE) {
                            $attributes = array();
                        }

                        foreach ($arrColumn as $column)
                        {
                            $columnNameAlias = array_map('trim', preg_split("/[aA][sS]/", $column));

                            $columnName = '';
                            $columnAlias = '';

                            if(count($columnNameAlias) === 2)
                            {
                                $columnName = $columnNameAlias[0];
                                $columnAlias = $columnNameAlias[1];
                            }

                            else
                            {
                                $columnName = $columnNameAlias[0];
                            }

                            if(($columnName != '') && ($column != '*'))
                            {
                                if($columnAlias !== '')
                                {
                                    $attributes[$columnAlias] = $model->$columnName;
                                }

                                else
                                {
                                    $attributes[$columnName] = $model->$columnName;
                                }
                            }
                        }
                    }
                }
            }

            $relations = array();
            $key_ignores = array();

            if($modelClass = get_class($model)){
                if(array_key_exists($modelClass,$ignoreRelations)){
                    $key_ignores = explode(',',$ignoreRelations[$modelClass]);
                }
            }

            foreach ($model->relatedRecords as $key => $related) {

              //  if ($model->hasRelated($key)) {
                    if(!in_array($key,$key_ignores))
                        $relations[$key] = self::convertArToArray($model->$key, $filterAttributes,$ignoreRelations);
             //   }
            }
            $all = array_merge($attributes, $relations);

            if ($arrayMode)
                array_push($result, $all);
            else
                $result = $all;
        }
        return $result;
    }

    public function searchRowInArArray($array, $conditions)
    {
        foreach($array as $row) {
            $isMatched = true;
            foreach($conditions as $prop => $value) {
                $isMatched = $isMatched && $row->$prop == $value;
            }
            if ($isMatched) {
                return $row;
            }
        }
        return false;

    }
}
