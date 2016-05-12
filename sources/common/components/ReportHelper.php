<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/5/2015
 * Time: 4:06 PM
 */

namespace common\components;

use common\enums\DeliveryType;
use common\enums\OrderStatus;
use common\models\Address;
use common\models\Company;
use common\models\Order;
use common\models\OrderContactHistory;
use common\models\ReportOrder;
use common\models\Voucher;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet;
use PHPExcel_Writer_Excel2007;
use yii\db\ActiveQuery;

class ReportHelper {

    /**
     * current row number
     * @var int
     */
    private $row_number = 1;

    /**
     * @var PHPExcel_Worksheet
     */
    private $sheet;

    /**
     * @var Array orders
     */
    private $orders;

    /**
     * @param Order $order
     * @return string
     */
    private function getDeliveryTypeAbbr($order) {
        $result = $order->delivery_provider == 'Restaurant' ? 'td' : 'wd';

        switch ($order->delivery_type) {
            case (DeliveryType::CollectionAsap):
                $result .= 'ca';
                break;
            case (DeliveryType::CollectionLater):
                $result .= 'cl';
                break;
            case (DeliveryType::DeliveryAsap):
                $result .= 'da';
                break;
            case (DeliveryType::DeliveryLater):
                $result .= 'dl';
                break;
        }

        return $result;
    }

    /**
     * Get vat divider
     * @param $vat
     * @return float
     */
    private function getVatDivider($vat) {
        return (1 + $vat/100) / ($vat/100);
    }

    /**
     * Format currency
     * @param $row_index
     * @param $currency_symbol
     */
    private function formatCurrencyCells($row_index, $currency_symbol) {
        $cols = [
            'Q',
            'R',
            'S',
            'T',
            'U',
            'X',
            'Z',
            'AA',
            'AB',
            'AC',
            'AF',
            'AG',
            'AH',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AZ',
            'BA',
            'BB',
            'BD',
            'BF',
            'BH',
            'BJ',
            'BP',
            'CE',
        ];

        foreach ($cols as $col) {
            $this->sheet->getStyle($col . $row_index)->getNumberFormat()->setFormatCode($currency_symbol . "#,##0.00");
        }
    }

    /**
     * @param Order $order
     * @param $status
     * @param $format
     * @return null|string
     */
    private function getOrderHistoryCreateOn($order, $status, $format) {
        foreach ($order->orderHistories as $history) {
            if ($history->status == $status) {
                return date($format, strtotime($history->create_on));
            }
        }
        return null;
    }

    /**
     * @throws \PHPExcel_Exception
     */
    private function generatePLHeader() {

        $headers = ['Cases', '', '', '', '', '', 'No.', 'Order ID', 'Restaurant', 'Delivery provided by', 'Delivery vs Collection', 'Now vs Later', 'Retail vs Corp', 'Single vs Group', 'Corp ID', 'Company', 'Customer Charged VAT inc.', 'Charged to Comp', 'Paid by Emp', 'Food total (Web) VAT inc.', 'Food total (Rest) VAT inc.', 'Sales fee rate', 'Sales Fee type', 'Sales fee amt', 'Sales Fee VAT type', 'VAT on sales fee', 'Delivery charge (VAT inc)', 'Dine in collected VAT on delivery charge', 'Promotional Amount', 'Promotional Type', 'Promotional Code', 'Refunded by Restaurant to Customer', 'Refunded by Restaurant to Corporate', 'Restaurant Charge', 'Rest Notes', 'SMS charge to rest (Vat Inc)', 'Vat on SMS', 'IVR charge to rest (Vat inc)', 'VAT on IVR', 'Total Owed to Restaurant', 'Comp Fee Rate', 'Comp Fee Amt (VAT exc)', 'VAT on Comp Fee', 'Payment fee (VAT inc)', 'VAT on Payment Fee', 'MarkUp (VAT inc)', 'VAT on MarkUp', 'Manual Costs/Purchases to Dine In', 'Refunded by Dine In to Customers', 'Refunded by Dine In to Corporate', 'Dine In Notes', 'Gross Profit (SF PC DC MU CF SMS IVR) VAT Exc', 'Cost of SMS sent to Rest (VAT exc)', 'Cost of SMS sent to Driver (vat exc)', 'IVR min', 'IVR cost to Dine In (vat exc)', 'CC type', 'CC cost to dine in', 'Gross Profit after SMS, IVR, CC Cost & Driv Tip', 'Time cost to dinein', 'Driver Delivery charge', 'net', 'Date', 'Month', 'Day', 'User Type', 'User ID', 'Total VAT (SF MU DC PC SMS IVR)', 'Payment date', 'Payment time', 'Assign time', 'Accept time', 'Way to rest time', 'At rest time', 'Time waiting for food', 'Food Pick time', 'Food en route time', 'Arrive at cust time', 'Delivery date', 'Delivery time', 'Driver', 'straight line distance', 'Driver\'s Tip'];

        $this->sheet->freezePane('C2');

        $this->sheet->fromArray($headers, NULL, 'A1');

        $last_letter = PHPExcel_Cell::stringFromColumnIndex(count($headers)-1);

        $this->sheet->getStyle("A1:{$last_letter}1")->getFont()->setBold(true);
        $this->sheet->getStyle("A1:{$last_letter}1")->getAlignment()->setWrapText(true);
        $this->sheet->getStyle("A1:{$last_letter}1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->sheet->getRowDimension($this->row_number)->setRowHeight(-1);

        $this->row_number++;
    }

    /**
     * generate by sales charge type
     * @param $sales_charge_type
     * @throws \PHPExcel_Exception
     */
    private function generateBySalesChargeType($sales_charge_type) {
        $this->sheet->fromArray(
            [
                $sales_charge_type == 'WebPrice' ? '% web price' : '% restaurant price'
            ], NULL, 'B' . $this->row_number);
        $this->row_number++;

        $this->generateByMarkup($sales_charge_type, false);
        $this->generateByMarkup($sales_charge_type, true);
    }

    /**
     * Generate by markup
     * @param $sales_charge_type
     * @param $is_markup
     * @throws \PHPExcel_Exception
     */
    private function generateByMarkup($sales_charge_type, $is_markup) {
        $this->sheet->fromArray(
            [
                $is_markup ? 'mark up' : 'no mark up'
            ], NULL, 'C' . $this->row_number);
        $this->row_number++;

        $this->generateByDeliveryCharge($sales_charge_type, $is_markup, true);
        $this->generateByDeliveryCharge($sales_charge_type, $is_markup, false);
    }

    /**
     * generate by delivery charge
     * @param $sales_charge_type
     * @param $is_markup
     * @param $is_delivery_charge
     * @throws \PHPExcel_Exception
     */
    private function generateByDeliveryCharge($sales_charge_type, $is_markup, $is_delivery_charge) {
        $this->sheet->fromArray(
            [
                $is_delivery_charge ? 'Delivery charge' : 'No delivery charge'
            ], NULL, 'D' . $this->row_number);
        $this->row_number++;

        $this->generateOrders($sales_charge_type, $is_markup, $is_delivery_charge);
    }

    /**
     * @param $sales_charge_type
     * @param $is_markup
     * @param $is_delivery_charge
     */
    private function generateOrders($sales_charge_type, $is_markup, $is_delivery_charge) {
        $order_index = 1;

        /** @var Order $order */
        foreach ($this->orders as $order) {
            if ($order->sales_charge_type == $sales_charge_type &&
                (($is_markup && $order->total != $order->restaurant_total) || (!$is_markup && $order->total == $order->restaurant_total)) &&
                (($is_delivery_charge && $order->delivery_charge > 0) || (!$is_delivery_charge && $order->delivery_charge == 0))
            ) {
                $this->generateOrder($order_index, $order);
                $order_index++;
            }
        }
    }

    /**
     * @param $order_index
     * @param Order $order
     */
    public function generateOrder($order_index, $order) {
        $row_index = $this->row_number;

        $voucher = null;

        if ($order->voucher_data) {
            $voucher = new Voucher();
            $voucher->setAttributes(unserialize($order->voucher_data));
        }

        $billing_address = null;

        if ($order->billing_address_data) {
            $billing_address = new Address();
            $billing_address->setAttributes(unserialize($order->billing_address_data));
        }

        $company = null;

        if ($order->corp_company_data) {
            $company = new Company();
            $company->setAttributes(unserialize($order->corp_company_data));
        }

        $order_contacts = $order->orderContactHistories;

        $sms_charge = '0';
        $ivr_charge = '0';
        $ivr_minutes = '0';

        /** @var OrderContactHistory $order_contact */
        foreach ($order_contacts as $order_contact) {
            if ($order_contact->status == 'completed') {
                if ($order_contact->type == 'Sms' && isset($order_contact->price)) {
                    $sms_charge += $order_contact->price;
                }
                else if ($order_contact->type == 'Ivt' && isset($order_contact->price)) {
                    $ivr_charge += $order_contact->price;
                    $ivr_minutes += $order_contact->duration;
                }
            }
        }

        $values = [
            $this->getDeliveryTypeAbbr($order),
            $order_index,
            $order->order_number,
            $order->restaurant_name,
            $order->delivery_provider,
            $order->delivery_type == DeliveryType::CollectionAsap || DeliveryType::CollectionAsap ? 'Collection' : 'Delivery',
            $order->delivery_type == DeliveryType::CollectionAsap || DeliveryType::DeliveryAsap ? 'Now' : 'Later',
            $order->is_corporate ? 'Corp' : 'Retail',
            'Single',
            $company ? $company->id : '',
            $company ? $company->name : '',
            $order->total,
            $order->corp_total_allocated,
            $order->paid,
            $order->subtotal,
            $order->restaurant_total,
            $order->sales_fee_value . '%',
            $order->sales_charge_type == 'WebPrice' ? 'Web' : 'Restaurant',
            "=T{$row_index}*V{$row_index}",
            $order->sales_fee_type == 'VatExclusive' ? 'Exc' : 'Inc',
            "=X{$row_index}/" . $this->getVatDivider($order->vat_value),
            $order->delivery_charge,
            "=AA{$row_index}/" .$this->getVatDivider($order->vat_value),
            $order->discount_items + $order->discount_delivery_charge,
            $voucher ? $voucher->promotion_type : '',
            $voucher ? $voucher->code : '',
            $order->restaurant_refund,
            $order->corporate_restaurant_refund,
            $order->restaurant_charge,
            $order->restaurant_comment,
            $sms_charge,
            "=AJ{$row_index}/" . $this->getVatDivider($order->vat_value),
            $ivr_charge,
            "=AL{$row_index}/" . $this->getVatDivider($order->vat_value),
            "=T{$row_index}-X{$row_index}-AC{$row_index}-AF{$row_index}-AH{$row_index}-AJ{$row_index}-AL{$row_index}-AG{$row_index}",
            $company ? $company->sales_fee : '',
            "=AO{$row_index}*(AA{$row_index}+T{$row_index})",
            "=AP{$row_index}*{$order->vat_value}%",
            "=CI{$row_index}",
            "=AR{$row_index}/" .$this->getVatDivider($order->vat_value),
            "=T6-U6",
            "=AT{$row_index}/" .$this->getVatDivider($order->vat_value),
            $order->client_cost,
            $order->client_refund,
            $order->corporate_client_refund,
            $order->internal_comment,
            "=(X{$row_index}-Z{$row_index})+(AA{$row_index}-AB{$row_index})+(AR{$row_index}-AS{$row_index})+(AT{$row_index}-AU{$row_index})+(AJ{$row_index}-AK{$row_index})+(AL{$row_index}-AM{$row_index})-AV{$row_index}-AW{$row_index}+AH{$row_index}-AX{$row_index}",
            '=AJ-AK',
            '',
            $ivr_minutes,
            '=AL-AM',
            $order->payment_method,
            $order->payment_charge,
            "=AZ{$row_index}-BD{$row_index}-BA{$row_index}-BB{$row_index}-CE{$row_index}",
            "=(CB{$row_index}-BU{$row_index})*CL{$row_index}",
            $order->driver_charge,
            "=BG{$row_index}-BH{$row_index}-BI{$row_index}",
            date('Ymd', strtotime($order->create_on)),
            date('F-d', strtotime($order->create_on)),
            date('l', strtotime($order->create_on)),
            $order->user_id ? 'User' : 'Guest',
            $order->user_id ? $order->user_id : ($billing_address ? $billing_address->email : ''),
            "=Z{$row_index}+AB{$row_index}+AS{$row_index}+AU{$row_index}+AQ{$row_index}+AK{$row_index}+AM{$row_index}",
            $this->getOrderHistoryCreateOn($order, OrderStatus::PaymentReceived, 'Ymd'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::PaymentReceived, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::AssignedToDriver, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::AcceptByDriver, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::WayToPickUp, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::DriverAtRestaurant, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::DriverWaiting, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::DriverPickedUp, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::FoodEnRoute, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::ArrivedAtCustomer, 'H:i'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::Delivered, 'Ymd'),
            $this->getOrderHistoryCreateOn($order, OrderStatus::Delivered, 'H:i'),
            '',
            '',
            '=IF(AND(J6="Client",K6="Delivery"),1,0)'
        ];

        $this->sheet->fromArray($values, NULL, 'F' . $row_index);

        $this->formatCurrencyCells($row_index, $order->currency_symbol);

        $this->row_number++;
    }

    /**
     * Generate P&L report
     * @param $client_id
     * @param $date_from
     * @param $date_to
     * @param $restaurant_id
     * @param $restaurant_group_id
     * @param $restaurant_chain_id
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function generatePL($client_id, $date_from, $date_to, $restaurant_chain_id = null, $restaurant_group_id = null, $restaurant_id = null) {
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);

        $this->sheet = &$excel->getActiveSheet();
        $this->sheet->setTitle('P&L');

        /** @var ActiveQuery $query */
        $query = ReportOrder::find()
            ->joinWith(
                [
                    'restaurant',
                    'orderItems',
                    'orderItems.orderOptions',
                    'orderHistories',
                    'restaurant.restaurantGroup',
                    'orderContactHistories'
                ]
            )
            ->where(['client_id' => $client_id])
            ->andWhere('report_order.create_on >= :date_from AND report_order.create_on < DATE(DATE_ADD(:date_to, INTERVAL +1 DAY))', ['date_from' => $date_from, 'date_to' => $date_to]);

        if ($restaurant_id) {
            $query->andWhere(['restaurant_id' => $restaurant_id]);
        }
        else if ($restaurant_group_id) {
            $query->andWhere(['restaurant.restaurant_group_id' => $restaurant_group_id]);
        }
        else if ($restaurant_chain_id) {
            $query->andWhere(['restaurant_group.restaurant_chain_id' => $restaurant_chain_id]);
        }

        $query->orderBy('order_number');

        $this->orders = $query->all();

        // generate
        $this->generatePLHeader($this->sheet);
        $order_index = 1;

        /** @var Order $order */
        foreach ($this->orders as $order) {
            $this->generateOrder($order_index, $order);
            $order_index++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($excel);

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="pl.xlsx"');
        $objWriter->save('php://output');
        die();
    }
}