<?php
/**
 * ============================================================================
 * Copyright (c) 2015-2023 贵州大师兄信息技术有限公司 All rights reserved.
 * siteַ: https://www.gzdsx.cn
 * ============================================================================
 * @author:     David Song<songdewei@163.com>
 * @version:    v1.0.0
 * ---------------------------------------------
 * Date: 2023/4/4
 * Time: 上午5:15
 */

namespace OpenAi;


/**
 * Class OpenAiModels
 * @package OpenAi
 */
abstract class OpenAiModels
{
    //completions models
    public static $text_davinci_003 = 'text-davinci-003';
    public static $text_davinci_002 = 'text-davinci-002';
    public static $text_curie_001 = ' text-curie-001';
    public static $text_babbage_001 = 'text-babbage-001';
    public static $text_ada_001 = 'text-ada-001';
    public static $davinci = 'davinci';
    public static $curie = 'curie';
    public static $babbage = 'babbage';
    public static $ada = 'ada';
    //chat completions models
    public static $gpt_4 = 'gpt-4';
    public static $gpt_4_0314 = 'gpt-4-0314';
    public static $gpt_4_32k = ' gpt-4-32k';
    public static $gpt_4_32k_0314 = 'gpt-4-32k-0314';
    public static $gpt_35_turbo = 'gpt-3.5-turbo';
    public static $gpt_35_turbo_0301 = 'gpt-3.5-turbo-0301';
    //edits models
    public static $text_davinci_edit_001 = 'text-davinci-edit-001';
    public static $code_davinci_edit_001 = ' code-davinci-edit-001';

    public static $whisper_1 = 'whisper-1';
    //embeddings models
    public static $text_embedding_ada_002 = 'text-embedding-ada-002';
    public static $text_search_ada_doc_001 = 'text-search-ada-doc-001';
    //moderations models
    public static $text_moderation_stable = 'text-moderation-stable';
    public static $text_moderation_latest = 'text-moderation-latest';

}
