<?
namespace Developx\Gcaptcha;

/**
 * Class Main
 */
class Main
{
    const MODULE_NAME = 'developx.gcaptcha';
    const LOG_PATH = '/bitrix/modules/developx.gcaptcha/log/captcha_fails_log.log';
    const GOOGLE_API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @return boolean
     */
    public function checkCaptcha()
    {
        $optionsObj = Options::getInstance();
        $options = $optionsObj->getOptions();
        global $USER;
        if (
            $USER->IsAuthorized() ||
            (!empty($_SESSION['CAPTCHA_CHECKED']) && $_SESSION['CAPTCHA_CHECKED'] == 'Y') ||
            $options['CAPTCHA_ACTIVE'] != 'Y' ||
            empty($options['CAPTCHA_KEY']) ||
            empty($options['CAPTCHA_SECRET']) ||
            empty($options['CAPTCHA_SENS'])
        ) {
            return true;
        }

        $token = $_COOKIE['gToken'];
        if (!empty($token)) {
            $response = $this->getCaptchaResponse($options['CAPTCHA_SECRET'], $token);
            if (
                $response['success'] &&
                $response['score'] >= $options['CAPTCHA_SENS'] &&
                $response['action'] == $optionsObj->getCaptchaAction()
            ) {
                $_SESSION['CAPTCHA_CHECKED'] = 'Y';
                \COption::SetOptionString(self::MODULE_NAME, 'CAPTCHA_SUCCESS_COUNT', $options['CAPTCHA_SUCCESS_COUNT'] + 1);
                $optionsObj->clearCache();
                return true;
            } else {
                $errorArray = $response;
            }
        } else{
            $errorArray = ['ERROR' => 'Empty token'];
        }
        \COption::SetOptionString(self::MODULE_NAME, 'CAPTCHA_FAIL_COUNT', $options['CAPTCHA_FAIL_COUNT'] + 1);
        $this->logCaptchaFail($errorArray, $options['CAPTCHA_FAILS_LOG']);
        $optionsObj->clearCache();
        return false;
    }

    /**
     * @param array $data
     * @param string $log
     */
    private function logCaptchaFail($data, $log)
    {
        if ($log == 'Y') {
            $logFile = $this->getLogPath();
            file_put_contents($logFile, date('d.m.Y h:i:s') . '----------------------' . PHP_EOL, FILE_APPEND);
            file_put_contents($logFile, 'ip = ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);
            file_put_contents($logFile, print_r($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
            file_put_contents($logFile, print_r($data, 1) . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * @return string
     */
    private function getLogPath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::LOG_PATH;
    }

    /**
     * @param string $secret
     * @param string $token
     * @return array
     */
    private function getCaptchaResponse($secret, $token)
    {
        $data = array(
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::GOOGLE_API_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * @return string
     */
    public function getLogData()
    {
        return file_get_contents($this->getLogPath());
    }
}