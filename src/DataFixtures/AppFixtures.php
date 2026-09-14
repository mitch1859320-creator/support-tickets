<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Etat;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création des Catégories demandées
        $categories = ['Incident', 'Panne', 'Evolution', 'Anomalie', 'Information'];
        foreach ($categories as $nomCat) {
            $cat = new Categorie();
            $cat->setNom($nomCat);
            $manager->persist($cat);
        }

        // 2. Création des États demandés
        $etats = ['Nouveau', 'Ouvert', 'Résolu', 'Fermé'];
        foreach ($etats as $nomEtat) {
            $etat = new Etat();
            $etat->setNom($nomEtat);
            $manager->persist($etat);
        }

        // 3. Création du compte Administrateur (Livrable PDF)
        $admin = new User();
        $admin->setEmail('admin@agence.fr');
        $admin->setNom('Administrateur');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // 4. Création d'un compte Personnel / Staff
        $staff = new User();
        $staff->setEmail('staff@agence.fr');
        $staff->setNom('Agent Support');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPassword($this->hasher->hashPassword($staff, 'staff123'));
        $manager->persist($staff);

        $manager->flush();
    }
}