<?php

namespace SmoDav\SAGE;

use App\Contract;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

class Cashier
{
    public static function invoice(Contract $contract, $unitPrice, $quantity, $projectId)
    {
        if (! $contract->stock_item_id) {
            return;
        }

        DB::transaction(function () use ($contract, $unitPrice, $quantity, $projectId) {
            $invoiceId = DB::table('InvNum')
                ->insertGetId(self::mapInvoice($contract, $unitPrice * $quantity, $projectId));
            DB::table('_btblInvoiceLines')
                ->insert(self::mapInvoiceLine($invoiceId, $contract, $unitPrice, $quantity, $projectId));
        }, 3);
    }

    private static function mapInvoice(Contract $contract, $totalAmount, $projectId)
    {
        $reference = 'WEB' . \uniqid(Str::random(6));
        $transactionDate = Carbon::now();
        $exclusive = (double) $totalAmount / 1.16;

        return [
            'iINVNUMAgentID' => 1,
            'DocType' => 0,
            'DocVersion' => 1,
            'DocState' => 1,
            'DocFlag' => 0,
            'OrigDocID' => 0,
            'GrvID' => 0,
            'GrvNumber' => '',
            'InvNumber' => '',
            'OrderNum' => $reference,
            'ExtOrderNum' => $reference,
            'AccountID' => (int) $contract->client_id,
            'Description' => $contract->name . ' billed ' . $contract->rate,
            'InvDate' => $transactionDate,
            'OrderDate' => $transactionDate,
            'DueDate' => $transactionDate,
            'DeliveryDate' => $transactionDate,
            'TaxInclusive' => 1,
            'Email_Sent' => 1,
            'InvTotExclDEx' => 0,
            'InvTotTaxDEx' => 0,
            'InvTotInclDEx' => $totalAmount,
            'InvTotExcl' => $exclusive,
            'InvTotTax' => $totalAmount - $exclusive,
            'InvTotIncl' => $totalAmount,
            'OrdDiscAmnt' => 0,
            'InvDisc' => 0,
            'InvDiscAmnt' => 0,
            'OrdDiscAmntEx' => 0,
            'OrdTotExclDEx' => 0,
            'OrdTotTaxDEx' => 0,
            'OrdTotInclDEx' => $totalAmount,
            'OrdTotExcl' => $exclusive,
            'OrdTotTax' => $totalAmount - $exclusive,
            'OrdTotIncl' => $totalAmount,
            'cTaxNumber' => null,
            'cAccountName' => '',
            'InvTotInclExRounding' => $totalAmount,
            'OrdTotInclExRounding' => $totalAmount,
            'ProjectID' => $projectId
        ];
    }

    private static function mapInvoiceLine($invoiceId, $contract, $unitPrice, $quantity, $projectId)
    {
        $stockItem = DB::table('StkItem')->where('StockLink', $contract->stock_item_id)->first();
        $tax = DB::table('TaxRate')->where('idTaxRate', $stockItem->TTI)->first();
        $totalPrice = $unitPrice * $quantity;

        if (! $tax) {
            $tax = new \stdClass();
            $tax->TaxRate = 16;
        }

        $exclusive = ((double) $totalPrice / (100 + $tax->TaxRate)) / 100;
        $unitExclusive = ((double) $unitPrice / (100 + $tax->TaxRate)) / 100;

        return [
            'iWarehouseID' => 0,
            'iInvoiceID' => $invoiceId,
            'cDescription' => $contract->name . ' billed ' . $contract->rate,
            'iUnitsOfMeasureStockingID' => 1,
            'iUnitsOfMeasureCategoryID' => 1,
            'iUnitsOfMeasureID' => 1,
            'iOrigLineID' => 0,
            'iGrvLineID' => 0,
            'fQtyLastProcess' => 0,
            'fQtyProcessed' => 0,
            'fQtyReserved' => 0,
            'fQtyReservedChange' => 0,
            'cLineNotes' => '',
            'fUnitCost' => 0,
            'fLineDiscount' => 0,
            'fTaxRate' => \floatval($tax->TaxRate),
            'fAddCost' => 0,
            'iJobID' => 0,
            'iPriceListNameID' => 1,
            'fQtyLastProcessLineTotIncl' => 0,
            'fQtyLastProcessLineTotExcl' => 0,
            'fQtyLastProcessLineTotInclNoDisc' => 0,
            'fQtyLastProcessLineTotExclNoDisc' => 0,
            'fQtyLastProcessLineTaxAmount' => 0,
            'fQtyLastProcessLineTaxAmountNoDisc' => 0,
            'fQtyProcessedLineTotIncl' => 0,
            'fQtyProcessedLineTotExcl' => 0,
            'fQtyProcessedLineTotInclNoDisc' => 0,
            'fQtyProcessedLineTotExclNoDisc' => 0,
            'fQtyProcessedLineTaxAmount' => 0,
            'fQtyProcessedLineTaxAmountNoDisc' => 0,
            'fUnitPriceExclForeign' => 0,
            'fUnitPriceInclForeign' => 0,
            'fAddCostForeign' => 0,
            'fQuantityLineTotInclForeign' => 0,
            'fQuantityLineTotExclForeign' => 0,
            'fQuantityLineTotInclNoDiscForeign' => 0,
            'fQuantityLineTotExclNoDiscForeign' => 0,
            'fQuantityLineTaxAmountForeign' => 0,
            'fQuantityLineTaxAmountNoDiscForeign' => 0,
            'fQtyChangeLineTotInclForeign' => 0,
            'fQtyChangeLineTotExclForeign' => 0,
            'fQtyChangeLineTotInclNoDiscForeign' => 0,
            'fQtyChangeLineTotExclNoDiscForeign' => 0,
            'fQtyChangeLineTaxAmountForeign' => 0,
            'fQtyChangeLineTaxAmountNoDiscForeign' => 0,
            'fQtyToProcessLineTotInclForeign' => 0,
            'fQtyToProcessLineTotExclForeign' => 0,
            'fQtyToProcessLineTotInclNoDiscForeign' => 0,
            'fQtyToProcessLineTotExclNoDiscForeign' => 0,
            'fQtyToProcessLineTaxAmountForeign' => 0,
            'fQtyToProcessLineTaxAmountNoDiscForeign' => 0,
            'fQtyLastProcessLineTotInclForeign' => 0,
            'fQtyLastProcessLineTotExclForeign' => 0,
            'fQtyLastProcessLineTotInclNoDiscForeign' => 0,
            'fQtyLastProcessLineTotExclNoDiscForeign' => 0,
            'fQtyLastProcessLineTaxAmountForeign' => 0,
            'fQtyLastProcessLineTaxAmountNoDiscForeign' => 0,
            'fQtyProcessedLineTotInclForeign' => 0,
            'fQtyProcessedLineTotExclForeign' => 0,
            'fQtyProcessedLineTotInclNoDiscForeign' => 0,
            'fQtyProcessedLineTotExclNoDiscForeign' => 0,
            'fQtyProcessedLineTaxAmountForeign' => 0,
            'fQtyProcessedLineTaxAmountNoDiscForeign' => 0,
            'iLineRepID' => 0,
            'iLineProjectID' => $projectId,
            'iLedgerAccountID' => 0,
            'iLotID' => 0,
            'cLotNumber' => '',
            'iMFPID' => 0,
            'iLineID' => 1,
            'iDeliveryMethodID' => 0,
            'iDeliveryStatus' => 0,
            'fPromotionPriceExcl' => 0,
            'fPromotionPriceIncl' => 0,
            'cPromotionCode' => '',
            'fQuantityUR' => $quantity,
            'fQtyChangeUR' => $quantity,
            'fQtyToProcessUR' => $quantity,
            'fQtyLastProcessUR' => 0,
            'fQtyProcessedUR' => 0,
            'fQtyReservedUR' => 0,
            'fQtyReservedChangeUR' => 0,
            'iSalesWhseID' => 0,
            '_btblInvoiceLines_iBranchID' => 0,
            'fQuantity' => $quantity,
            'fQtyChange' => $quantity,
            'fQtyToProcess' => $quantity,
            'fUnitPriceExcl' => $unitExclusive,
            'fUnitPriceIncl' => $unitPrice,
            'iModule' => 0,
            'iStockCodeID' => (int) $stockItem->StockLink,
            'iTaxTypeID' => (int) $stockItem->TTI,
            'fQuantityLineTotIncl' => $totalPrice,
            'fQuantityLineTotExcl' => $exclusive,
            'fQuantityLineTotInclNoDisc' => $totalPrice,
            'fQuantityLineTotExclNoDisc' => $exclusive,
            'fQuantityLineTaxAmount' => $totalPrice - $exclusive,
            'fQuantityLineTaxAmountNoDisc' => $totalPrice - $exclusive,
            'fQtyChangeLineTotIncl' => $totalPrice,
            'fQtyChangeLineTotExcl' => $exclusive,
            'fQtyChangeLineTotInclNoDisc' => $totalPrice,
            'fQtyChangeLineTotExclNoDisc' => $exclusive,
            'fQtyChangeLineTaxAmount' => 0,
            'fQtyChangeLineTaxAmountNoDisc' => 0,
            'fQtyToProcessLineTotIncl' => $totalPrice,
            'fQtyToProcessLineTotExcl' => $exclusive,
            'fQtyToProcessLineTotInclNoDisc' => $totalPrice,
            'fQtyToProcessLineTotExclNoDisc' => $exclusive,
            'fQtyToProcessLineTaxAmount' => $totalPrice - $exclusive,
            'fQtyToProcessLineTaxAmountNoDisc' => $totalPrice - $exclusive,
        ];
    }
}
