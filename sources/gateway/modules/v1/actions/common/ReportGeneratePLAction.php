<?php
namespace gateway\modules\v1\actions\common;

use common\components\ReportHelper;
use gateway\modules\v1\forms\common\ReportGeneratePLForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class ReportGeneratePLAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return ReportGeneratePLForm
	 */
	protected function createRequestForm()
	{
		return new ReportGeneratePLForm();
	}

	/**
	 * Generate P&L
	 *
	 * @param ReportGeneratePLForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {

            $client = Yii::$app->globalCache->getClient($requestForm->client_key);

            $r = new ReportHelper();
            $r->generatePL(
                $client['id'],
                $requestForm->date_from,
                $requestForm->date_to,
                $requestForm->restaurant_chain_id,
                $requestForm->restaurant_group_id,
                $requestForm->restaurant_id);

            die();
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}