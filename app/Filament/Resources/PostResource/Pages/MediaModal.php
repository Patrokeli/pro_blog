<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\Page;

class MediaModal extends Page
{
    protected static string $resource = PostResource::class;
    protected static string $view = 'filament.resources.post-resource.pages.media-modal';

    public $mediaUrl;
    public $mediaType;

    public function mount($url)
    {
        $this->mediaUrl = $url;
        $this->mediaType = str_contains($url, 'video-icon.png') ? 'video' : 'image';
    }
}