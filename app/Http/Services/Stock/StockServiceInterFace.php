<?php
    namespace App\Http\Services\Stock;


    interface StockServiceInterFace
    {
        public function ControleItemLowStockRupured(array $item_ids);

        public function CheckRuptureLowStockState(string $productID);
        public function StockOutProduct(array $item_ids, array $product_quantitys);
        public static function SetItemSaldoZeroException(string $tableNumber);
        public static function checkSetItemSaldoZeroAddItemToOrder(int $itemID);
    }
