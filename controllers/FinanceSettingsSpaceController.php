<?php

namespace olan\finance\controllers;

use olan\finance\jobs\SyncAkaunting;
use Yii;
use olan\finance\models\AkauntingCompany;
use yii\helpers\Json;

/**
 * Thorugh this we are mapping Humhub space with akaunting
 */
class FinanceSettingsSpaceController extends \humhub\modules\space\modules\manage\components\Controller
{
    public function actionIndex()
    {
        $space = $this->contentContainer;

        $link_space = AkauntingCompany::linked($space->id);

        $API_url = Yii::$app->getModule('finance')->settings->get('API_url');

        return $this->render('index', [
            'space'      => $space,
            'link_space' => $link_space,
            'API_url'    => $API_url
        ]);
    }

    public function actionLinkSpace()
    {
        Yii::$app->queue->push(new SyncAkaunting(['new_space' => true]));

        $ping_status['response'] = Yii::t('FinanceModule.base', 'This space will be linked with Akaunting Shortly.');
        return Json::encode($ping_status);
    }
}
