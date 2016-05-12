<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   7/15/15
 * @time   11:35 AM
 */

namespace console\controllers;

use common\components\SshService;
use common\enums\OrderExportType;
use common\enums\OrderStatus;
use common\enums\RecordType;
use common\enums\UserType;
use common\models\Order;
use common\models\OrderExport;
use common\models\OrderHistory;
use common\models\Restaurant;
use common\models\RestaurantGroup;
use common\models\User;
use \DateTime;
use gateway\modules\v1\services\EmailService;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;

class OrderExportController extends Controller
{
    /**
     * @var string Day for export
     */
    public $end;

    /**
     * Email enable
     *
     * @var bool
     * if true - email will not send
     */
    public $notSendEmail = false;
    public function options()
    {
        return [
            'end',
            'notSendEmail'
        ];
    }
    public function actionBuild()
    {
        $endOpt = $this->end;
        if(is_null($endOpt)){
            $end = new \DateTime();
            $start = new \DateTime();
        }else{
            $end = new \DateTime($endOpt);
            $start = new \DateTime($endOpt);
        }
        $start = $start->sub(new \DateInterval('PT24H'));
        /** @var OrderExport[] $reports */
        $reports = OrderExport::find()
            ->joinWith([ 'restaurant', 'restaurantChain', 'restaurantGroup', 'client'])
            ->where(['order_export.record_type'=>RecordType::Active])->all();
        foreach ($reports as $report) {
            try{
                $this->handleReport($report,$start,$end);
            }catch (\Exception $e){
                Yii::error($e->__toString());
            }
        }
    }


    /**
     * Build and send report
     *
     * @param OrderExport $report
     * @param DateTime    $startTime
     * @param DateTime    $endTime
     */
    private function handleReport(OrderExport $report,DateTime $startTime, \DateTime $endTime)
    {
        try{
            $filePath = tempnam(sys_get_temp_dir(),$report->id.'_'.dechex(mt_rand(1,65000)));
            $file = new \SplFileObject($filePath,'w');
            if($report->type == OrderExportType::NewOrders){
                $this->getOrderReport($file,$this->getOrders($report,$startTime,$endTime));
            }elseif($report->type == OrderExportType::NewUsers){
                $this->getUsersReport($file,$this->getUsers($report,$startTime,$endTime));
            }else{
                Yii::error('Wrong export type'.PHP_EOL.var_export($report->type),'order-export');
            }

            $fileName = $this->sendViaSsh($file,$report);
            if($report->email && $fileName && !$this->notSendEmail){
                try{
                    EmailService::sendOrderExported($report,$fileName,$startTime,$endTime);
                }catch (\Exception $e){
                    Yii::error($e->__toString());
                }
            }
        }catch (\Exception $e){
            Yii::error($e->__toString());
        }

    }


    /**
     * Get orders
     *
     * @param OrderExport $export
     * @param \DateTime   $startTime
     * @param DateTime    $endTime
     * @return array|\yii\db\ActiveRecord[]
     */
    private function getOrders(OrderExport $export,\DateTime $startTime,\DateTime $endTime)
    {
        $restaurantIds = Restaurant::find()
            ->select(['id']);
        switch(true){
            case $export->client:
                $restaurantIds = $restaurantIds->where(['client_id'=>$export->client_id,'record_type'=>RecordType::Active]);
                break;
            case $export->restaurantChain:
                $groupIds = $export->restaurantChain->getRestaurantGroups()->all();
                $groupIds = !$groupIds ? [] : array_map(function(RestaurantGroup $g){return $g->id;},$groupIds);
                $restaurantIds = $restaurantIds->where( ['restaurant_group_id'=>$groupIds,'record_type'=>RecordType::Active]);
                break;
            case $export->restaurantGroup:
                $groupIds = handleGroup($export->restaurantGroup);
                $restaurantIds = $restaurantIds->where(['restaurant_group_id'=>$groupIds,'record_type'=>RecordType::Active]);
                break;
            case $export->restaurant:
                $restaurantIds = [$export->restaurant_id];
                break;
            default:
                $restaurantIds = [];
                break;
        }
        if(!is_array($restaurantIds)){
            $restaurantIds = $restaurantIds->asArray(true)->column();
        }
        $ordersHistoryIds = OrderHistory::find()
            ->asArray()
            ->select(['order_id'])
            ->where([
                'record_type'=>RecordType::Active,
                'status'=>OrderStatus::OrderConfirmed,
            ])
            ->andWhere(['>=','last_update',$startTime->format('Y-m-d H:i:s')])
            ->andWhere(['<=','last_update',$endTime->format('Y-m-d H:i:s')])
            ->column();
        return Order::find()
            ->where([
                'order.restaurant_id' => $restaurantIds,
                'order.id'=>$ordersHistoryIds
            ])
            ->joinWith(['user'])
            ->all();
    }

    private function getUsers(OrderExport $export,\DateTime $startTime,\DateTime $endTime)
    {
        return User::find()
            ->where( [
                'record_type' => RecordType::Active,
                'client_id'=>$export->client_id,
                'user_type'=>UserType::Member
            ] )
            ->andWhere(['>=', 'last_update', $startTime->format('Y-m-d H:i:s')])
            ->andWhere(['<=', 'last_update', $endTime->format('Y-m-d H:i:s')])
            ->all();
    }

    private function getUsersReport(\SplFileObject $file,array $users)
    {
        //File columns
        $columnTitle = [
            'VoucherCode'=>'N',
            'Email'=>function(User $u){
                return $u->primaryAddress->email;
            },
            'Title'=>function(User $u){
                return $u->title;
            },
            'firstName'=>function(User $u){
                return $u->first_name;
            },
            'secondName'=>function(User $u){
                return $u->last_name;
            },
            'MobilePhoneNumber'=>function(User $u){
                return $u->primaryAddress->phone;
            },
            'GlobalOptOut'=>'N',
            'EmailOptOut'=>'N',
            'MailOptOut'=>'N',
            'PhoneOptOut'=>'N',
            'MobileOptOut'=>'N',
            'SMSOptOut'=>'N',
            'FaxOptOut'=>'N',
            'SourceCode'=>'Delivery',
            'Address1'=>'N',
            'Address2'=>'N',
            'Address3'=>'N',
            'Address4'=>'N',
            'Address5'=>'N',
            'Address6'=>'N',
            'Region'=>'N',
            'Time'=>'N',
            'Kids'=>'N',
            'Kid1GenderCode'=>'N',
            'Kid1DateOfBirth'=>'N',
            'Kid2GenderCode'=>'N',
            'Kid2DateOfBirth'=>'N',
            'Kid3GenderCode'=>'N',
            'Kid3DateOfBirth'=>'N',
            'Kid4GenderCode'=>'N',
            'Kid4DateOfBirth'=>'N',
            'Kid5GenderCode'=>'N',
            'Kid5DateOfBirth'=>'N',
            'Kid6GenderCode'=>'N',
            'Kid6DateOfBirth'=>'N',
            'Kid7GenderCode'=>'N',
            'Kid7DateOfBirth'=>'N',
            'Kid8GenderCode'=>'N',
            'Kid8DateOfBirth'=>'N',
            'FavouritePizza'=>'N',
            'FavouriteRestaurant1'=>'N',
            'FavouriteRestaurant2'=>'N',
            'FavouriteRestaurant3'=>'N',
            'HomePhoneNumber'=>'N',
            'WorkPhoneNumber'=>'N',
            'OrganisationName'=>'N',
            'CustomerID'=>function(User $u){return $u->id;},
            'DateofBirth'=>'N'
        ];
        // Add header
        $file->fputcsv(array_keys($columnTitle));
        if($users){
            /** @var Order[] $order */
            foreach($users as $user){
                $file->fputcsv($this->buildRow($columnTitle,$user));
            }
        }
        return $file;
    }

    /**
     * @param \SplFileObject $file
     * @param array          $orders
     * @return \SplFileObject
     */
    private function getOrderReport(\SplFileObject $file, array $orders)
    {
        //File columns
        $columnTitle = [
            'VoucherCode'=>function(Order $order){
                $result = null;
                if($order->voucher_data){
                    $voucher = unserialize($order->voucher_data);
                    if(is_array($voucher) && $voucher){
                        if(isset($voucher['validation_service']) && !is_null($voucher['validation_service'])){
                            $result = $voucher['code'];
                        }else{
                            $result = $order->voucher_code ?: null;
                        }
                    }else{
                        throw new Exception('Order::voucher_data is not empty and it not instance of Voucher. Order #'.$order->id);
                    }
                }
                return $result;
            },
            'Email'=>function(Order $order){
                return $order->user && $order->user->primaryAddress
                    ? $order->user->primaryAddress->email:$order['delivery_address_data']['email'];
            },
            'Title'=>function(Order $order){
                return $order->user ? $order->user->title : $order['delivery_address_data']['title'];
            },
            'firstName'=>function(Order $order){
                return $order->user ? $order->user->first_name : $order['delivery_address_data']['first_name'];
            },
            'secondName'=>function(Order $order){
                return $order->user ? $order->user->last_name : $order['delivery_address_data']['last_name'];
            },
            'MobilePhoneNumber'=>function(Order $order){
                return $order->user ? $order->user->primaryAddress->phone :$order['delivery_address_data']['phone'];
            },
            'GlobalOptOut'=>'N',
            'EmailOptOut'=>'N',
            'MailOptOut'=>'N',
            'PhoneOptOut'=>'N',
            'MobileOptOut'=>'N',
            'SMSOptOut'=>'N',
            'FaxOptOut'=>'N',
            'SourceCode'=>'Delivery',
            'Address1'=>'N',
            'Address2'=>'N',
            'Address3'=>'N',
            'Address4'=>'N',
            'Address5'=>'N',
            'Address6'=>'N',
            'Region'=>'N',
            'Time'=>'N',
            'Kids'=>'N',
            'Kid1GenderCode'=>'N',
            'Kid1DateOfBirth'=>'N',
            'Kid2GenderCode'=>'N',
            'Kid2DateOfBirth'=>'N',
            'Kid3GenderCode'=>'N',
            'Kid3DateOfBirth'=>'N',
            'Kid4GenderCode'=>'N',
            'Kid4DateOfBirth'=>'N',
            'Kid5GenderCode'=>'N',
            'Kid5DateOfBirth'=>'N',
            'Kid6GenderCode'=>'N',
            'Kid6DateOfBirth'=>'N',
            'Kid7GenderCode'=>'N',
            'Kid7DateOfBirth'=>'N',
            'Kid8GenderCode'=>'N',
            'Kid8DateOfBirth'=>'N',
            'FavouritePizza'=>'N',
            'FavouriteRestaurant1'=>'N',
            'FavouriteRestaurant2'=>'N',
            'FavouriteRestaurant3'=>'N',
            'HomePhoneNumber'=>'N',
            'WorkPhoneNumber'=>'N',
            'OrganisationName'=>'N',
            'CustomerID'=>function(Order $o){return $o->user_id?:'';},
            'DateofBirth'=>'N'
        ];
        // Add header
        $file->fputcsv(array_keys($columnTitle));
        if($orders){
            /** @var Order[] $order */
            foreach($orders as $order){
                $order['delivery_address_data'] = unserialize($order['delivery_address_data']);
                $file->fputcsv($this->buildRow($columnTitle,$order));
            }
        }
        return $file;
    }

    /**
     * Build row
     * @param array $columnTitles
     * @param array $order
     * @return array
     */
    private function buildRow($columnTitles, $order)
    {
        $row = [];
        foreach($columnTitles as $key=>$value)
        {
            if(is_callable($value)){
                $row[] = call_user_func($value,$order);
            }else{
                $row[] = $value;
            }
        }
        return $row;
    }

    /**
     * Upload by ssh
     * @param \SplFileObject    $file
     * @param OrderExport       $export
     * @return bool|string
     * Return false on error and otherwise uploaded filename
     */
    private function sendViaSsh(\SplFileObject $file, OrderExport $export)
    {
        try{
            $sshService = new SshService([
                'host'       => $export->ssh_host,
                'port'       => $export->ssh_port,
                'user'       => $export->ssh_user,
                'password'   => $export->ssh_password,
                'publicKey'  => $export->ssh_public_key,
                'privateKey' => $export->ssh_private_key,
                'passphrase' => $export->ssh_key_passpharse
            ]);
            $fileName = $export->filename.date('Ymd').'.csv';
            $uploadPath = ($export->host_dir ? rtrim($export->host_dir,'/').'/' :'').$fileName;
            return $sshService->uploadFile($file->getRealPath(),$uploadPath) ? $fileName : false;
        }catch (\Exception $e){
            Yii::error($e->__toString());
            return false;
        }
    }

}

function handleGroup(RestaurantGroup $g){
    $result = [$g->id];
    foreach($g->getRestaurantGroups()->all() as $gr){
        $result = array_merge($result,handleGroup($gr));
    }
    return $result;
}
