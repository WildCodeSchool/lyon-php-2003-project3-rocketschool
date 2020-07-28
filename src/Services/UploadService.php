<?php

namespace App\Services;

class UploadService
{

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @return string|null
     */
    public function upload($item): ?string
    {
        $newName = null;

        if ($item) {
            $originalName = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = $this->slugify->generate($originalName);
            $newName = $safeName . "-" . uniqid() . "." . $item->guessExtension();
        }

        return $newName;
    }
}
