<?php

declare(strict_types=1);

namespace App\Support;

final class LanguageKeys
{
    public static function admin(): array
    {
        return [
            'a-cancel','a-delete','a-required-fields','a-please-wait','a-information-updated','a-message-delete','a-confirm-delete','a-message-delete-category',
            'a-message-update-to-db','a-confirm-update-to-db','a-update-to-db','a-success-title','a-error-title','a-confirm-activate','a-select-one-review','a-activate-comments',
            'a-confirm-delete-comments','a-delete-comments','a-message-careful','a-activate-reviews','a-delete-reviews','a-message-success','a-language-de',
            'a-language-ro','a-menu-type-top','a-menu-type-sidebar','a-invalid-email-address','a-select-attribute','a-select-variant','a-attribute-position','f-select-city','a-in_stock',
            'a-supplier_stock','a-in_order','a-out_of_stock','a-coupon-type-percent','a-coupon-type-numeric'
        ];
    }

    public static function frontend(): array
    {
        return [
            'f-required-fields','f-error-invalid-email','f-informations-updated','f-error-review-empty-star',
            'f-1-stars','f-2-stars','f-3-stars','f-4-stars','f-5-stars','f-ajax-load-error','f-no-reviews-after-filters',
            'f-please-wait','f-register-error-accept-terms','f-save-order-successfully','f-select-district','f-select-city'
        ];
    }
}

