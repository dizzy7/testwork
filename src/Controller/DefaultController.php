<?php

namespace Controller;

use Db\OrderDao;
use Db\ProductsDao;
use Service\CartService;
use Service\FormService;
use Service\OrderService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Service\Templating;

class DefaultController
{
    private $request;

    const MAIN_PAGE_PRODUCTS_COUNT = 3;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function indexAction()
    {
        $productsDao = new ProductsDao();
        $cartService = new CartService();
        $templating = new Templating();

        $products = $productsDao->findRandom(self::MAIN_PAGE_PRODUCTS_COUNT);
        $cartData = $cartService->getCartData();

        return new Response($templating->render('index.php', ['products' => $products, 'cartData' => $cartData]));
    }

    public function addToCartAction()
    {
        $cartService = new CartService();
        $productId = $this->request->request->get('id');

        $cartService->addToCart($productId);

        return new RedirectResponse('/');
    }

    public function cartEditAction()
    {
        $cartService = new CartService();

        $cardData = $this->request->request->all();
        foreach ($cardData as $key => $value) {
            $data = explode('_', $key);
            if (!isset($data[1]) || !is_numeric($data[1])) {
                continue;
            }

            $productId = (int)$data[1];
            $qty = (int)$value;
            $cartService->setQty($productId, $qty);
        }

        return new RedirectResponse('/');
    }

    public function orderAction()
    {
        $cartService = new CartService();
        $templating = new Templating();
        $formService = new FormService();

        $cartData = $cartService->getCartData();

        $formErrors = [];
        if ($this->request->getMethod() === Request::METHOD_POST) {
            $formData = $this->request->request->get('order');

            $formErrors = $formService->validateOrderForm($formData);

            if (!$formErrors) {
                $orderService = new OrderService();
                $orderService->processOrder($formData);

                return new RedirectResponse('/success');
            }
        }

        return new Response($templating->render(
            'order.php',
            ['cartData' => $cartData, 'csrfToken' => $formService->generateCsrfToken(), 'errors' => $formErrors]
        ));
    }

    public function applyBonusAction()
    {
        $cartService = new CartService();
        $cartService->applyBonus($this->request->request->get('bonus'));

        return new RedirectResponse('/order');
    }

    public function successAction()
    {
        $templating = new Templating();

        return new Response($templating->render(
            'success.php'
        ));
    }

    public function showAction()
    {
        $templating = new Templating();
        $db = new OrderDao();
        $order = $db->find($this->request->get('id'));
        $products = $db->findProducts($order['id']);

        return new Response($templating->render(
            'showOrder.php',
            [
                'order' => $order,
                'products' => $products
            ]
        ));
    }
}