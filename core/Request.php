<?php
namespace application\core;

use PDO;

class Request
{
    private array $rules = [];
    private array $messages = [];
    private array $attributes = [];
    private array $errors = [];
    private array $routeParameters = [];
    private array $dataRequest = [];
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function path()
    {
        $path = explode("/", $_SERVER['REQUEST_URI']);
        $path = array_filter($path);
        array_shift($path);
        $path = implode("/", $path);
        $path = "/$path";
        $position = strpos($path, '?');
        return ($position !== false) ? substr($path, 0, $position) : $path;
    }

    public function method()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function isGetMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
    }

    public function isPostMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
    }

    public function getDataRequest()
    {
        if (empty($this->dataRequest))
        {
            $this->dataRequest = [];

            if ($this->isGetMethod())
            {
                foreach ($_GET as $key => $value)
                {
                    if (is_array($value))
                    {
                        $this->dataRequest[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }
                    else
                    {
                        $this->dataRequest[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }

            if ($this->isPostMethod())
            {
                foreach ($_POST as $key => $value)
                {
                    if (is_array($value))
                    {
                        $this->dataRequest[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }
                    else
                    {
                        $this->dataRequest[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }

            if (!empty($_FILES))
            {
                foreach ($_FILES as $fieldName => $fieldImageUploader)
                {
                    foreach ($fieldImageUploader['tmp_name'] as $key => $images)
                    {
                        $imageName = $fieldImageUploader['name'][$key];
                        $imageTemporaryName = $fieldImageUploader['tmp_name'][$key];
                        $destination = transformDirectoryPath(ROOT_DIRECTORY."/uploads/images/".$imageName);

                        if (move_uploaded_file($imageTemporaryName, $destination))
                        {
                            $this->dataRequest[$fieldName][] = $imageName;
                        }
                    }
                }
            }
        }

        return $this->dataRequest;
    }

    public function validate()
    {
        $dataRequest = $this->getDataRequest();

        foreach ($this->getRules() as $attribute => $attributeRules)
        {
            $attributeRulesArray = explode('|', $attributeRules);
            $attributeValue = $dataRequest[$attribute];

            foreach ($attributeRulesArray as $attributeRule)
            {
                $attributeRuleArray = explode(':', $attributeRule);
                $attributeRuleType = $attributeRuleArray[0];
                $attributeRuleValue = (count($attributeRuleArray) >= 2) ? $attributeRuleArray[1] : null;

                if ($attributeRuleType === REQUIRED && empty($attributeValue))
                {
                    $this->appendErrorMessage($attribute, REQUIRED);
                }

                if ($attributeRuleType === INVALIDE_EMAIL && !filter_var($attributeValue, FILTER_VALIDATE_EMAIL))
                {
                    $this->appendErrorMessage($attribute, INVALIDE_EMAIL);
                }

                if ($attributeRuleType === MIN_LENGTH && strlen(trim($attributeValue)) < $attributeRuleValue)
                {
                    $this->appendErrorMessage($attribute, MIN_LENGTH, $attributeRuleValue);
                }

                if ($attributeRuleType === MAX_LENGTH && strlen(trim($attributeValue)) > $attributeRuleValue)
                {
                    $this->appendErrorMessage($attribute, MAX_LENGTH, $attributeRuleValue);
                }

                if ($attributeRuleType === MATCH_PASSWORD && $attributeValue !== $dataRequest[$attributeRuleValue])
                {
                    $this->appendErrorMessage($attribute, MATCH_PASSWORD, $attributeRuleValue);
                }

                if ($attributeRuleType === POSITIVE && (!is_int(intval($attributeValue)) || $attributeValue <= 0))
                {
                    $this->appendErrorMessage($attribute, POSITIVE);
                }

                if ($attributeRuleType === UNIQUE)
                {
                    $tableName = $attributeRuleValue;
                    $query = "SELECT * FROM $tableName WHERE $attribute = :$attribute";
                    $statement = $this->connection->prepare($query);
                    $statement->bindValue(":$attribute", $dataRequest[$attribute]);
                    $statement->execute();

                    if ($statement->rowCount() > 0)
                    {
                        $this->appendErrorMessage($attribute, UNIQUE);
                    }
                }

            }
        }

        return empty($this->errors);
    }

    public function appendErrorMessage(string $attribute, string $ruleType, string $ruleValue = '')
    {
        $messages = $this->getMessages();

        if (!empty($messages[$ruleType]))
            $message = str_replace(":attribute", $attribute, $messages[$ruleType]);
        elseif(!empty($messages["$attribute.$ruleType"]))
            $message = $messages["$attribute.$ruleType"];
        else
            $message = $this->defaultMessages($attribute, $ruleType);

        if (preg_match('~(:.+?)\s+~i', $message, $matches))
        {
            $toReplace = trim($matches[0]);
            $message = str_replace($toReplace, $ruleValue, $message);
        }

        if (!empty($this->attributes[$attribute]))
        {
            $message = str_replace($attribute, $this->attributes[$attribute], $message);
        }

        $this->errors[$attribute][] = $message;
    }

    public function defaultMessages(string $attribute, string $ruleType)
    {
        return [
            REQUIRED => ucfirst($attribute)." không được để trống",
            INVALIDE_EMAIL => ucfirst($attribute)." không hợp lệ",
            MIN_LENGTH => ucfirst($attribute)." phải có tối thiểu :MIN_LENGTH ký tự",
            MAX_LENGTH => ucfirst($attribute)." phải có tối đa :MAX_LENGTH ký tự",
            MATCH_PASSWORD => ucfirst($attribute)." phải khớp với :MATCH_PASSWORD ",
            UNIQUE => ucfirst($attribute)." đã tồn tại",
            POSITIVE => ucfirst($attribute)." Không phải số dương",
        ][$ruleType];
    }

    public function getFirstError(string $attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function getFirstErrors()
    {
        $firstErrors = [];
        $attributes = array_keys($this->getRules());

        foreach ($attributes as $key => $attribute)
        {
            $firstErrors[$attribute] = $this->errors[$attribute][0] ?? false;
        }

        return $firstErrors;
    }

	/**
	 *
	 * @return array
	 */
	function getRules(): array {
		return $this->rules;
	}

	/**
	 *
	 * @param array $rules
	 * @return Request
	 */
	function setRules(array $rules): self {
		$this->rules = $rules;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	function getMessages(): array {
		return $this->messages;
	}

	/**
	 *
	 * @param array $messages
	 * @return Request
	 */
	function setMessages(array $messages): self {
		$this->messages = $messages;
		return $this;
	}
	/**
	 *
	 * @return array
	 */
	function getAttributes(): array {
		return $this->attributes;
	}

	/**
	 *
	 * @param array $attributes
	 * @return Request
	 */
	function setAttributes(array $attributes): self {
		$this->attributes = $attributes;
		return $this;
	}
	/**
	 *
	 * @return array
	 */
	function getRouteParameters(): array {
		return $this->routeParameters;
	}

	/**
	 *
	 * @param array $routeParameters
	 * @return Request
	 */
	function setRouteParameters(array $routeParameters): self {
		$this->routeParameters = $routeParameters;
		return $this;
	}
	/**
	 *
	 * @return array
	 */
	function getErrors(): array {
		return $this->errors;
	}

	/**
	 *
	 * @param array $errors
	 * @return Request
	 */
	function setErrors(array $errors): self {
		$this->errors = $errors;
		return $this;
	}
	/**
	 *
	 * @param array $dataRequest
	 * @return Request
	 */
	function setDataRequest(array $dataRequest): self {
		$this->dataRequest = $dataRequest;
		return $this;
	}
}
