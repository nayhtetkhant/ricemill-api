<?php

namespace App\Enums;

enum ProductType: string
{
    case RawMaterial = 'RAW_MATERIAL';
    case Finished = 'FINISHED';
    case ByProduct = 'BY_PRODUCT';
}
