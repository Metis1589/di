<?php
namespace frontend\controllers;

use common\enums\DeliveryType;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    private function formatSeoPath($path)
    {
        return strtolower(urlencode(str_replace(' ', '-', $path)));
    }

    /**
     * @param $restaurant
     * @param $seo_areas
     * @return string.
     */
    private function getRestaurantUrl($restaurant, $seo_areas)
    {
        return
            '/' .
            $restaurant['id'] .
            '/restaurant/' .
            $this->formatSeoPath(isset($seo_areas[$restaurant['seo_area_id']]) ? $seo_areas[$restaurant['seo_area_id']]['seo_name'] : '') .
            '/' .
            (count($restaurant['restaurantCuisines']) > 0 ? $this->formatSeoPath($restaurant['restaurantCuisines'][0]['cuisine']['seo_name']) : '') .
            '/' .
            $this->formatSeoPath($restaurant['slug']) .
            '.html';
    }

    public function actionIndex()
    {
        $deliveryTypes = [
            DeliveryType::DeliveryAsap    => 'Delivery Asap',
            DeliveryType::DeliveryLater   => 'Delivery Later',
            DeliveryType::CollectionAsap  => 'Collect Asap',
            DeliveryType::CollectionLater => 'Collect Later',
        ];

        $deliveryDates = Yii::$app->deliveryDatesService->generateDeliveryDates();
        $restaurants   = Yii::$app->frontendCache->getRestaurantFeatured();

        shuffle($restaurants);
        $restaurants = array_slice($restaurants,0,6);

        // footer links
        $seo_areas   = Yii::$app->frontendCache->getSeoAreas();
        $cuisines    = Yii::$app->frontendCache->getCuisines(Yii::$app->language);

        $footer_links = [];

        foreach ($cuisines as $cuisine) {
            $footer_links[] = [
                'url' => $cuisine['id'] . '/restaurants/cuisine/' . $cuisine['seo_name'] . '.html',
                'name' => $cuisine['name']
            ];
        }

        foreach ($seo_areas as $area) {
            $footer_links[] = [
                'url' => $area['id'] . '/london_restaurant_delivery_in/' . $area['seo_name'] . '.html',
                'name' => $area['name']
            ];
        }

        return $this->render('index', [
            'delivery_types'       => $deliveryTypes,
            'delivery_types_order' => array_keys($deliveryTypes),
            'delivery_dates'       => $deliveryDates,
            'restaurants'          => $restaurants,
            'footer_links'         => $footer_links,
        ]);
    }

    public function actionSiteMap() {
        $restaurants = Yii::$app->frontendCache->getRestaurants();
        $seo_areas   = Yii::$app->frontendCache->getSeoAreas();
        $pages       = Yii::$app->frontendCache->getPages();
        $cuisines    = Yii::$app->frontendCache->getCuisines(Yii::$app->language);

        if (isset($cuisines) && sizeof($cuisines)) {
            foreach ($cuisines as &$cuisine) {
                $cuisine['restaurants'] = [];

                foreach ($restaurants as $restaurant) {
                    foreach ($restaurant['restaurantCuisines'] as $restaurant_cuisine) {
                        if ($restaurant_cuisine['cuisine_id'] == $cuisine['id']) {

                            $restaurant['seo_url'] = $this->getRestaurantUrl($restaurant, $seo_areas);

                            $cuisine['restaurants'][] = $restaurant;
                        }
                    }
                }
            }
        }

        if (isset($seo_areas) && sizeof($seo_areas)) {
            foreach ($seo_areas as &$area) {
                $area['restaurants'] = [];

                foreach ($restaurants as $restaurant) {
                    if ($restaurant['seo_area_id'] == $area['id']) {

                        $restaurant['seo_url'] = $this->getRestaurantUrl($restaurant, $seo_areas);

                        $area['restaurants'][] = $restaurant;
                    }
                }
            }
        }

        return $this->render('site_map', [
            'seo_areas' => $seo_areas,
            'pages'     => $pages,
            'cuisines'  => $cuisines,
        ]);
    }

    public function actionAllergies()
    {
        return $this->render('allergies', [
            'allergies' => Yii::$app->frontendCache->getAllergies()
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionRestaurantSignUp()
    {
        return $this->render('restaurant_sign_up', [
            'cuisines' => Yii::$app->frontendCache->getCuisines(Yii::$app->language)
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSuggestRestaurant()
    {
        $cuisines = Yii::$app->frontendCache->getCuisines(Yii::$app->language);
        $seo_areas = Yii::$app->frontendCache->getSeoAreas();

        return $this->render('suggest', [
            'cuisines' => $cuisines,
            'seo_areas' => $seo_areas,
        ]);
    }

    public function actionRegister()
    {
        return $this->render('register');
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        return $this->render('reset_password');
    }

    public function actionActivate($token)
    {
        return $this->render('activate');
    }
}
