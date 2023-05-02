<?php
namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;

class ImageUploadController
{

    public  function __invoke(Request $request):Categorie{

        $categorie = new Categorie();
        $categorie->setName($request->request->get('name'));

        $imageFile = $request->files->get('imageFile');
        if ($imageFile) {
            $categorie->setImageFile($imageFile);
            $categorie->setImage($imageFile->getClientOriginalName());
        }

        return $categorie;

    }
}
