<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }
/*
public function configureFields(string $pageName): iterable
{
return [
IdField::new ('id'),
TextField::new ('name'),
TextEditorField::new ('description'),
];
}*/

}