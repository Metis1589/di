<?php
namespace gateway\modules\v1\forms;

use yii\base\Model;

class FormModel extends Model
{

	/**
	 * If model was populated by user input.
	 *
	 * @var boolean
	 */
	private $_isPopulated = false;

	/**
	 * Populates model with values from array. Returns true if incoming array contains values for the model.
	 *
	 * @param array   $inputArray   Input array (GET or POST).
	 * @param boolean $useClassName If to use class name as an array name.
	 *
	 * @return boolean.
	 */
	public function populate(array $inputArray, $useClassName = false)
    {
//        if (count($inputArray) == 0) {
//            return true;
//        }

        $realInputArray = array();
        if ($useClassName) {
            $className = get_called_class();
            if (isset($inputArray[$className])) {
                $realInputArray = $inputArray[$className];
            }
        } else {
            $safeAttributes = $this->attributes();
            foreach ($safeAttributes as $attribute) {
                $realInputArray[$attribute] = isset($inputArray[$attribute]) ? $inputArray[$attribute] : null;
            }
        }

        if ($realInputArray) {
            foreach ($realInputArray as $key => $value) {
                $this->$key = $value;
            }
            $this->_isPopulated = true;
            return true;
        }

        return false;
    }

	/**
	 * Returns first appeared error.
	 *
	 * @return string|null
	 */
	public function getFirstAttributeError()
	{
		if ($this->hasErrors()) {
			$errors = $this->getErrors();
			$firstAttributeErrors = current($errors);
			return current($firstAttributeErrors);
		}

		return null;
	}

} 