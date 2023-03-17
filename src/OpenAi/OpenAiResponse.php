<?php
/**
 * ============================================================================
 * Copyright (c) 2015-2023 贵州大师兄信息技术有限公司 All rights reserved.
 * siteַ: https://www.gzdsx.cn
 * ============================================================================
 * @author:     David Song<songdewei@163.com>
 * @version:    v1.0.0
 * ---------------------------------------------
 * Date: 2023/3/13
 * Time: 下午6:07
 */

namespace OpenAi;

/**
 * Class OpenAiResponse
 *
 * @property int $id
 * @property string $object
 * @property int $created
 * @property string $model
 * @property array $choices
 * @property array $usage
 * @property string $text
 * @property string|array $data
 * @property string|array $error
 * @property array $results
 */
class OpenAiResponse
{
    protected $messages = [];

    public function __construct($messages = [])
    {
        if (is_array($messages)) {
            $this->messages = $messages;
        }
    }

    public function toArray()
    {
        return $this->messages;
    }

    public function success()
    {
        return !array_key_exists('error', $this->messages);
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->messages[$name] = $value;
    }

    public function __get($name)
    {
        return $this->messages[$name] ?? null;
    }
}
