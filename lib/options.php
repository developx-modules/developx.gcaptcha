<?

namespace Developx\Gcaptcha;

/**
 * Class Options
 */
class Options
{
    protected static $_instance;
    const MODULE_NAME = 'developx.gcaptcha';
    const CACHE_TIME = 36000000;
    const CAPTCHA_ACTION = 'developxCaptcha';

    /** @var array */
    public $arOptions = [
        'CAPTCHA_ACTIVE' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'N'
        ],
        'CAPTCHA_KEY' => [
            'TYPE' => 'text',
            'SIZE' => 50
        ],
        'CAPTCHA_SECRET' => [
            'TYPE' => 'text',
            'SIZE' => 50
        ],
        'CAPTCHA_SENS' => [
            'TYPE' => 'text',
            'DEFAULT' => 0.5
        ],
        'CAPTCHA_FAILS_LOG' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y'
        ],
        'INCLUDE_JQUERY' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y'
        ]
    ];

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $obCache = new \CPHPCache();
        if ($obCache->InitCache(self::CACHE_TIME, $this->getCachePatch(), '/')) {
            $this->arOptions = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            foreach ($this->arOptions as $code => $prop) {
                $this->arOptions[$code]['VALUE'] = \COption::GetOptionString(self::MODULE_NAME, $code);
                if (empty($this->arOptions[$code]['VALUE']) && !empty($prop['DEFAULT'])) {
                    $this->arOptions[$code]['VALUE'] = $prop['DEFAULT'];
                }
            }
            $obCache->EndDataCache($this->arOptions);
        }
    }

    /**
     * @return string
     */
    private function getCachePatch()
    {
        return 'cache' . self::MODULE_NAME . 'options';
    }

    public function clearCache()
    {
        $cache = new \CPHPCache();
        $cache->Clean($this->getCachePatch(), '/');
    }

    public function setOption($code, $value)
    {
        if ($this->arOptions[$code]['TYPE'] == 'checkbox' && empty($value)) {
            $value = 'N';
        }
        \COption::SetOptionString(self::MODULE_NAME, $code, $value);
    }

    /**
     * @return array
     **/
    public function getOptions()
    {
        $arOptions = [];
        foreach ($this->arOptions as $code => $option) {
            $arOptions[$code] = $option['VALUE'];
        }
        return $arOptions;
    }

    /**
     * @return boolean
     **/
    public function checkCaptchaActive()
    {
        $options = $this->getOptions();
        if (
            $options['CAPTCHA_ACTIVE'] == 'Y' &&
            !empty($options['CAPTCHA_KEY']) &&
            !empty($options['CAPTCHA_SECRET']) &&
            !empty($options['CAPTCHA_SENS'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $code
     * @param string $title
     */
    public function showHtmlOption($code, $title)
    {
        $params = $this->arOptions[$code];
        switch ($params['TYPE']) {
            case 'checkbox':
                echo '
                <tr>
                    <td width="50%">' . $title . '</td>
                    <td width="50%"><input type="checkbox" 
                    name="' . $code . '"
                    value="Y" ' . ($params['VALUE'] == "Y" ? "checked" : "") . '></td>
                </tr>';
                break;
            case 'text':
                echo '
                <tr>
                    <td width="50%">' . $title . '</td>
                    <td width="50%">
                    <input type="text" size="' . $params['SIZE'] . '" maxlength="255" value="' . $params['VALUE'] . '" name="' . $code . '">
                    </td>
                </tr>';
                break;
        }
    }

    /**
     * @return string
     */
    public function getCaptchaAction()
    {
        return self::CAPTCHA_ACTION;
    }
}

?>