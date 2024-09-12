<?php

namespace app\controllers;


use app\models\Auth;
use app\models\form\LoginForm;
use app\models\form\RegisterForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth-vk' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param $client
     *
     * @return Response
     *
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function onAuthSuccess($client): Response
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id']
        ])->one();

        if (!empty($auth)) {
            $user = $auth->user;
            Yii::$app->user->login($user);

            return $this->redirect(['/note/index']);
        } else {
            if (empty($attributes['email'])) {
                Yii::$app->getSession()->setFlash('error', "У данного пользователя отсутствует email, поэтому вы не можете авторизоваться.");
            } else {
                /** @var User $user */
                $user = User::find()->where(['email' => $attributes['email']])->one();

                if (!empty($user)) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]);

                    if ($auth->save()) {
                        Yii::$app->user->login($user);

                        return $this->redirect(['/note/index']);
                    } else {
                        print_r($auth->getErrors());
                    }
                } else {
//                    $password = Yii::$app->security->generateRandomString(6);
                    $password = '123456';
                    $user = new User([
                        'email' => $attributes['email']
                    ]);
                    $user->setPassword($password);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($user->save(false)) {
                            $auth = new Auth([
                                'user_id' => $user->id,
                                'source' => $client->getId(),
                                'source_id' => (string)$attributes['id']
                            ]);

                            if ($auth->save(false)) {
                                $transaction->commit();

                                Yii::$app->user->login($user);

                                return $this->redirect(['/note/index']);
                            } else {
                                print_r($auth->getErrors());
                            }
                        } else {
                            print_r($user->getErrors());
                        }
                    } catch (\Throwable $e) {
                        Yii::info('[Message]: ' . $e->getMessage() . ' [Line]:' . $e->getLine()
                            . ' [File]: ' . $e->getFile());
                    }
                }
            }

            return $this->redirect(['/site/login']);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                return $this->redirect(['/note/index']);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Register action.
     *
     * @return string|Response
     *
     * @throws Exception
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->register()) {
                return $this->redirect(['/note/index']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
