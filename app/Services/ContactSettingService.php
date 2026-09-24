<?php

namespace App\Services;

use App\Http\Requests\Admin\UpdateContactSettingRequest;
use App\Settings\ContactSetting;

class ContactSettingService
{
    public function __construct(
        private ContactSetting $contactSetting
    ) {}

    public function getSettings(): array
    {
        return [
            'facebook_link' => $this->contactSetting->facebook_link,
            'x_link' => $this->contactSetting->x_link,
            'instagram_link' => $this->contactSetting->instagram_link,
            'snapchat_link' => $this->contactSetting->snapchat_link,
            'tiktok_link' => $this->contactSetting->tiktok_link,
            'youtube_link' => $this->contactSetting->youtube_link,
            'whatsapp_number' => $this->contactSetting->whatsapp_number,
            'contact_numbers' => $this->contactSetting->contact_numbers ?? [],
            'whatsapp_link' => filled($this->contactSetting->whatsapp_number)
                ? 'https://wa.me/'.preg_replace('/\D+/', '', (string) $this->contactSetting->whatsapp_number)
                : null,
        ];
    }

    public function updateFromArray(array $data): void
    {
        foreach ([
            'facebook_link',
            'x_link',
            'instagram_link',
            'snapchat_link',
            'tiktok_link',
            'youtube_link',
            'whatsapp_number',
            'contact_numbers',
        ] as $key) {
            if (array_key_exists($key, $data)) {
                $this->contactSetting->{$key} = $data[$key];
            }
        }

        $this->contactSetting->save();
    }

    public function updateSettings(UpdateContactSettingRequest $request): void
    {
        $this->contactSetting->facebook_link = $request->input('facebook_link');
        $this->contactSetting->x_link = $request->input('x_link');
        $this->contactSetting->instagram_link = $request->input('instagram_link');
        $this->contactSetting->snapchat_link = $request->input('snapchat_link');
        $this->contactSetting->tiktok_link = $request->input('tiktok_link');
        $this->contactSetting->youtube_link = $request->input('youtube_link');
        $this->contactSetting->whatsapp_number = $request->input('whatsapp_number');
        $this->contactSetting->contact_numbers = $request->input('contact_numbers', []);

        $this->contactSetting->save();
    }
}
