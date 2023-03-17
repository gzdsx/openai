# PHP SDK for OpenAi

### Installation

```
composer require gzdsx/openai
```

### 默认配置：

```PHP
OpenAiClient.init('YOUR_OPEN_AI_KEY','YOUR_OPEN_AI_ORG_ID');
```
如可以放在Laravel的AppServiceProvider的boot里

### 接口调用

```php
$config = new OpenAiRequestConfig();
$config->model = 'text-davinci-003';
$config->prompt = 'Hello Ai';
$config->temperature = 0;
$config->top_p = 1;
$config->n = 1;
$config->max_tokens = 1024;
$config->stream = false;
$config->stop = ["Human:", " AI:"];
$config->echo = true;

$client = new OpenAiClient();
$response = $client->completions($config);
if ($response->success()) {
    //成功调用,返回结果
    return $response->toArray();
}else{
    //返回错误信息
    return $response->error;
}
```

其他接口调用查看类OpenAiClient
