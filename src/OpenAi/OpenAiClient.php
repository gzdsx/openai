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
 * Time: 下午4:29
 */

namespace OpenAi;

/**
 * Class OpenAiClient
 */
class OpenAiClient
{
    protected static $openai_key;
    protected static $openai_org_id;
    protected $api = 'https://api.openai.com/v1';

    private $key = '';
    private $org_id = null;
    private $timeout = 0;
    private $proxy = "";
    private $curlInfo = [];

    public function __construct($key = null, $org_id = null)
    {
        if ($key) {
            $this->key = $key;
        } else {
            $this->key = static::$openai_key;
        }

        if ($org_id) {
            $this->org_id = $org_id;
        } else {
            $this->org_id = static::$openai_org_id;
        }
    }

    /**
     * @param $openai_key
     * @param null $openai_org_id
     */
    public static function init($openai_key, $openai_org_id = null)
    {
        static::$openai_key = $openai_key;
        static::$openai_org_id = $openai_org_id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getCurlInfo()
    {
        return $this->curlInfo;
    }

    /**
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function listModels()
    {
        $url = $this->api . '/models';
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param $model
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function retrieveModel($model)
    {
        $url = $this->api . '/models/' . $model;
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @param null $streamFunc
     * @return OpenAiResponse
     * @throws \Exception
     */
    public function completions(OpenAiRequestConfig $requestConfig, $streamFunc = null)
    {
        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        if ($requestConfig->stream && !$streamFunc) {
            throw new OpenAiInvalidException('Please provide a stream function.');
        }

        $url = $this->api . '/completions';
        return $this->sendRequest($url, 'POST', $options, $streamFunc);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @param null $streamFunc
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function chat(OpenAiRequestConfig $requestConfig, $streamFunc = null)
    {
        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        if (!array_key_exists('messages', $options)) {
            throw new OpenAiInvalidException('missing param messages!');
        }

        if (!is_array($options['messages'])) {
            throw new OpenAiInvalidException('param messages must be an array!');
        }

        if ($requestConfig->stream && !$streamFunc) {
            throw new OpenAiInvalidException('Please provide a stream function.');
        }

        $url = $this->api . '/chat/completions';
        return $this->sendRequest($url, 'POST', $options, $streamFunc);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws \Exception
     */
    public function edit(OpenAiRequestConfig $requestConfig)
    {
        if (!$requestConfig->instruction) {
            throw new OpenAiInvalidException('missing param instruction!');
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        $url = $this->api . '/edits';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws \Exception
     */
    public function imageGenerate(OpenAiRequestConfig $requestConfig)
    {
        if (!$requestConfig->prompt) {
            throw new OpenAiInvalidException('missing param prompt!');
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        $url = $this->api . '/images/generations';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function imageEdit(OpenAiRequestConfig $requestConfig)
    {
        if (!$requestConfig->image) {
            throw new OpenAiInvalidException('missing param image!');
        }

        if (!$requestConfig->prompt) {
            throw new OpenAiInvalidException('missing param prompt!');
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        $url = $this->api . '/images/edits';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function imageVariation(OpenAiRequestConfig $requestConfig)
    {
        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        if (!$requestConfig->image) {
            throw new OpenAiInvalidException('missing param image!');
        }

        $url = $this->api . '/images/variations';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function embeddings(OpenAiRequestConfig $requestConfig)
    {
        $options = $requestConfig->toArray();
        $this->validateOptions($options);

        if (!$requestConfig->input) {
            throw new OpenAiInvalidException('missing param input!');
        }

        $url = $this->api . '/embeddings';
        return $this->sendRequest($url, 'POST', $options);
    }


    /**
     * ID of the model to use. Only whisper-1 is currently available.
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function audioTranscription(OpenAiRequestConfig $requestConfig)
    {
        if ($requestConfig->model) {
            $requestConfig->model = 'whisper-1';
        }

        if (!$requestConfig->file) {
            throw new OpenAiInvalidException('missing param file!');
        }

        $options = $requestConfig->toArray();
        $url = $this->api . '/audio/transcriptions';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * ID of the model to use. Only whisper-1 is currently available.
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function audioTranslation(OpenAiRequestConfig $requestConfig)
    {
        if ($requestConfig->model) {
            $requestConfig->model = 'whisper-1';
        }

        if (!$requestConfig->file) {
            throw new OpenAiInvalidException('missing param file!');
        }

        $options = $requestConfig->toArray();
        $url = $this->api . '/audio/translations';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * Returns a list of files that belong to the user's organization.
     * @return OpenAiResponse
     * @throws \Exception
     */
    public function listFiles()
    {
        $url = $this->api . '/files';
        return $this->sendRequest($url, 'GET');
    }


    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function uploadFile(OpenAiRequestConfig $requestConfig)
    {

        if (!$requestConfig->file) {
            throw new OpenAiInvalidException('missing param file!');
        }

        if (!$requestConfig->purpose) {
            $requestConfig->purpose = 'fine-tune';
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);
        $url = $this->api . '/files';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param $file_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function deleteFile($file_id)
    {
        $url = $this->api . '/files/' . $file_id;
        return $this->sendRequest($url, 'DELETE');
    }

    /**
     * @param $file_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function retrieveFile($file_id)
    {
        $url = $this->api . '/files/' . $file_id;
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param $file_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function retrieveFileContent($file_id)
    {
        $url = $this->api . '/files/' . $file_id . '/content';
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function listFineTunes()
    {
        $url = $this->api . '/fine-tunes';
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function fineTune(OpenAiRequestConfig $requestConfig)
    {
        if (!$requestConfig->training_file) {
            throw new OpenAiInvalidException('missing param training_file!');
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);
        $url = $this->api . '/fine-tunes';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param $fine_tune_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function retrieveFineTune($fine_tune_id)
    {
        $url = $this->api . '/fine-tunes/' . $fine_tune_id;
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param $fine_tune_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function cancelFineTune($fine_tune_id)
    {
        $url = $this->api . '/fine-tunes/' . $fine_tune_id . '/cancel';
        return $this->sendRequest($url, 'POST');
    }

    /**
     * @param $fine_tune_id
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function listFineTuneEvents($fine_tune_id)
    {
        $url = $this->api . '/models/' . $fine_tune_id . '/events';
        return $this->sendRequest($url, 'GET');
    }

    /**
     * @param OpenAiRequestConfig $requestConfig
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    public function moderation(OpenAiRequestConfig $requestConfig)
    {
        if ($requestConfig->model) {
            $requestConfig->model = 'text-moderation-latest';
        }

        if (!$requestConfig->input) {
            throw  new OpenAiInvalidException('missing param input');
        }

        $options = $requestConfig->toArray();
        $this->validateOptions($options);
        $url = $this->api . '/moderations';
        return $this->sendRequest($url, 'POST', $options);
    }

    /**
     * @param $model
     * @return OpenAiResponse
     * @throws \Exception
     */
    public function deleteFineTuneModel($model)
    {
        $url = $this->api . '/fine-tunes/' . $model;
        $result = $this->sendRequest($url, 'DELETE');
        return new OpenAiResponse($result);
    }

    /**
     * @param $options
     * @throws \Exception
     */
    protected function validateOptions($options)
    {
        if (!isset($options['model']) || is_null($options['model'])) {
            throw new OpenAiInvalidException('missing param model!');
        }
    }

    /**
     * @param $url
     * @param string $method
     * @param array $options
     * @param null $steamFunc
     * @return OpenAiResponse
     * @throws OpenAiInvalidException
     */
    protected function sendRequest($url, $method = 'POST', $options = [], $steamFunc = null)
    {
        $headers = [];
        if (!$this->key) {
            throw new OpenAiInvalidException('missing openai key');
        } else {
            $headers[] = 'Authorization: Bearer ' . $this->key;
        }

        if (array_key_exists('file', $options) || array_key_exists('image', $options)) {
            $headers[] = 'Content-Type: multipart/form-data';
        } else {
            $headers[] = 'Content-Type: application/json';
        }


        if ($this->org_id) {
            $headers[] = 'OpenAI-Organization: ' . $this->org_id;
        }

        $curl_info = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if (!empty($options)) {
            $curl_info[CURLOPT_POST] = true;
            $curl_info[CURLOPT_POSTFIELDS] = json_encode($options);
        }

        if ($this->proxy) {
            $curl_info[CURLOPT_PROXY] = $this->proxy;
        }

        if (array_key_exists('stream', $options) && $options['stream']) {
            $curl_info[CURLOPT_WRITEFUNCTION] = $steamFunc;
        }

        $curl = curl_init();
        curl_setopt_array($curl, $curl_info);
        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $this->curlInfo = $info;
        curl_close($curl);

        return new OpenAiResponse(json_decode($response, true));
    }

    /**
     * @param string $proxy
     */
    public function setProxy($proxy)
    {
        if ($proxy && strpos($proxy, '://') === false) {
            $proxy = 'https://'.$proxy;
        }
        $this->proxy = $proxy;
    }
}
