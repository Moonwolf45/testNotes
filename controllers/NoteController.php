<?php

namespace app\controllers;

use app\models\Notes;
use app\models\NotesTags;
use app\models\search\NotesSearch;
use app\models\Tags;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * NoteController implements the CRUD actions for Notes model.
 */
class NoteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Notes models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new NotesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single Notes model.
     *
     * @param int $tags_id
     *
     * @return string
     */
    public function actionTags(int $tags_id): string
    {
        $searchModel = new NotesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([Tags::tableName() . '.id' => $tags_id]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single Notes model.
     * @param int $id ID
     *
     * @return string
     *
     */
    public function actionView(int $id): string
    {
        $notes = Notes::find()->joinWith(['tags'])->where([Notes::tableName() . '.id' => $id])->one();

        return $this->render('view', [
            'model' => $notes,
        ]);
    }

    /**
     * Creates a new Notes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     *
     * @throws Exception
     */
    public function actionCreate()
    {
        $tags = Tags::find()->where(['user_id' => Yii::$app->user->identity->id])->indexBy('id')->all();
        $model = new Notes();
        $model->user_id = Yii::$app->user->identity->id;

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($this->request->post())) {
                    if ($model->save()) {
                        $tagsLink = $model->tagsLink;
                        if (!empty($tagsLink)) {
                            for ($i = 0; $i < count($tagsLink); $i++) {
                                $model->link('tags', $tags[$tagsLink[$i]]);
                            }
                        }

                        $transaction->commit();

                        Yii::$app->session->setFlash('success', 'Заметка успешно создана');

                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'В форме найдены ошибки');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model', 'tags'));
    }

    /**
     * Updates an existing Notes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     *
     * @return string|Response
     *
     * @throws Exception if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $tags = Tags::find()->where(['user_id' => Yii::$app->user->identity->id])->indexBy('id')->all();
        $model = Notes::findOne(['id' => $id]);
        $tagsLink = NotesTags::find()->select(['tags_id AS id'])->where(['notes_id' => $id])->asArray()->all();
        foreach ($tagsLink as $tag) {
            $model->tagsLink[] = $tag['id'];
        }

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $old_tagsLink = $model->tagsLink;

                if ($model->load($this->request->post())) {
                    $tagsLink = $model->tagsLink;

                    $delete_tagsLinks = array_diff($old_tagsLink, $tagsLink);
                    $add_tagsLinks = array_diff($tagsLink, $old_tagsLink);

                    if (!empty($delete_tagsLinks)) {
                        foreach ($delete_tagsLinks as $delete_tagsLink) {
                            $model->unlink('tags', $tags[$delete_tagsLink], true);
                        }
                    }

                    if (!empty($add_tagsLinks)) {
                        foreach ($add_tagsLinks as $add_tagsLink) {
                            $model->link('tags', $tags[$add_tagsLink]);
                        }
                    }

                    if ($model->save()) {
                        $transaction->commit();

                        Yii::$app->session->setFlash('success', 'Заметка успешно обновлена');

                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'В форме найдены ошибки');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            }
        }

        return $this->render('update', compact('model', 'tags'));
    }

    /**
     * Deletes an existing Notes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     *
     * @return Response
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Notes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     *
     * @return Notes the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Notes
    {
        if (($model = Notes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
