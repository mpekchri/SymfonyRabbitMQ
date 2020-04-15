<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
    /**
     * @Route("/basic", name="basic")
     */
    public function index()
    {
      $url = 'https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results';
      $options = array(
          'http' => array(
              'header'  => "Content-type: application/json\r\n",
              'method'  => 'GET',
          )
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      if ($result === FALSE) {
        /* Handle error */
        $result = 'IT SHOULD RAISE AN EXCEPTION, SHOULDNT IT?';
      }else{
        $result = json_decode($result);
      }
      // \var_dump($result); die;

      return $this->json([
          'result' => $result,
      ]);
    }
}
