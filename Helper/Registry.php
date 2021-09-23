<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Helper;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Registry
{
    /**
     * @var array
     */
    private $data;

    /**
     * Register a data
     *
     * @param string $key
     * @param mixed  $data
     */
    public function register($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * Registry data by given key
     *
     * @param string $key
     * @return mixed
     */
    public function registry($key)
    {
        return $this->data[$key] ?? null;
    }
}
