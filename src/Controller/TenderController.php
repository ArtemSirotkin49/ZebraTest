<?php

namespace App\Controller;

use App\Entity\Tender;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TenderController extends AbstractController
{
    #[Route('/tender/', name: 'create_tender', methods: ['POST'])]
    public function createTender(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $tender = new Tender();
        if (!empty($request->query->get("ext_code"))) {
            $tender->setExtCode($request->query->get("ext_code"));
        } else {
            return $this->json([
                "message" => "Tender is not saved",
                "status" => "error",
            ]);
        }
        if (!empty($request->query->get("number"))) {
            $tender->setNumber($request->query->get("number"));
        } else {
            return $this->json([
                "message" => "Tender is not saved",
                "status" => "error",
            ]);
        }
        if (!empty($request->query->get("status"))) {
            $tender->setStatus($request->query->get("status"));
        } else {
            return $this->json([
                "message" => "Tender is not saved",
                "status" => "error",
            ]);
        }
        if (!empty($request->query->get("name"))) {
            $tender->setName($request->query->get("name"));
        } else {
            return $this->json([
                "message" => "Tender is not saved",
                "status" => "error",
            ]);
        }
        if (!empty($request->query->get("date_upd"))) {
            $tender->setDateUpdate($request->query->get("date_upd"));
        } else {
            return $this->json([
                "message" => "Tender is not saved",
                "status" => "error",
            ]);
        }
        $entityManager->persist($tender);
        $entityManager->flush();
        return $this->json([
            "message" => "Saved new tender with id " . $tender->getId(),
            "status" => "OK",
        ]);
    }

    #[Route('/tender/', name: 'get_tender', methods: ['GET'])]
    public function getTender(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $repository = $doctrine->getRepository(Tender::class);
        if (!empty($request->query->get("ext_code"))) {
            $tender = $repository->findOneBy(['ext_code' => $request->query->get("ext_code")]);
        } else {
            return $this->json([
                "message" => "Set external code!",
                "status" => "error",
            ]);
        }
        return $this->json([
            "ext_code" => $tender->getExtCode(),
            "number" => $tender->getNumber(),
            "status" => $tender->getStatus(),
            "name" => $tender->getName(),
            "date_update" => $tender->getDateUpdate()
        ]);
    }

    #[Route('/tenders/', name: 'get_tenders', methods: ['GET'])]
    public function getTenders(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $repository = $doctrine->getRepository(Tender::class);
        if (!empty($request->query->get("name")) && !empty($request->query->get("date_update"))) {
            $tenders = $repository->findBy(["name" => $request->query->get("name"), "date_update" => $request->query->get("date_update")]);
        } elseif (!empty($request->query->get("name"))) {
            $tenders = $repository->findBy(["name" => $request->query->get("name")]);
        } elseif (!empty($request->query->get("date_update"))) {
            $tenders = $repository->findBy(["date_update" => $request->query->get("date_update")]);
        } else {
            $tenders = $repository->findAll();
        }
        $result = [];
        foreach ($tenders as $key => $tender) {
            $result[] = [
                "ext_code" => $tender->getExtCode(),
                "number" => $tender->getNumber(),
                "status" => $tender->getStatus(),
                "name" => $tender->getName(),
                "date_update" => $tender->getDateUpdate()
            ];
        }
        return $this->json($result);
    }

    #[Route('/tenders/fill/', name: 'fill_tenders', methods: ['POST'])]
    public function fillTenders(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $file = $request->server->get("DOCUMENT_ROOT") . "/test_task_data.csv";
        //открываем файл с CSV-данными
        $fh = fopen($file, "r");
        // делаем пропуск первой строки, смещая указатель на одну строку
        fgetcsv($fh, 0, ',');
        //читаем построчно содержимое CSV-файла
        while (($row = fgetcsv($fh, 0, ",")) !== false) {
            $tender = new Tender();
            $tender->setExtCode($row[0]);
            $tender->setNumber($row[1]);
            $tender->setStatus($row[2]);
            $tender->setName($row[3]);
            $tender->setDateUpdate($row[4]);
            $entityManager->persist($tender);
        }
        $entityManager->flush();
        return $this->json([
            "message" => "Rows added",
            "status" => "OK",
        ]);
    }
}
