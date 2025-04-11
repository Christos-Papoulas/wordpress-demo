<?php

namespace App\HT\Models;

use App\HT\Models\Post;
class Store extends Post{

    public const POST_TYPE = 'store';
    public const METAKEY_NAME = '_ht_store_pickup';
    public const INPUT_NAME = 'ht_store_pickup';

}
