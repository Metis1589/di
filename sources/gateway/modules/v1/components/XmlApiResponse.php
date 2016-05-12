<?php
namespace gateway\modules\v1\components;

class XmlApiResponse extends ApiResponse
{

	/**
	 * Renders response in XML format.
	 *
	 * @param array $response Response data array.
	 *
	 * @return void
	 */
	protected function renderFormatted(array $response)
	{
		$xml = new \SimpleXMLElement('<?xml version="1.0"?><response></response>');

		self::_arrayToXml($response, $xml);

		echo $xml->asXML();
	}

	/**
	 * Adds specific Content-Type header to response.
	 *
	 * @return void
	 */
	protected function addHeader()
	{
		header('Content-Type: text/xml');
	}

	/**
	 * Convert array to xml.
	 *
	 * @param array $array           Input array.
	 * @param SimpleXmlElement &$xml SimpleXmlElement instance.
	 *
	 * @return void
	 */
	protected  static function _arrayToXml($array, &$xml) {
		foreach($array as $key => $value) {

			if (is_array($value)) {
                if ($key === 'attributes') {
                    foreach($value as $a => $v) {
                        $xml->addAttribute($a, $v);
                    }
                    continue;
                }
				if(!is_numeric($key)){
					$subnode = $xml->addChild("$key");
					self::_arrayToXml($value, $subnode);
				}
				else {
                    $wrapperName = isset($value['XmlWrapperName']) ? $value['XmlWrapperName'] : 'Item';
					$subnode = $xml->addChild($wrapperName);
					self::_arrayToXml($value, $subnode);
				}
			}
            else if ($value instanceof \yii\db\ActiveRecord) {
                //TODO: create array by attributes and cast it to xml
            }
			else {
				$xml->addChild($key, (string)$value);
			}
		}
	}

} 