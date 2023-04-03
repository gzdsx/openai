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
 * Time: 下午4:37
 */

namespace OpenAi;


/**
 * OpenAi\ChatRequestConfig
 * ID of the model to use. You can use the List models API to see all of your available models,
 * or see our Model overview for descriptions of them.
 * @property string $model
 *
 * string or arrayOptionalDefaults to <|endoftext|>
 * The prompt(s) to generate completions for, encoded as a string, array of strings, array of tokens, or array of token arrays.
 * Note that <|endoftext|> is the document separator that the model sees during training,
 * so if a prompt is not specified the model will generate as if from the beginning of a new document.
 * @property string $prompt
 *
 * string Optional Defaults to null
 * The suffix that comes after a completion of inserted text.
 * @property string $suffix
 *
 * @property int $max_tokens
 *
 * @property float $temperature;
 *
 * @property int $top_p
 *
 * @property int $n
 *
 * @property boolean $stream
 *
 * @property int $logprobs
 *
 * @property boolean $echo
 *
 * @property string|array $stop
 *
 * @property number $presence_penalty
 *
 * @property number $frequency_penalty;
 *
 * @property int $best_of
 *
 * @property array $logit_bias
 *
 * @property string $user
 *
 * @property string|array $messages
 *
 * @property string $input
 *
 * @property string $instruction
 *
 * @property string $image
 *
 * @property string $size
 *
 * @property string $response_format
 *
 * @property string $purpose
 *
 * @property string $file
 *
 * @property string training_file
 *
 * @property string $validation_file
 *
 * @property int $n_epochs
 *
 * @property int $batch_size
 *
 * @property int|float $learning_rate_multiplier
 *
 * @property float $prompt_loss_weight
 *
 * @property boolean $compute_classification_metrics
 *
 * @property int $classification_n_classes
 *
 * @property string $classification_positive_class
 *
 * @property array $classification_betas
 *
 */
class OpenAiRequestConfig
{
    protected $options = [];

    /**
     * ChatRequestMessage constructor.
     * @param array $opeions
     */
    public function __construct($options = [])
    {
        foreach ($options as $key => $val) {
            if (!is_numeric($key) && !is_null($val) && !empty($val)) {
                $this->options[$key] = $val;
            }
        }
    }


    /**
     * @return string[]
     */
    public function toArray()
    {
        foreach ($this->options as $key => $val) {
            if (is_numeric($key)) {
                unset($this->options[$key]);
            }

            if (is_null($val) || empty($val)) {
                unset($this->options[$key]);
            }
        }

        return $this->options;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->options[$name] = $value;
    }

    public function __get($name)
    {
        return $this->options[$name] ?? null;
    }
}
