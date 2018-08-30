Api Platform Bundle
===================

Введение
--------

Бандл расширяет компонет Symfony [http-foundation](https://github.com/symfony/http-foundation) позволя выделить
работу с API в отдельную инкапсулированную зону. 

Предоставляется работа с контентом запроса в формате `JSON` посредством `ParameterBag`.
Архитектура бандла не допускает фатального падения в зоне API и всегда возвращает валидный ответ
с соответствующем кодом ошибки. Полный список кодов ошибок доступен в виде констант в классе
[ApiException](./Exception/ApiException.php).

Для описания спецификации API обязательно использование
[Swagger 2](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/2.0.md)
в одном из форматов:
- [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle).
- [swagger-php](https://github.com/zircote/swagger-php).
- Использование файла конфигурации в формате `json` или `yaml` (`yml`).

Установка
---------

### Шаг 1: Загрузка бандла

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду для загрузки наиболее подходящей
стабильной версии этого бандла:
```bash
    composer require wakeapp/api-platform-bundle
```
*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

### Шаг 2: Подключение бандла

После включите бандл добавив его в список зарегистрированных бандлов в `app/AppKernel.php` файл вашего проекта:

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = [
            // ...

            new Linkin\Bundle\SwaggerResolverBundle\LinkinSwaggerResolverBundle(),
            new Wakeapp\Bundle\ApiPlatformBundle\WakeappApiPlatformBundle(),
        ];

        return $bundles;
    }

    // ...
}
```

Конфигурация
------------

Чтобы начать использовать бандл требуется определить создать и определить `guesser` - объект содержащий правила
определения зоны API вашего проекта.

```php
<?php

declare(strict_types=1);

namespace App\Guesser\ApiAreaGuesser;

use Symfony\Component\HttpFoundation\Request;
use Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiAreaGuesserInterface;

class ApiAreaGuesser implements ApiAreaGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function isApiRequest(Request $request): bool
    {
        return strpos($request->getPathInfo(), '/api') === 0;
    }
}
```

**Примечание:** Если вы не используете `autowire` то вам необходимо зарегистрировать `ApiAreaGuesser` как сервис.

```yaml
# app/config.yml
wakeapp_api_platform:
    api_area_guesser_service:   App\Guesser\ApiAreaGuesser
```

### Полный набор параметров

```yaml
# app/config.yml
wakeapp_api_platform:
    # полное имя класса DTO для стандартизации ответа
    api_result_dto_class:       Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto

    # идентификатор сервиса для определения зоны API
    api_area_guesser_service:   App\Guesser\ApiAreaGuesser

    # идентификатор сервиса для глобального отлавливания ошибок и выдачи специализированных сообщений вместо 500
    error_code_guesser_service: Wakeapp\Bundle\ApiPlatformBundle\Guesser\ApiErrorCodeGuesser

    # флаг для отладки ошибок - если установлен в true - ответ ошибки содержит trace.
    response_debug:             false
```

Использование
-------------

Использование функционала бандла начинается с создание контроллера и первого метода в указанной зоне API.
В качестве примера рассмотрим возвращение простейшего профиля пользователя.

Для начала нам необходимо создать DTO возвращаемых данных:

```php
<?php

declare(strict_types=1);

namespace App\Dto;

use Swagger\Annotations as SWG;
use Wakeapp\Component\DtoResolver\Dto\AbstractDtoResolver;

/**
 * @SWG\Definition(
 *      type="object",
 *      description="Profile info",
 *      required={"email", "firstName", "lastName"},
 * )
 */
class ProfileResultDto extends AbstractDtoResolver
{
    /**
     * @var string
     *
     * @SWG\Property(description="Profile email", example="test@gmail.com")
     */
    protected $email;

    /**
     * @var string
     *
     * @SWG\Property(description="User's first name", example="John")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @SWG\Property(description="User's last name", example="Doe")
     */
    protected $lastName;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
```

Теперь добавим контроллер с соответствующим методом.
**Примечание:** в качестве примера реализации используется подключение
[NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle).

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Wakeapp\Bundle\ApiPlatformBundle\Exception\ApiException;
use Wakeapp\Bundle\ApiPlatformBundle\Factory\ApiDtoFactory;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiRequest;
use Wakeapp\Bundle\ApiPlatformBundle\HttpFoundation\ApiResponse;

/**
 * @Route("/api/profile")
 */
class ProfileController
{
    /**
     * Returns user profile
     *
     * @Route(methods={"GET"})
     *
     * @SWG\Parameter(name="username", in="query", type="string", required=true, description="User login")
     * @SWG\Response(
     *      response=ApiResponse::HTTP_OK,
     *      description="Successful result in 'data' offset",
     *      @Model(type=ProfileResultDto::class)
     * )
     *
     * @param ApiRequest $apiRequest
     * @param ApiDtoFactory $factory
     *
     * @return ApiResponse
     */
    public function getProfile(ApiRequest $apiRequest, ApiDtoFactory $factory): ApiResponse
    {
        $username = $apiRequest->query->get('username');

        if (!$username) {
            throw new ApiException(ApiException::HTTP_BAD_REQUEST_DATA);
        }

        // обработка данных

        $resultDto = $factory->createApiDto(ProfileResultDto::class, [
            'email' => 'test-user@mail.ru',
            'firstName' => 'Test',
            'lastName' => 'User',
        ]);

        return new ApiResponse($resultDto);
    }
}
```

Дополнительно
-------------

### Как описать в формат обертки ответа в аннотациях

Чтобы описать формат обертки ответа `Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto` при помощи аннотаций
можно использовать следующий подход:

**Шаг 1** Создайте собственный ответ сервера:

```php
<?php

declare(strict_types=1);

namespace App\Dto;

use Swagger\Annotations as SWG;
use Wakeapp\Bundle\ApiPlatformBundle\Dto\ApiResultDto as BaseApiResultDto;
use Wakeapp\Component\DtoResolver\Dto\DtoResolverInterface;

/**
 * @SWG\Definition(
 *      type="object",
 *      description="Common API response object template",
 *      required={"code", "message"},
 * )
 */
class ApiResultDto extends BaseApiResultDto
{
    /**
     * @var int
     *
     * @SWG\Property(description="Response api code", example=0, default=0)
     */
    protected $code = 0;

    /**
     * @var string
     *
     * @SWG\Property(description="Localized human readable text", example="Successfully")
     */
    protected $message;

    /**
     * @var DtoResolverInterface|null
     *
     * @SWG\Property(type="object", description="Some specific response data or null")
     */
    protected $data = null;
}

```

**Шаг 2** Добавьте в конфигурацию:

```yaml
# app/config.yml
wakeapp_api_platform:
    api_result_dto_class:       App\Dto\MyApiResultDto
```

**Шаг 3** Добавьте описание к вашему методу:

```php
<?php

// ...

class ProfileController
{
    /**
     * ...
     * 
     * @SWG\Parameter(name="username", in="query", type="string", required=true, description="User login")
     * @SWG\Response(
     *      response=ApiResponse::HTTP_OK,
     *      description="Successful result in 'data' offset",
     *      @Model(type=ProfileResultDto::class)
     * )
     * @SWG\Response(response="default", @Model(type=ApiResultDto::class), description="Response wrapper")
     *
     * ... 
     */
    public function getProfile(ApiRequest $apiRequest, ApiDtoFactory $factory): ApiResponse
    {
        // ...
    }
}
```

Лицензия
--------

![license](https://img.shields.io/badge/License-proprietary-red.svg?style=flat-square)