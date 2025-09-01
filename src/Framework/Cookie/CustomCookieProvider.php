<?php declare(strict_types=1);

namespace Alpha\Popup\Framework\Cookie;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class CustomCookieProvider implements CookieProviderInterface {

    public function __construct(private readonly CookieProviderInterface $originalService)
    {
    }

    private const popupCookie = [
        'snippet_name' => 'cookie.alphaPopupName',
        'snippet_description' => 'cookie.alphaPopupDescription',
        'cookie' => 'dontShowPopup-',
        'value'=> 'true',
        'expiration' => 'max-age'
    ];


    public function getCookieGroups(): array
    {
        $cookieGroups = $this->originalService->getCookieGroups();

        foreach ($cookieGroups as &$group) {
            if ($group['snippet_name'] === 'cookie.groupRequired') {
                $group['entries'][] = self::popupCookie;
                break;
            }
        }
        unset($group); // unset reference to keep the array safe

        return $cookieGroups;
    }
}
