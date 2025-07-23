<?php

namespace App\Controllers;

class ContactController extends BaseController
{
    public function index(): void
    {
        $this->render('pages/contact.twig', [
            'title' => 'Contact Us - Wedding Decoration Rental',
        ]);
    }
}