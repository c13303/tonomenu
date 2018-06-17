<?php

namespace TonoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TonoBundle\Entity\Meal;
use TonoBundle\Form\MealType;
use TonoBundle\Entity\Slot;
use TonoBundle\Form\SlotType;
use TonoBundle\Entity\Recette;
use TonoBundle\Form\RecetteType;
use Symfony\Component\HttpFoundation\JsonResponse;


class MealController extends Controller {

    private function generateMeal(Slot $slot, $recette) {

        $em = $this->getDoctrine()->getManager();
        $meal = new Meal();
        $meal->setSlot($slot);
        $meal->setRecette($recette);             
        $em->persist($meal);
        $em->flush();
    }

    /**
     * @Route("/meal/generate_week", name="generate_meal")
     */
    public function generateWeekMealAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repoM = $em->getRepository('TonoBundle:Meal');
        $repoR = $em->getRepository('TonoBundle:Recette');
        $slotList = $em->getRepository('TonoBundle:Slot')->findAll();
        $repoM->clear_week();
        $exceptions=array(0);        
        
        foreach ($slotList as $slot) {
             $randomRecette = $repoR->get_random($exceptions,$slot->getLourdeur());               
             $this->generateMeal($slot, $randomRecette);     
             if($randomRecette instanceof Recette)
             {
                 $exceptions[] = $randomRecette->getId();
             }             
        }
       
        $request->getSession()
        ->getFlashBag()
        ->add('success', 'Nouveau menu de la semaine généré')
        ;
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/meal/replace_meal/{id}/{lourdeur}", name="replace_meal",defaults={"lourdeur"=0})
     * @ParamConverter("meal", class="TonoBundle:Meal")
     */
    public function replaceMealAction(Request $request, Meal $meal,$lourdeur) {

        $exceptions = array();
        $em = $this->getDoctrine()->getManager();
        $repoM = $em->getRepository('TonoBundle:Meal');
        $semaine = $repoM->getActualWeek();
        $repoR = $em->getRepository('TonoBundle:Recette');

        foreach ($semaine as $recette_to_avoid) {
            if($recette_to_avoid->getRecette())
            {
                 $exceptions[] = $recette_to_avoid->getRecette()->getId();
            }
           
        }
        $repoM->deleteFromId($meal->getId());
        $randomRecette = $repoR->get_random($exceptions,$lourdeur);
        $this->generateMeal($meal->getSlot(), $randomRecette);

        $request->getSession()
        ->getFlashBag()
        ->add('success', 'Menu remplacé par : '.$randomRecette->getNom() .' ('.Recette::LOURD_NAMES[$lourdeur].')')
        ;

        return $this->redirectToRoute('index');
    }
    
    
     /**
     * @Route("/slot/add_slot/", name="ajout_slot")
     */
    public function addSlotAction(Request $request) {

        $slot = new Slot();
        $form = $this->createForm(SlotType::class, $slot);


        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($slot);
            $em->flush();
            return $this->redirectToRoute('ajout_slot');
        }


       $slotList = $em->getRepository('TonoBundle:Slot')->findAll(array('position' => 'ASC'));
        return $this->render('TonoBundle:Meal:form_slot.html.twig', array(
                    'form' => $form->createView(),
                    'slotList'=>$slotList
        ));
    }
    
    
    /**
     * @Route("/edit_slot/{slot}", name="edit_slot")
     * @ParamConverter("slot", class="TonoBundle:Slot")
     */
    public function editSlotAction(Request $request, Slot $slot) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slot);
            $em->flush();
            $request->getSession()
            ->getFlashBag()
            ->add('success', 'Slot édité')
            ;
        }

        $slotList = $em->getRepository('TonoBundle:Slot')->findAll(array('position' => 'ASC'));
        return $this->render('TonoBundle:Meal:form_slot.html.twig', array(
                    'form' => $form->createView(),
                    'slotList'=>$slotList
        ));
    }
    
    
    
    
    
    

    /**
     * @Route("/meal/add_meal/", name="ajout_meal")
     */
    public function addMealAction(Request $request) {

        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);


        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meal);
            $em->flush();
            return $this->redirectToRoute('ajout_meal');
        }


        $recetteList = $em->getRepository('TonoBundle:Recette')->findAll();

        return $this->render('TonoBundle:Meal:form_meal.html.twig', array(
                    'form' => $form->createView(),
                    'recetteList' => $recetteList,
        ));
    }

    /**
     * @Route("/edit_meal/{meal}", name="edit_meal")
     * @ParamConverter("meal", class="TonoBundle:Meal")
     */
    public function editMealAction(Request $request, Meal $meal) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meal);
            $em->flush();
            $request->getSession()
            ->getFlashBag()
            ->add('success', 'Menu édité')
            ;
        }

        $recetteList = $em->getRepository('TonoBundle:Recette')->findAll();
        return $this->render('TonoBundle:Meal:form_meal.html.twig', array(
                    'form' => $form->createView(),
                    'recetteList' => $recetteList,
        ));
    }
    

    /**
     * @Route("/edit_meal_ajax/{meal}", name="edit_meal_ajax")
     * @ParamConverter("meal", class="TonoBundle:Meal")
     */
    public function editMealAjaxAction(Request $request, Meal $meal) {
        $em = $this->getDoctrine()->getManager();
        $recetteId =$request->request->get('recette');
        
        $recette =  $em->getRepository('TonoBundle:Recette')->find($recetteId);
        $deja='';
        /* le meal existe til avec cette recette ? */
        $mealsdeja = $em->getRepository('TonoBundle:Meal')->findOneByRecette($recette);
        if(!empty($mealsdeja))
        {
            $mealsdeja->setRecette(NULL);
            $em->persist($mealsdeja);
            $em->flush();
            $deja=$mealsdeja->getId();
        }
        
        
        $meal->setRecette($recette);
        $em->persist($meal);
        $em->flush();
        
        return new JsonResponse(array('id'=>$recette->getId(),'name'=>$recette->getNom(),'deja'=>$deja));
  
    }
    
    
    
    
    /**
     * @Route("/delete_meal_ajax/{meal}", name="delete_meal_ajax")
     * @ParamConverter("meal", class="TonoBundle:Meal")
     */
    public function deleteMealAjaxAction(Request $request, Meal $meal) {
        $em = $this->getDoctrine()->getManager();
        $meal->setRecette(null);
        $em->persist($meal);
        $em->flush();
        
        return new JsonResponse(array('ok'));
  
    }
    
    
    
    

    /**
     * @Route("/delete_meal/{id}", name="delete_meal")
     */
    public function deleteMealAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $mealRep = $em->getRepository('TonoBundle:Meal')->find($id);

        if (!$mealRep) {
            throw $this->createNotFoundException('No meal found for id ' . $id);
        }

        //$em->remove($mealRep);
        $mealRep->setRecette(null);
        $em->persist($mealRep);
        $em->flush();
        $request->getSession()
        ->getFlashBag()
        ->add('success', 'Menu retiré')
        ;

        return $this->redirectToRoute('index');
    }

}
