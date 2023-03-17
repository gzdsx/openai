<?php
/**
 * ============================================================================
 * Copyright (c) 2015-2023 贵州大师兄信息技术有限公司 All rights reserved.
 * siteַ: https://www.gzdsx.cn
 * ============================================================================
 * @author:     David Song<songdewei@163.com>
 * @version:    v1.0.0
 * ---------------------------------------------
 * Date: 2023/3/17
 * Time: 下午5:52
 */

namespace OpenAi;


use Throwable;

/**
 * Class OpenAiInvalidException
 * @package OpenAi
 */
class OpenAiInvalidException extends \Exception
{
    protected $code = 405;

    public function __construct($message = "")
    {
        parent::__construct($message, $this->code, null);
    }
}
