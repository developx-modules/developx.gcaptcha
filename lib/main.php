<?
namespace Developx\Gcaptcha;

/**
 * Class Captcha
 */
class Main
{
    const MODULE_NAME = 'developx.gcaptcha';
    const LOG_PATH = '/bitrix/modules/developx.comments/log/captcha_fails_log.log';

    /**
     * @return boolean
     */
    public function checkCaptcha()
    {
        $optionsObj = Options::getInstance();
        $options = $optionsObj->getOptions();
        if (
            $options['CAPTCHA_ACTIVE'] != 'Y' ||
            empty($options['CAPTCHA_KEY']) ||
            empty($options['CAPTCHA_SECRET']) ||
            empty($options['CAPTCHA_SENS'])
        ) {
            return true;
        }

        $token = $_REQUEST['token'];
        if (!empty($token)) {
            $url_google_api = 'https://www.google.com/recaptcha/api/siteverify';
            $query = $url_google_api . '?secret=' . $options['CAPTCHA_SECRET'] . '&response=' . $token . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
            $data = json_decode(file_get_contents($query));

            if ($data->success && $data->score > $options['CAPTCHA_SENS'] && $data->action == $optionsObj->getCaptchaAction()) {
                return true;
            } elseif ($options['CAPTCHA_FAILS_LOG'] == 'Y') {
                $this->logCaptchaFail($data);
            }
        }
        return false;
    }

    /**
     * @return string
     */
    private function getLogPath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::LOG_PATH;
    }

    /**
     * @param array $data
     */
    private function logCaptchaFail($data)
    {
        $logFile = $this->getLogPath();
        file_put_contents($logFile, date('d.m.Y h:i:s') . '----------------------'.PHP_EOL, FILE_APPEND);
        file_put_contents($logFile, print_r($_REQUEST, 1).PHP_EOL, FILE_APPEND);
        file_put_contents($logFile, print_r($data, 1).PHP_EOL, FILE_APPEND);
    }

    /**
     * @return string
     */
    public function getLogData()
    {
        return file_get_contents($this->getLogPath());
    }
}