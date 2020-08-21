<?php

namespace App\Controller;
use App\Entity\Record;
use App\Entity\Artist;
use App\Entity\Label;
use App\Repository\ArtistRepository;
use App\Repository\LabelRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
    /**
     * liste des artistes
     * @Route("/artist", name="artist_list")
     * 
     */
    public function index(ArtistRepository $repository)
    
    {
        return $this->render('record/artist_list.html.twig', [
            'artist_list' => $repository->findAll()
        ]);
    }


    /**
     * page d'un artist
     * @Route("/artist/{id}", name="artist_page")
     */
    public function artistPage(Artist $artist)
    {
        return $this->render('record/artist_page.html.twig',[
            'artist' => $artist
        ]);
    }
/**
 * page d'album
 * @Route("/record/{id}", name="record_page")
 */
    public function recordPage(Record $record)
    {
        return $this->render('record/record_page.html.twig',[
            'record' => $record
        ]);
    }


    /**
     * nouveaux albums
     * @Route("news", name="records_news")
     */
    public function recordNews(RecordRepository $repository)
    {
       return $this->render('record/record_news.html.twig',[
           'record_news' => $repository->findNews(),
       ]);

    }

/**
 * page lablel
 *@Route("/label/{id}", name="label_page")
 * 
 */
    public function Label_Page(Label  $label)
    {
        return $this->render('label/Label_Page.html.twig',[
            'label'=> $label
        ]);
    }



}
