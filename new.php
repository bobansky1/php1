namespace YourPlugin;

class YourApi {
    public $api_url;

    public function fetchVacancies($postId, $specificVacancyId = 0) {
        $result = array();

        if (!is_object($postId)) {
            return false;
        }

        $page = 0;
        $found = false;
        $params = "status=all&id_user=" . $this->getPluginOption('superjob_user_id') . "&with_new_response=0&order_field=date&order_direction=desc&page={$page}&count=100";

        do {
            $response = $this->sendApiRequest($this->api_url . '/hr/vacancies/?' . $params);
            $decodedResponse = json_decode($response);

            if ($response !== false && is_object($decodedResponse) && isset($decodedResponse->objects)) {
                $result = array_merge($decodedResponse->objects, $result);

                if ($specificVacancyId > 0) {
                    foreach ($decodedResponse->objects as $key => $value) {
                        if ($value->id == $specificVacancyId) {
                            $found = $value;
                            break;
                        }
                    }
                }

                if ($found === false && $decodedResponse->more) {
                    $page++;
                } else {
                    return (is_object($found)) ? $found : $result;
                }
            } else {
                return false;
            }
        } while ($decodedResponse->more);

        return false;
    }

    public function sendApiRequest($url) {
        // Здесь реализуйте отправку API-запроса, например, с помощью cURL
        // return результат API-запроса
    }

    public function getPluginOption($optionName) {
        // Здесь реализуйте получение настроек плагина, например, с использованием функций WordPress
        // return значение настройки
    }
}
