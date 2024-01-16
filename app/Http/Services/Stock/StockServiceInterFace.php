<?php
    namespace App\Http\Services\Stock;


    interface StockServiceInterFace
    {
        public function ControleItemLowStockRuptured(array $item_ids): void;

        public function CheckRuptureLowStockState(string $productID);
        public function StockOutProduct(array $item_ids, array $product_quantitys);
        public static function SetItemSaldoZeroException(string $tableNumber = null, string $menuitem = null);
        public static function checkSetItemSaldoZeroAddItemToOrder(int $itemID);
    }
