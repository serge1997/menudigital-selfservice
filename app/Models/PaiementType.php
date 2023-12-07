<?php
    namespace App\Models;

    class PaiementType
    {
        private int $credit = 3;
        private int $debito = 1;
        private int $cash = 2;
        private int $pix = 4;
        private int $canceled = 5;
        private int $progress = 6;

        public function getCredit(): int
        {
            return $this->credit;
        }
        public function getDebit(): int
        {
            return $this->debito;
        }
        public function getCanceled(): int
        {
            return $this->canceled;
        }
        public function getPix(): int
        {
            return $this->pix;
        }
        public function getCash():int
        {
            return $this->cash;
        }

        public function getProgress(): int
        {
            return $this->progress;
        }
    }
