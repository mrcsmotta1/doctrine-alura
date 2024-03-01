<?php

use Alura\Doctrine\Entity\Phone;
use Alura\Doctrine\Entity\Student;
use Alura\Doctrine\Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = EntityManagerCreator::createEntityManager();

$student = new Student('Aluno com telefone');
$student->addPhone(new Phone('(11) 99999-123'));
$student->addPhone(new Phone('(11) 12348-123'));

$entityManager->persist($student);
$entityManager->flush();
