<?php

namespace TonoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TonoBundle\Entity\Ingredient;
use TonoBundle\Form\IngredientType;
use TonoBundle\Entity\Recette;
use TonoBundle\Form\RecetteType;
use TonoBundle\Entity\Meal;
use TonoBundle\Entity\Slot;
use TonoBundle\Form\MealType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // ← this line
use Symfony\Component\Form\Extension\Core\Type\TextType; // ← this line
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // ← this line
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DefaultController extends Controller {

    /**
     * @Route("/", name="indexredir")
     */
    public function enterAction() {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/home/{print}", name="index",defaults={"print" : 0})
     */
    public function homeAction(Request $request, $print) {

        $em = $this->getDoctrine()->getManager();

        $recetteList = $em->getRepository('TonoBundle:Recette')->findAll();
        $mealList = $em->getRepository('TonoBundle:Meal')->getActualWeek();
        $mealFormatedList=array();
        foreach($mealList as $meal)
        {
            if(!empty($meal->getSlot()))
            $mealFormatedList[$meal->getSlot()->getId()]=$meal;
        }
        
        
        
        /*
         * listecourse
         */
        $em = $this->getDoctrine()->getManager();
        $mealRep = $em->getRepository('TonoBundle:Meal')->getActualWeek();
        $ingredientsP1 = array();
        $ingredientsP2 = array();
        $allIngredients = array();

        foreach ($mealRep as $meal) {
            
                $recette = $meal->getRecette();
                if ($recette) {
                    $ingArray = $recette->getIngredients();
                    foreach ($ingArray as $ingros) {
                        $prefix = $ingros->getType();
                        $prefix = preg_replace('/[^A-Za-z0-9]/', "", $prefix);

                        $allIngredients[$prefix . $ingros->getNom()] = $ingros;
                        
                        if (!empty($ingredientsP1[$prefix.$ingros->getNom()]))
                                $ingredientsP1[$prefix . $ingros->getNom()] ++;
                            else
                                $ingredientsP1[$prefix . $ingros->getNom()] = 1;

                       
                    }
                }
            
        }
        ksort($ingredientsP1);
        ksort($ingredientsP2);
        $template = 'TonoBundle:Default:home.html.twig';
        if ($print) {
            /* print = 1 print all
             * print = 2 print batch 1
             * print = 3 print batch 2
             */
            $template = 'TonoBundle:Default:print.html.twig';
        }
        
        $allslots= $em->getRepository('TonoBundle:Slot')->findAll();

        /* generer un select des recettes */
        $select='';
        foreach($recetteList as $recette)
        {
            $select.='<option value="'.$recette->getId().'">'.$recette->getNom().'</option>';
        }
        $select='<select id="recettereplacer">'.$select.'</select>';
        
        
        return $this->render(
                    $template, array(
                    'print' => $print,
                    'recetteList' => $recetteList,
                    'mealList' => $mealFormatedList,
                    'ingredientsP1' => $ingredientsP1,
                    'ingredientsP2' => $ingredientsP2,
                    'slots' => $allslots,
                    'allIngredients' => $allIngredients,
                    'selectRecetteReplacer'=>$select

        ));
    }

    /* ingredients */

    /**
     * @Route("/add_ingredient/", name="ajout_ingredient")
     */
    public function addIngredientAction(Request $request) {

        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($ingredient);
            $em->flush();
            return $this->redirectToRoute('ajout_ingredient');
        }

        $ingredientList = $em->getRepository('TonoBundle:Ingredient')->findAll();

        return $this->render('TonoBundle:Default:form_ingredient.html.twig', array(
                    'form' => $form->createView(),
                    'ingredientList' => $ingredientList
        ));
    }

    /**
     * @Route("/edit_ingredient/{ingredient}", name="edit_ingredient")
     * @ParamConverter("ingredient", class="TonoBundle:Ingredient")
     */
    public function editIngredientAction(Request $request, Ingredient $ingredient) {

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();
        }
        $em = $this->getDoctrine()->getManager();
        $ingredientList = $em->getRepository('TonoBundle:Ingredient')->findAll();


        return $this->render('TonoBundle:Default:form_ingredient.html.twig', array(
                    'form' => $form->createView(),
                    'ingredientList' => $ingredientList
        ));
    }

    /**
     * @Route("/delete_ingredient/{id}", name="delete_ingredient")
     */
    public function deleteIngredientAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $ingredientRep = $em->getRepository('TonoBundle:Ingredient')->find($id);

        if (!$ingredientRep) {
            throw $this->createNotFoundException('No ingredient found for id ' . $id);
        }

        $em->remove($ingredientRep);
        $em->flush();

        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Ingrédient effacé')
        ;

        return $this->redirectToRoute('ajout_ingredient');
    }

    /* recettes */

    /**
     * @Route("/add_recette/", name="ajout_recette")
     */
    public function addRecetteAction(Request $request) {

        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recette);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Recette ajoutée')
            ;

            return $this->redirectToRoute('ajout_recette');
        }

        $recetteList = $em->getRepository('TonoBundle:Recette')->findAll();

        return $this->render('TonoBundle:Default:form_recette.html.twig', array(
                    'form' => $form->createView(),
                    'id' => null
        ));
    }

    /**
     * @Route("/recette_manager", name="manager_recette")
     */
    public function ManagerRecetteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $recetteList = $em->getRepository('TonoBundle:Recette')->findAll();

        return $this->render('TonoBundle:Default:recette_manager.html.twig', array(
                    'recetteList' => $recetteList,
        ));
    }

    /**
     * @Route("/edit_recette/{recette}", name="edit_recette")
     * @ParamConverter("recette", class="TonoBundle:Recette")
     */
    public function editRecetteAction(Request $request, Recette $recette) {

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recette);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Recette sauvegardée')
            ;
        }

        return $this->render('TonoBundle:Default:form_recette.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $recette->getId()
        ));
    }

    /**
     * @Route("/delete_recette/{id}", name="delete_recette")
     */
    public function deleteRecetteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $recetteRep = $em->getRepository('TonoBundle:Recette')->find($id);
        $mealRep = $em->getRepository('TonoBundle:Meal')->findByRecette($id);
        if (!$recetteRep) {
            throw $this->createNotFoundException('No recette found for id ' . $id);
        }
        foreach ($mealRep as $meal) {
            $meal->setRecette(null);
            $em->persist($meal);
            $em->flush();
        }
        
        $em->remove($recetteRep);

        $em->flush();

        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Recette effacée')
        ;
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/ajax_saveingredient", name="ajax_saveingredient")
     */
    public function saveIngredientAjaxAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $ingredient = new Ingredient();
        $term = $request->request->get('term');

        $selected = $request->request->get('selected');
        $selectedarray = json_decode($selected);

        /* verifie qu'il existe pas déjà */
        $exist = $em->getRepository('TonoBundle:Ingredient')->findByNom($term);
        if (!$exist) {
            if ($term) {
                $ingredient->setNom($term);
                $ingredient->setIsvegan(1);
                $ingredient->setDefaultquantity(1);
                $em->persist($ingredient);
                $em->flush();
            }
        }



        $ingredientList = $em->getRepository('TonoBundle:Ingredient')->findAll();
        $opts = '';
        foreach ($ingredientList as $ing) {
            if (in_array($ing->getId(), $selectedarray) || $ing->getNom() == $term) {
                $opts .= '<option value="' . $ing->getId() . '" selected="selected">' . $ing->getNom() . '</option>';
            } else {
                $opts .= '<option value="' . $ing->getId() . '">' . $ing->getNom() . '</option>';
            }
        }
        $response = '<select>' . $opts . '</select>';




        return new JsonResponse(array('list' => $response, 'choice' => $term));
    }

    /**
     * @Route("/find_recette", name="find_recette")
     */
    public function findRecette(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TonoBundle:Recette');
        //$find = $repo->findRecetteFromIngredients($ingArray);
        $recettes_scores = array();
        $recettes_array = array();
        $defaultData = array('message' => 'Type your message here');

        $form = $this->createFormBuilder($defaultData)
                ->add('ingredient', EntityType::class, array(
                    'class' => 'TonoBundle:Ingredient',
                    'choice_label' => 'nom',
                    'multiple' => true,
                ))
                ->add('send', SubmitType::class)
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $ingredients_to_find = $data['ingredient']->toArray();
            /* parsing all recettes */
            $all = $repo->findAll();

            foreach ($all as $recette) {
                $recette_ingredients = $recette->getIngredients();
                foreach ($recette_ingredients as $ingo) {
                    if (in_array($ingo, $ingredients_to_find)) {
                        $recettes_array[$recette->getId()] = $recette;
                        if (empty($recettes_scores[$recette->getId()])) {
                            $recettes_scores[$recette->getId()] = 1;
                        } else {
                            $recettes_scores[$recette->getId()] ++;
                        }
                    }
                }
                ksort($recettes_scores);
            }
        }

        // ... render the form


        return $this->render('TonoBundle:Default:find_recette.html.twig', array(
                    'form' => $form->createView(),
                    'scores' => $recettes_scores,
                    'recettes' => $recettes_array
        ));
    }

    /**
     * @Route("/changetype/{id}/{type}", name="change_ingredient_type")
     */
    public function changeTypeFromAjaxAction(Request $request, $id, $type) {

        $em = $this->getDoctrine()->getManager();
        $ingredient = $em->getRepository('TonoBundle:Ingredient')->findOneById($id);
        $ingredient->setType($type);
        $em->persist($ingredient);
        $em->flush();


        return new JsonResponse(array('cool' => 1));
    }
    
    
    
    /**
     * @Route("/changelourdeur/{id}/{lourdeur}", name="change_recette_lourdeur")
     */
    public function changeLourdeurFromAjaxAction(Request $request, $id, $lourdeur) {

        $em = $this->getDoctrine()->getManager();
        $recette = $em->getRepository('TonoBundle:Recette')->findOneById($id);
        $recette->setLourd($lourdeur);
        $em->persist($recette);
        $em->flush();


        return new JsonResponse(array('id'=>$id,'lourdeur' => Recette::LOURD_NAMES[$lourdeur]));
    }

}
